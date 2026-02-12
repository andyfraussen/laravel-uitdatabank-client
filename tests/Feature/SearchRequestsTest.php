<?php

use AndyFraussen\UiTdatabankClient\DTOs\SearchResultData;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;

it('searches events with configured header credentials', function (): void {
    $fixture = json_decode(file_get_contents(__DIR__ . '/../Fixtures/search-result.json'), true);

    Http::fake([
        'search-test.uitdatabank.be/events*' => Http::response($fixture, 200),
    ]);

    $result = app(UiTdatabankManager::class)
        ->events()
        ->search(['q' => 'brussel', 'limit' => 1]);

    expect($result)->toBeInstanceOf(SearchResultData::class);
    expect($result->totalItems)->toBe(15609);
    expect($result->member[0]->type)->toBe('Event');

    Http::assertSent(fn (Request $request): bool =>
        $request->url() === 'https://search-test.uitdatabank.be/events?q=brussel&limit=1'
        && $request->method() === 'GET'
        && $request->hasHeader('x-client-id', 'test-client-id')
        && $request->hasHeader('x-api-key', 'test-api-key')
    );
});

it('supports fluent pending search query builder', function (): void {
    $fixture = json_decode(file_get_contents(__DIR__ . '/../Fixtures/search-result.json'), true);

    Http::fake([
        'search-test.uitdatabank.be/offers*' => Http::response($fixture, 200),
    ]);

    $result = app(UiTdatabankManager::class)
        ->offers()
        ->newQuery()
        ->q('muziek')
        ->limit(2)
        ->start(0)
        ->embed(['location', 'organizer'])
        ->where('sort[created]', 'desc')
        ->get();

    expect($result)->toBeInstanceOf(SearchResultData::class);

    Http::assertSent(fn (Request $request): bool =>
        str_starts_with($request->url(), 'https://search-test.uitdatabank.be/offers?')
        && $request->hasHeader('x-client-id', 'test-client-id')
        && $request->hasHeader('x-api-key', 'test-api-key')
    );
});

it('supports runtime credentials override', function (): void {
    $fixture = json_decode(file_get_contents(__DIR__ . '/../Fixtures/search-result.json'), true);

    Http::fake([
        'search-test.uitdatabank.be/places*' => Http::response($fixture, 200),
    ]);

    app(UiTdatabankManager::class)
        ->withCredentials('override-client-id', 'override-api-key')
        ->places()
        ->search(['q' => 'gent']);

    Http::assertSent(fn (Request $request): bool =>
        $request->hasHeader('x-client-id', 'override-client-id')
        && $request->hasHeader('x-api-key', 'override-api-key')
    );
});
