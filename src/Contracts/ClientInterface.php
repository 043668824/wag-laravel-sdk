<?php

namespace WAG\LaravelSDK\Contracts;

interface ClientInterface
{
    /**
     * Set user token for authentication
     */
    public function setUserToken(string $token): self;
    
    /**
     * Get the current user token
     */
    public function getUserToken(): ?string;
    
    /**
     * Make a request with user token authentication
     */
    public function request(string $method, string $endpoint, array $data = []): array;
    
    /**
     * Make a request with admin token authentication
     */
    public function requestAsAdmin(string $method, string $endpoint, array $data = []): array;
}