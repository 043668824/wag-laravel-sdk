<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WUZAPI Base URL
    |--------------------------------------------------------------------------
    |
    | This is the base URL for the WUZAPI service.
    |
    */
    'base_url' => env('WUZAPI_BASE_URL', 'http://localhost:8080'),

    /*
    |--------------------------------------------------------------------------
    | WUZAPI Admin Token
    |--------------------------------------------------------------------------
    |
    | This is the admin token used for administrative operations.
    |
    */
    'admin_token' => env('WUZAPI_ADMIN_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | HTTP Client Configuration
    |--------------------------------------------------------------------------
    |
    | Configure Guzzle HTTP client settings
    |
    */
    'timeout' => env('WUZAPI_TIMEOUT', 30),
    'connect_timeout' => env('WUZAPI_CONNECT_TIMEOUT', 10),

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure SDK logging behavior
    |
    */
    'logging' => [
        'enabled' => env('WUZAPI_LOGGING', false),
        'channel' => env('WUZAPI_LOG_CHANNEL', 'stack'),
    ],
];