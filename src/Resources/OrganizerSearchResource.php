<?php

namespace AndyFraussen\UiTdatabankClient\Resources;

class OrganizerSearchResource extends AbstractSearchResource
{
    protected function endpoint(): string
    {
        return '/organizers';
    }
}
