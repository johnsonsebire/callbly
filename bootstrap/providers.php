<?php

return [
    /*
     * Package Service Providers...
     */
    Spatie\Permission\PermissionServiceProvider::class,

    /*
     * Application Service Providers...
     */
    App\Providers\AppServiceProvider::class,
    App\Providers\AuthServiceProvider::class,
    App\Providers\EventServiceProvider::class,
    App\Providers\RouteServiceProvider::class,
    App\Providers\RouteMiddlewareProvider::class,
    App\Providers\CurrencyServiceProvider::class,
    App\Providers\PaystackServiceProvider::class,
    App\Providers\ReCaptchaServiceProvider::class,
    App\Providers\SmsServiceProvider::class,
    App\Providers\TeamServiceProvider::class,
];
