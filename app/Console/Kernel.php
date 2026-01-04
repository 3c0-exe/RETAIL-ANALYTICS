<?php

// app/Console/Kernel.php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Check for alerts every hour
        $schedule->command('alerts:check')->hourly();

        // Low stock check (your existing command)
        $schedule->command('alerts:check-low-stock')->daily();

        // Daily summary at 11 PM
        $schedule->command('alerts:check')->dailyAt('23:00');

        // Check forecast deviations daily at 6 AM
        $schedule->command('forecast:check-deviation')
            ->dailyAt('06:00')
            ->withoutOverlapping()
            ->onOneServer();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
