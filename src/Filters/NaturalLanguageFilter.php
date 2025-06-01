<?php

namespace HayderHatem\FilamentNaturalLanguageFilter\Filters;

use Filament\Tables\Filters\BaseFilter;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Placeholder;
use Illuminate\Database\Eloquent\Builder;
use HayderHatem\FilamentNaturalLanguageFilter\Contracts\NaturalLanguageProcessorInterface;
use HayderHatem\FilamentNaturalLanguageFilter\Services\ProcessorManager;
use HayderHatem\FilamentNaturalLanguageFilter\Enums\ProcessorType;
use Illuminate\Support\Facades\Log;

class NaturalLanguageFilter extends BaseFilter
{
    // Force load our custom view with debugging
    // protected string $view = 'filament-natural-language-filter::filters.natural-language-filter';

    protected array $availableColumns = [];
    protected array $customColumnMappings = [];
    protected ?NaturalLanguageProcessorInterface $processor = null;

    // Static properties to track the latest configuration
    protected static array $latestAvailableColumns = [];
    protected static array $latestCustomColumnMappings = [];

    // Static property to collect scripts
    protected static array $globalScripts = [];

    // Static property to accumulate filter data
    protected static array $accumulatedFilterData = [];

    public static function make(?string $name = 'natural_language'): static
    {
        return parent::make($name);
    }

    public function availableColumns(array $columns): static
    {
        $this->availableColumns = $columns;
        static::$latestAvailableColumns = $columns;

        // Also store in global registry for immediate access
        app()->instance('natural_language_filter.available_columns', $columns);

        return $this;
    }

    public function columnMappings(array $mappings): static
    {
        $this->customColumnMappings = $mappings;
        static::$latestCustomColumnMappings = $mappings;

        // Also store in global registry for immediate access
        app()->instance('natural_language_filter.column_mappings', $mappings);

        return $this;
    }

    public function getAvailableColumns(): array
    {
        return $this->availableColumns;
    }

    public function getCustomColumnMappings(): array
    {
        return $this->customColumnMappings;
    }

    protected function getProcessorType(): string
    {
        return 'llm'; // Always LLM since custom processing is removed
    }

    protected function getAllPatterns(): array
    {
        // Since we only use LLM processing, we don't need complex patterns
        // Return basic examples for help documentation
        return [
            'basic_examples' => [
                'name contains john',
                'status is active',
                'created after 2023-01-01',
                'age greater than 25',
            ]
        ];
    }

    protected function getSupportedLanguages(): array
    {
        $configuredLanguages = config('app.supported_locales', ['en' => 'English']);
        $languages = [];

        foreach ($configuredLanguages as $lang => $name) {
            try {
                $langName = trans('filament-natural-language-filter::ui.languages.' . $lang, [], $lang);
                if ($langName && $langName !== 'filament-natural-language-filter::ui.languages.' . $lang) {
                    $languages[$lang] = $langName;
                } else {
                    // Use configured name or fallback
                    $languages[$lang] = $name;
                }
            } catch (\Exception $e) {
                // Use configured name as fallback
                $languages[$lang] = $name;
            }
        }

        return $languages;
    }

    protected function setUp(): void
    {
        parent::setUp();

        $processorInfo = $this->getProcessorInfo();
        $formConfig = $this->getUIConfig('form');

        $this->form([
            Placeholder::make('processor_info')
                ->label($formConfig['processor_info_label'] ?? 'Processing Mode')
                ->content($processorInfo['label'] . ' - ' . $processorInfo['description'])
                ->extraAttributes(['class' => 'text-sm text-gray-600 dark:text-gray-400']),

            TextInput::make('query')
                ->label($formConfig['query_label'] ?? 'Natural Language Filter')
                ->placeholder($processorInfo['placeholder'])
                ->live(onBlur: true)
                ->debounce(1500)
                ->prefix(new \Illuminate\Support\HtmlString(
                    view('filament-natural-language-filter::components.help-button', [
                        'patterns' => $this->getAllPatterns()
                    ])->render()
                ))
                ->extraInputAttributes($formConfig['form_attributes'] ?? [
                    'autocomplete' => 'off',
                    'spellcheck' => 'false',
                ])
                ->helperText($formConfig['query_helper'] ?? 'Press Enter or click outside to apply filter'),
        ]);
    }

    protected function getUIConfig(string $section): array
    {
        $locale = app()->getLocale();
        $translationKey = "filament-natural-language-filter::ui.{$section}";

        if (\Illuminate\Support\Facades\Lang::has($translationKey, $locale)) {
            $result = trans($translationKey, [], $locale);
            // Ensure we return an array
            if (is_array($result)) {
                return $result;
            }
        }

        // Fallback to English
        $fallback = trans($translationKey, [], 'en');
        if (is_array($fallback)) {
            return $fallback;
        }

        // Final fallback to empty array
        return [];
    }

