<?php

namespace WAG\LaravelSDK\Services;

class DeviceService extends BaseService
{
    /**
     * Get all devices
     */
    public function list(): array
    {
        return $this->client->request('GET', '/devices');
    }

    /**
     * Get device info
     */
    public function info(string $deviceId): array
    {
        return $this->client->request('GET', "/device/{$deviceId}");
    }

    /**
     * Create new device
     */
    public function create(string $deviceId): array
    {
        return $this->client->request('POST', '/device', [
            'device_id' => $deviceId
        ]);
    }

    /**
     * Delete device
     */
    public function delete(string $deviceId): array
    {
        return $this->client->request('DELETE', "/device/{$deviceId}");
    }
}