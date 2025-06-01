<?php

namespace HayderHatem\FilamentNaturalLanguageFilter\Enums;

enum ProcessorType: string
{
    case LLM = 'llm';

    public function getLabel(): string
    {
        return match ($this) {
            self::LLM => 'AI/LLM Processing',
        };
    }

    public function getDescription(): string
    {
        return match ($this) {
            self::LLM => 'Use OpenAI or other LLM services to process natural language queries',
        };
    }
}
