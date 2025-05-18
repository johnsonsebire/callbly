<?php

namespace App\Providers;

use App\Services\Payment\PaystackService;
use Illuminate\Support\ServiceProvider;

class PaystackServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(PaystackService::class, function ($app) {
            return new PaystackService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}