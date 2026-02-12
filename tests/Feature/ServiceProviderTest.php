<?php

use AndyFraussen\UiTdatabankClient\Facades\UiTdatabank;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;

it('registers the manager and facade', function (): void {
    expect(app(UiTdatabankManager::class))->toBeInstanceOf(UiTdatabankManager::class);
    expect(UiTdatabank::offers())->toBeObject();
});

it('loads search-api config defaults', function (): void {
    expect(config('uitdatabank.environment'))->toBe('testing');
    expect(config('uitdatabank.base_url.testing'))->toBe('https://search-test.uitdatabank.be');
});
