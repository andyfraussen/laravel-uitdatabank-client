<?php

namespace AndyFraussen\UiTdatabankClient;

use AndyFraussen\UiTdatabankClient\Http\TaxonomyClient;
use AndyFraussen\UiTdatabankClient\Http\UiTdatabankClient;
use AndyFraussen\UiTdatabankClient\Resources\EventSearchResource;
use AndyFraussen\UiTdatabankClient\Resources\OfferSearchResource;
use AndyFraussen\UiTdatabankClient\Resources\OrganizerSearchResource;
use AndyFraussen\UiTdatabankClient\Resources\PlaceSearchResource;
use AndyFraussen\UiTdatabankClient\Resources\TaxonomyResource;
use AndyFraussen\UiTdatabankClient\Support\Credentials;

class UiTdatabankManager
{
    public function __construct(
        private readonly UiTdatabankClient $client,
        private readonly TaxonomyClient $taxonomyClient,
        private readonly ?Credentials $credentials = null,
    ) {
    }

    public function offers(): OfferSearchResource
    {
        return new OfferSearchResource($this->scopedClient());
    }

    public function events(): EventSearchResource
    {
        return new EventSearchResource($this->scopedClient());
    }

    public function places(): PlaceSearchResource
    {
        return new PlaceSearchResource($this->scopedClient());
    }

    public function organizers(): OrganizerSearchResource
    {
        return new OrganizerSearchResource($this->scopedClient());
    }

    public function taxonomy(): TaxonomyResource
    {
        return new TaxonomyResource($this->taxonomyClient);
    }

    public function withCredentials(string $clientId, string $apiKey): self
    {
        return new self($this->client, $this->taxonomyClient, Credentials::explicit($clientId, $apiKey));
    }

    private function scopedClient(): UiTdatabankClient
    {
        if ($this->credentials === null) {
            return $this->client;
        }

        return $this->client->withCredentials($this->credentials);
    }
}
