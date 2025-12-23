<?php

use Laravel\Sanctum\Sanctum;

return [
    'stateful' => explode(',', env(
        'SANCTUM_STATEFUL_DOMAINS',
        'localhost,localhost:8000,127.0.0.1,127.0.0.1:8000,127.0.0.1:3000,::1'
    )),

    'guard' => ['web'],

    'expiration' => 120,

    'token_prefix' => env('SANCTUM_TOKEN_PREFIX', ''),

    'middleware' => [
        'verify_csrf_token' => App\Http\Middleware\VerifyCsrfToken::class,
        'encrypt_cookies' => App\Http\Middleware\EncryptCookies::class,
    ],
];