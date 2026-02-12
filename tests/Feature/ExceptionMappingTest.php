<?php

use AndyFraussen\UiTdatabankClient\Exceptions\AuthenticationException;
use AndyFraussen\UiTdatabankClient\Exceptions\NotFoundException;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Illuminate\Support\Facades\Http;

it('maps 401 problem+json to AuthenticationException', function (): void {
    $problem = json_decode(file_get_contents(__DIR__ . '/../Fixtures/problem-unauthorized.json'), true);

    Http::fake([
        'search-test.uitdatabank.be/events*' => Http::response($problem, 401),
    ]);

    expect(fn () => app(UiTdatabankManager::class)
        ->events()
        ->search(['q' => 'brussel']))
        ->toThrow(AuthenticationException::class);
});

it('maps 404 to NotFoundException', function (): void {
    Http::fake([
        'search-test.uitdatabank.be/organizers*' => Http::response([
            'title' => 'Not Found',
            'type' => 'https://api.publiq.be/probs/not-found',
            'status' => 404,
        ], 404),
    ]);

    expect(fn () => app(UiTdatabankManager::class)
        ->organizers()
        ->search(['id' => 'does-not-exist']))
        ->toThrow(NotFoundException::class);
});
