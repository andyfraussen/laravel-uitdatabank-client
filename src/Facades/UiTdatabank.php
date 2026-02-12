<?php

namespace AndyFraussen\UiTdatabankClient\Facades;

use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Illuminate\Support\Facades\Facade;

/**
 * @method static \AndyFraussen\UiTdatabankClient\Resources\OfferSearchResource offers()
 * @method static \AndyFraussen\UiTdatabankClient\Resources\EventSearchResource events()
 * @method static \AndyFraussen\UiTdatabankClient\Resources\PlaceSearchResource places()
 * @method static \AndyFraussen\UiTdatabankClient\Resources\OrganizerSearchResource organizers()
 * @method static UiTdatabankManager withCredentials(string $clientId, string $apiKey)
 */
class UiTdatabank extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return UiTdatabankManager::class;
    }
}
