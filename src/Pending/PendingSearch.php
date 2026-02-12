<?php

namespace AndyFraussen\UiTdatabankClient\Pending;

use AndyFraussen\UiTdatabankClient\DTOs\SearchResultData;
use AndyFraussen\UiTdatabankClient\Http\UiTdatabankClient;

class PendingSearch
{
    private array $query = [];

    public function __construct(
        private readonly UiTdatabankClient $client,
        private readonly string $endpoint,
    ) {
    }

    public function query(array $query): static
    {
        foreach ($query as $key => $value) {
            $this->query[(string) $key] = $value;
        }

        return $this;
    }

    public function where(string $key, mixed $value): static
    {
        $this->query[$key] = $value;

        return $this;
    }

    public function q(string $q): static
    {
        $this->query['q'] = $q;

        return $this;
    }

    public function text(string $text): static
    {
        $this->query['text'] = $text;

        return $this;
    }

    public function limit(int $limit): static
    {
        $this->query['limit'] = $limit;

        return $this;
    }

    public function start(int $start): static
    {
        $this->query['start'] = $start;

        return $this;
    }

    public function embed(array|string $embed): static
    {
        $this->query['embed'] = is_array($embed) ? implode(',', $embed) : $embed;

        return $this;
    }

    public function get(): SearchResultData
    {
        $response = $this->client->get($this->endpoint, $this->query);

        return SearchResultData::fromArray($response->json() ?? []);
    }
}
