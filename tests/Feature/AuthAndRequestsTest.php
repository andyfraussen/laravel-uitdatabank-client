<?php

namespace Tests\Feature;

use AndyFraussen\UiTdatabankClient\Exceptions\AuthenticationException;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Illuminate\Support\Facades\Config;
use Tests\TestCase;

class AuthAndRequestsTest extends TestCase
{
    public function testThrowsWhenSearchCredentialsAreMissing(): void
    {
        Config::set('uitdatabank.auth.client_id', null);
        Config::set('uitdatabank.auth.api_key', null);

        $this->expectException(AuthenticationException::class);

        app(UiTdatabankManager::class)
            ->events()
            ->search(['q' => 'test']);
    }
}
