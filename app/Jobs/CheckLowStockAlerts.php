<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Models\BranchProduct;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class CheckLowStockAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        // Get all branch products with low stock
        $lowStockProducts = BranchProduct::with(['branch', 'product'])
            ->whereColumn('inventory_count', '<=', 'low_stock_threshold')
            ->where('inventory_count', '>', 0)
            ->get();

        foreach ($lowStockProducts as $branchProduct) {
            // Find users to notify (branch manager + admins)
            $usersToNotify = User::where(function ($query) use ($branchProduct) {
                $query->where('role', 'admin')
                    ->orWhere(function ($q) use ($branchProduct) {
                        $q->where('role', 'branch_manager')
                          ->where('branch_id', $branchProduct->branch_id);
                    });
            })->get();

            foreach ($usersToNotify as $user) {
                // Check if alert already exists today
                $existingAlert = Alert::where('user_id', $user->id)
                    ->where('type', 'low_stock')
                    ->where('related_type', BranchProduct::class)
                    ->where('related_id', $branchProduct->id)
                    ->whereDate('created_at', today())
                    ->first();

                if ($existingAlert) {
                    continue; // Don't spam same alert
                }

                // Create alert
                Alert::create([
                    'user_id' => $user->id,
                    'type' => 'low_stock',
                    'title' => 'Low Stock Alert',
                    'message' => sprintf(
                        '%s at %s is running low (Stock: %d, Threshold: %d)',
                        $branchProduct->product->name,
                        $branchProduct->branch->name,
                        $branchProduct->inventory_count,
                        $branchProduct->low_stock_threshold
                    ),
                    'severity' => $branchProduct->inventory_count === 0 ? 'critical' : 'warning',
                    'related_type' => BranchProduct::class,
                    'related_id' => $branchProduct->id,
                ]);

                // Send email notification (optional)
                // Mail::to($user->email)->send(new LowStockNotification($branchProduct));
            }
        }
    }
}
