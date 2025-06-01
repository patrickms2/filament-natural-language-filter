<?php

use HayderHatem\FilamentNaturalLanguageFilter\Enums\ProcessorType;

return [
    /*
    |--------------------------------------------------------------------------
    | Processor Type Configuration
    |--------------------------------------------------------------------------
    |
    | The package now uses only LLM (AI-powered) processing with OpenAI.
    | Custom processing has been removed.
    |
    */
    'processor_type' => env('FILAMENT_NL_FILTER_PROCESSOR_TYPE', ProcessorType::LLM->value),

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
        'timeout' => env('FILAMENT_NL_FILTER_TIMEOUT', 30),
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
        'allowed_patterns' => [
            // Add regex patterns for allowed query formats
        ],
        'blocked_patterns' => [
            // Add regex patterns for blocked query formats
        ],
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
