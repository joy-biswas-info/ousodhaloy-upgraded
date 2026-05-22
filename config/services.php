<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'mailgun' => [
        'domain'   => env('MAILGUN_DOMAIN'),
        'secret'   => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme'   => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key'    => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    // ── SSL Commerz ─────────────────────────────────────────────────────────
    'sslcommerz' => [
        'store_id'     => env('SSLCOMMERZ_STORE_ID'),
        'store_passwd' => env('SSLCOMMERZ_STORE_PASSWD'),
        'is_live'      => env('SSLCOMMERZ_IS_LIVE', false),
    ],

    // ── Pathao Courier ───────────────────────────────────────────────────────
    'pathao' => [
        'client_id'       => env('PATHAO_CLIENT_ID'),
        'client_secret'   => env('PATHAO_CLIENT_SECRET'),
        'username'        => env('PATHAO_USERNAME'),
        'password'        => env('PATHAO_PASSWORD'),
        'is_live'         => env('PATHAO_IS_LIVE', false),
        'store_id'        => env('PATHAO_STORE_ID'),
        'default_city_id' => env('PATHAO_DEFAULT_CITY_ID', 1),
        'default_zone_id' => env('PATHAO_DEFAULT_ZONE_ID', 1),
    ],

    // ── MimSMS ───────────────────────────────────────────────────────────────
    'mimsms' => [
        'api_key'   => env('MIMSMS_API_KEY'),
        'sender_id' => env('MIMSMS_SENDER_ID', 'Ousodhaloy'),
    ],

    // ── bKash Payment Gateway ────────────────────────────────────────────────
    'bkash' => [
        'app_key'    => env('BKASH_APP_KEY'),
        'app_secret' => env('BKASH_APP_SECRET'),
        'username'   => env('BKASH_USERNAME'),
        'password'   => env('BKASH_PASSWORD'),
        'is_sandbox' => env('BKASH_IS_SANDBOX', true),
    ],

];
