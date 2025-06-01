# Filament Natural Language Filter

A powerful Filament package that allows users to filter table data using natural language queries powered by OpenAI's GPT models.

## Features

- 🤖 **AI-Powered**: Uses OpenAI's GPT models to understand natural language queries
- 🌍 **Multi-language Support**: Works with English, Arabic, and other languages
- ⚡ **Caching**: Built-in caching to reduce API calls and improve performance
- 🔧 **Configurable**: Extensive configuration options for fine-tuning
- 📊 **Comprehensive Filtering**: Supports all common database operations
- 🎨 **Beautiful UI**: Clean and intuitive interface with help documentation

## Installation

Install the package via Composer:

```bash
composer require hayderhatem/filament-natural-language-filter
```

Install the OpenAI PHP client:

```bash
composer require openai-php/laravel
```

Publish the configuration file:

```bash
php artisan vendor:publish --tag="filament-natural-language-filter-config"
```

Publish the language files (optional):

```bash
php artisan vendor:publish --tag="filament-natural-language-filter-lang"
```

## Configuration

### Environment Variables

Add your OpenAI API key to your `.env` file:

```env
OPENAI_API_KEY=your-openai-api-key-here
OPENAI_ORGANIZATION=your-organization-id # Optional

# Natural Language Filter Configuration
FILAMENT_NL_FILTER_MODEL=gpt-3.5-turbo
FILAMENT_NL_FILTER_MAX_TOKENS=500
FILAMENT_NL_FILTER_TEMPERATURE=0.1
FILAMENT_NL_FILTER_TIMEOUT=30
FILAMENT_NL_FILTER_CACHE_ENABLED=true
FILAMENT_NL_FILTER_CACHE_TTL=3600
```

### OpenAI Setup

Configure OpenAI in your `config/openai.php`:

```php
return [
    'api_key' => env('OPENAI_API_KEY'),
    'organization' => env('OPENAI_ORGANIZATION'),
];
```

## Usage

### Basic Usage

Add the natural language filter to your Filament resource:

```php
use HayderHatem\FilamentNaturalLanguageFilter\Filters\NaturalLanguageFilter;

class UserResource extends Resource
{
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Your columns
            ])
            ->filters([
                NaturalLanguageFilter::make()
                    ->availableColumns([
                        'name',
                        'email', 
                        'created_at',
                        'status'
                    ]),
            ]);
    }
}
```

### Advanced Usage

```php
NaturalLanguageFilter::make('search')
    ->availableColumns([
        'name',
        'email',
        'phone',
        'created_at',
        'updated_at',
        'status',
        'role',
        'age'
    ])
    ->columnMappings([
        'full_name' => 'name',
        'contact' => 'email',
        'registration_date' => 'created_at'
    ])
```

### Using the Trait

For easier integration, use the provided trait:

```php
use HayderHatem\FilamentNaturalLanguageFilter\Traits\HasNaturalLanguageFilter;

class UserResource extends Resource
{
    use HasNaturalLanguageFilter;

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Your columns
            ])
            ->filters([
                self::addNaturalLanguageFilter([
                    'name', 'email', 'created_at', 'status'
                ]),
            ]);
    }
}
```

## Query Examples

The AI understands natural language queries and converts them to database filters:

### Basic Filtering
- `name contains john`
- `status is active`
- `email ends with @gmail.com`
- `age greater than 25`

### Date Filtering
- `created after 2023-01-01`
- `updated before yesterday`
- `published between 2022 and 2023`
- `registered last week`

### Advanced Queries
- `users with pending status`
- `orders greater than 100`
- `products in electronics category`
- `customers from last month`

### Multi-language Support
- `الاسم يحتوي أحمد` (Arabic: name contains Ahmed)
- `البريد ينتهي بـ gmail.com` (Arabic: email ends with gmail.com)
- `الحالة نشط` (Arabic: status is active)

## Supported Operators

The package supports all common database operations:

- `equals` / `not_equals`
- `contains` / `starts_with` / `ends_with`
- `greater_than` / `less_than`
- `between`
- `in` / `not_in`
- `is_null` / `is_not_null`
- `date_equals` / `date_before` / `date_after` / `date_between`

## Configuration Options

### Model Configuration

```php
// config/filament-natural-language-filter.php
return [
    'model' => 'gpt-3.5-turbo', // or 'gpt-4', 'gpt-4-turbo-preview'
    
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'organization' => env('OPENAI_ORGANIZATION'),
        'timeout' => 30,
        'max_tokens' => 500,
        'temperature' => 0.1,
    ],
];
```

### Caching Configuration

```php
'cache' => [
    'enabled' => true,
    'ttl' => 3600, // 1 hour
    'prefix' => 'filament_nl_filter',
],
```

### Validation Rules

```php
'validation' => [
    'min_length' => 3,
    'max_length' => 500,
    'allowed_patterns' => [],
    'blocked_patterns' => [],
],
```

### Supported Filter Types

```php
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
```

## Localization

The package supports multiple languages. Publish the language files and customize them:

```bash
php artisan vendor:publish --tag="filament-natural-language-filter-lang"
```

Available languages:
- English (`en`)
- Arabic (`ar`)

## Performance Optimization

### Caching

The package automatically caches OpenAI responses to reduce API calls and improve performance. Configure caching in the config file:

```php
'cache' => [
    'enabled' => true,
    'ttl' => 3600, // Cache for 1 hour
    'prefix' => 'filament_nl_filter',
],
```

### API Usage Optimization

- Use `gpt-3.5-turbo` for faster and cheaper responses
- Set appropriate `max_tokens` limit
- Enable caching to avoid duplicate API calls
- Use `temperature: 0.1` for consistent results

## Troubleshooting

### Common Issues

1. **OpenAI API Key Not Working**
   - Ensure your API key is correctly set in `.env`
   - Check that you have sufficient OpenAI credits
   - Verify the API key has the necessary permissions

2. **No Results Returned**
   - Check the logs for OpenAI API errors
   - Ensure your query is clear and specific
   - Verify the available columns are correctly configured

3. **Performance Issues**
   - Enable caching to reduce API calls
   - Use `gpt-3.5-turbo` instead of `gpt-4` for faster responses
   - Reduce `max_tokens` if responses are too long

### Logging

Enable logging to debug issues:

```php
'logging' => [
    'enabled' => true,
    'channel' => 'default',
    'level' => 'info',
],
```

## Requirements

- PHP 8.1+
- Laravel 10+
- Filament 3.x
- OpenAI API key

## License

This package is open-sourced software licensed under the [MIT license](LICENSE.md).

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security-related issues, please email the maintainer instead of using the issue tracker.

## Credits

- [Hayder Hatem](https://github.com/hayderhatem)
- [All Contributors](../../contributors)

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently. 