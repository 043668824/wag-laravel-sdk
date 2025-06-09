<?php

namespace WAG\LaravelSDK\Services;

class AdminService extends BaseService
{
    /**
     * List all users
     * GET /admin/users
     * 
     * Retrieve a list of users from the database, displaying also connection status and other info.
     */
    public function listUsers(): array
    {
        return $this->client->requestAsAdmin('GET', '/admin/users');
    }

    /**
     * Create a new user
     * POST /admin/users
     * 
     * Add a new user to the database.
     */
    public function createUser(array $userData): array
    {
        return $this->client->requestAsAdmin('POST', '/admin/users', $userData);
    }

    /**
     * Create a simple user with basic information
     */
    public function createSimpleUser(string $name, ?string $webhook = null, ?string $events = 'All'): array
    {
        $userData = [
            'name' => $name,
            'events' => $events
        ];

        if ($webhook) {
            $userData['webhook'] = $webhook;
        }

        return $this->createUser($userData);
    }

    /**
     * Create a user with proxy configuration
     */
    public function createUserWithProxy(
        string $name, 
        string $webhook,
        string $proxyUrl,
        bool $proxyEnabled = true,
        string $events = 'All'
    ): array {
        return $this->createUser([
            'name' => $name,
            'webhook' => $webhook,
            'events' => $events,
            'proxy_config' => [
                'enabled' => $proxyEnabled,
                'proxy_url' => $proxyUrl
            ]
        ]);
    }

    /**
     * Create a user with S3 configuration
     */
    public function createUserWithS3(
        string $name,
        string $webhook,
        array $s3Config,
        string $events = 'All'
    ): array {
        return $this->createUser([
            'name' => $name,
            'webhook' => $webhook,
            'events' => $events,
            's3_config' => $s3Config
        ]);
    }

    /**
     * Delete a user from database only
     * DELETE /admin/users/{id}
     * 
     * Deletes a user by their ID.
     */
    public function deleteUser(string $userId): array
    {
        return $this->client->requestAsAdmin('DELETE', "/admin/users/{$userId}");
    }

    /**
     * Delete a user completely (DB, S3, logout, disconnect, memory cleanup)
     * DELETE /admin/users/{id}/full
     * 
     * Deletes a user by their ID, including logout, disconnect and memory cleanup.
     * Also removes all user files from S3.
     */
    public function deleteUserFull(string $userId): array
    {
        return $this->client->requestAsAdmin('DELETE', "/admin/users/{$userId}/full");
    }

    /**
     * Get user information by ID (helper method to find user in list)
     */
    public function getUser(string $userId): ?array
    {
        $users = $this->listUsers();
        
        if (!isset($users['data']) || !is_array($users['data'])) {
            return null;
        }

        foreach ($users['data'] as $user) {
            if (isset($user['id']) && $user['id'] === $userId) {
                return $user;
            }
        }

        return null;
    }

    /**
     * Check if user exists
     */
    public function userExists(string $userId): bool
    {
        return $this->getUser($userId) !== null;
    }
}