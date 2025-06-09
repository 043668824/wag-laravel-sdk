<?php

namespace WAG\LaravelSDK\Tests;

use Orchestra\Testbench\TestCase as Orchestra;
use WAG\LaravelSDK\WAGServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            WAGServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('wag.base_url', 'http://localhost:8080');
        $app['config']->set('wag.api_key', 'test-api-key');
    }
}