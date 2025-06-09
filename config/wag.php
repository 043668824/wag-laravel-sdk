<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WUZAPI Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for your WUZAPI service.
    |
    */
    'base_url' => env('WAG_BASE_URL', 'http://localhost:8080'),

    /*
    |--------------------------------------------------------------------------
    | Admin API Key
    |--------------------------------------------------------------------------
    |
    | The API key for authenticating with WUZAPI admin endpoints.
    | This is for administrative operations only.
    |
    */
    'admin_api_key' => env('WAG_ADMIN_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for API requests.
    |
    */
    'timeout' => env('WAG_TIMEOUT', 30),
];