<?php

return [
    /*
    |--------------------------------------------------------------------------
    | OpenAI Model Configuration
    |--------------------------------------------------------------------------
    |
    | Configure which OpenAI model to use for natural language processing.
    | Available models: gpt-3.5-turbo, gpt-4, gpt-4-turbo-preview
    |
    */
    'model' => env('FILAMENT_NL_FILTER_MODEL', 'gpt-3.5-turbo'),

    /*
    |--------------------------------------------------------------------------
    | OpenAI API Configuration
    |--------------------------------------------------------------------------
    |
    | Configure OpenAI API settings. The API key should be set in your
    | environment file as OPENAI_API_KEY.
    |
    */
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'max_tokens' => env('FILAMENT_NL_FILTER_MAX_TOKENS', 500),
        'temperature' => env('FILAMENT_NL_FILTER_TEMPERATURE', 0.1),
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Configuration
    |--------------------------------------------------------------------------
    |
    | Configure caching for natural language processing results to improve
    | performance and reduce API calls.
    |
    */
    'cache' => [
        'enabled' => env('FILAMENT_NL_FILTER_CACHE_ENABLED', true),
        'ttl' => env('FILAMENT_NL_FILTER_CACHE_TTL', 3600), // 1 hour
        'prefix' => 'filament_nl_filter',
    ],

    /*
    |--------------------------------------------------------------------------
    | Validation Rules
    |--------------------------------------------------------------------------
    |
    | Configure validation rules for natural language queries.
    |
    */
    'validation' => [
        'min_length' => 3,
        'max_length' => 500,
    ],

    /*
    |--------------------------------------------------------------------------
    | Language Support
    |--------------------------------------------------------------------------
    |
    | Configure universal language support for natural language processing.
    | The AI automatically detects and processes queries in any language.
    |
    */
    'languages' => [
        'universal_support' => env('FILAMENT_NL_FILTER_UNIVERSAL_SUPPORT', true),
        'auto_detect_direction' => env('FILAMENT_NL_FILTER_AUTO_DETECT_DIRECTION', true),
        'preserve_original_values' => env('FILAMENT_NL_FILTER_PRESERVE_ORIGINAL_VALUES', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Supported Filter Types
    |--------------------------------------------------------------------------
    |
    | Define which filter types are supported by the natural language processor.
    | You can disable certain filter types by removing them from this array.
    |
    */
    'supported_filters' => [
        'equals',
        'not_equals',
        'contains',
        'starts_with',
        'ends_with',
        'greater_than',
        'less_than',
        'between',
        'in',
        'not_in',
        'is_null',
        'is_not_null',
        'date_equals',
        'date_before',
        'date_after',
        'date_between',
    ],

    /*
    |--------------------------------------------------------------------------
    | Logging Configuration
    |--------------------------------------------------------------------------
    |
    | Configure logging for natural language filter operations.
    |
    */
    'logging' => [
        'enabled' => env('FILAMENT_NL_FILTER_LOGGING', true),
        'channel' => env('FILAMENT_NL_FILTER_LOG_CHANNEL', 'default'),
        'level' => env('FILAMENT_NL_FILTER_LOG_LEVEL', 'info'),
    ],
];
