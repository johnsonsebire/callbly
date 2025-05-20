<?php

namespace App\Providers;

use App\Contracts\SmsProviderInterface;
use App\Services\Currency\CurrencyService;
use App\Services\Sms\NaloSmsProvider;
use App\Services\Sms\SmsWithCurrencyService;
use App\Services\SmsService;
use Illuminate\Support\ServiceProvider;

class SmsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the base SMS provider implementation
        $this->app->singleton(SmsProviderInterface::class, function ($app) {
            $default = config('sms.default');
            
            return match ($default) {
                'nalo' => new NaloSmsProvider(),
                // Add other providers as they are implemented
                default => new NaloSmsProvider()
            };
        });
        
        // Register the general SmsService with proper dependency injection
        $this->app->singleton(SmsService::class, function ($app) {
            return new SmsService(
                $app->make(SmsProviderInterface::class)
            );
        });
        
        // Register our currency-aware SMS service
        $this->app->singleton(SmsWithCurrencyService::class, function ($app) {
            return new SmsWithCurrencyService(
                $app->make(SmsService::class),
                $app->make(CurrencyService::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}