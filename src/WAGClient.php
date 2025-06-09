<?php

namespace WAG\LaravelSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WAG\LaravelSDK\Exceptions\WAGException;
use WAG\LaravelSDK\Services\{
    AdminService,
    SessionService,
    WebhookService,
    NewsletterService,
    MessageService,
    ContactService,
    GroupService,
    MediaService
};

class WAGClient
{
    private Client $httpClient;
    private string $baseUrl;
    private ?string $userToken;
    private ?string $adminApiKey;

    public function __construct(string $baseUrl, ?string $userToken = null)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->userToken = $userToken;
        $this->adminApiKey = config('wag.admin_api_key');
        
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => config('wag.timeout', 30),
        ]);
    }

    /**
     * Set user token for the current session
     */
    public function setUserToken(string $token): self
    {
        $this->userToken = $token;
        return $this;
    }

    /**
     * Get user token
     */
    public function getUserToken(): ?string
    {
        return $this->userToken;
    }

    public function admin(): AdminService
    {
        return new AdminService($this);
    }

    public function session(): SessionService
    {
        return new SessionService($this);
    }

    public function webhook(): WebhookService
    {
        return new WebhookService($this);
    }

    public function newsletter(): NewsletterService
    {
        return new NewsletterService($this);
    }

    public function message(): MessageService
    {
        return new MessageService($this);
    }

    public function contact(): ContactService
    {
        return new ContactService($this);
    }

    public function group(): GroupService
    {
        return new GroupService($this);
    }

    public function media(): MediaService
    {
        return new MediaService($this);
    }

    /**
     * Make request with user token authentication
     */
    public function request(string $method, string $endpoint, array $data = []): array
    {
        if (!$this->userToken) {
            throw new WAGException('User token is required for this operation. Use setUserToken() or pass token to constructor.');
        }

        return $this->makeRequest($method, $endpoint, $data, ['token' => $this->userToken]);
    }

    /**
     * Make request with admin API key authentication
     */
    public function requestAsAdmin(string $method, string $endpoint, array $data = []): array
    {
        if (!$this->adminApiKey) {
            throw new WAGException('Admin API key is required for this operation. Set WAG_ADMIN_API_KEY in config.');
        }

        return $this->makeRequest($method, $endpoint, $data, ['Authorization' => $this->adminApiKey]);
    }

    /**
     * Make request without authentication (for public endpoints)
     */
    public function requestPublic(string $method, string $endpoint, array $data = []): array
    {
        return $this->makeRequest($method, $endpoint, $data);
    }

    /**
     * Internal method to make HTTP requests
     */
    private function makeRequest(string $method, string $endpoint, array $data = [], array $headers = []): array
    {
        try {
            $options = [
                'headers' => array_merge([
                    'Content-Type' => 'application/json',
                    'Accept' => 'application/json',
                ], $headers)
            ];
            
            if (in_array(strtoupper($method), ['POST', 'PUT', 'PATCH']) && !empty($data)) {
                $options['json'] = $data;
            } elseif (strtoupper($method) === 'GET' && !empty($data)) {
                $options['query'] = $data;
            }

            $response = $this->httpClient->request($method, $endpoint, $options);
            
            return json_decode($response->getBody()->getContents(), true);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 0;
            $body = $response ? $response->getBody()->getContents() : $e->getMessage();
            
            throw new WAGException("API request failed: {$body}", $statusCode, $e);
        }
    }
}