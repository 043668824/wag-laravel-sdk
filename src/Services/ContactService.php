<?php

namespace WAG\LaravelSDK\Services;

class ContactService extends BaseService
{
    /**
     * Get contact info
     */
    public function getInfo(string $chatId): array
    {
        return $this->client->request('GET', '/contact/info', [
            'chatId' => $chatId
        ]);
    }

    /**
     * Get contact profile picture
     */
    public function getProfilePicture(string $chatId): array
    {
        return $this->client->request('GET', '/contact/profilepicture', [
            'chatId' => $chatId
        ]);
    }

    /**
     * Check if contact exists on WhatsApp
     */
    public function checkExists(string $phone): array
    {
        return $this->client->request('POST', '/contact/checknumber', [
            'phone' => $phone
        ]);
    }

    /**
     * Get all contacts
     */
    public function getAll(): array
    {
        return $this->client->request('GET', '/contact/list');
    }
}