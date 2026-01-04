<?php

namespace App\Console\Commands;

use App\Models\Alert;
use App\Models\Branch;
use App\Models\Forecast;
use App\Models\Transaction;
use App\Models\User;
use App\Mail\ForecastDeviationAlert;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class CheckForecastDeviation extends Command
{
    protected $signature = 'forecast:check-deviation {--date=} {--branch_id=}';
    protected $description = 'Check for forecast deviations and send alerts';

    protected $warningThreshold = 20; // 20%
    protected $criticalThreshold = 30; // 30%

    public function handle()
    {
        $this->info('🔍 Checking forecast deviations...');

        // Determine date to check (default: yesterday)
        $checkDate = $this->option('date')
            ? Carbon::parse($this->option('date'))
            : Carbon::yesterday();

        $this->info("Checking date: {$checkDate->toDateString()}");

        // Get branches to check
        $branches = $this->option('branch_id')
            ? Branch::where('id', $this->option('branch_id'))->where('status', 'active')->get()
            : Branch::where('status', 'active')->get();

        if ($branches->isEmpty()) {
            $this->error('No active branches found!');
            return 1;
        }

        $alertCount = 0;

        foreach ($branches as $branch) {
            $this->info("Checking {$branch->name}...");

            try {
                // Get forecast for this date
                $forecast = Forecast::where('branch_id', $branch->id)
                    ->whereNull('product_id')
                    ->whereNull('category')
                    ->where('forecast_date', $checkDate->toDateString())
                    ->first();

                if (!$forecast) {
                    $this->warn("  ⚠ No forecast found for {$checkDate->toDateString()}");
                    continue;
                }

                // Get actual sales for this date
                $actualSales = Transaction::where('branch_id', $branch->id)
                    ->whereDate('timestamp', $checkDate->toDateString())
                    ->where('status', 'completed')
                    ->sum('total_amount');

                $forecastedSales = $forecast->predicted_sales;

                // Calculate deviation
                if ($forecastedSales == 0) {
                    $this->warn("  ⚠ Forecasted sales is zero, skipping");
                    continue;
                }

                $deviation = abs($actualSales - $forecastedSales);
                $deviationPercent = ($deviation / $forecastedSales) * 100;

                $this->info("  Forecasted: ₱" . number_format($forecastedSales, 2));
                $this->info("  Actual: ₱" . number_format($actualSales, 2));
                $this->info("  Deviation: " . number_format($deviationPercent, 1) . "%");

                // Check if deviation exceeds thresholds
                if ($deviationPercent < $this->warningThreshold) {
                    $this->info("  ✓ Deviation within acceptable range");
                    continue;
                }

                // Determine severity
                $severity = $deviationPercent >= $this->criticalThreshold ? 'critical' : 'warning';
                $deviationType = $actualSales > $forecastedSales ? 'over' : 'under';

                // Create alert in database
                $this->createAlerts($branch, $checkDate, $forecastedSales, $actualSales, $deviationPercent, $deviationType, $severity);

                // Send email notifications
                $this->sendEmailNotifications($branch, $checkDate, $forecastedSales, $actualSales, $deviationPercent, $deviationType, $severity);

                $alertCount++;
                $this->warn("  🚨 Alert created and sent!");

            } catch (\Exception $e) {
                $this->error("  ✗ Error: " . $e->getMessage());
            }
        }

        $this->newLine();
        $this->info("✅ Forecast deviation check complete!");
        $this->info("📧 Alerts created: {$alertCount}");

        return 0;
    }

    protected function createAlerts($branch, $checkDate, $forecastedSales, $actualSales, $deviationPercent, $deviationType, $severity)
    {
        $title = $severity === 'critical'
            ? "🚨 Critical Forecast Deviation - {$branch->name}"
            : "⚠️ Forecast Deviation Warning - {$branch->name}";

        $message = sprintf(
            "Sales on %s were %s by %.1f%%. Forecasted: ₱%s, Actual: ₱%s",
            $checkDate->format('M d, Y'),
            $deviationType === 'over' ? 'above forecast' : 'below forecast',
            $deviationPercent,
            number_format($forecastedSales, 2),
            number_format($actualSales, 2)
        );

        $metadata = [
            'branch_id' => $branch->id,
            'branch_name' => $branch->name,
            'date' => $checkDate->toDateString(),
            'forecasted_sales' => $forecastedSales,
            'actual_sales' => $actualSales,
            'deviation_percent' => round($deviationPercent, 2),
            'deviation_type' => $deviationType,
        ];

        // Alert for branch manager
        if ($branch->manager_id) {
            Alert::create([
                'user_id' => $branch->manager_id,
                'type' => 'forecast_deviation',
                'title' => $title,
                'message' => $message,
                'severity' => $severity,
                'metadata' => $metadata,
            ]);
        }

        // Alerts for all admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            Alert::create([
                'user_id' => $admin->id,
                'type' => 'forecast_deviation',
                'title' => $title,
                'message' => $message,
                'severity' => $severity,
                'metadata' => $metadata,
            ]);
        }
    }

    protected function sendEmailNotifications($branch, $checkDate, $forecastedSales, $actualSales, $deviationPercent, $deviationType, $severity)
    {
        $alertUrl = url('/forecasts');

        // Send to branch manager
        if ($branch->manager_id) {
            $manager = User::find($branch->manager_id);
            if ($manager && $this->shouldSendEmail($manager, 'forecast_deviation')) {
                Mail::to($manager->email)->send(new ForecastDeviationAlert(
                    $manager->name,
                    $branch->name,
                    $checkDate->format('F d, Y'),
                    $forecastedSales,
                    $actualSales,
                    round($deviationPercent, 1),
                    $deviationType,
                    $severity,
                    $alertUrl
                ));
            }
        }

        // Send to admins
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            if ($this->shouldSendEmail($admin, 'forecast_deviation')) {
                Mail::to($admin->email)->send(new ForecastDeviationAlert(
                    $admin->name,
                    $branch->name,
                    $checkDate->format('F d, Y'),
                    $forecastedSales,
                    $actualSales,
                    round($deviationPercent, 1),
                    $deviationType,
                    $severity,
                    $alertUrl
                ));
            }
        }
    }

    protected function shouldSendEmail($user, $notificationType): bool
    {
        // Check if user has notification preferences
        if (!$user->notification_preferences) {
            return true; // Default to sending if no preferences set
        }

        $preferences = is_string($user->notification_preferences)
            ? json_decode($user->notification_preferences, true)
            : $user->notification_preferences;

        // Always send critical alerts
        if ($notificationType === 'forecast_deviation') {
            return $preferences["email_{$notificationType}"] ?? true;
        }

        return true;
    }
}
