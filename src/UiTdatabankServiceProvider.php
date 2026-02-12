<?php

namespace AndyFraussen\UiTdatabankClient;

use AndyFraussen\UiTdatabankClient\Http\UiTdatabankClient;
use Illuminate\Support\ServiceProvider;

class UiTdatabankServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/uitdatabank.php', 'uitdatabank');

        $this->app->singleton(UiTdatabankClient::class, fn ($app) => new UiTdatabankClient(
            config: $app['config']
        ));

        $this->app->singleton(UiTdatabankManager::class, fn ($app) => new UiTdatabankManager(
            $app->make(UiTdatabankClient::class)
        ));

        $this->app->alias(UiTdatabankManager::class, 'uitdatabank');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__ . '/../config/uitdatabank.php' => config_path('uitdatabank.php'),
        ], 'uitdatabank-config');
    }
}
