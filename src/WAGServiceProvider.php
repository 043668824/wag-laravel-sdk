<?php

namespace WAG\LaravelSDK;

use Illuminate\Support\ServiceProvider;

class WAGServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->singleton('wag', function ($app) {
            return new WAGClient(
                $app['config']['wag.base_url'],
                $app['config']['wag.api_key']
            );
        });

        $this->mergeConfigFrom(
            __DIR__ . '/../config/wag.php',
            'wag'
        );
    }

    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/wag.php' => config_path('wag.php'),
        ], 'wag-config');
    }
}