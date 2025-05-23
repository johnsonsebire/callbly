<?php

namespace App\Providers;

use App\Http\Middleware\TeamAccess;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteMiddlewareProvider extends RouteServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Route::middleware('team.access', TeamAccess::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Apply team.access middleware to team routes
        Route::pattern('team', '[0-9]+');
    }
}