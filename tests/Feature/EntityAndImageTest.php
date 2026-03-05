<?php

namespace Tests\Feature;

use Tests\TestCase;

class EntityAndImageTest extends TestCase
{
    public function testImageResourceIsAbsent(): void
    {
        $this->assertFalse(class_exists(\AndyFraussen\UiTdatabankClient\Resources\ImageResource::class));
    }
}
