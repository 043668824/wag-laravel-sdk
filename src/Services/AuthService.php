<?php

namespace WAG\LaravelSDK\Services;

class AuthService extends BaseService
{
    /**
     * Get QR code for device authentication
     */
    public function getQRCode(string $deviceId): array
    {
        return $this->client->request('GET', "/auth/qr/{$deviceId}");
    }

    /**
     * Check authentication status
     */
    public function status(string $deviceId): array
    {
        return $this->client->request('GET', "/auth/status/{$deviceId}");
    }

    /**
     * Logout device
     */
    public function logout(string $deviceId): array
    {
        return $this->client->request('POST', "/auth/logout/{$deviceId}");
    }

    /**
     * Reconnect device
     */
    public function reconnect(string $deviceId): array
    {
        return $this->client->request('POST', "/auth/reconnect/{$deviceId}");
    }
}