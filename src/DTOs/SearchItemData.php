<?php

namespace AndyFraussen\UiTdatabankClient\DTOs;

class SearchItemData
{
    public function __construct(
        public readonly ?string $id,
        public readonly ?string $type,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        return new self(
            id: isset($data['@id']) ? (string) $data['@id'] : null,
            type: isset($data['@type']) ? (string) $data['@type'] : null,
            raw: $data,
        );
    }

    public function toArray(): array
    {
        return $this->raw;
    }
}
