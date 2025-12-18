<?php

namespace App\Jobs;

use App\Models\Alert;
use App\Models\Branch;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckSalesDropAlerts implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $branches = Branch::where('status', 'active')->get();

        foreach ($branches as $branch) {
            // Get sales for last 7 days
            $last7Days = Transaction::where('branch_id', $branch->id)
                ->where('timestamp', '>=', Carbon::now()->subDays(7))
                ->where('status', 'completed')
                ->sum('total_amount');

            // Get sales for previous 7 days
            $previous7Days = Transaction::where('branch_id', $branch->id)
                ->whereBetween('timestamp', [
                    Carbon::now()->subDays(14),
                    Carbon::now()->subDays(7)
                ])
                ->where('status', 'completed')
                ->sum('total_amount');

            if ($previous7Days == 0) {
                continue; // Not enough data
            }

            // Calculate drop percentage
            $dropPercentage = (($previous7Days - $last7Days) / $previous7Days) * 100;

            // Alert if drop is more than 20%
            if ($dropPercentage > 20) {
                // Find users to notify
                $usersToNotify = User::where(function ($query) use ($branch) {
                    $query->where('role', 'admin')
                        ->orWhere(function ($q) use ($branch) {
                            $q->where('role', 'branch_manager')
                              ->where('branch_id', $branch->id);
                        });
                })->get();

                foreach ($usersToNotify as $user) {
                    // Check if alert already exists today
                    $existingAlert = Alert::where('user_id', $user->id)
                        ->where('type', 'sales_drop')
                        ->where('related_type', Branch::class)
                        ->where('related_id', $branch->id)
                        ->whereDate('created_at', today())
                        ->first();

                    if ($existingAlert) {
                        continue;
                    }

                    Alert::create([
                        'user_id' => $user->id,
                        'type' => 'sales_drop',
                        'title' => 'Sales Drop Detected',
                        'message' => sprintf(
                            '%s sales dropped %.1f%% compared to last week (from %s %.2f to %s %.2f)',
                            $branch->name,
                            $dropPercentage,
                            $branch->currency,
                            $previous7Days,
                            $branch->currency,
                            $last7Days
                        ),
                        'severity' => $dropPercentage > 40 ? 'critical' : 'warning',
                        'related_type' => Branch::class,
                        'related_id' => $branch->id,
                    ]);
                }
            }
        }
    }
}
