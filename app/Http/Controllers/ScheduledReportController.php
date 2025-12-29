<?php

namespace App\Http\Controllers;

use App\Models\ScheduledReport;
use App\Models\ReportLog;
use App\Mail\ScheduledReportMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ScheduledReportController extends Controller
{
    /**
     * Display scheduled reports management page
     */
    public function index()
    {
        $scheduledReports = auth()->user()->scheduledReports()
            ->with(['logs' => function($q) {
                $q->latest()->limit(5);
            }])
            ->latest()
            ->get();

        return view('reports.scheduled', compact('scheduledReports'));
    }

    /**
     * Store new scheduled report
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'report_type' => 'required|string|in:sales,customers,custom',
            'custom_report_id' => 'nullable|exists:custom_reports,id',
            'frequency' => 'required|in:daily,weekly,monthly',
            'day_of_week' => 'nullable|required_if:frequency,weekly|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'day_of_month' => 'nullable|required_if:frequency,monthly|integer|min:1|max:31',
            'time' => 'required|date_format:H:i',
            'recipients' => 'required|array|min:1',
            'recipients.*' => 'email',
            'format' => 'required|in:pdf,excel,csv'
        ]);

        // Clean up conditional fields based on frequency
        if ($validated['frequency'] !== 'weekly') {
            $validated['day_of_week'] = null;
        }

        if ($validated['frequency'] !== 'monthly') {
            $validated['day_of_month'] = null;
        }

        $report = auth()->user()->scheduledReports()->create($validated);

        // Calculate first run time
        $report->next_run_at = $report->calculateNextRun();
        $report->save();

        return response()->json([
            'message' => 'Scheduled report created successfully',
            'report' => $report
        ]);
    }

    /**
     * Update scheduled report
     */
    public function update(Request $request, ScheduledReport $scheduledReport)
    {
        if ($scheduledReport->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'frequency' => 'sometimes|in:daily,weekly,monthly',
            'day_of_week' => 'required_if:frequency,weekly',
            'day_of_month' => 'required_if:frequency,monthly|integer|min:1|max:31',
            'time' => 'sometimes|date_format:H:i',
            'recipients' => 'sometimes|array|min:1',
            'recipients.*' => 'email',
            'format' => 'sometimes|in:pdf,excel,csv',
            'is_active' => 'sometimes|boolean'
        ]);

        $scheduledReport->update($validated);

        // Recalculate next run if schedule changed
        if (isset($validated['frequency']) || isset($validated['time'])) {
            $scheduledReport->next_run_at = $scheduledReport->calculateNextRun();
            $scheduledReport->save();
        }

        return response()->json([
            'message' => 'Scheduled report updated successfully',
            'report' => $scheduledReport
        ]);
    }

    /**
     * Delete scheduled report
     */
    public function destroy(ScheduledReport $scheduledReport)
    {
        if ($scheduledReport->user_id !== auth()->id()) {
            abort(403);
        }

        $scheduledReport->delete();

        return response()->json(['message' => 'Scheduled report deleted successfully']);
    }

    /**
     * Toggle active status
     */
    public function toggle(ScheduledReport $scheduledReport)
    {
        if ($scheduledReport->user_id !== auth()->id()) {
            abort(403);
        }

        $scheduledReport->is_active = !$scheduledReport->is_active;
        $scheduledReport->save();

        return response()->json([
            'message' => 'Status updated successfully',
            'is_active' => $scheduledReport->is_active
        ]);
    }

    /**
     * Get report logs
     */
    public function logs(ScheduledReport $scheduledReport)
    {
        if ($scheduledReport->user_id !== auth()->id()) {
            abort(403);
        }

        $logs = $scheduledReport->logs()
            ->latest()
            ->paginate(20);

        return response()->json($logs);
    }

    /**
     * Send report immediately (test)
     */
    public function sendNow(ScheduledReport $scheduledReport)
    {
        if ($scheduledReport->user_id !== auth()->id()) {
            abort(403);
        }

        try {
            $this->generateAndSendReport($scheduledReport);

            return response()->json(['message' => 'Report sent successfully']);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to send report',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Generate and send report
     */
    private function generateAndSendReport(ScheduledReport $scheduledReport)
    {
        // Get report data based on type
        $reportData = $this->generateReportData($scheduledReport);

        // Generate file (PDF/Excel/CSV)
        $filePath = $this->generateReportFile($scheduledReport, $reportData);

        // Send email to all recipients
        foreach ($scheduledReport->recipients as $recipient) {
            Mail::to($recipient)->send(
                new ScheduledReportMail($scheduledReport, $reportData, $filePath)
            );
        }

        // Log the send
        ReportLog::create([
            'scheduled_report_id' => $scheduledReport->id,
            'sent_at' => now(),
            'status' => 'success',
            'recipient_count' => count($scheduledReport->recipients)
        ]);

        // Update last run and calculate next run
        $scheduledReport->last_run_at = now();
        $scheduledReport->next_run_at = $scheduledReport->calculateNextRun();
        $scheduledReport->save();

        // Clean up file
        if (file_exists($filePath)) {
            unlink($filePath);
        }
    }

    /**
     * Generate report data
     */
    private function generateReportData(ScheduledReport $scheduledReport)
    {
        // Default date range: last 30 days
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        switch ($scheduledReport->report_type) {
            case 'sales':
                return $this->generateSalesReport($startDate, $endDate);
            case 'customers':
                return $this->generateCustomerReport($startDate, $endDate);
            case 'custom':
                if ($scheduledReport->customReport) {
                    return $this->generateCustomReport($scheduledReport->customReport);
                }
                break;
        }

        return ['summary' => [], 'results' => []];
    }

    /**
     * Generate sales report data
     */
    private function generateSalesReport($startDate, $endDate)
    {
        $results = DB::table('transactions')
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('
                DATE(timestamp) as date,
                COUNT(*) as transaction_count,
                SUM(total_amount) as total_sales,
                AVG(total_amount) as avg_transaction
            ')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return [
            'results' => $results,
            'summary' => [
                'total_sales' => $results->sum('total_sales'),
                'transaction_count' => $results->sum('transaction_count'),
                'avg_transaction' => $results->avg('avg_transaction')
            ]
        ];
    }

    /**
     * Generate customer report data
     */
    private function generateCustomerReport($startDate, $endDate)
    {
        $newCustomers = DB::table('customers')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();

        $activeCustomers = DB::table('transactions')
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->distinct('customer_id')
            ->count();

        return [
            'results' => [],
            'summary' => [
                'new_customers' => $newCustomers,
                'active_customers' => $activeCustomers
            ]
        ];
    }

    /**
     * Generate custom report data
     */
    private function generateCustomReport($customReport)
    {
        $config = $customReport->config;
        // Use the same logic from CustomReportController
        // For simplicity, return basic data
        return ['summary' => [], 'results' => []];
    }

    /**
     * Generate report file (PDF/Excel/CSV)
     */
    private function generateReportFile(ScheduledReport $scheduledReport, $reportData)
    {
        $filename = 'report_' . time() . '.' . $scheduledReport->format;
        $path = storage_path('app/temp/' . $filename);

        // Ensure directory exists
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        switch ($scheduledReport->format) {
            case 'csv':
                $this->generateCSV($path, $reportData);
                break;
            case 'pdf':
                // For now, generate CSV (PDF requires additional library)
                $this->generateCSV($path, $reportData);
                break;
            case 'excel':
                // For now, generate CSV (Excel requires additional library)
                $this->generateCSV($path, $reportData);
                break;
        }

        return $path;
    }

    /**
     * Generate CSV file
     */
    private function generateCSV($path, $reportData)
    {
        $file = fopen($path, 'w');

        if (!empty($reportData['results'])) {
            $firstRow = (array)$reportData['results'][0];
            fputcsv($file, array_keys($firstRow));

            foreach ($reportData['results'] as $row) {
                fputcsv($file, (array)$row);
            }
        }

        fclose($file);
    }
}
