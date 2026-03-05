<?php

namespace Tests\Feature;

use AndyFraussen\UiTdatabankClient\Facades\UiTdatabank;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;
use Tests\TestCase;

class ServiceProviderTest extends TestCase
{
    public function testRegistersTheManagerAndFacade(): void
    {
        $this->assertInstanceOf(UiTdatabankManager::class, app(UiTdatabankManager::class));
        $this->assertIsObject(UiTdatabank::offers());
    }

    public function testLoadsSearchApiConfigDefaults(): void
    {
        $this->assertSame('testing', config('uitdatabank.environment'));
        $this->assertSame('https://search-test.uitdatabank.be', config('uitdatabank.base_url.testing'));
    }
}
