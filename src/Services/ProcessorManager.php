<?php

namespace HayderHatem\FilamentNaturalLanguageFilter\Services;

use HayderHatem\FilamentNaturalLanguageFilter\Contracts\NaturalLanguageProcessorInterface;
use HayderHatem\FilamentNaturalLanguageFilter\Enums\ProcessorType;
use Illuminate\Support\Facades\Log;

class ProcessorManager implements NaturalLanguageProcessorInterface
{
    protected ?NaturalLanguageProcessor $llmProcessor = null;

    public function __construct()
    {
        // Always use LLM processor since custom processing has been removed
    }

    public function processQuery(string $query, array $availableColumns = []): array
    {
        try {
            Log::info('ProcessorManager::processQuery called', [
                'query' => $query,
                'availableColumns' => $availableColumns,
                'processorType' => 'llm'
            ]);

            $result = $this->getLlmProcessor()?->processQuery($query, $availableColumns) ?? [];

            Log::info('ProcessorManager result', [
                'result' => $result,
                'count' => count($result),
                'processorType' => 'llm'
            ]);

            return $result;
        } catch (\Exception $e) {
            Log::error('Error processing natural language query: ' . $e->getMessage());
            return [];
        }
    }

    public function canProcess(string $query): bool
    {
        try {
            return $this->getLlmProcessor()?->canProcess($query) ?? false;
        } catch (\Exception $e) {
            Log::error('Error checking if query can be processed: ' . $e->getMessage());
            return false;
        }
    }

    public function getSupportedFilterTypes(): array
    {
        try {
            return $this->getLlmProcessor()?->getSupportedFilterTypes() ?? [];
        } catch (\Exception $e) {
            Log::error('Error getting supported filter types: ' . $e->getMessage());
            return [];
        }
    }

    public function getProcessorType(): ProcessorType
    {
        return ProcessorType::LLM;
    }

    public function getLlmProcessor(): ?NaturalLanguageProcessor
    {
        if ($this->llmProcessor === null) {
            $this->initializeLlmProcessor();
        }
        return $this->llmProcessor;
    }

    protected function initializeLlmProcessor(): void
    {
        try {
            if ($this->llmProcessor === null) {
                $this->llmProcessor = new NaturalLanguageProcessor();
            }
        } catch (\Exception $e) {
            Log::error('Failed to initialize LLM processor: ' . $e->getMessage());
            $this->llmProcessor = null;
        }
    }

    public function setCustomColumnMappings(array $mappings): void
    {
        // LLM processor doesn't use custom column mappings but we keep this for interface compliance
    }

    public function getCustomColumnMappings(): array
    {
        // LLM processor doesn't use custom column mappings
        return [];
    }

    public function setLocale(string $locale): void
    {
        // Reset processor to force re-initialization with new locale
        $this->llmProcessor = null;

        // Update app locale
        app()->setLocale($locale);
    }
}