    protected function getProcessor(): ?NaturalLanguageProcessorInterface
    {
        if ($this->processor === null) {
            try {
                $this->processor = app(NaturalLanguageProcessorInterface::class);

                // Set the current app locale
                $this->processor->setLocale(app()->getLocale());

                // If it's a ProcessorManager, set custom column mappings
                if ($this->processor instanceof ProcessorManager) {
                    $this->processor->setCustomColumnMappings($this->customColumnMappings);
                }
            } catch (\Exception $e) {
                Log::error('Failed to resolve NaturalLanguageProcessorInterface: ' . $e->getMessage());
                // Fallback to a new processor manager
                try {
                    $this->processor = new ProcessorManager();
                    $this->processor->setLocale(app()->getLocale());
                    $this->processor->setCustomColumnMappings($this->customColumnMappings);
                } catch (\Exception $fallbackException) {
                    Log::error('Failed to create fallback processor: ' . $fallbackException->getMessage());
                    $this->processor = null;
                }
            }
        }
        return $this->processor;
    }

    protected function getProcessorInfo(): array
    {
        try {
            // Always return LLM processor info since custom processing is removed
            $config = $this->getUIConfig("processor_info.llm");
            if (!empty($config)) {
                return $config;
            }
        } catch (\Exception $e) {
            Log::error('Error getting processor info: ' . $e->getMessage());
        }

        // Fallback
        return [
            'label' => 'AI-Powered',
            'description' => 'Using artificial intelligence to understand your queries',
            'placeholder' => 'e.g., users created after 2023'
        ];
    }

    public function apply(Builder $query, array $data = []): Builder
    {
        // Early return if no query or query is too short
        if (empty($data['query']) || strlen(trim($data['query'])) < 3) {
            return $query;
        }

        $queryText = trim($data['query']);

        try {
            $processor = $this->getProcessor();

            if (!$processor) {
                return $query;
            }

            // Auto-detect Arabic text and set appropriate locale
            $this->setAppropriateLocale($queryText, $processor);

            if (!$processor->canProcess($queryText)) {
                return $query;
            }

            $filters = $processor->processQuery($queryText, $this->getAvailableColumns());

            // Only apply filters if we have valid results
            if (empty($filters)) {
                return $query;
            }

            foreach ($filters as $filter) {
                if (!isset($filter['column'], $filter['operator'], $filter['value'])) {
                    continue;
                }

                $this->applyFilter($query, $filter);
            }
        } catch (\Exception $e) {
            // Silent fail
        }

        return $query;
    }

    protected function setAppropriateLocale(string $queryText, NaturalLanguageProcessorInterface $processor): void
    {
        $detectedLocale = $this->detectTextLocale($queryText);

        if ($detectedLocale && $detectedLocale !== app()->getLocale()) {
            $processor->setLocale($detectedLocale);
        }
    }

    protected function detectTextLocale(string $text): ?string
    {
        $detectionPatterns = $this->getLanguageDetectionPatterns();

        // Detect Arabic text
        if (isset($detectionPatterns['arabic']) && preg_match($detectionPatterns['arabic'], $text)) {
            return 'ar';
        }

        // Detect Spanish text
        if (isset($detectionPatterns['spanish']) && preg_match($detectionPatterns['spanish'], $text)) {
            return 'es';
        }

        // Default to English
        return 'en';
    }

    protected function getLanguageDetectionPatterns(): array
    {
        $locale = app()->getLocale();
        $translationKey = "filament-natural-language-filter::patterns.system.language_detection";

        if (\Illuminate\Support\Facades\Lang::has($translationKey, $locale)) {
            return trans($translationKey, [], $locale);
        }

        // Fallback to English
        return trans($translationKey, [], 'en') ?? [];
    }

    protected function applyFilter(Builder $query, array $filter): void
    {
        $column = $filter['column'];
        $operator = $filter['operator'];
        $value = $filter['value'];

        switch ($operator) {
            case 'equals':
                $query->where($column, '=', $value);
                break;
            case 'not_equals':
                $query->where($column, '!=', $value);
                break;
            case 'contains':
                $query->where($column, 'LIKE', "%{$value}%");
                break;
            case 'starts_with':
                $query->where($column, 'LIKE', "{$value}%");
                break;
            case 'ends_with':
                $query->where($column, 'LIKE', "%{$value}");
                break;
            case 'greater_than':
                $query->where($column, '>', $value);
                break;
            case 'less_than':
                $query->where($column, '<', $value);
                break;
            case 'between':
                if (is_array($value) && count($value) === 2) {
                    $query->whereBetween($column, $value);
                }
                break;
            case 'in':
                if (is_array($value)) {
                    $query->whereIn($column, $value);
                }
                break;
            case 'not_in':
                if (is_array($value)) {
                    $query->whereNotIn($column, $value);
                }
                break;
            case 'is_null':
                $query->whereNull($column);
                break;
            case 'is_not_null':
                $query->whereNotNull($column);
                break;
            case 'date_equals':
                $query->whereDate($column, '=', $value);
                break;
            case 'date_before':
                $query->whereDate($column, '<', $value);
                break;
            case 'date_after':
                $query->whereDate($column, '>', $value);
                break;
            case 'date_between':
                if (is_array($value) && count($value) === 2) {
                    $query->whereBetween($column, $value);
                }
                break;
        }
    }
}
