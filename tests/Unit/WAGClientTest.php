<?php

namespace WAG\LaravelSDK\Tests\Unit;

use WAG\LaravelSDK\Tests\TestCase;
use WAG\LaravelSDK\WAGClient;
use WAG\LaravelSDK\Services\ChatService;

/**
 * WAGClient Unit Tests
 *
 * @author  Your Name
 * @version 1.0.0
 * @date    2025-06-09
 */
class WAGClientTest extends TestCase
{
    private WAGClient $client;

    protected function setUp(): void
    {
        parent::setUp();
        $this->client = new WAGClient('http://localhost:8080', 'test-api-key');
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
}