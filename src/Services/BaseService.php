<?php

namespace WAG\LaravelSDK\Services;

use WAG\LaravelSDK\WAGClient;

abstract class BaseService
{
    protected WAGClient $client;

    public function __construct(WAGClient $client)
    {
        $this->client = $client;
    }
}