<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'recaptcha' => [
        'site_key' => env('GOOGLE_RECAPTCHA_KEY'),
        'secret_key' => env('GOOGLE_RECAPTCHA_SECRET'),
        'admin_site_key' => env('ADMIN_RECAPTCHA_SITE_KEY'),
        'admin_secret_key' => env('ADMIN_RECAPTCHA_SECRET_KEY'),
    ],

    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect' => env('GOOGLE_REDIRECT_URI'),
    ],

    'abstractApi' => [
        'email_validation' => [
            'enabled' => env('GOOGLE_EMAIL_VALIDATION_ENABLED', false),
            'api_key' => env('GOOGLE_EMAIL_VALIDATION_API_KEY'),
        ],
    ],

    'ransAuthApi' => [
        'api_key' => env('RANZ_AUTH_API'),
    ],
];
