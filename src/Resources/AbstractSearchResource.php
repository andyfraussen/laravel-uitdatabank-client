<?php

namespace AndyFraussen\UiTdatabankClient\Resources;

use AndyFraussen\UiTdatabankClient\DTOs\SearchResultData;
use AndyFraussen\UiTdatabankClient\Http\UiTdatabankClient;
use AndyFraussen\UiTdatabankClient\Pending\PendingSearch;

abstract class AbstractSearchResource
{
    public function __construct(
        protected readonly UiTdatabankClient $client,
    ) {
    }

    abstract protected function endpoint(): string;

    public function search(array $query = []): SearchResultData
    {
        return $this->newQuery()->query($query)->get();
    }

    public function newQuery(): PendingSearch
    {
        return new PendingSearch($this->client, $this->endpoint());
    }
}
