<?php

return [
    'environment' => env('UITDATABANK_ENV', 'testing'),

    'base_url' => [
        'testing' => 'https://search-test.uitdatabank.be',
        'production' => 'https://search.uitdatabank.be',
    ],

    'auth' => [
        'client_id' => env('UITDATABANK_CLIENT_ID'),
        'api_key' => env('UITDATABANK_API_KEY'),
    ],

    'timeout' => (int) env('UITDATABANK_TIMEOUT', 30),

    'retry' => [
        'times' => (int) env('UITDATABANK_RETRY_TIMES', 3),
        'sleep' => (int) env('UITDATABANK_RETRY_SLEEP', 100),
    ],
];
