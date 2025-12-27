<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;

class RateLimitServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // API rate limit
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        // Login attempts
        RateLimiter::for('login', function (Request $request) {
            return Limit::perMinute(5)->by($request->ip());
        });

        // Password reset/change requests
        RateLimiter::for('password', function (Request $request) {
            return Limit::perHour(5)->by($request->user()?->id ?: $request->ip());
        });

        // Export operations (resource-intensive)
        RateLimiter::for('exports', function (Request $request) {
            return Limit::perMinute(10)->by($request->user()->id);
        });

        // Forecast regeneration (very resource-intensive)
        RateLimiter::for('forecast', function (Request $request) {
            return Limit::perHour(5)->by($request->user()->id);
        });

        // Import operations
        RateLimiter::for('imports', function (Request $request) {
            return Limit::perHour(10)->by($request->user()->id);
        });

        // Email verification requests
        RateLimiter::for('verification', function (Request $request) {
            return Limit::perMinute(6)->by($request->user()?->id ?: $request->ip());
        });
    }
}
