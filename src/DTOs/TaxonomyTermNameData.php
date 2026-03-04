<?php

namespace AndyFraussen\UiTdatabankClient\DTOs;

readonly class TaxonomyTermNameData
{
    public function __construct(
        public string $nl,
        public ?string $fr,
        public ?string $de,
        public ?string $en,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            nl: (string) ($data['nl'] ?? ''),
            fr: isset($data['fr']) ? (string) $data['fr'] : null,
            de: isset($data['de']) ? (string) $data['de'] : null,
            en: isset($data['en']) ? (string) $data['en'] : null,
        );
    }
}
