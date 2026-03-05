<?php

namespace Tests\Unit;

use AndyFraussen\UiTdatabankClient\DTOs\SearchResultData;
use Tests\TestCase;

class DtoAndEnumTest extends TestCase
{
    public function testHydratesSearchResultDataAndMemberItems(): void
    {
        $fixture = json_decode((string) file_get_contents(__DIR__ . '/../Fixtures/search-result.json'), true);

        $result = SearchResultData::fromArray($fixture);

        $this->assertSame('http://www.w3.org/ns/hydra/context.jsonld', $result->context);
        $this->assertSame('PagedCollection', $result->type);
        $this->assertSame(1, $result->itemsPerPage);
        $this->assertSame(15609, $result->totalItems);
        $this->assertCount(1, $result->member);
        $this->assertSame('https://io-test.uitdatabank.be/event/e7806ab8-876c-4315-93ab-393492442ea3', $result->member[0]->id);
        $this->assertSame('Event', $result->member[0]->type);
        $this->assertSame($fixture, $result->toArray());
    }
}
