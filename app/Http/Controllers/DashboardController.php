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
    public function index()
    {
        $user = auth()->user();

        // Get branch filter based on role
        $branchId = ($user->role === 'admin') ? null : $user->branch_id;

        // Today's Sales
        $todaySales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('timestamp', today()) // FIXED: was transaction_date
            ->sum('total_amount'); // FIXED: was total

        // Monthly Sales (current month)
        $monthlySales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereYear('timestamp', now()->year) // FIXED
            ->whereMonth('timestamp', now()->month) // FIXED
            ->sum('total_amount'); // FIXED

        // YTD Sales
        $ytdSales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereYear('timestamp', now()->year) // FIXED
            ->sum('total_amount'); // FIXED

        // Average Transaction Value
        $avgTransaction = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('timestamp', '>=', now()->subDays(30)) // FIXED
            ->avg('total_amount') ?? 0; // FIXED

        // Transaction counts
        $todayTransactions = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('timestamp', today()) // FIXED
            ->count();

        $totalTransactions = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();

        // Product count
        // FIXED: Changed 'is_active' to 'status' based on your migrations
        $productCount = Product::where('status', 'active')->count();

        // Customer count
        $customerCount = Customer::count();

        // Top Branch (Admin only)
        $topBranch = null;
        if ($user->role === 'admin') {
            $topBranch = Transaction::select('branches.name', DB::raw('SUM(total_amount) as total')) // FIXED: Summing total_amount
                ->join('branches', 'transactions.branch_id', '=', 'branches.id')
                ->whereMonth('timestamp', now()->month) // FIXED
                ->groupBy('branches.id', 'branches.name')
                ->orderByDesc('total')
                ->first();
        }

        // Sales Trend (Last 30 days)
        $salesTrend = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select(
                DB::raw('DATE(timestamp) as date'), // FIXED
                DB::raw('SUM(total_amount) as total') // FIXED
            )
            ->whereDate('timestamp', '>=', now()->subDays(30)) // FIXED
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Top 5 Products
        $topProducts = DB::table('transaction_items')
            ->select(
                'transaction_items.product_name',
                DB::raw('SUM(transaction_items.quantity) as total_quantity'),
                DB::raw('SUM(transaction_items.subtotal) as total_sales')
            )
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereDate('transactions.timestamp', '>=', now()->subDays(30)) // FIXED
            ->groupBy('transaction_items.product_name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // Payment Method Breakdown
        $paymentMethods = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total_amount) as total')) // FIXED
            ->whereMonth('timestamp', now()->month) // FIXED
            ->groupBy('payment_method')
            ->get();

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
            'paymentMethods'
        ));
    }
}
