<?php

namespace WAG\LaravelSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use WAG\LaravelSDK\Exceptions\WAGException;
use WAG\LaravelSDK\Services\{
    AuthService,
    DeviceService,
    MessageService,
    ContactService,
    GroupService,
    MediaService
};

class WAGClient
{
    private Client $httpClient;
    private string $baseUrl;
    private ?string $apiKey;

    public function __construct(string $baseUrl, ?string $apiKey = null)
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->apiKey = $apiKey;
        
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => config('wag.timeout', 30),
            'headers' => array_filter([
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
                'Authorization' => $this->apiKey ? "Bearer {$this->apiKey}" : null,
            ]),
        ]);
    }

    public function auth(): AuthService
    {
        return new AuthService($this);
    }

    public function device(): DeviceService
    {
        return new DeviceService($this);
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

    public function request(string $method, string $endpoint, array $data = []): array
    {
        try {
            $options = [];
            
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