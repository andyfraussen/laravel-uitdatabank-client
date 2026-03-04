<?php

namespace AndyFraussen\UiTdatabankClient\Resources;

use AndyFraussen\UiTdatabankClient\DTOs\TaxonomyResultData;
use AndyFraussen\UiTdatabankClient\Http\TaxonomyClient;

class TaxonomyResource
{
    public function __construct(private readonly TaxonomyClient $client)
    {
    }

    public function terms(): TaxonomyResultData
    {
        $response = $this->client->get('/terms');

        return TaxonomyResultData::fromArray($response->json());
    }
}
