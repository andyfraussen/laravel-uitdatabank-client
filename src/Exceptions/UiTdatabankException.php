<?php

namespace AndyFraussen\UiTdatabankClient\Exceptions;

use Illuminate\Http\Client\Response;
use RuntimeException;

class UiTdatabankException extends RuntimeException
{
    public function __construct(
        string $message = 'UiTdatabank request failed.',
        public readonly ?int $status = null,
        public readonly ?string $type = null,
        public readonly ?string $title = null,
        public readonly ?string $detail = null,
        public readonly array $errors = [],
        public readonly ?array $raw = null,
    ) {
        parent::__construct($message, $status ?? 0);
    }

    public static function fromResponse(Response $response): self
    {
        $data = $response->json();
        $payload = is_array($data) ? $data : ['body' => $response->body()];

        $status = $response->status();
        $type = is_array($data) ? ($data['type'] ?? null) : null;
        $title = is_array($data) ? ($data['title'] ?? null) : null;
        $detail = is_array($data) ? ($data['detail'] ?? null) : null;
        $errors = is_array($data) ? ($data['schemaErrors'] ?? []) : [];

        $class = self::resolveExceptionClass($status, $type);
        $message = $detail ?? $title ?? sprintf('UiTdatabank request failed with status %d.', $status);

        return new $class(
            message: $message,
            status: $status,
            type: is_string($type) ? $type : null,
            title: is_string($title) ? $title : null,
            detail: is_string($detail) ? $detail : null,
            errors: is_array($errors) ? $errors : [],
            raw: $payload,
        );
    }

    private static function resolveExceptionClass(int $status, mixed $type): string
    {
        $typeValue = is_string($type) ? $type : null;
        $validationTypes = [
            'https://api.publiq.be/probs/body/missing',
            'https://api.publiq.be/probs/body/invalid-syntax',
            'https://api.publiq.be/probs/body/invalid-data',
        ];

        if ($status === 401) {
            return AuthenticationException::class;
        }

        if ($status === 403) {
            return AuthorizationException::class;
        }

        if ($status === 404) {
            return NotFoundException::class;
        }

        if ($status === 409 || in_array($typeValue, [
            'https://api.publiq.be/probs/uitdatabank/duplicate-place',
            'https://api.publiq.be/probs/uitdatabank/duplicate-url',
        ], true)) {
            return DuplicateException::class;
        }

        if ($status === 400 && in_array($typeValue, $validationTypes, true)) {
            return ValidationException::class;
        }

        return self::class;
    }
}
