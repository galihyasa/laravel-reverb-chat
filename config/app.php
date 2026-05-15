<?php

return [
    'name' => env('APP_NAME', 'Mini Chat Reverb'),

    'env' => env('APP_ENV', 'local'),

    'debug' => (bool) env('APP_DEBUG', true),

    'url' => env('APP_URL', 'http://localhost'),

    'timezone' => 'Asia/Makassar',

    'locale' => 'en',

    'fallback_locale' => 'en',

    'faker_locale' => 'en_US',

    'key' => env('APP_KEY'),

    'cipher' => 'AES-256-CBC',

    'providers' => [
        App\Providers\AppServiceProvider::class,
    ],
];
