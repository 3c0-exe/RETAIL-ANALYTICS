<?php

// app/Console/Commands/CheckAlertsCommand.php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NotificationService;
use App\Models\BranchProduct;
use App\Models\Transaction;
use App\Models\Branch;
use Carbon\Carbon;

class CheckAlertsCommand extends Command
{
    protected $signature = 'alerts:check';
    protected $description = 'Check for various alert conditions';

    public function handle(NotificationService $notificationService)
    {
        $this->info('Checking for alerts...');

        $this->checkOutOfStock($notificationService);
        $this->checkOverstock($notificationService);
        $this->checkSalesDrops($notificationService);
        $this->checkDailySummary($notificationService);

        $this->info('Alert check completed!');
    }

    /**
     * Check for out of stock products
     */
    private function checkOutOfStock(NotificationService $service): void
    {
        $outOfStockProducts = BranchProduct::with(['product', 'branch'])
            ->where('quantity', '<=', 0)
            ->get();

        foreach ($outOfStockProducts as $branchProduct) {
            // Check if alert already exists today
            $existsToday = \App\Models\Alert::where('type', 'out_of_stock')
                ->where('related_type', get_class($branchProduct))
                ->where('related_id', $branchProduct->id)
                ->whereDate('created_at', today())
                ->exists();

            if (!$existsToday) {
                $service->notify(
                    type: 'out_of_stock',
                    title: 'Out of Stock Alert',
                    message: "{$branchProduct->product->name} is out of stock at {$branchProduct->branch->name}",
                    severity: 'critical',
                    related: $branchProduct,
                    targetUser: $branchProduct->branch->manager,
                    metadata: [
                        'product_name' => $branchProduct->product->name,
                        'sku' => $branchProduct->product->sku,
                        'branch' => $branchProduct->branch->name,
                    ]
                );
                $this->warn("Out of stock: {$branchProduct->product->name}");
            }
        }
    }

    /**
     * Check for overstock products
     */
    private function checkOverstock(NotificationService $service): void
    {
        $overstockProducts = BranchProduct::with(['product', 'branch'])
            ->whereRaw('quantity > (low_stock_threshold * ?)', [
                NotificationService::THRESHOLDS['overstock_multiplier']
            ])
            ->get();

        foreach ($overstockProducts as $branchProduct) {
            $existsToday = \App\Models\Alert::where('type', 'overstock')
                ->where('related_type', get_class($branchProduct))
                ->where('related_id', $branchProduct->id)
                ->whereDate('created_at', today())
                ->exists();

            if (!$existsToday) {
                $service->notify(
                    type: 'overstock',
                    title: 'Overstock Warning',
                    message: "{$branchProduct->product->name} has excessive inventory at {$branchProduct->branch->name}",
                    severity: 'warning',
                    related: $branchProduct,
                    targetUser: $branchProduct->branch->manager,
                    metadata: [
                        'product_name' => $branchProduct->product->name,
                        'current_quantity' => $branchProduct->quantity,
                        'threshold' => $branchProduct->low_stock_threshold,
                        'branch' => $branchProduct->branch->name,
                    ]
                );
            }
        }
    }

    /**
     * Check for sales drops
     */
    private function checkSalesDrops(NotificationService $service): void
    {
        $branches = Branch::with('manager')->get();

        foreach ($branches as $branch) {
            // Get today's sales
            $todaySales = Transaction::where('branch_id', $branch->id)
                ->whereDate('created_at', today())
                ->sum('total_amount');

            // Get 7-day average (excluding today) - FIXED QUERY
            $avgSales = \DB::table('transactions')
                ->selectRaw('AVG(daily_total) as avg')
                ->fromSub(function ($query) use ($branch) {
                    $query->selectRaw('DATE(created_at) as date, SUM(total_amount) as daily_total')
                        ->from('transactions')
                        ->where('branch_id', $branch->id)
                        ->whereBetween('created_at', [now()->subDays(7)->startOfDay(), now()->subDay()->endOfDay()])
                        ->groupBy('date');
                }, 'daily_sales')
                ->value('avg') ?? 0;

            if ($avgSales > 0) {
                $dropPercentage = (($avgSales - $todaySales) / $avgSales) * 100;

                if ($dropPercentage >= NotificationService::THRESHOLDS['sales_drop_percentage']) {
                    $existsToday = \App\Models\Alert::where('type', 'sales_drop')
                        ->where('related_type', get_class($branch))
                        ->where('related_id', $branch->id)
                        ->whereDate('created_at', today())
                        ->exists();

                    if (!$existsToday) {
                        $service->notify(
                            type: 'sales_drop',
                            title: 'Sales Drop Alert',
                            message: "Sales at {$branch->name} are down " . round($dropPercentage, 1) . "% compared to 7-day average",
                            severity: 'warning',
                            related: $branch,
                            targetUser: $branch->manager,
                            metadata: [
                                'today_sales' => number_format($todaySales, 2),
                                'avg_sales' => number_format($avgSales, 2),
                                'drop_percentage' => round($dropPercentage, 2),
                                'branch' => $branch->name,
                            ]
                        );
                        $this->warn("Sales drop at {$branch->name}: " . round($dropPercentage, 1) . "%");
                    }
                }
            }
        }
    }

    /**
     * Send daily summary
     */
    private function checkDailySummary(NotificationService $service): void
    {
        // Only run at end of day (e.g., 11 PM)
        if (now()->hour !== 23) {
            return;
        }

        $branches = Branch::with('manager')->get();

        foreach ($branches as $branch) {
            $todaySales = Transaction::where('branch_id', $branch->id)
                ->whereDate('created_at', today())
                ->sum('total_amount');

            $transactionCount = Transaction::where('branch_id', $branch->id)
                ->whereDate('created_at', today())
                ->count();

            $service->notify(
                type: 'daily_summary',
                title: 'Daily Sales Summary',
                message: "{$branch->name} - Total Sales: ₱" . number_format($todaySales, 2) . " ({$transactionCount} transactions)",
                severity: 'info',
                related: $branch,
                targetUser: $branch->manager,
                metadata: [
                    'branch' => $branch->name,
                    'total_sales' => number_format($todaySales, 2),
                    'transaction_count' => $transactionCount,
                    'avg_transaction' => $transactionCount > 0 ? number_format($todaySales / $transactionCount, 2) : 0,
                ]
            );
        }

        $this->info('Daily summaries sent!');
    }
}
