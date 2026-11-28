<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
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

    'cloudinary' => [
        'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
        'api_key' => env('CLOUDINARY_API_KEY'),
        'api_secret' => env('CLOUDINARY_API_SECRET'),
        'secure' => true,
    ],

    'meta_whatsapp' => [
        'api_version' => env('META_WHATSAPP_API_VERSION', 'v27.0'),
        'phone_number_id' => env('META_WHATSAPP_PHONE_NUMBER_ID'),
        'access_token' => env('META_WHATSAPP_ACCESS_TOKEN'),
        'template_name' => env('META_WHATSAPP_TEMPLATE_NAME', 'custom_cake_quotation'),
        'template_language' => env('META_WHATSAPP_TEMPLATE_LANGUAGE', 'en'),
    ],

    'msg91' => [
        'auth_key' => env('MSG91_AUTH_KEY'),
        'template_id' => env('MSG91_TEMPLATE_ID'),
    ],
];
