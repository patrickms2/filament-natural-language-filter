<?php

namespace HayderHatem\FilamentNaturalLanguageFilter\Traits;

use HayderHatem\FilamentNaturalLanguageFilter\Filters\NaturalLanguageFilter;

trait HasNaturalLanguageFilter
{
    /**
     * Add natural language filter to the table filters
     */
    public function addNaturalLanguageFilter(array $availableColumns = []): NaturalLanguageFilter
    {
        return NaturalLanguageFilter::make()
            ->availableColumns($availableColumns);
    }

    /**
     * Get default columns for natural language filtering
     * Override this method in your resource to customize available columns
     */
    protected function getNaturalLanguageFilterColumns(): array
    {
        return [];
    }

    /**
     * Check if natural language filter should be enabled
     * Override this method to add custom logic
     */
    protected function shouldEnableNaturalLanguageFilter(): bool
    {
        return config('filament-natural-language-filter.enabled', true);
    }
}
