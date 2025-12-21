<?php

namespace App\Console\Commands;

use App\Jobs\SendLowStockAlert;
use App\Models\Alert;
use App\Models\BranchProduct;
use App\Models\User;
use Illuminate\Console\Command;

class CheckLowStock extends Command
{
    protected $signature = 'alerts:check-low-stock';
    protected $description = 'Check for low stock products and create alerts with email notifications';

    public function handle()
    {
        $this->info('Checking for low stock products...');

        // Get all branch products below threshold
        $lowStockProducts = BranchProduct::with(['product', 'branch'])
            ->whereRaw('quantity <= low_stock_threshold')
            ->where('quantity', '>', 0)
            ->get();

        if ($lowStockProducts->isEmpty()) {
            $this->info('✅ No low stock products found.');
            return 0;
        }

        $this->info("Found {$lowStockProducts->count()} low stock products.");

        $alertsCreated = 0;
        $emailsQueued = 0;

        foreach ($lowStockProducts as $branchProduct) {
            // 1. Get branch manager for this specific branch
            $branchManager = User::where('role', 'branch_manager')
                ->where('branch_id', $branchProduct->branch_id)
                ->whereNotNull('email')
                ->first();

            if ($branchManager) {
                $alert = $this->createAlertIfNotExists($branchManager, $branchProduct);
                if ($alert) {
                    $alertsCreated++;
                    SendLowStockAlert::dispatch($alert);
                    $emailsQueued++;
                }
            }

            // 2. Get all admins (they monitor all branches)
            $admins = User::where('role', 'admin')
                ->whereNotNull('email')
                ->get();

            foreach ($admins as $admin) {
                $alert = $this->createAlertIfNotExists($admin, $branchProduct);
                if ($alert) {
                    $alertsCreated++;
                    SendLowStockAlert::dispatch($alert);
                    $emailsQueued++;
                }
            }
        }

        $this->info("✅ Created {$alertsCreated} low stock alerts.");
        $this->info("📧 Queued {$emailsQueued} email notifications.");

        return 0;
    }

    private function createAlertIfNotExists(User $user, BranchProduct $branchProduct): ?Alert
    {
        // Check if alert already exists today for this user + product + branch combination
        $existingAlert = Alert::where('user_id', $user->id)
            ->where('type', 'low_stock')
            ->whereDate('created_at', today())
            ->whereJsonContains('metadata->product_id', $branchProduct->product_id)
            ->whereJsonContains('metadata->branch_id', $branchProduct->branch_id)
            ->exists();

        if ($existingAlert) {
            $this->line("  ⏭️  Skipping duplicate: {$branchProduct->product->name} at {$branchProduct->branch->name} for {$user->email}");
            return null;
        }

        // Create the alert with metadata
        $alert = Alert::create([
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
            'metadata' => [
                'product_id' => $branchProduct->product_id,
                'branch_id' => $branchProduct->branch_id,
                'product_name' => $branchProduct->product->name,
                'branch_name' => $branchProduct->branch->name,
                'quantity' => $branchProduct->quantity,
                'threshold' => $branchProduct->low_stock_threshold,
            ],
        ]);

        $this->info("  ✓ Created alert: {$branchProduct->product->name} at {$branchProduct->branch->name} for {$user->email}");

        return $alert;
    }
}
