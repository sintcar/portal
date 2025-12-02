<?php

return [
    'developer_console_url' => env('DEVELOPER_CONSOLE_URL', 'https://developer-console.internal'),
    'api_token' => env('DEVELOPER_CONSOLE_TOKEN'),
    'storage_disk' => env('UPDATE_STORAGE_DISK', 'local'),
    'healthcheck_url' => env('UPDATE_HEALTHCHECK_URL'),
    'http_timeout' => env('UPDATE_HTTP_TIMEOUT', 15),
    'download_timeout' => env('UPDATE_DOWNLOAD_TIMEOUT', 120),
    'canary_delay' => env('UPDATE_CANARY_DELAY', 5),
];
