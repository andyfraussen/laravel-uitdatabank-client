<?php

use AndyFraussen\UiTdatabankClient\Exceptions\AuthenticationException;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Illuminate\Support\Facades\Config;

it('throws when search credentials are missing', function (): void {
    Config::set('uitdatabank.auth.client_id', null);
    Config::set('uitdatabank.auth.api_key', null);

    expect(fn () => app(UiTdatabankManager::class)
        ->events()
        ->search(['q' => 'test']))
        ->toThrow(AuthenticationException::class);
});
