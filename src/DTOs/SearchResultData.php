<?php

namespace AndyFraussen\UiTdatabankClient\DTOs;

class SearchResultData
{
    /**
     * @param array<int,SearchItemData> $member
     */
    public function __construct(
        public readonly ?string $context,
        public readonly ?string $type,
        public readonly ?int $itemsPerPage,
        public readonly ?int $totalItems,
        public readonly array $member,
        public readonly array $raw,
    ) {
    }

    public static function fromArray(array $data): self
    {
        $member = array_map(
            static fn (array $item): SearchItemData => SearchItemData::fromArray($item),
            array_values(array_filter($data['member'] ?? [], static fn ($item): bool => is_array($item)))
        );

        return new self(
            context: isset($data['@context']) ? (string) $data['@context'] : null,
            type: isset($data['@type']) ? (string) $data['@type'] : null,
            itemsPerPage: isset($data['itemsPerPage']) ? (int) $data['itemsPerPage'] : null,
            totalItems: isset($data['totalItems']) ? (int) $data['totalItems'] : null,
            member: $member,
            raw: $data,
        );
    }

    public function toArray(): array
    {
        return $this->raw;
    }
}
