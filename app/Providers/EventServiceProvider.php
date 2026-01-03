<?php

namespace App\Providers;

use Illuminate\Auth\Events\Failed;
use App\Listeners\LoginEventListener;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    protected $listen = [
        Failed::class => [
            LoginEventListener::class,
        ],
    ];

    public function boot(): void
    {
        //
    }
}
