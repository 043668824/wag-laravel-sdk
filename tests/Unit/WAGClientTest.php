<?php

namespace WAG\LaravelSDK\Tests\Unit;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Psr7\Response;
use WAG\LaravelSDK\Tests\TestCase;
use WAG\LaravelSDK\WAGClient;
use WAG\LaravelSDK\Services\ChatService;
use WAG\LaravelSDK\Exceptions\WAGException;

/**
 * WAGClient Unit Tests
 *
 * @author WAG Team
 * @version 1.0.0
 * @date    2025-06-09
 */
class WAGClientTest extends TestCase
{
    private WAGClient $client;
    private MockHandler $mock;
    
    protected function setUp(): void
    {
        parent::setUp();
        
        // Create mock handler for Guzzle
        $this->mock = new MockHandler([
            new Response(200, [], json_encode(['success' => true])),
        ]);
        
        $handlerStack = HandlerStack::create($this->mock);
        $httpClient = new Client(['handler' => $handlerStack]);
        
        // Create client with test configuration
        $this->client = new WAGClient('http://localhost:8080', 'test-admin-token', [
            'http' => [
                'timeout' => 10,
                'connect_timeout' => 5
            ],
            'logging' => [
                'enabled' => false
            ]
        ]);
        
        // Use reflection to inject the mock HTTP client
        $reflection = new \ReflectionClass($this->client);
        $property = $reflection->getProperty('httpClient');
        $property->setAccessible(true);
        $property->setValue($this->client, $httpClient);
    }

    public function test_can_create_client()
    {
        $this->assertInstanceOf(WAGClient::class, $this->client);
    }

    public function test_can_get_chat_service()
    {
        $chatService = $this->client->chat();
        $this->assertInstanceOf(ChatService::class, $chatService);
    }
    
    public function test_can_set_and_get_user_token()
    {
        $this->client->setUserToken('test-user-token');
        $this->assertEquals('test-user-token', $this->client->getUserToken());
    }
    
    public function test_can_clear_user_token()
    {
        $this->client->setUserToken('test-user-token');
        $this->client->clearUserToken();
        $this->assertNull($this->client->getUserToken());
    }
    
    public function test_can_format_phone_number()
    {
        $formattedPhone = $this->client->formatPhoneNumber('+1 (234) 567-8901');
        $this->assertEquals('12345678901', $formattedPhone);
    }
    
    public function test_can_format_phone_to_jid()
    {
        $formattedJid = $this->client->formatPhoneToJID('12345678901');
        $this->assertEquals('12345678901@s.whatsapp.net', $formattedJid);
    }
    
    public function test_request_throws_exception_when_no_user_token()
    {
        $this->expectException(WAGException::class);
        $this->expectExceptionMessage('User token is required');
        
        $this->client->request('GET', '/some/endpoint');
    }
    
    public function test_request_succeeds_with_user_token()
    {
        $this->client->setUserToken('test-user-token');
        $response = $this->client->request('GET', '/some/endpoint');
        
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
    }
    
    public function test_request_as_admin_succeeds()
    {
        $response = $this->client->requestAsAdmin('GET', '/admin/endpoint');
        
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
    }
    
    public function test_request_with_token_succeeds()
    {
        $response = $this->client->requestWithToken('GET', '/some/endpoint', 'one-time-token');
        
        $this->assertArrayHasKey('success', $response);
        $this->assertTrue($response['success']);
    }
}