<?php

namespace Tests;

use AndyFraussen\UiTdatabankClient\UiTdatabankServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;

abstract class TestCase extends Orchestra
{
    protected function getPackageProviders($app): array
    {
        return [
            UiTdatabankServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app): void
    {
        $app['config']->set('uitdatabank.environment', 'testing');
        $app['config']->set('uitdatabank.base_url.testing', 'https://search-test.uitdatabank.be');
        $app['config']->set('uitdatabank.base_url.production', 'https://search.uitdatabank.be');
        $app['config']->set('uitdatabank.auth.client_id', 'test-client-id');
        $app['config']->set('uitdatabank.auth.api_key', 'test-api-key');
        $app['config']->set('uitdatabank.timeout', 30);
        $app['config']->set('uitdatabank.retry.times', 1);
        $app['config']->set('uitdatabank.retry.sleep', 1);
    }
}
