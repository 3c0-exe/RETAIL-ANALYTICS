<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CustomerAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Date range filter (default: all time)
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : Carbon::create(2020, 1, 1);

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : now();

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

        // 2. TOP 20 CUSTOMERS (changed from 100)
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
            'segmentFilter'
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
        $lastPurchase = $customer->transactions()->max('timestamp');
        $daysSinceLastPurchase = $lastPurchase
            ? Carbon::parse($lastPurchase)->diffInDays(now())
            : null;

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
}
