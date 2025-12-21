<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\BranchProduct;
use App\Models\User;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    protected $signature = 'alerts:check-low-stock';
    protected $description = 'Check for low stock products and create alerts';

    public function handle()
    {
        $this->info('Checking for low stock products...');

        // Get all branch products that are below threshold
        $lowStockProducts = BranchProduct::with(['product', 'branch'])
            ->whereColumn('quantity', '<=', 'low_stock_threshold')
            ->where('quantity', '>', 0) // Exclude out of stock
            ->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('✅ No low stock products found.');
            return 0;
        }

        $alertsCreated = 0;

        foreach ($lowStockProducts as $branchProduct) {
            // Get the branch manager for this branch
            $branchManager = User::where('role', 'branch_manager')
                ->where('branch_id', $branchProduct->branch_id)
                ->first();

            // Create alert for branch manager
            if ($branchManager) {
                $this->createAlertIfNotExists(
                    $branchManager,
                    $branchProduct
                );
                $alertsCreated++;
            }

            // Create alert for all admins
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                $this->createAlertIfNotExists(
                    $admin,
                    $branchProduct
                );
                $alertsCreated++;
            }
        }

        $this->info("✅ Created {$alertsCreated} low stock alerts.");
        return 0;
    }

    private function createAlertIfNotExists(User $user, BranchProduct $branchProduct)
    {
        // Check if alert already exists (not read, same product, created today)
        $existingAlert = Alert::where('user_id', $user->id)
            ->where('type', 'low_stock')
            ->where('related_type', BranchProduct::class)
            ->where('related_id', $branchProduct->id)
            ->where('is_read', false)
            ->whereDate('created_at', today())
            ->exists();

        if ($existingAlert) {
            return; // Don't create duplicate
        }

        // Create the alert
        Alert::create([
            'user_id' => $user->id,
            'type' => 'low_stock',
            'title' => 'Low Stock Alert',
            'message' => sprintf(
                '%s at %s is running low. Current stock: %d (Threshold: %d)',
                $branchProduct->product->name,
                $branchProduct->branch->name,
                $branchProduct->quantity,
                $branchProduct->low_stock_threshold
            ),
            'severity' => $branchProduct->quantity <= ($branchProduct->low_stock_threshold * 0.5)
                ? 'critical'
                : 'warning',
            'related_type' => BranchProduct::class,
            'related_id' => $branchProduct->id,
        ]);
    }
}
