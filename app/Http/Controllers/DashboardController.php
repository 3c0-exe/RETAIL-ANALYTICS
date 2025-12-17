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
        $branchId = $user->isAdmin() ? null : $user->branch_id;

        // Today's Sales
        $todaySales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('transaction_date', today())
            ->sum('total');

        // Monthly Sales (current month)
        $monthlySales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereYear('transaction_date', now()->year)
            ->whereMonth('transaction_date', now()->month)
            ->sum('total');

        // YTD Sales
        $ytdSales = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereYear('transaction_date', now()->year)
            ->sum('total');

        // Average Transaction Value
        $avgTransaction = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('transaction_date', '>=', now()->subDays(30))
            ->avg('total') ?? 0;

        // Transaction counts
        $todayTransactions = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereDate('transaction_date', today())
            ->count();

        $totalTransactions = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->count();

        // Product count
        $productCount = Product::active()->count();

        // Customer count
        $customerCount = Customer::count();

        // Top Branch (Admin only)
        $topBranch = null;
        if ($user->isAdmin()) {
            $topBranch = Transaction::select('branches.name', DB::raw('SUM(total) as total'))
                ->join('branches', 'transactions.branch_id', '=', 'branches.id')
                ->whereMonth('transaction_date', now()->month)
                ->groupBy('branches.id', 'branches.name')
                ->orderByDesc('total')
                ->first();
        }

        // Sales Trend (Last 30 days)
        $salesTrend = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select(
                DB::raw('DATE(transaction_date) as date'),
                DB::raw('SUM(total) as total')
            )
            ->whereDate('transaction_date', '>=', now()->subDays(30))
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
            ->whereDate('transactions.transaction_date', '>=', now()->subDays(30))
            ->groupBy('transaction_items.product_name')
            ->orderByDesc('total_sales')
            ->limit(5)
            ->get();

        // Payment Method Breakdown
        $paymentMethods = Transaction::when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->select('payment_method', DB::raw('COUNT(*) as count'), DB::raw('SUM(total) as total'))
            ->whereMonth('transaction_date', now()->month)
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
