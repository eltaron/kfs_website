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

    'efinance' => [
        'sender_id'       => env('EFINANCE_SENDER_ID', '5057'),
        'sender_name'     => env('EFINANCE_SENDER_NAME', 'محافظة كفر الشيخ'),
        'password'        => env('EFINANCE_PASSWORD', '1234'),
        'service_code'    => env('EFINANCE_SERVICE_CODE', '05057'),
        'settlement_code' => env('EFINANCE_SETTLEMENT_CODE', '5066'),
        'url'             => env('EFINANCE_GATEWAY_URL', 'https://test-payment.efinance.com.eg/CardPaymentRequestIntiation/index'),
    ],

];
