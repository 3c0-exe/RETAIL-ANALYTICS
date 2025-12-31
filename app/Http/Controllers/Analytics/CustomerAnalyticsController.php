<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Branch;
use App\Traits\DateFilterTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerAnalyticsController extends Controller
{
    use DateFilterTrait;

    public function index(Request $request)
    {
        $user = auth()->user();

        // Get date range (defaults to actual data range)
        $dateRange = $this->getDateRange($request);
        $startDate = $dateRange['start'];
        $endDate = $dateRange['end'];

        // Segment filter
        $segmentFilter = $request->segment;

        // Base query
        $customersQuery = Customer::query()
            ->when($segmentFilter, fn($q) => $q->where('segment', $segmentFilter));

        // 1. CUSTOMER SEGMENT BREAKDOWN
        $segmentStats = Customer::select('segment', DB::raw('count(*) as count'))
            ->groupBy('segment')
            ->get()
            ->mapWithKeys(function($item) {
                return [$item->segment => $item->count];
            });

        $segmentData = [
            'vip' => $segmentStats['vip'] ?? 0,
            'loyal' => $segmentStats['loyal'] ?? 0,
            'regular' => $segmentStats['regular'] ?? 0,
            'at_risk' => $segmentStats['at_risk'] ?? 0,
            'new' => $segmentStats['new'] ?? 0,
            'dormant' => $segmentStats['dormant'] ?? 0,
        ];

        // 2. TOP 20 CUSTOMERS
        $topCustomers = Customer::query()
            ->when($segmentFilter, fn($q) => $q->where('segment', $segmentFilter))
            ->orderByDesc('total_spent')
            ->limit(20)
            ->get();

        // 3. CUSTOMER LIFETIME VALUE DISTRIBUTION
        $clvDistribution = Customer::select(
                DB::raw('CASE
                    WHEN total_spent >= 50000 THEN "50K+"
                    WHEN total_spent >= 20000 THEN "20K-50K"
                    WHEN total_spent >= 10000 THEN "10K-20K"
                    WHEN total_spent >= 5000 THEN "5K-10K"
                    ELSE "0-5K"
                END as bracket'),
                DB::raw('COUNT(*) as count')
            )
            ->groupBy('bracket')
            ->get();

        // 4. COHORT ANALYSIS (Monthly cohorts)
        $cohorts = DB::table('customers')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as cohort'),
                DB::raw('COUNT(*) as customers'),
                DB::raw('SUM(total_spent) as total_revenue'),
                DB::raw('AVG(visit_count) as avg_visits')
            )
            ->groupBy('cohort')
            ->orderBy('cohort', 'desc')
            ->limit(12)
            ->get();

        // 5. KEY METRICS
        $totalCustomers = Customer::count();
        $activeCustomers = Customer::whereIn('segment', ['vip', 'loyal', 'regular', 'new'])->count();
        $avgLifetimeValue = Customer::avg('total_spent') ?? 0;
        $avgVisitCount = Customer::avg('visit_count') ?? 0;

        // Customer acquisition by month
        $customerAcquisition = Customer::select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count')
            )
            ->whereBetween('created_at', [$startDate, $endDate])
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        // 6. RFM DISTRIBUTION
        $rfmDistribution = Customer::whereNotNull('rfm_score')
            ->get()
            ->groupBy(function($customer) {
                $total = $customer->getTotalRfmScore();
                if ($total >= 13) return '13-15 (Excellent)';
                if ($total >= 10) return '10-12 (Good)';
                if ($total >= 7) return '7-9 (Average)';
                if ($total >= 4) return '4-6 (Below Average)';
                return '3 (Poor)';
            })
            ->map(fn($group) => $group->count());

        // 7. PURCHASE PATTERNS BY DEMOGRAPHICS

        // A. By Branch
        $purchasesByBranch = Transaction::whereBetween('timestamp', [$startDate, $endDate])
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
            ->join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->select(
                'branches.name as branch_name',
                DB::raw('COUNT(DISTINCT customers.id) as customer_count'),
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as avg_transaction')
            )
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('total_sales')
            ->get();

        // B. By Age Group
        $purchasesByAge = Transaction::whereBetween('timestamp', [$startDate, $endDate])
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
            ->whereNotNull('customers.age')
            ->select(
                DB::raw('CASE
                    WHEN customers.age < 18 THEN "Under 18"
                    WHEN customers.age BETWEEN 18 AND 25 THEN "18-25"
                    WHEN customers.age BETWEEN 26 AND 35 THEN "26-35"
                    WHEN customers.age BETWEEN 36 AND 45 THEN "36-45"
                    WHEN customers.age BETWEEN 46 AND 55 THEN "46-55"
                    ELSE "56+"
                END as age_group'),
                DB::raw('COUNT(DISTINCT customers.id) as customer_count'),
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as avg_transaction')
            )
            ->groupBy('age_group')
            ->orderByRaw('MIN(customers.age)')
            ->get();

        // C. By Gender
        $purchasesByGender = Transaction::whereBetween('timestamp', [$startDate, $endDate])
            ->join('customers', 'transactions.customer_id', '=', 'customers.id')
            ->whereNotNull('customers.gender')
            ->select(
                'customers.gender',
                DB::raw('COUNT(DISTINCT customers.id) as customer_count'),
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as avg_transaction')
            )
            ->groupBy('customers.gender')
            ->orderByDesc('total_sales')
            ->get();

        // 8. TOP PRODUCTS BY DEMOGRAPHICS
        $topProductsByDemographic = $this->getTopProductsByDemographic();

        // Segments list for filter
        $segments = [
            'vip' => 'VIP',
            'loyal' => 'Loyal',
            'regular' => 'Regular',
            'at_risk' => 'At Risk',
            'new' => 'New',
            'dormant' => 'Dormant'
        ];

        // Date range display
        $dateDisplay = $this->getDateRangeDisplay($startDate, $endDate);

        return view('analytics.customers', compact(
            'segmentData',
            'topCustomers',
            'clvDistribution',
            'cohorts',
            'totalCustomers',
            'activeCustomers',
            'avgLifetimeValue',
            'avgVisitCount',
            'customerAcquisition',
            'rfmDistribution',
            'purchasesByBranch',
            'purchasesByAge',
            'purchasesByGender',
            'topProductsByDemographic',
            'segments',
            'startDate',
            'endDate',
            'segmentFilter',
            'dateDisplay'
        ));
    }

    /**
     * Get top products purchased by each demographic segment
     */
    private function getTopProductsByDemographic()
    {
        // Top products by Age Group
        $byAge = DB::table('customers')
            ->join('transactions', 'customers.id', '=', 'transactions.customer_id')
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereNotNull('customers.age')
            ->where('transactions.status', 'completed')
            ->select(
                DB::raw("CASE
                    WHEN customers.age BETWEEN 18 AND 25 THEN '18-25'
                    WHEN customers.age BETWEEN 26 AND 35 THEN '26-35'
                    WHEN customers.age BETWEEN 36 AND 45 THEN '36-45'
                    WHEN customers.age BETWEEN 46 AND 55 THEN '46-55'
                    ELSE '56+'
                END as age_group"),
                'products.name as product_name',
                'products.category_id',
                DB::raw('SUM(transaction_items.quantity) as total_quantity'),
                DB::raw('SUM(transaction_items.subtotal) as total_sales'),
                DB::raw('COUNT(DISTINCT transactions.id) as purchase_count')
            )
            ->groupBy('age_group', 'products.id', 'products.name', 'products.category_id')
            ->get()
            ->groupBy('age_group')
            ->map(function ($products) {
                return $products->sortByDesc('total_sales')->take(5)->values();
            });

        // Top products by Gender
        $byGender = DB::table('customers')
            ->join('transactions', 'customers.id', '=', 'transactions.customer_id')
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->whereNotNull('customers.gender')
            ->where('transactions.status', 'completed')
            ->select(
                'customers.gender',
                'products.name as product_name',
                'products.category_id',
                DB::raw('SUM(transaction_items.quantity) as total_quantity'),
                DB::raw('SUM(transaction_items.subtotal) as total_sales'),
                DB::raw('COUNT(DISTINCT transactions.id) as purchase_count')
            )
            ->groupBy('customers.gender', 'products.id', 'products.name', 'products.category_id')
            ->get()
            ->groupBy('gender')
            ->map(function ($products) {
                return $products->sortByDesc('total_sales')->take(5)->values();
            });

        // Top products by Branch
        $byBranch = DB::table('branches')
            ->join('transactions', 'branches.id', '=', 'transactions.branch_id')
            ->join('transaction_items', 'transactions.id', '=', 'transaction_items.transaction_id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->where('transactions.status', 'completed')
            ->select(
                'branches.name as branch_name',
                'products.name as product_name',
                'products.category_id',
                DB::raw('SUM(transaction_items.quantity) as total_quantity'),
                DB::raw('SUM(transaction_items.subtotal) as total_sales'),
                DB::raw('COUNT(DISTINCT transactions.id) as purchase_count')
            )
            ->groupBy('branches.id', 'branches.name', 'products.id', 'products.name', 'products.category_id')
            ->get()
            ->groupBy('branch_name')
            ->map(function ($products) {
                return $products->sortByDesc('total_sales')->take(5)->values();
            });

        return [
            'by_age' => $byAge,
            'by_gender' => $byGender,
            'by_branch' => $byBranch
        ];
    }

    public function show(Customer $customer)
    {
        // Load customer transactions
        $customer->load(['transactions' => function($q) {
            $q->orderBy('timestamp', 'desc');
        }]);

        // Calculate additional metrics
        $avgOrderValue = $customer->transactions()->avg('total_amount') ?? 0;

        // Get last purchase timestamp
        $lastPurchase = $customer->transactions()->max('timestamp');

        // Calculate days since last purchase
        if ($lastPurchase) {
            $daysSinceLastPurchase = Carbon::parse($lastPurchase)->diffInDays(now());
        } elseif ($customer->last_visit_date) {
            $daysSinceLastPurchase = $customer->last_visit_date->diffInDays(now());
        } else {
            $daysSinceLastPurchase = null;
        }

        // Ensure last_visit_date is properly loaded
        if ($customer->last_visit_date && !($customer->last_visit_date instanceof Carbon)) {
            $customer->last_visit_date = Carbon::parse($customer->last_visit_date);
        }
        // Monthly purchase trend
        $monthlyTrend = $customer->transactions()
            ->select(
                DB::raw('DATE_FORMAT(timestamp, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total_amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        return view('analytics.customer-detail', compact(
            'customer',
            'avgOrderValue',
            'daysSinceLastPurchase',
            'monthlyTrend'
        ));
    }


    /**
     * Get product combination analysis (Market Basket Analysis)
     */
    public function getProductCombinations(Request $request)
    {
        $minSupport = $request->get('min_support', 3);
        $limit = $request->get('limit', 20);

        // Find product pairs bought together in same transaction
        $productPairs = DB::table('transaction_items as ti1')
            ->join('transaction_items as ti2', function($join) {
                $join->on('ti1.transaction_id', '=', 'ti2.transaction_id')
                     ->whereColumn('ti1.product_id', '<', 'ti2.product_id');
            })
            ->join('products as p1', 'ti1.product_id', '=', 'p1.id')
            ->join('products as p2', 'ti2.product_id', '=', 'p2.id')
            ->join('transactions', 'ti1.transaction_id', '=', 'transactions.id')
            ->where('transactions.status', 'completed')
            ->select(
                'p1.id as product_a_id',
                'p1.name as product_a',
                'p2.id as product_b_id',
                'p2.name as product_b',
                DB::raw('COUNT(DISTINCT ti1.transaction_id) as frequency'),
                DB::raw('SUM(ti1.quantity + ti2.quantity) as total_quantity')
            )
            ->groupBy('p1.id', 'p1.name', 'p2.id', 'p2.name')
            ->having('frequency', '>=', $minSupport)
            ->orderByDesc('frequency')
            ->limit($limit)
            ->get();

        $totalTransactions = DB::table('transactions')
            ->where('status', 'completed')
            ->count();

        $productPairs = $productPairs->map(function($pair) use ($totalTransactions) {
            $pair->support = ($pair->frequency / $totalTransactions) * 100;

            $productACount = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->where('transaction_items.product_id', $pair->product_a_id)
                ->where('transactions.status', 'completed')
                ->distinct('transaction_items.transaction_id')
                ->count('transaction_items.transaction_id');

            $productBCount = DB::table('transaction_items')
                ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
                ->where('transaction_items.product_id', $pair->product_b_id)
                ->where('transactions.status', 'completed')
                ->distinct('transaction_items.transaction_id')
                ->count('transaction_items.transaction_id');

            $pair->confidence_a_to_b = $productACount > 0
                ? ($pair->frequency / $productACount) * 100
                : 0;

            $pair->confidence_b_to_a = $productBCount > 0
                ? ($pair->frequency / $productBCount) * 100
                : 0;

            $expectedFrequency = ($productACount * $productBCount) / $totalTransactions;
            $pair->lift = $expectedFrequency > 0
                ? $pair->frequency / $expectedFrequency
                : 0;

            $pair->revenue_impact = DB::table('transaction_items as ti1')
                ->join('transaction_items as ti2', 'ti1.transaction_id', '=', 'ti2.transaction_id')
                ->where('ti1.product_id', $pair->product_a_id)
                ->where('ti2.product_id', $pair->product_b_id)
                ->sum(DB::raw('ti1.subtotal + ti2.subtotal'));

            return $pair;
        });

        return response()->json([
            'pairs' => $productPairs,
            'total_transactions' => $totalTransactions,
            'min_support' => $minSupport
        ]);
    }

    /**
     * Get "Frequently Bought Together" recommendations
     */
    public function getFrequentlyBoughtTogether(Request $request, $productId)
    {
        $limit = $request->get('limit', 5);

        $recommendations = DB::table('transaction_items as ti1')
            ->join('transaction_items as ti2', 'ti1.transaction_id', '=', 'ti2.transaction_id')
            ->join('products as p', 'ti2.product_id', '=', 'p.id')
            ->join('transactions', 'ti1.transaction_id', '=', 'transactions.id')
            ->where('ti1.product_id', $productId)
            ->where('ti2.product_id', '!=', $productId)
            ->where('transactions.status', 'completed')
            ->select(
                'p.id',
                'p.name',
                'p.category_id',
                DB::raw('COUNT(DISTINCT ti1.transaction_id) as times_bought_together'),
                DB::raw('SUM(ti2.quantity) as total_quantity_sold'),
                DB::raw('SUM(ti2.subtotal) as total_revenue')
            )
            ->groupBy('p.id', 'p.name', 'p.category_id')
            ->orderByDesc('times_bought_together')
            ->limit($limit)
            ->get();

        $sourceProductTransactionCount = DB::table('transaction_items')
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->where('transaction_items.product_id', $productId)
            ->where('transactions.status', 'completed')
            ->distinct('transaction_items.transaction_id')
            ->count('transaction_items.transaction_id');

        $recommendations = $recommendations->map(function($rec) use ($sourceProductTransactionCount) {
            $rec->confidence = $sourceProductTransactionCount > 0
                ? ($rec->times_bought_together / $sourceProductTransactionCount) * 100
                : 0;
            return $rec;
        });

        return response()->json([
            'product_id' => $productId,
            'recommendations' => $recommendations,
            'source_transaction_count' => $sourceProductTransactionCount
        ]);
    }

    /**
     * Get cohort retention analysis
     */
    public function getCohortRetention(Request $request)
    {
        $monthsToTrack = $request->get('months', 12);

        $cohorts = DB::table('customers')
            ->select(
                DB::raw('DATE_FORMAT(created_at, "%Y-%m") as cohort_month'),
                DB::raw('COUNT(*) as cohort_size')
            )
            ->groupBy('cohort_month')
            ->orderBy('cohort_month', 'desc')
            ->limit($monthsToTrack)
            ->get()
            ->keyBy('cohort_month');

        if ($cohorts->isEmpty()) {
            return response()->json([
                'cohorts' => [],
                'retention_curves' => [],
                'metrics' => [
                    'avg_retention_rate' => 0,
                    'avg_churn_rate' => 0,
                    'total_cohorts' => 0,
                    'avg_cohort_size' => 0
                ]
            ]);
        }

        $retentionMatrix = [];

        foreach ($cohorts as $cohortMonth => $cohortData) {
            $cohortStart = Carbon::parse($cohortMonth . '-01');
            $cohortSize = $cohortData->cohort_size;

            $cohortCustomerIds = DB::table('customers')
                ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$cohortMonth])
                ->pluck('id');

            if ($cohortCustomerIds->isEmpty()) {
                continue;
            }

            $retentionData = [
                'cohort' => $cohortMonth,
                'cohort_size' => $cohortSize,
                'months' => []
            ];

            for ($i = 0; $i <= 11; $i++) {
                $periodStart = $cohortStart->copy()->addMonths($i);
                $periodEnd = $periodStart->copy()->endOfMonth();

                if ($periodStart->isFuture()) {
                    break;
                }

                $activeCustomers = DB::table('transactions')
                    ->whereIn('customer_id', $cohortCustomerIds)
                    ->whereBetween('timestamp', [$periodStart, $periodEnd])
                    ->where('status', 'completed')
                    ->distinct('customer_id')
                    ->count('customer_id');

                $retentionRate = $cohortSize > 0 ? ($activeCustomers / $cohortSize) * 100 : 0;

                $retentionData['months'][] = [
                    'month_index' => $i,
                    'period' => $periodStart->format('Y-m'),
                    'active_customers' => $activeCustomers,
                    'retention_rate' => round($retentionRate, 2),
                    'churned' => $cohortSize - $activeCustomers
                ];
            }

            $retentionMatrix[] = $retentionData;
        }

        $allRetentionRates = [];
        foreach ($retentionMatrix as $cohort) {
            foreach ($cohort['months'] as $month) {
                if ($month['month_index'] > 0) {
                    $allRetentionRates[] = $month['retention_rate'];
                }
            }
        }

        $avgRetentionRate = !empty($allRetentionRates)
            ? array_sum($allRetentionRates) / count($allRetentionRates)
            : 0;

        $avgChurnRate = 100 - $avgRetentionRate;

        $retentionCurves = [];
        for ($i = 0; $i <= 11; $i++) {
            $ratesForMonth = [];
            foreach ($retentionMatrix as $cohort) {
                if (isset($cohort['months'][$i])) {
                    $ratesForMonth[] = $cohort['months'][$i]['retention_rate'];
                }
            }

            if (!empty($ratesForMonth)) {
                $retentionCurves[] = [
                    'month_index' => $i,
                    'avg_retention' => round(array_sum($ratesForMonth) / count($ratesForMonth), 2),
                    'min_retention' => round(min($ratesForMonth), 2),
                    'max_retention' => round(max($ratesForMonth), 2)
                ];
            }
        }

        return response()->json([
            'cohorts' => array_values($retentionMatrix),
            'retention_curves' => $retentionCurves,
            'metrics' => [
                'avg_retention_rate' => round($avgRetentionRate, 2),
                'avg_churn_rate' => round($avgChurnRate, 2),
                'total_cohorts' => count($retentionMatrix),
                'avg_cohort_size' => round($cohorts->avg('cohort_size'))
            ]
        ]);
    }

    /**
     * Export cohort retention data as CSV
     */
    public function exportCohortRetention(Request $request)
    {
        $retentionData = $this->getCohortRetention($request);
        $data = json_decode($retentionData->getContent(), true);

        $filename = 'cohort_retention_' . now()->format('Y-m-d') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];

        $callback = function() use ($data) {
            $file = fopen('php://output', 'w');

            $headerRow = ['Cohort', 'Cohort Size'];
            for ($i = 0; $i <= 11; $i++) {
                $headerRow[] = "Month $i";
            }
            fputcsv($file, $headerRow);

            foreach ($data['cohorts'] as $cohort) {
                $row = [$cohort['cohort'], $cohort['cohort_size']];

                foreach ($cohort['months'] as $month) {
                    $row[] = $month['retention_rate'] . '%';
                }

                fputcsv($file, $row);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
