<?php

namespace AndyFraussen\UiTdatabankClient\Resources;

class PlaceSearchResource extends AbstractSearchResource
{
    protected function endpoint(): string
    {
        return '/places';
    }
}
