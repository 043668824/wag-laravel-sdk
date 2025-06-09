<?php

namespace WAG\LaravelSDK;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use WAG\LaravelSDK\Contracts\ClientInterface;
use WAG\LaravelSDK\Exceptions\WAGException;
use WAG\LaravelSDK\Utils\PhoneFormatter;
use WAG\LaravelSDK\Utils\ResponseFormatter;
use WAG\LaravelSDK\Services\{
    AdminService,
    SessionService,
    WebhookService,
    NewsletterService,
    GroupService,
    ChatService,
    UserService
};

class WAGClient implements ClientInterface
{
    private Client $httpClient;
    private string $baseUrl;
    private ?string $userToken = null;
    private ?string $adminToken;
    private bool $logging;
    private string $logChannel;

    /**
     * Create a new WAG client instance
     */
    public function __construct(string $baseUrl, ?string $adminToken = null, array $config = [])
    {
        $this->baseUrl = rtrim($baseUrl, '/');
        $this->adminToken = $adminToken;
        
        // Configure logging
        $this->logging = $config['logging']['enabled'] ?? false;
        $this->logChannel = $config['logging']['channel'] ?? 'stack';
        
        // Configure HTTP client
        $this->httpClient = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => $config['http']['timeout'] ?? 30,
            'connect_timeout' => $config['http']['connect_timeout'] ?? 10,
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

    /**
     * Clear the user token
     */
    public function clearUserToken(): self
    {
        $this->userToken = null;
        return $this;
    }

    /**
     * Format phone number according to WUZAPI requirements
     */
    public function formatPhoneNumber(string $phone): string
    {
        return PhoneFormatter::format($phone);
    }
    
    /**
     * Format phone number to JID format
     */
    public function formatPhoneToJID(string $phone): string
    {
        return PhoneFormatter::toJID($phone);
    }
    
    /**
     * Validate phone number
     */
    public function isValidPhoneNumber(string $phone): bool
    {
        return PhoneFormatter::validate($phone);
    }

    /**
     * Create a new admin service instance
     */
    public function admin(): AdminService
    {
        return new AdminService($this);
    }
    
    /**
     * Create a new session service instance
     */
    public function session(): SessionService
    {
        return new SessionService($this);
    }
    
    /**
     * Create a new webhook service instance
     */
    public function webhook(): WebhookService
    {
        return new WebhookService($this);
    }
    
    /**
     * Create a new chat service instance
     */
    public function chat(): ChatService
    {
        return new ChatService($this);
    }
    
    /**
     * Create a new group service instance
     */
    public function group(): GroupService
    {
        return new GroupService($this);
    }
    
    /**
     * Create a new user service instance
     */
    public function user(): UserService
    {
        return new UserService($this);
    }
    
    /**
     * Create a new newsletter service instance
     */
    public function newsletter(): NewsletterService
    {
        return new NewsletterService($this);
    }
    
    /**
     * Make request with user token authentication
     */
    public function request(string $method, string $endpoint, array $data = []): array
    {
        if (!$this->userToken) {
            throw new WAGException('User token is required. Use setUserToken() before making requests.');
        }

        return $this->makeRequest($method, $endpoint, $data, ['token' => $this->userToken]);
    }

    /**
     * Make request with admin API key authentication
     */
    public function requestAsAdmin(string $method, string $endpoint, array $data = []): array
    {
        if (!$this->adminToken) {
            throw new WAGException('Admin token is required. Set WUZAPI_ADMIN_TOKEN in config.');
        }

        return $this->makeRequest($method, $endpoint, $data, ['Authorization' => $this->adminToken]);
    }

    /**
     * Make one-time request with specific token
     */
    public function requestWithToken(string $method, string $endpoint, string $token, array $data = []): array
    {
        return $this->makeRequest($method, $endpoint, $data, ['token' => $token]);
    }

    /**
     * Internal method to make HTTP requests
     */
    private function makeRequest(string $method, string $endpoint, array $data = [], array $headers = []): array
    {
        $endpoint = ltrim($endpoint, '/');  // Ensure no leading slash
        
        try {
            // Log request if logging is enabled
            if ($this->logging) {
                $this->logRequest($method, $endpoint, $data);
            }
            
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
            $contents = $response->getBody()->getContents();
            $result = json_decode($contents, true);
            
            // Log response if logging is enabled
            if ($this->logging) {
                $this->logResponse($result);
            }
            
            return $result ?: ['success' => true];
            
        } catch (RequestException $e) {
            $response = $e->getResponse();
            $statusCode = $response ? $response->getStatusCode() : 0;
            $body = $response ? $response->getBody()->getContents() : $e->getMessage();
            
            // Log error if logging is enabled
            if ($this->logging) {
                $this->logError($e, $statusCode, $body);
            }
            
            throw new WAGException("API request failed: {$body}", $statusCode, $e);
        }
    }
    
    /**
     * Log API request
     */
    private function logRequest(string $method, string $endpoint, array $data): void
    {
        // Don't log sensitive information
        $sanitizedData = $this->sanitizeData($data);
        
        Log::channel($this->logChannel)->debug('WAG API Request', [
            'method' => $method,
            'endpoint' => $endpoint,
            'data' => $sanitizedData,
        ]);
    }
    
    /**
     * Log API response
     */
    private function logResponse(array $response): void
    {
        // Don't log potentially sensitive response data
        $sanitizedResponse = $this->sanitizeData($response);
        
        Log::channel($this->logChannel)->debug('WAG API Response', [
            'response' => $sanitizedResponse,
        ]);
    }
    
    /**
     * Log API error
     */
    private function logError(\Exception $e, int $statusCode, string $body): void
    {
        Log::channel($this->logChannel)->error('WAG API Error', [
            'status' => $statusCode,
            'message' => $e->getMessage(),
            'response' => $body,
        ]);
    }
    
    /**
     * Remove sensitive data from logs
     */
    private function sanitizeData(array $data): array
    {
        $sensitiveFields = [
            'token', 'password', 'secret', 'key', 'auth', 'authorization', 'image', 'audio', 'video', 'document'
        ];
        
        $sanitized = [];
        
        foreach ($data as $key => $value) {
            if (is_string($key) && in_array(strtolower($key), $sensitiveFields)) {
                $sanitized[$key] = '[REDACTED]';
            } elseif (is_array($value)) {
                $sanitized[$key] = $this->sanitizeData($value);
            } else {
                $sanitized[$key] = $value;
            }
        }
        
        return $sanitized;
    }
}