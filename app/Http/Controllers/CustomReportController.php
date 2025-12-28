<?php

namespace App\Http\Controllers;

use App\Models\CustomReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class CustomReportController extends Controller
{
    /**
     * Display custom report builder page
     */
    public function index()
    {
        $savedReports = auth()->user()->customReports()->latest()->get();

        return view('reports.custom-builder', compact('savedReports'));
    }

    /**
     * List all saved reports for current user (API endpoint)
     */
    public function list()
    {
        $reports = auth()->user()->customReports()->latest()->get();
        return response()->json($reports);
    }

    /**
     * Generate custom report based on user configuration
     */
    public function generate(Request $request)
    {
        try {
            $validated = $request->validate([
                'metrics' => 'required|array',
                'metrics.*' => 'string|in:total_sales,transaction_count,avg_transaction,customer_count,product_count',
                'dimensions' => 'required|array',
                'dimensions.*' => 'string|in:branch,category,product,date,month',
                'date_range' => 'required|array',
                'date_range.start' => 'required|date',
                'date_range.end' => 'required|date|after_or_equal:date_range.start',
                'chart_type' => 'required|string|in:bar,line,pie,table'
            ]);

            Log::info('Report generation started', $validated);

            $metrics = $validated['metrics'];
            $dimensions = $validated['dimensions'];
            $startDate = Carbon::parse($validated['date_range']['start'])->startOfDay();
            $endDate = Carbon::parse($validated['date_range']['end'])->endOfDay();

            // Build dynamic query based on selections
            $data = $this->buildQuery($metrics, $dimensions, $startDate, $endDate);

            return response()->json([
                'data' => $data,
                'config' => $validated
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed', ['errors' => $e->errors()]);
            return response()->json([
                'error' => 'Validation failed',
                'details' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            Log::error('Report generation failed', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to generate report',
                'message' => $e->getMessage(),
                'details' => config('app.debug') ? $e->getTraceAsString() : 'Enable debug mode for details'
            ], 500);
        }
    }

    /**
     * Save custom report configuration
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'type' => 'required|string|in:sales,customers,products',
                'config' => 'required|array',
                'is_favorite' => 'boolean'
            ]);

            $report = auth()->user()->customReports()->create($validated);

            return response()->json([
                'message' => 'Report saved successfully',
                'report' => $report
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save report', ['error' => $e->getMessage()]);
            return response()->json(['error' => 'Failed to save report'], 500);
        }
    }

    /**
     * Load saved report
     */
    public function show(CustomReport $report)
    {
        // Ensure user owns this report
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        // Generate data using saved config
        $config = $report->config;
        $data = $this->buildQuery(
            $config['metrics'],
            $config['dimensions'],
            Carbon::parse($config['date_range']['start']),
            Carbon::parse($config['date_range']['end'])
        );

        return response()->json([
            'report' => $report,
            'data' => $data
        ]);
    }

    /**
     * Delete saved report
     */
    public function destroy(CustomReport $report)
    {
        if ($report->user_id !== auth()->id()) {
            abort(403);
        }

        $report->delete();

        return response()->json(['message' => 'Report deleted successfully']);
    }

    /**
     * Build dynamic query based on metrics and dimensions
     */
    private function buildQuery($metrics, $dimensions, $startDate, $endDate)
    {
        // Start with base query
        $query = DB::table('transactions')
            ->whereBetween('transactions.timestamp', [$startDate, $endDate]);

        // Check if status column exists, otherwise skip this filter
        if (DB::getSchemaBuilder()->hasColumn('transactions', 'status')) {
            $query->where('transactions.status', 'completed');
        }

        $hasTransactionItems = false;
        $hasProducts = false;
        $hasCategories = false;

        // Add joins based on dimensions
        if (in_array('branch', $dimensions)) {
            $query->join('branches', 'transactions.branch_id', '=', 'branches.id');
            $query->addSelect('branches.name as branch_name', 'branches.id as branch_id');
        }

        if (in_array('category', $dimensions) || in_array('product', $dimensions)) {
            if (!$hasTransactionItems) {
                $query->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id');
                $hasTransactionItems = true;
            }

            if (!$hasProducts) {
                $query->join('products', 'transaction_items.product_id', '=', 'products.id');
                $hasProducts = true;
            }
        }

        if (in_array('category', $dimensions)) {
            if (!$hasCategories) {
                $query->join('categories', 'products.category_id', '=', 'categories.id');
                $hasCategories = true;
            }
            $query->addSelect('categories.name as category_name', 'categories.id as category_id');
        }

        if (in_array('product', $dimensions)) {
            $query->addSelect('products.name as product_name', 'products.id as product_id');
        }

        if (in_array('date', $dimensions)) {
            $query->addSelect(DB::raw('DATE(transactions.timestamp) as date'));
        }

        if (in_array('month', $dimensions)) {
            $query->addSelect(DB::raw('DATE_FORMAT(transactions.timestamp, "%Y-%m") as month'));
        }

        // Add metrics
        foreach ($metrics as $metric) {
            switch ($metric) {
                case 'total_sales':
                    $query->addSelect(DB::raw('COALESCE(SUM(transactions.total_amount), 0) as total_sales'));
                    break;
                case 'transaction_count':
                    $query->addSelect(DB::raw('COUNT(DISTINCT transactions.id) as transaction_count'));
                    break;
                case 'avg_transaction':
                    $query->addSelect(DB::raw('COALESCE(AVG(transactions.total_amount), 0) as avg_transaction'));
                    break;
                case 'customer_count':
                    $query->addSelect(DB::raw('COUNT(DISTINCT transactions.customer_id) as customer_count'));
                    break;
                case 'product_count':
                    if (!$hasTransactionItems) {
                        $query->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id');
                        $hasTransactionItems = true;
                    }
                    $query->addSelect(DB::raw('COALESCE(SUM(transaction_items.quantity), 0) as product_count'));
                    break;
            }
        }

        // Add GROUP BY clauses
        $groupByColumns = [];

        if (in_array('branch', $dimensions)) {
            $groupByColumns[] = 'branches.id';
            $groupByColumns[] = 'branches.name';
        }
        if (in_array('category', $dimensions)) {
            $groupByColumns[] = 'categories.id';
            $groupByColumns[] = 'categories.name';
        }
        if (in_array('product', $dimensions)) {
            $groupByColumns[] = 'products.id';
            $groupByColumns[] = 'products.name';
        }
        if (in_array('date', $dimensions)) {
            $groupByColumns[] = DB::raw('DATE(transactions.timestamp)');
        }
        if (in_array('month', $dimensions)) {
            $groupByColumns[] = DB::raw('DATE_FORMAT(transactions.timestamp, "%Y-%m")');
        }

        if (!empty($groupByColumns)) {
            $query->groupBy($groupByColumns);
        }

        // Order by first metric descending
        if (!empty($metrics)) {
            $firstMetric = $metrics[0];
            $query->orderByDesc($firstMetric);
        }

        Log::info('Generated SQL', ['sql' => $query->toSql(), 'bindings' => $query->getBindings()]);

        $results = $query->limit(50)->get();

        return [
            'results' => $results,
            'summary' => $this->calculateSummary($results, $metrics)
        ];
    }

    /**
     * Calculate summary statistics
     */
    private function calculateSummary($results, $metrics)
    {
        $summary = [];

        foreach ($metrics as $metric) {
            if ($results->isEmpty()) {
                $summary[$metric] = 0;
                continue;
            }

            switch ($metric) {
                case 'total_sales':
                    $summary[$metric] = round($results->sum('total_sales'), 2);
                    break;
                case 'transaction_count':
                    $summary[$metric] = $results->sum('transaction_count');
                    break;
                case 'avg_transaction':
                    $summary[$metric] = round($results->avg('avg_transaction'), 2);
                    break;
                case 'customer_count':
                    $summary[$metric] = $results->sum('customer_count');
                    break;
                case 'product_count':
                    $summary[$metric] = $results->sum('product_count');
                    break;
            }
        }

        return $summary;
    }
}
