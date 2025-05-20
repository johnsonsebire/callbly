<?php

namespace App\Providers;

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Services\NavigationService;
use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(NavigationService::class, function ($app) {
            return new NavigationService();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $router = $this->app->make(Router::class);
        
        // Register authentication middleware
        $router->aliasMiddleware('auth', Authenticate::class);
        $router->aliasMiddleware('guest', RedirectIfAuthenticated::class);
        $router->aliasMiddleware('verified', EnsureEmailIsVerified::class);
        $router->aliasMiddleware('signed', \Illuminate\Routing\Middleware\ValidateSignature::class);
        $router->aliasMiddleware('throttle', \Illuminate\Routing\Middleware\ThrottleRequests::class);
        
        // Define middleware groups
        $router->middlewareGroup('web', [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\ValidateCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
        
        $router->middlewareGroup('api', [
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        // Use Bootstrap 5 pagination as default
        \Illuminate\Pagination\Paginator::useBootstrapFive();

        view()->share('navigationService', app(NavigationService::class));
    }
}
