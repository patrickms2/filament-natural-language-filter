<?php

return [
    /*
    |--------------------------------------------------------------------------
    | UI Text for Natural Language Filter
    |--------------------------------------------------------------------------
    |
    | These translations are used for user interface elements, labels,
    | descriptions, and help text.
    |
    */

    'processor_info' => [
        'llm' => [
            'label' => 'AI-Powered',
            'description' => 'Using artificial intelligence to understand your queries',
            'placeholder' => 'e.g., users created after 2023',
        ],
    ],

    'form' => [
        'processor_info_label' => 'Processing Mode',
        'query_label' => 'Natural Language Filter',
        'query_helper' => 'Describe what you\'re looking for in natural language. Press Enter to apply.',
        'form_attributes' => [
            'autocomplete' => 'off',
            'spellcheck' => 'false',
        ],
    ],

    'modal' => [
        'title' => 'Natural Language Filter Guide',
        'close_button' => 'Close',
        'info_tooltip' => 'View usage examples and supported patterns',
    ],

    'help' => [
        'title' => 'How to Use Natural Language Filters',
        'subtitle' => 'Examples of natural language queries you can use:',
        'examples' => [
            'Basic Filtering' => [
                'name contains john',
                'status is active',
                'email ends with @gmail.com',
                'age greater than 25',
            ],
            'Date Filtering' => [
                'created after 2023-01-01',
                'updated before yesterday',
                'published between 2022 and 2023',
            ],
            'Advanced Queries' => [
                'users with pending status',
                'orders greater than 100',
                'products in electronics category',
            ],
        ],
        'tips' => [
            'Be specific in your queries for better results',
            'Use column names that exist in your data',
            'Date formats are flexible (YYYY-MM-DD, yesterday, last week, etc.)',
            'Combine multiple conditions naturally',
        ],
    ],
];
