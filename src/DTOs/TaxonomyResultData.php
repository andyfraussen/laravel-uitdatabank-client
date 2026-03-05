<?php

namespace AndyFraussen\UiTdatabankClient\DTOs;

readonly class TaxonomyResultData
{
    public function __construct(
        public array $terms,
        public array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $terms = array_map(
            static fn (array $term): TaxonomyTermData => TaxonomyTermData::fromArray($term),
            $data['terms'] ?? []
        );

        return new self(
            terms: $terms,
            raw: $data,
        );
    }
}
