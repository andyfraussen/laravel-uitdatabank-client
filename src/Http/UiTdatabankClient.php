<?php

namespace AndyFraussen\UiTdatabankClient\Http;

use AndyFraussen\UiTdatabankClient\Exceptions\AuthenticationException;
use AndyFraussen\UiTdatabankClient\Exceptions\UiTdatabankException;
use AndyFraussen\UiTdatabankClient\Support\Credentials;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class UiTdatabankClient
{
    public function __construct(
        private readonly ConfigRepository $config,
        private readonly ?Credentials $credentials = null,
    ) {
    }

    public function withCredentials(Credentials $credentials): self
    {
        return new self($this->config, $credentials);
    }

    public function get(string $uri, array $query = []): Response
    {
        $response = $this->baseRequest()->get($uri, $query);

        if (! $response->successful()) {
            throw UiTdatabankException::fromResponse($response);
        }

        return $response;
    }

    private function baseRequest(): PendingRequest
    {
        $environment = (string) $this->config->get('uitdatabank.environment', 'testing');
        $baseUrl = (string) $this->config->get("uitdatabank.base_url.{$environment}");

        if ($baseUrl === '') {
            throw new UiTdatabankException("UiTdatabank base URL for environment [{$environment}] is not configured.");
        }

        $credentials = $this->credentials ?? Credentials::fromConfig($this->config);
        if (! $credentials->isConfigured()) {
            throw new AuthenticationException('Search API credentials are not configured. Expected uitdatabank.auth.client_id and uitdatabank.auth.api_key.');
        }

        $timeout = (int) $this->config->get('uitdatabank.timeout', 30);
        $retryTimes = (int) $this->config->get('uitdatabank.retry.times', 3);
        $retrySleep = (int) $this->config->get('uitdatabank.retry.sleep', 100);

        return Http::baseUrl($baseUrl)
            ->acceptJson()
            ->withHeaders($credentials->toHeaders())
            ->timeout($timeout)
            ->retry($retryTimes, $retrySleep, throw: false);
    }
}
