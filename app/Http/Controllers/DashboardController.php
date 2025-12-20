<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();

        // Get filters from request
        $dateRange = $request->get('date_range', 'last_30_days');
        $selectedBranchId = $request->get('branch_id', 'all');

        // Determine branch filter based on role
        if ($user->role === 'admin') {
            // Admin can filter by branch or see all
            $branchId = ($selectedBranchId === 'all') ? null : $selectedBranchId;
        } else {
            // Non-admins always see their own branch
            $branchId = $user->branch_id;
        }

        // Calculate date range
        $dateFilter = $this->getDateFilter($dateRange);
        $startDate = $dateFilter['start'];
        $endDate = $dateFilter['end'];

        // Today's Sales
        $todaySales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('timestamp', today())
            ->sum('total_amount');

        // Sales for selected period
        $periodSales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->sum('total_amount');

        // Monthly Sales (current month)
        $monthlySales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereYear('timestamp', now()->year)
            ->whereMonth('timestamp', now()->month)
            ->sum('total_amount');

        // YTD Sales
        $ytdSales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereYear('timestamp', now()->year)
            ->sum('total_amount');

        // Average Transaction Value (for selected period)
        $avgTransaction = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->avg('total_amount') ?? 0;

        // Transaction counts
        $todayTransactions = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('timestamp', today())
            ->count();

        $totalTransactions = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();

        // Product count
        $productCount = Product::where('status', 'active')->count();

        // Customer count
        $customerCount = Customer::count();

        // Top Branch (Admin only, and only if viewing all branches)
        $topBranch = null;
        if ($user->role === 'admin' && $selectedBranchId === 'all') {
            $topBranch = Transaction::select('branches.name', DB::raw('SUM(total_amount) as total'))
                ->join('branches', 'transactions.branch_id', '=', 'branches.id')
                ->whereBetween('timestamp', [$startDate, $endDate])
                ->groupBy('branches.id', 'branches.name')
                ->orderByDesc('total')
                ->first();
        }

        // Sales Trend (for selected period)
        $salesTrend = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select(
                DB::raw('DATE(timestamp) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 Products (for selected period)
        $topProducts = DB::table('transaction_items')
            ->select(
                'transaction_items.product_name',
                DB::raw('SUM(transaction_items.quantity) as total_quantity'),
                DB::raw('SUM(transaction_items.subtotal) as total_sales')
            )
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.timestamp', [$startDate, $endDate])
            ->groupBy('transaction_items.product_name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // Payment Method Breakdown (for selected period)
        $paymentMethods = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total'))
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->groupBy('payment_method')
            ->get();

        // Get all branches for dropdown (admin only)
        $branches = ($user->role === 'admin') ? Branch::where('status', 'active')->get() : collect();

        return view('dashboard', compact(
            'todaySales',
            'monthlySales',
            'ytdSales',
            'avgTransaction',
            'todayTransactions',
            'totalTransactions',
            'productCount',
            'customerCount',
            'topBranch',
            'salesTrend',
            'topProducts',
            'paymentMethods',
            'dateRange',
            'selectedBranchId',
            'branches',
            'periodSales',
            'startDate',
            'endDate'
        ));
    }

    /**
     * Calculate start and end dates based on selected range
     */
    private function getDateFilter($dateRange)
    {
        $end = now();

        switch ($dateRange) {
            case 'today':
                $start = now()->startOfDay();
                break;
            case 'last_7_days':
                $start = now()->subDays(7)->startOfDay();
                break;
            case 'last_30_days':
                $start = now()->subDays(30)->startOfDay();
                break;
            case 'this_month':
                $start = now()->startOfMonth();
                break;
            case 'last_month':
                $start = now()->subMonth()->startOfMonth();
                $end = now()->subMonth()->endOfMonth();
                break;
            case 'this_year':
                $start = now()->startOfYear();
                break;
            default:
                $start = now()->subDays(30)->startOfDay();
        }

        return [
            'start' => $start,
            'end' => $end,
        ];
    }
}
