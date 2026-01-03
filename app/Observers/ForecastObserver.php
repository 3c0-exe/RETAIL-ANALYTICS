<?php

// app/Observers/ForecastObserver.php

namespace App\Observers;

use App\Models\Forecast;
use App\Models\Transaction;
use App\Services\NotificationService;

class ForecastObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    /**
     * Handle forecast created/updated to check for deviations
     */
    public function updated(Forecast $forecast): void
    {
        // Only check if forecast date is today or in the past
        if ($forecast->forecast_date->isFuture()) {
            return;
        }

        // Get actual sales for the forecast date
        $actualSales = Transaction::where('branch_id', $forecast->branch_id)
            ->whereDate('created_at', $forecast->forecast_date)
            ->sum('total_amount');

        // Calculate deviation percentage
        $predicted = $forecast->predicted_sales;

        if ($predicted > 0) {
            $deviation = abs(($actualSales - $predicted) / $predicted) * 100;

            // Trigger notification if deviation exceeds threshold (20%)
            if ($deviation >= NotificationService::THRESHOLDS['forecast_deviation']) {
                // Check if alert already exists for this forecast
                $existsToday = \App\Models\Alert::where('type', 'forecast_deviation')
                    ->where('related_type', get_class($forecast))
                    ->where('related_id', $forecast->id)
                    ->whereDate('created_at', today())
                    ->exists();

                if (!$existsToday) {
                    $direction = $actualSales > $predicted ? 'higher' : 'lower';

                    $this->notificationService->notify(
                        type: 'forecast_deviation',
                        title: 'Forecast Deviation Alert',
                        message: "Actual sales at {$forecast->branch->name} are {$direction} than predicted by " . round($deviation, 1) . "%",
                        severity: $deviation >= 30 ? 'warning' : 'info',
                        related: $forecast,
                        targetUser: $forecast->branch->manager,
                        metadata: [
                            'predicted_sales' => number_format($predicted, 2),
                            'actual_sales' => number_format($actualSales, 2),
                            'deviation_percentage' => round($deviation, 2),
                            'direction' => $direction,
                            'branch' => $forecast->branch->name,
                            'forecast_date' => $forecast->forecast_date->format('Y-m-d'),
                        ]
                    );
                }
            }
        }
    }
}
