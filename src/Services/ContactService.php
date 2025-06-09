<?php

namespace WAG\LaravelSDK\Services;

class ContactService extends BaseService
{
    /**
     * Get contact info
     */
    public function info(string $deviceId, string $jid): array
    {
        return $this->client->request('GET', "/contact/info", [
            'device_id' => $deviceId,
            'jid' => $jid
        ]);
    }

    /**
     * Get contact avatar
     */
    public function avatar(string $deviceId, string $jid): array
    {
        return $this->client->request('GET', "/contact/avatar", [
            'device_id' => $deviceId,
            'jid' => $jid
        ]);
    }

    /**
     * Check if number is on WhatsApp
     */
    public function checkExists(string $deviceId, string $phone): array
    {
        return $this->client->request('GET', "/contact/check", [
            'device_id' => $deviceId,
            'phone' => $phone
        ]);
    }

    /**
     * Get contacts list
     */
    public function list(string $deviceId): array
    {
        return $this->client->request('GET', "/contacts", [
            'device_id' => $deviceId
        ]);
    }
}