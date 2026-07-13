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

    'telegram' => [
        'bot_token' => env('TELEGRAM_BOT_TOKEN'),
    ],

    'fcm' => [
        'server_key' => env('FCM_SERVER_KEY'),
        'credentials_file' => env('FIREBASE_CREDENTIALS'),
        'project_id' => 'smarthr-25972',
    ],

];
