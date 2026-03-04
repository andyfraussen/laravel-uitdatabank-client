<?php

namespace Tests\Feature;

use AndyFraussen\UiTdatabankClient\DTOs\TaxonomyResultData;
use AndyFraussen\UiTdatabankClient\DTOs\TaxonomyTermData;
use AndyFraussen\UiTdatabankClient\Enums\TaxonomyDomain;
use AndyFraussen\UiTdatabankClient\Exceptions\NotFoundException;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Illuminate\Http\Client\Request;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class TaxonomyRequestsTest extends TestCase
{
    public function testTermsReturnsTaxonomyResultData(): void
    {
        $fixture = $this->taxonomyFixture();

        Http::fake([
            'taxonomy.uitdatabank.be/terms' => Http::response($fixture, 200),
        ]);

        $result = app(UiTdatabankManager::class)->taxonomy()->terms();

        $this->assertInstanceOf(TaxonomyResultData::class, $result);
        $this->assertCount(2, $result->terms);
    }

    public function testTermsMapsToCorrectDtos(): void
    {
        $fixture = $this->taxonomyFixture();

        Http::fake([
            'taxonomy.uitdatabank.be/terms' => Http::response($fixture, 200),
        ]);

        $result = app(UiTdatabankManager::class)->taxonomy()->terms();

        /** @var TaxonomyTermData $facility */
        $facility = $result->terms[0];
        $this->assertSame('3.23.3.0.0', $facility->id);
        $this->assertSame(TaxonomyDomain::Facility, $facility->domain);
        $this->assertSame(['events', 'places'], $facility->scope);
        $this->assertSame('Rolstoeltoegankelijk', $facility->name->nl);
        $this->assertSame('Accessible en fauteuil roulant', $facility->name->fr);
        $this->assertCount(0, $facility->otherSuggestedTerms);

        /** @var TaxonomyTermData $eventtype */
        $eventtype = $result->terms[1];
        $this->assertSame('0.50.4.0.0', $eventtype->id);
        $this->assertSame(TaxonomyDomain::Eventtype, $eventtype->domain);
        $this->assertSame('Concert', $eventtype->name->nl);
        $this->assertCount(1, $eventtype->otherSuggestedTerms);

        /** @var TaxonomyTermData $suggested */
        $suggested = $eventtype->otherSuggestedTerms[0];
        $this->assertSame('1.8.3.5.0', $suggested->id);
        $this->assertSame(TaxonomyDomain::Theme, $suggested->domain);
        $this->assertSame('Pop en rock', $suggested->name->nl);
    }

    public function testCorrectUrlIsCalled(): void
    {
        Http::fake([
            'taxonomy.uitdatabank.be/terms' => Http::response($this->taxonomyFixture(), 200),
        ]);

        app(UiTdatabankManager::class)->taxonomy()->terms();

        Http::assertSent(fn (Request $request): bool =>
            $request->url() === 'https://taxonomy.uitdatabank.be/terms'
            && $request->method() === 'GET'
        );
    }

    public function testNoAuthHeadersAreSent(): void
    {
        Http::fake([
            'taxonomy.uitdatabank.be/terms' => Http::response($this->taxonomyFixture(), 200),
        ]);

        app(UiTdatabankManager::class)->taxonomy()->terms();

        Http::assertSent(fn (Request $request): bool =>
            ! $request->hasHeader('x-client-id')
            && ! $request->hasHeader('x-api-key')
        );
    }

    public function testMaps404ToNotFoundException(): void
    {
        Http::fake([
            'taxonomy.uitdatabank.be/terms' => Http::response([
                'title' => 'Not Found',
                'type' => 'https://api.publiq.be/probs/not-found',
                'status' => 404,
            ], 404),
        ]);

        $this->expectException(NotFoundException::class);

        app(UiTdatabankManager::class)->taxonomy()->terms();
    }

    private function taxonomyFixture(): array
    {
        return json_decode((string) file_get_contents(__DIR__ . '/../Fixtures/taxonomy-terms.json'), true);
    }
}
