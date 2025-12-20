<?php

namespace App\Http\Controllers\Analytics;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Models\Branch;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesAnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->isAdmin() ? $request->branch_id : $user->branch_id;

        // Date range filter (default: last 30 days)
        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : now()->subDays(30);

        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : now();

        // Get filter options
        $branches = Branch::where('status', 'active')->get();
        $categories = Category::orderBy('name')->get();

        // Build base query
        $baseQuery = Transaction::query()
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('timestamp', [$startDate, $endDate]);

        // Apply category filter
        if ($request->category_id) {
            $baseQuery->whereHas('transactionItems.product', function($q) use ($request) {
                $q->where('category_id', $request->category_id);
            });
        }

        // Apply cashier filter
        if ($request->cashier_id) {
            $baseQuery->where('cashier_id', $request->cashier_id);
        }

        // 1. SALES BY BRANCH
        $salesByBranch = Transaction::select(
                'branches.id',
                'branches.name',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as avg_transaction')
            )
            ->join('branches', 'transactions.branch_id', '=', 'branches.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.timestamp', [$startDate, $endDate])
            ->groupBy('branches.id', 'branches.name')
            ->orderByDesc('total_sales')
            ->get();

        // Calculate growth % for each branch (vs previous period)
        $periodDays = $startDate->diffInDays($endDate);
        $previousStart = $startDate->copy()->subDays($periodDays);
        $previousEnd = $startDate->copy()->subDay();

        $salesByBranch = $salesByBranch->map(function($branch) use ($previousStart, $previousEnd) {
            $previousSales = Transaction::where('branch_id', $branch->id)
                ->whereBetween('timestamp', [$previousStart, $previousEnd])
                ->sum('total_amount');

            if ($previousSales > 0) {
                $branch->growth = (($branch->total_sales - $previousSales) / $previousSales) * 100;
            } else {
                $branch->growth = $branch->total_sales > 0 ? 100 : 0;
            }

            return $branch;
        });

        // 2. SALES BY CATEGORY
        $salesByCategory = DB::table('transaction_items')
            ->select(
                'categories.name as category_name',
                DB::raw('SUM(transaction_items.quantity) as total_quantity'),
                DB::raw('SUM(transaction_items.subtotal) as total_sales')
            )
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.timestamp', [$startDate, $endDate])
            ->groupBy('categories.id', 'categories.name')
            ->orderByDesc('total_sales')
            ->get();

        $totalCategorySales = $salesByCategory->sum('total_sales');
        $salesByCategory = $salesByCategory->map(function($cat) use ($totalCategorySales) {
            $cat->percentage = $totalCategorySales > 0
                ? ($cat->total_sales / $totalCategorySales) * 100
                : 0;
            return $cat;
        });

        // 3. TOP 20 PRODUCTS
        $topProducts = DB::table('transaction_items')
            ->select(
                'products.sku',
                'products.name as product_name',
                'categories.name as category_name',
                DB::raw('SUM(transaction_items.quantity) as units_sold'),
                DB::raw('SUM(transaction_items.subtotal) as revenue'),
                DB::raw('AVG(products.price - products.cost) as avg_margin')
            )
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->join('categories', 'products.category_id', '=', 'categories.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.timestamp', [$startDate, $endDate])
            ->groupBy('products.id', 'products.sku', 'products.name', 'products.price', 'products.cost', 'categories.name')
            ->orderByDesc('revenue')
            ->limit(20)
            ->get();

        // 4. SALES HEATMAP (Hour x Day of Week)
        $heatmapData = Transaction::select(
                DB::raw('HOUR(timestamp) as hour'),
                DB::raw('DAYOFWEEK(timestamp) as day'),
                DB::raw('SUM(total_amount) as total_sales'),
                DB::raw('COUNT(*) as transaction_count')
            )
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->groupBy('hour', 'day')
            ->get();

        // Transform to matrix format
        $heatmap = [];
        for ($day = 1; $day <= 7; $day++) {
            for ($hour = 0; $hour < 24; $hour++) {
                $record = $heatmapData->first(function($item) use ($day, $hour) {
                    return $item->day == $day && $item->hour == $hour;
                });

                $heatmap[$day][$hour] = [
                    'sales' => $record ? $record->total_sales : 0,
                    'count' => $record ? $record->transaction_count : 0
                ];
            }
        }

        // 5. SALES BY CASHIER
        $salesByCashier = Transaction::select(
                'users.name as cashier_name',
                DB::raw('COUNT(transactions.id) as transaction_count'),
                DB::raw('SUM(transactions.total_amount) as total_sales'),
                DB::raw('AVG(transactions.total_amount) as avg_transaction')
            )
            ->join('users', 'transactions.cashier_id', '=', 'users.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.timestamp', [$startDate, $endDate])
            ->groupBy('users.id', 'users.name')
            ->orderByDesc('total_sales')
            ->limit(10)
            ->get();

        // Get all cashiers for filter
        $cashiers = User::whereNotNull('branch_id')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->orderBy('name')
            ->get();

        // Calculate max sales for heatmap intensity
        $maxSales = collect($heatmap)->flatten(1)->max('sales') ?: 1;

        return view('analytics.sales', compact(
            'branches',
            'categories',
            'cashiers',
            'salesByBranch',
            'salesByCategory',
            'topProducts',
            'heatmap',
            'maxSales',
            'salesByCashier',
            'startDate',
            'endDate',
            'branchId'
        ));
    }

    /**
     * NEW: Get heatmap cell details via AJAX
     */
    public function getHeatmapDetail(Request $request)
    {
        $user = auth()->user();
        $branchId = $user->isAdmin() ? $request->branch_id : $user->branch_id;

        $day = $request->day; // 1-7 (MySQL DAYOFWEEK)
        $hour = $request->hour; // 0-23

        $startDate = $request->start_date
            ? Carbon::parse($request->start_date)
            : now()->subDays(30);
        $endDate = $request->end_date
            ? Carbon::parse($request->end_date)
            : now();

        // Get transactions for this hour/day
        $transactions = Transaction::select('transactions.*')
            ->when($branchId, fn($q) => $q->where('branch_id', $branchId))
            ->whereBetween('timestamp', [$startDate, $endDate])
            ->whereRaw('HOUR(timestamp) = ?', [$hour])
            ->whereRaw('DAYOFWEEK(timestamp) = ?', [$day])
            ->get();

        $totalSales = $transactions->sum('total_amount');
        $transactionCount = $transactions->count();
        $avgTransaction = $transactionCount > 0 ? $totalSales / $transactionCount : 0;

        // Get top 3 products for this time slot
        $topProducts = DB::table('transaction_items')
            ->select(
                'products.name',
                DB::raw('SUM(transaction_items.quantity) as total_qty'),
                DB::raw('SUM(transaction_items.subtotal) as total_revenue')
            )
            ->join('transactions', 'transaction_items.transaction_id', '=', 'transactions.id')
            ->join('products', 'transaction_items.product_id', '=', 'products.id')
            ->when($branchId, fn($q) => $q->where('transactions.branch_id', $branchId))
            ->whereBetween('transactions.timestamp', [$startDate, $endDate])
            ->whereRaw('HOUR(transactions.timestamp) = ?', [$hour])
            ->whereRaw('DAYOFWEEK(transactions.timestamp) = ?', [$day])
            ->groupBy('products.id', 'products.name')
            ->orderByDesc('total_revenue')
            ->limit(3)
            ->get();

        return response()->json([
            'totalSales' => $totalSales,
            'transactionCount' => $transactionCount,
            'avgTransaction' => $avgTransaction,
            'topProducts' => $topProducts
        ]);
    }
}
