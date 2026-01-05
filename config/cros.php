<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Cross-Origin Resource Sharing (CORS) Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure your settings for cross-origin resource sharing
    | or "CORS". This determines what cross-origin operations may execute
    | in web browsers. You are free to adjust these settings as needed.
    |
    */

    'paths' => [
        'api/*',
        'sanctum/csrf-cookie',
        'login',
        'logout',
        'register',
        'password/*',
        'email/verify/*',
        'password-reset/*',
        'oauth/*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed HTTP Methods
    |--------------------------------------------------------------------------
    */
    'allowed_methods' => explode(',', env('ALLOWED_METHODS', 'GET,POST,PUT,PATCH,DELETE,OPTIONS,HEAD')),

    /*
    |--------------------------------------------------------------------------
    | Allowed Origins
    |--------------------------------------------------------------------------
    */
    'allowed_origins' => array_filter(explode(',', env('ALLOWED_ORIGINS', 'http://localhost:3000'))),

    /*
    |--------------------------------------------------------------------------
    | Allowed Origin Patterns
    |--------------------------------------------------------------------------
    */
    'allowed_origins_patterns' => [
        '*.localhost',
        'localhost:*',
        '127.0.0.1:*',
        '*.test',
        '*.local',
        'exp://*',
        'http://*',
        'https://*',
    ],

    /*
    |--------------------------------------------------------------------------
    | Allowed Headers
    |--------------------------------------------------------------------------
    */
    'allowed_headers' => explode(',', env('ALLOWED_HEADERS', 'Content-Type,Authorization,X-Requested-With,X-CSRF-Token,Accept,Accept-Language')),

    /*
    |--------------------------------------------------------------------------
    | Exposed Headers
    |--------------------------------------------------------------------------
    */
    'exposed_headers' => [
        'Authorization',
        'Content-Language',
        'X-Content-Language',
        'X-Locale',
        'X-Request-ID',
        'X-RateLimit-Limit',
        'X-RateLimit-Remaining',
        'X-RateLimit-Reset',
        'X-Pagination-Current-Page',
        'X-Pagination-Page-Count',
        'X-Pagination-Per-Page',
        'X-Pagination-Total-Count',
        'X-API-Version',
    ],

    /*
    |--------------------------------------------------------------------------
    | Max Age (seconds)
    |--------------------------------------------------------------------------
    */
    'max_age' => 86400, // 24 hours

    /*
    |--------------------------------------------------------------------------
    | Supports Credentials
    |--------------------------------------------------------------------------
    */
    'supports_credentials' => true,
];