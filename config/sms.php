<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SMS Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default SMS provider that will be used to send
    | messages. You may set this to any of the providers defined in the
    | "providers" array below.
    |
    */
    'default' => env('SMS_PROVIDER', 'nalo'),

    /*
    |--------------------------------------------------------------------------
    | SMS Providers
    |--------------------------------------------------------------------------
    |
    | Here you may configure all of the SMS providers used by your application.
    | Available providers include "nalo", "africastalking", "twilio", and others.
    |
    */
    'providers' => [
        'nalo' => [
            'api_key' => env('NALO_API_KEY'),
            'username' => env('NALO_USERNAME'),
            'password' => env('NALO_PASSWORD'),
            'api_url' => env('NALO_API_URL', 'https://sms.nalosolutions.com/smsbackend'),
            'sender_id' => env('NALO_SENDER_ID', 'CALLBLY'),
            'username_prefix' => env('NALO_USERNAME_PREFIX', 'Resl_Nalo'),
        ],
        
        'africastalking' => [
            'api_key' => env('AFRICASTALKING_API_KEY'),
            'username' => env('AFRICASTALKING_USERNAME'),
            'sender_id' => env('AFRICASTALKING_SENDER_ID', 'CALLBLY'),
            'sandbox' => env('AFRICASTALKING_SANDBOX', false),
        ],
        
        'twilio' => [
            'sid' => env('TWILIO_SID'),
            'token' => env('TWILIO_TOKEN'),
            'from' => env('TWILIO_FROM'),
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS Pricing
    |--------------------------------------------------------------------------
    |
    | Here you may configure the pricing for SMS based on destination 
    | and provider.
    |
    */
    'pricing' => [
        'default' => [
            'price_per_credit' => env('SMS_PRICE_PER_CREDIT', 2.5), // Default price in your currency
            'credits_per_sms' => env('SMS_CREDITS_PER_SMS', 1),     // Default credits consumed per SMS
        ],
    ],
    
    /*
    |--------------------------------------------------------------------------
    | SMS Credits
    |--------------------------------------------------------------------------
    |
    | Configure the minimum balance required to send SMS
    |
    */
    'min_balance' => env('SMS_MIN_BALANCE', 1),
    
    /*
    |--------------------------------------------------------------------------
    | Sender Name Settings
    |--------------------------------------------------------------------------
    |
    | Configure sender name requirements
    |
    */
    'sender_name' => [
        'max_length' => 11, // Maximum length of sender name
        'min_length' => 3,  // Minimum length of sender name
        'require_approval' => true,  // Whether sender names require approval
    ],
];