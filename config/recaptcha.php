<?php

return [
    'enable' => env('RECAPTCHA_ENABLE', true),
    'site_key' => env('RECAPTCHA_SITE_KEY', ''),
    'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
    'version' => env('RECAPTCHA_VERSION', 'v2'), // Options: v2, v3
    'score_threshold' => env('RECAPTCHA_SCORE_THRESHOLD', 0.5), // For v3 only
];