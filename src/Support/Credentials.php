<?php

namespace AndyFraussen\UiTdatabankClient\Support;

use Illuminate\Contracts\Config\Repository as ConfigRepository;

final class Credentials
{
    private function __construct(
        public readonly ?string $clientId,
        public readonly ?string $apiKey,
    ) {
    }

    public static function fromConfig(ConfigRepository $config): self
    {
        return new self(
            clientId: self::normalize($config->get('uitdatabank.auth.client_id')),
            apiKey: self::normalize($config->get('uitdatabank.auth.api_key')),
        );
    }

    public static function explicit(string $clientId, string $apiKey): self
    {
        return new self($clientId, $apiKey);
    }

    public function withOverrides(?string $clientId = null, ?string $apiKey = null): self
    {
        return new self(
            clientId: $clientId ?? $this->clientId,
            apiKey: $apiKey ?? $this->apiKey,
        );
    }

    public function isConfigured(): bool
    {
        return $this->clientId !== null && $this->clientId !== ''
            && $this->apiKey !== null && $this->apiKey !== '';
    }

    public function toHeaders(): array
    {
        return [
            'x-client-id' => $this->clientId,
            'x-api-key' => $this->apiKey,
        ];
    }

    private static function normalize(mixed $value): ?string
    {
        if (! is_string($value)) {
            return null;
        }

        $trimmed = trim($value);

        return $trimmed === '' ? null : $trimmed;
    }
}
