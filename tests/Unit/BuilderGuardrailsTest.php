<?php

namespace Tests\Unit;

use AndyFraussen\UiTdatabankClient\Pending\PendingSearch;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Tests\TestCase;

class BuilderGuardrailsTest extends TestCase
{
    public function testReturnsPendingSearchBuildersFromResources(): void
    {
        $pending = app(UiTdatabankManager::class)
            ->offers()
            ->newQuery();

        $this->assertInstanceOf(PendingSearch::class, $pending);
    }
}
