<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withProviders()
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Add global middleware that sets permission team context for authenticated users (run early)
        $middleware->web(prepend: [
            \App\Http\Middleware\SetPermissionTeamContext::class,
        ]);
        
        // Add session timeout check middleware for web routes
        $middleware->web(append: [
            \App\Http\Middleware\CheckSessionTimeout::class,
        ]);
        
        // Add session timeout check middleware for API routes that use web sessions
        $middleware->api(append: [
            \App\Http\Middleware\CheckApiSessionTimeout::class,
        ]);
        
        // Register Spatie permission middleware aliases
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'session.timeout' => \App\Http\Middleware\CheckSessionTimeout::class,
            'api.session.timeout' => \App\Http\Middleware\CheckApiSessionTimeout::class,
            'coming.soon' => \App\Http\Middleware\ComingSoonForCustomers::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
