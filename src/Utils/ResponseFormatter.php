<?php

namespace WAG\LaravelSDK\Utils;

class ResponseFormatter
{
    /**
     * Extract success status from response
     */
    public static function isSuccessful(array $response): bool
    {
        return ($response['success'] ?? false) === true;
    }
    
    /**
     * Extract error message from response
     */
    public static function getErrorMessage(array $response): ?string
    {
        return $response['error'] ?? null;
    }
    
    /**
     * Extract status code from response
     */
    public static function getStatusCode(array $response): int
    {
        return $response['code'] ?? 0;
    }
    
    /**
     * Extract data from response
     */
    public static function getData(array $response): ?array
    {
        return $response['data'] ?? null;
    }
}