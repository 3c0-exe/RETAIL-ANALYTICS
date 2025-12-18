<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Jobs\CheckLowStockAlerts;
use App\Jobs\CheckSalesDropAlerts;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::command('forecast:generate')->dailyAt('02:00');

// Check low stock every hour
Schedule::job(new CheckLowStockAlerts)->hourly();

// Check sales drops daily at 9 AM
Schedule::job(new CheckSalesDropAlerts)->dailyAt('09:00');
