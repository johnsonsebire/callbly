<?php

namespace App\Providers;

use App\Http\Middleware\TeamAccess;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Middlewares\RoleMiddleware;
use Spatie\Permission\Middlewares\PermissionMiddleware;
use Spatie\Permission\Middlewares\RoleOrPermissionMiddleware;

class RouteMiddlewareProvider extends RouteServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        Route::middleware('team.access', TeamAccess::class);
        Route::middleware('role', RoleMiddleware::class);
        Route::middleware('permission', PermissionMiddleware::class);
        Route::middleware('role_or_permission', RoleOrPermissionMiddleware::class);
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