<?php

namespace Tests\Feature;

use AndyFraussen\UiTdatabankClient\DTOs\SearchResultData;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class SearchRequestsTest extends TestCase
{
    public function testSearchesEventsWithConfiguredHeaderCredentials(): void
    {
        $fixture = $this->searchFixture();

        Http::fake([
            'search-test.uitdatabank.be/events*' => Http::response($fixture, 200),
        ]);

        $result = app(UiTdatabankManager::class)
            ->events()
            ->search(['q' => 'brussel', 'limit' => 1]);

        $this->assertInstanceOf(SearchResultData::class, $result);
        $this->assertSame(15609, $result->totalItems);
        $this->assertSame('Event', $result->member[0]->type);

        Http::assertSent(fn (Request $request): bool =>
            $request->url() === 'https://search-test.uitdatabank.be/events?q=brussel&limit=1'
            && $request->method() === 'GET'
            && $request->hasHeader('x-client-id', 'test-client-id')
            && $request->hasHeader('x-api-key', 'test-api-key')
        );
    }

    public function testSupportsFluentPendingSearchQueryBuilder(): void
    {
        $fixture = $this->searchFixture();

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

        $this->assertInstanceOf(SearchResultData::class, $result);

        Http::assertSent(fn (Request $request): bool =>
            str_starts_with($request->url(), 'https://search-test.uitdatabank.be/offers?')
            && $request->hasHeader('x-client-id', 'test-client-id')
            && $request->hasHeader('x-api-key', 'test-api-key')
        );
    }

    public function testSupportsRuntimeCredentialsOverride(): void
    {
        $fixture = $this->searchFixture();

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
    }

    private function searchFixture(): array
    {
        return json_decode((string) file_get_contents(__DIR__ . '/../Fixtures/search-result.json'), true);
    }
}
