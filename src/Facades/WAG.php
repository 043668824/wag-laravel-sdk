<?php

namespace WAG\LaravelSDK\Facades;

use Illuminate\Support\Facades\Facade;

class WAG extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'wag';
    }
}