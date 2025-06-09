<?php

namespace WAG\LaravelSDK\Tests\Unit;

use WAG\LaravelSDK\Tests\TestCase;
use WAG\LaravelSDK\WAGClient;
use WAG\LaravelSDK\Services\MessageService;

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

    public function test_can_get_message_service()
    {
        $messageService = $this->client->message();
        $this->assertInstanceOf(MessageService::class, $messageService);
    }
}