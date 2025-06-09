<?php

namespace WAG\LaravelSDK;

use Illuminate\Support\ServiceProvider;
use WAG\LaravelSDK\Contracts\ClientInterface;

class WAGServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/wag.php',
            'wag'
        );

        $this->app->singleton('wag', function ($app) {
            $config = $app['config']['wag'];
            
            return new WAGClient(
                $config['base_url'],
                $config['admin_token']
            );
        });

        $this->app->alias('wag', WAGClient::class);
        $this->app->alias('wag', ClientInterface::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/wag.php' => config_path('wag.php'),
            ], 'wag-config');
        }
    }
}