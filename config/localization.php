<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Localization Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the localization settings for your application.
    |
    */

    'default_locale' => env('APP_LOCALE', 'en'),

    'fallback_locale' => env('APP_FALLBACK_LOCALE', 'en'),

    'available_locales' => ['ar', 'en'],

    'locale_names' => [
        'ar' => 'العربية',
        'en' => 'English',
    ],

    'locale_flags' => [
        'ar' => '🇸🇦',
        'en' => '🇺🇸',
    ],

    'rtl_locales' => ['ar'],

    'date_formats' => [
        'ar' => 'd/m/Y',
        'en' => 'Y-m-d',
    ],

    'time_formats' => [
        'ar' => 'h:i A',
        'en' => 'H:i',
    ],

    'currency' => [
        'ar' => [
            'symbol' => 'ر.س',
            'name' => 'ريال سعودي',
            'position' => 'right',
            'decimal_separator' => '.',
            'thousands_separator' => ',',
        ],
        'en' => [
            'symbol' => 'SAR',
            'name' => 'Saudi Riyal',
            'position' => 'left',
            'decimal_separator' => '.',
            'thousands_separator' => ',',
        ],
    ],

    'translations' => [
        'cities' => [
            'file' => 'cities.php',
            'auto_load' => true,
        ],
        'states' => [
            'file' => 'states.php',
            'auto_load' => true,
        ],
        'messages' => [
            'file' => 'messages.php',
            'auto_load' => true,
        ],
    ],
];