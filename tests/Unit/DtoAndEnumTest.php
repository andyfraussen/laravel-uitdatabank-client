<?php

use AndyFraussen\UiTdatabankClient\DTOs\SearchResultData;

it('hydrates search result data and member items', function (): void {
    $fixture = json_decode(file_get_contents(__DIR__ . '/../Fixtures/search-result.json'), true);

    $result = SearchResultData::fromArray($fixture);

    expect($result->context)->toBe('http://www.w3.org/ns/hydra/context.jsonld');
    expect($result->type)->toBe('PagedCollection');
    expect($result->itemsPerPage)->toBe(1);
    expect($result->totalItems)->toBe(15609);
    expect($result->member)->toHaveCount(1);
    expect($result->member[0]->id)->toBe('https://io-test.uitdatabank.be/event/e7806ab8-876c-4315-93ab-393492442ea3');
    expect($result->member[0]->type)->toBe('Event');
    expect($result->toArray())->toBe($fixture);
});
