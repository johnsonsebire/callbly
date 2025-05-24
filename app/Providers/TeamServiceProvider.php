<?php

namespace App\Providers;

use App\Services\TeamResourceService;
use Illuminate\Support\ServiceProvider;

class TeamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(TeamResourceService::class, function ($app) {
            return new TeamResourceService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Share the $user variable with all team-related views
        view()->composer('teams.*', function ($view) {
            if (!$view->offsetExists('user')) {
                $view->with('user', auth()->user());
            }
        });
    }
}