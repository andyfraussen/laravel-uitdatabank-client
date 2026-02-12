<?php

it('keeps package focused on search resources only', function (): void {
    expect(class_exists(\AndyFraussen\UiTdatabankClient\Resources\ImageResource::class))->toBeFalse();
});
