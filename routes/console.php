<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your console based routes.
| These routes are loaded by the RouteServiceProvider and all of them
| will be assigned to the "console" middleware group.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Scheduled Tasks
|--------------------------------------------------------------------------
|
| Here we define scheduled tasks. We'll enable them properly in Phase 8.6
|
*/

// Generate forecasts daily at 2 AM
Schedule::command('forecast:generate')->dailyAt('02:00');

// Check low stock daily at midnight
Schedule::command('alerts:check-low-stock')->daily();

// Check sales drops daily at 9 AM (future feature)
Schedule::command('alerts:check-sales-drop')->dailyAt('09:00');
