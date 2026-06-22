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

    'resend' => [
        'key' => env('RESEND_KEY'),
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

    'gosms' => [
        'enabled' => env('GOSMS_ENABLED', false),
        'base_url' => env('GOSMS_BASE_URL', 'https://api.gosms.ge'),
        'api_key' => env('GOSMS_API_KEY'),
        'sender_name' => env('GOSMS_SENDER_NAME'),
        'timeout' => env('GOSMS_TIMEOUT', 15),
    ],

    'bog' => [
        'base_url' => env('BOG_BASE_URL', 'https://api.bog.ge'),
        'client_id' => env('BOG_CLIENT_ID'),
        'secret_key' => env('BOG_SECRET_KEY'),
        'merchant_id' => env('BOG_MERCHANT_ID'),
        'terminal_id' => env('BOG_TERMINAL_ID'),
        'client_inn' => env('BOG_CLIENT_INN'),
    ],

];
