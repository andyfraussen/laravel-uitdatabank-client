<?php

namespace Tests\Feature;

use AndyFraussen\UiTdatabankClient\Exceptions\AuthenticationException;
use AndyFraussen\UiTdatabankClient\Exceptions\NotFoundException;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ExceptionMappingTest extends TestCase
{
    public function testMaps401ProblemJsonToAuthenticationException(): void
    {
        $problem = json_decode((string) file_get_contents(__DIR__ . '/../Fixtures/problem-unauthorized.json'), true);

        Http::fake([
            'search-test.uitdatabank.be/events*' => Http::response($problem, 401),
        ]);

        $this->expectException(AuthenticationException::class);

        app(UiTdatabankManager::class)
            ->events()
            ->search(['q' => 'brussel']);
    }

    public function testMaps404ToNotFoundException(): void
    {
        Http::fake([
            'search-test.uitdatabank.be/organizers*' => Http::response([
                'title' => 'Not Found',
                'type' => 'https://api.publiq.be/probs/not-found',
                'status' => 404,
            ], 404),
        ]);

        $this->expectException(NotFoundException::class);

        app(UiTdatabankManager::class)
            ->organizers()
            ->search(['id' => 'does-not-exist']);
    }
}
