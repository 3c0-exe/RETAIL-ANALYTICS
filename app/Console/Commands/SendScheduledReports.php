<?php

namespace App\Console\Commands;

use App\Models\ScheduledReport;
use App\Models\ReportLog;
use App\Mail\ScheduledReportMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SendScheduledReports extends Command
{
    protected $signature = 'reports:send-scheduled';
    protected $description = 'Send all due scheduled reports';

    public function handle()
    {
        $this->info('Checking for scheduled reports to send...');

        $dueReports = ScheduledReport::where('is_active', true)
            ->where(function($query) {
                $query->whereNull('next_run_at')
                      ->orWhere('next_run_at', '<=', Carbon::now());
            })
            ->get();

        if ($dueReports->isEmpty()) {
            $this->info('No reports due at this time.');
            return 0;
        }

        $this->info("Found {$dueReports->count()} report(s) to send.");

        foreach ($dueReports as $report) {
            try {
                $this->info("Sending: {$report->name}");

                $reportData = $this->generateReportData($report);
                $filePath = $this->generateReportFile($report, $reportData);

                foreach ($report->recipients as $recipient) {
                    Mail::to($recipient)->send(
                        new ScheduledReportMail($report, $reportData, $filePath)
                    );
                }

                ReportLog::create([
                    'scheduled_report_id' => $report->id,
                    'sent_at' => now(),
                    'status' => 'success',
                    'recipient_count' => count($report->recipients)
                ]);

                $report->last_run_at = now();
                $report->next_run_at = $report->calculateNextRun();
                $report->save();

                if (file_exists($filePath)) {
                    unlink($filePath);
                }

                $this->info("✓ Sent successfully to " . count($report->recipients) . " recipient(s)");

            } catch (\Exception $e) {
                ReportLog::create([
                    'scheduled_report_id' => $report->id,
                    'sent_at' => now(),
                    'status' => 'failed',
                    'error_message' => $e->getMessage(),
                    'recipient_count' => 0
                ]);

                $this->error("✗ Failed to send: {$e->getMessage()}");
            }
        }

        $this->info('Done!');
        return 0;
    }

    private function generateReportData($report)
    {
        $startDate = Carbon::now()->subDays(30);
        $endDate = Carbon::now();

        $results = DB::table('transactions')
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->where('status', 'completed')
            ->selectRaw('
                DATE(timestamp) as date,
                COUNT(*) as transaction_count,
                SUM(total_amount) as total_sales
            ')
            ->groupBy('date')
            ->get();

        return [
            'results' => $results,
            'summary' => [
                'total_sales' => $results->sum('total_sales'),
                'transaction_count' => $results->sum('transaction_count')
            ]
        ];
    }

    private function generateReportFile($report, $reportData)
    {
        $filename = 'report_' . time() . '.csv';
        $path = storage_path('app/temp/' . $filename);

        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0755, true);
        }

        $file = fopen($path, 'w');

        if (!empty($reportData['results'])) {
            $firstRow = (array)$reportData['results'][0];
            fputcsv($file, array_keys($firstRow));

            foreach ($reportData['results'] as $row) {
                fputcsv($file, (array)$row);
            }
        }

        fclose($file);
        return $path;
    }
}
