<?php

return [
    /*
    |--------------------------------------------------------------------------
    | WhatsApp API Gateway Base URL
    |--------------------------------------------------------------------------
    |
    | The base URL for your WhatsApp API Gateway service.
    |
    */
    'base_url' => env('WAG_BASE_URL', 'http://localhost:8080'),

    /*
    |--------------------------------------------------------------------------
    | API Key
    |--------------------------------------------------------------------------
    |
    | The API key for authenticating with your WhatsApp API Gateway.
    |
    */
    'api_key' => env('WAG_API_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Request Timeout
    |--------------------------------------------------------------------------
    |
    | The timeout in seconds for API requests.
    |
    */
    'timeout' => env('WAG_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Default Device ID
    |--------------------------------------------------------------------------
    |
    | The default device ID to use for WhatsApp operations.
    |
    */
    'default_device_id' => env('WAG_DEFAULT_DEVICE_ID'),
];