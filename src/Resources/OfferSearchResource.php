<?php

namespace AndyFraussen\UiTdatabankClient\Resources;

class OfferSearchResource extends AbstractSearchResource
{
    protected function endpoint(): string
    {
        return '/offers';
    }
}
