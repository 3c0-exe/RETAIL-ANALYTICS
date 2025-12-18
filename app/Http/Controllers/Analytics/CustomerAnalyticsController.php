<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Transaction;
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

        // 2. TOP 100 CUSTOMERS
        $topCustomers = Customer::query()
            ->when($segmentFilter, fn($q) => $q->where('segment', $segmentFilter))
            ->orderByDesc('total_spent')
            ->limit(100)
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
            'segments',
            'startDate',
            'endDate',
            'segmentFilter'
        ));
    }

    public function show(Customer $customer)
    {
        // Load customer transactions
        $customer->load(['transactions' => function($q) {
            $q->orderBy('transaction_date', 'desc');
        }]);

        // Calculate additional metrics
        $avgOrderValue = $customer->transactions()->avg('total') ?? 0;
        $lastPurchase = $customer->transactions()->max('transaction_date');
        $daysSinceLastPurchase = $lastPurchase
            ? Carbon::parse($lastPurchase)->diffInDays(now())
            : null;

        // Monthly purchase trend
        $monthlyTrend = $customer->transactions()
            ->select(
                DB::raw('DATE_FORMAT(transaction_date, "%Y-%m") as month'),
                DB::raw('COUNT(*) as count'),
                DB::raw('SUM(total) as total')
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
