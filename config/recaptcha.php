<?php

return [
    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Configuration
    |--------------------------------------------------------------------------
    */

    'enable' => env('RECAPTCHA_ENABLE', false),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA Version (v2 or v3)
    |--------------------------------------------------------------------------
    */
    'version' => env('RECAPTCHA_VERSION', 'v2'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA v2 Keys
    |--------------------------------------------------------------------------
    */
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA v3 Keys
    |--------------------------------------------------------------------------
    */
    'v3_site_key' => env('V3CAPTCHA_SITE_KEY'),
    'v3_secret_key' => env('V3CAPTCHA_SECRET_KEY'),

    /*
    |--------------------------------------------------------------------------
    | reCAPTCHA v3 Score Threshold
    |--------------------------------------------------------------------------
    |
    | This is the minimum score that will be considered valid for v3.
    | The score is between 0.0 and 1.0, where 1.0 is very likely a good
    | interaction, and 0.0 is very likely a bot.
    |
    */
    'score_threshold' => env('RECAPTCHA_SCORE_THRESHOLD', 0.5),
];