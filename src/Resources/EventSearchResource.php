<?php

namespace AndyFraussen\UiTdatabankClient\Resources;

class EventSearchResource extends AbstractSearchResource
{
    protected function endpoint(): string
    {
        return '/events';
    }
}
