<?php

namespace WAG\LaravelSDK\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Cache;

class UserTokenService
{
    /**
     * Store user token in session
     */
    public static function storeInSession(string $token, string $userId = null): void
    {
        $key = $userId ? "wag_token_{$userId}" : 'wag_token';
        Session::put($key, $token);
    }

    /**
     * Get user token from session
     */
    public static function getFromSession(string $userId = null): ?string
    {
        $key = $userId ? "wag_token_{$userId}" : 'wag_token';
        return Session::get($key);
    }

    /**
     * Store user token in cache with TTL
     */
    public static function storeInCache(string $token, string $userId, int $ttlMinutes = 60): void
    {
        Cache::put("wag_token_{$userId}", $token, now()->addMinutes($ttlMinutes));
    }

    /**
     * Get user token from cache
     */
    public static function getFromCache(string $userId): ?string
    {
        return Cache::get("wag_token_{$userId}");
    }

    /**
     * Remove user token from storage
     */
    public static function forget(string $userId = null): void
    {
        if ($userId) {
            Session::forget("wag_token_{$userId}");
            Cache::forget("wag_token_{$userId}");
        } else {
            Session::forget('wag_token');
        }
    }
}