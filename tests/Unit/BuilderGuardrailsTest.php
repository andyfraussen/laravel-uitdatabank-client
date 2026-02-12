<?php

use AndyFraussen\UiTdatabankClient\Pending\PendingSearch;
use AndyFraussen\UiTdatabankClient\UiTdatabankManager;

it('returns pending search builders from resources', function (): void {
    $pending = app(UiTdatabankManager::class)
        ->offers()
        ->newQuery();

    expect($pending)->toBeInstanceOf(PendingSearch::class);
});
