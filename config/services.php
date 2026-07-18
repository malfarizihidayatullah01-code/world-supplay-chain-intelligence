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
        'key' => env('POSTMARK_API_KEY'),
    ],

    'resend' => [
        'key' => env('RESEND_API_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'rest_countries' => [
        'base_url' => env('REST_COUNTRIES_URL', 'https://api.restcountries.com/countries/v5'),
        'key' => env('REST_COUNTRIES_API_KEY'),
    ],

    'weather' => [
        'base_url' => env('WEATHER_API_URL'),
        'key' => env('WEATHER_API_KEY'),
    ],

    'exchange_rate' => [
        'base_url' => env('EXCHANGE_RATE_API_URL'),
        'key' => env('EXCHANGE_RATE_API_KEY'),
    ],

    'news' => [
        'base_url' => env('NEWS_API_URL'),
        'key' => env('NEWS_API_KEY'),
    ],

];
