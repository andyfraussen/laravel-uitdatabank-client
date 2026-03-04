<?php

namespace AndyFraussen\UiTdatabankClient\Http;

use AndyFraussen\UiTdatabankClient\Exceptions\UiTdatabankException;
use Illuminate\Contracts\Config\Repository as ConfigRepository;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TaxonomyClient
{
    public function __construct(
        private readonly ConfigRepository $config,
    ) {
    }

    public function get(string $uri): Response
    {
        $baseUrl = (string) $this->config->get('uitdatabank.taxonomy.base_url', 'https://taxonomy.uitdatabank.be');

        $timeout = (int) $this->config->get('uitdatabank.timeout', 30);
        $retryTimes = (int) $this->config->get('uitdatabank.retry.times', 3);
        $retrySleep = (int) $this->config->get('uitdatabank.retry.sleep', 100);

        $response = Http::baseUrl($baseUrl)
            ->acceptJson()
            ->timeout($timeout)
            ->retry($retryTimes, $retrySleep, throw: false)
            ->get($uri);

        if (! $response->successful()) {
            throw UiTdatabankException::fromResponse($response);
        }

        return $response;
    }
}
