<?php

namespace WAG\LaravelSDK\Facades;

use Illuminate\Support\Facades\Facade;
use WAG\LaravelSDK\WAGClient;

/**
 * @method static \WAG\LaravelSDK\WAGClient setUserToken(string $token)
 * @method static \WAG\LaravelSDK\Services\AdminService admin()
 * @method static \WAG\LaravelSDK\Services\SessionService session()
 * @method static \WAG\LaravelSDK\Services\WebhookService webhook()
 * @method static \WAG\LaravelSDK\Services\ChatService chat()
 * @method static \WAG\LaravelSDK\Services\GroupService group()
 * @method static \WAG\LaravelSDK\Services\UserService user()
 * @method static \WAG\LaravelSDK\Services\NewsletterService newsletter()
 * 
 * @see \WAG\LaravelSDK\WAGClient
 */
class WAG extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'wag';
    }
}