<?php

namespace App\Providers;

use App\Services\Currency\CurrencyService;
use App\Services\Payment\PaymentWithCurrencyService;
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
        
        // Register the PaymentWithCurrencyService with dependencies
        $this->app->singleton(PaymentWithCurrencyService::class, function ($app) {
            return new PaymentWithCurrencyService(
                $app->make(PaystackService::class),
                $app->make(CurrencyService::class)
            );
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