<?php

namespace AndyFraussen\UiTdatabankClient\DTOs;

use AndyFraussen\UiTdatabankClient\Enums\TaxonomyDomain;

readonly class TaxonomyTermData
{
    public function __construct(
        public string $id,
        public TaxonomyDomain $domain,
        public array $scope,
        public TaxonomyTermNameData $name,
        public array $otherSuggestedTerms,
        public array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $suggested = array_map(
            static fn (array $term): self => self::fromArray($term),
            $data['otherSuggestedTerms'] ?? []
        );

        return new self(
            id: (string) ($data['id'] ?? ''),
            domain: TaxonomyDomain::from((string) ($data['domain'] ?? '')),
            scope: (array) ($data['scope'] ?? []),
            name: TaxonomyTermNameData::fromArray((array) ($data['name'] ?? [])),
            otherSuggestedTerms: $suggested,
            raw: $data,
        );
    }
}
