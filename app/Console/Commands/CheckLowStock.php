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

        // Get all branch products below threshold - FIXED QUERY
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
            // Get branch manager
            $branchManager = User::where('role', 'branch_manager')
                ->where('branch_id', $branchProduct->branch_id)
                ->first();

            // Create alert for branch manager
            if ($branchManager) {
                $alert = $this->createAlertIfNotExists($branchManager, $branchProduct);
                if ($alert) {
                    $alertsCreated++;
                    SendLowStockAlert::dispatch($alert);
                    $emailsQueued++;
                }
            }

            // Create alert for admins
            $admins = User::where('role', 'admin')->get();
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
        // Check if alert already exists today
        $existingAlert = Alert::where('user_id', $user->id)
            ->where('type', 'low_stock')
            ->where('related_type', BranchProduct::class)
            ->where('related_id', $branchProduct->id)
            ->where('is_read', false)
            ->whereDate('created_at', today())
            ->exists();

        if ($existingAlert) {
            return null;
        }

        // Create the alert
        return Alert::create([
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
