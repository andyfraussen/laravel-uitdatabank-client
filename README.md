# Laravel UiTdatabank Client

[![Latest Version on Packagist](https://img.shields.io/packagist/v/andyfraussen/laravel-uitdatabank-client.svg?style=flat-square)](https://packagist.org/packages/andyfraussen/laravel-uitdatabank-client)
[![Total Downloads](https://img.shields.io/packagist/dt/andyfraussen/laravel-uitdatabank-client.svg?style=flat-square)](https://packagist.org/packages/andyfraussen/laravel-uitdatabank-client)
[![License](https://img.shields.io/packagist/l/andyfraussen/laravel-uitdatabank-client.svg?style=flat-square)](LICENSE.md)

A fluent Laravel client for **UiTdatabank APIs**.

Current module support:

- `Search API` (available now)

Search endpoints currently supported:

- `GET /offers`
- `GET /events`
- `GET /places`
- `GET /organizers`

## Roadmap

- [x] Search API
- [ ] Taxonomy API (`GET /terms`)
- [ ] Entry API

## Features

- Laravel service provider + facade auto-discovery
- Fluent query builder (`newQuery()->q()->limit()->where()->get()`)
- Typed DTO responses (`SearchResultData`, `SearchItemData`)
- Config-based credentials (`x-client-id` + `x-api-key`)
- Runtime credential override for multi-tenant scenarios
- Problem+JSON exception mapping

## Installation

```bash
composer require andyfraussen/laravel-uitdatabank-client
```

## Requirements

- PHP `8.3+`
- Laravel `12`

Publish the config file:

```bash
php artisan vendor:publish --provider="AndyFraussen\UiTdatabankClient\UiTdatabankServiceProvider" --tag="uitdatabank-config"
```

## Configuration

Set credentials in `.env`:

```env
UITDATABANK_ENV=testing
UITDATABANK_CLIENT_ID=your-client-id
UITDATABANK_API_KEY=your-api-key
UITDATABANK_TIMEOUT=30
UITDATABANK_RETRY_TIMES=3
UITDATABANK_RETRY_SLEEP=100
```

Available environments:

- `testing` -> `https://search-test.uitdatabank.be`
- `production` -> `https://search.uitdatabank.be`

## Usage

### Basic search

```php
use AndyFraussen\UiTdatabankClient\Facades\UiTdatabank;

$result = UiTdatabank::events()->search([
    'q' => 'brussel',
    'limit' => 10,
]);

$total = $result->totalItems;
$items = $result->member;
```

### Fluent query builder

```php
$result = UiTdatabank::offers()
    ->newQuery()
    ->q('muziek')
    ->limit(20)
    ->start(0)
    ->embed(['location', 'organizer'])
    ->where('sort[created]', 'desc')
    ->get();
```

### Runtime credential override

```php
$result = UiTdatabank::withCredentials($clientId, $apiKey)
    ->places()
    ->search(['q' => 'gent', 'limit' => 5]);
```

## Response DTOs

`SearchResultData` contains:

- `context` (`@context`)
- `type` (`@type`)
- `itemsPerPage`
- `totalItems`
- `member` (array of `SearchItemData`)
- `raw` (original response array)

`SearchItemData` contains:

- `id` (`@id`)
- `type` (`@type`)
- `raw` (original item payload)

## Error handling

Non-2xx responses throw package exceptions:

- `AuthenticationException` (`401`)
- `AuthorizationException` (`403`)
- `NotFoundException` (`404`)
- `DuplicateException` (`409`, when applicable)
- `ValidationException` (`400` validation problem types)
- Fallback: `UiTdatabankException`

```php
use AndyFraussen\UiTdatabankClient\Exceptions\AuthenticationException;

try {
    $result = UiTdatabank::events()->search(['q' => 'antwerpen']);
} catch (AuthenticationException $e) {
    report($e);
}
```

## Testing

```bash
composer test
```

## License

The MIT License (MIT). See [LICENSE.md](LICENSE.md).
