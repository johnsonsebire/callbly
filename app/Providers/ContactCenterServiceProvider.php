<?php

namespace App\Providers;

use App\Services\ContactCenter\ContactCenterService;
use Illuminate\Support\ServiceProvider;

class ContactCenterServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(ContactCenterService::class, function ($app) {
            return new ContactCenterService();
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