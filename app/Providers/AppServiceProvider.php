<?php

// app/Providers/AppServiceProvider.php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Transaction;
use App\Models\Import;
use App\Observers\TransactionObserver;
use App\Observers\ImportObserver;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register NotificationService as singleton
        $this->app->singleton(\App\Services\NotificationService::class, function ($app) {
            return new \App\Services\NotificationService();
        });
    }

    public function boot(): void
    {
        // Register observers
        Transaction::observe(TransactionObserver::class);
        Import::observe(ImportObserver::class);
        \App\Models\Forecast::observe(\App\Observers\ForecastObserver::class);
        \App\Models\User::observe(\App\Observers\UserObserver::class);
    }
}
