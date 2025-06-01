<?php

namespace HayderHatem\FilamentNaturalLanguageFilter\Tests;

use PHPUnit\Framework\TestCase;
use HayderHatem\FilamentNaturalLanguageFilter\Services\NaturalLanguageProcessor;

class NaturalLanguageProcessorTest extends TestCase
{
    protected NaturalLanguageProcessor $processor;

    protected function setUp(): void
    {
        parent::setUp();
        $this->processor = new NaturalLanguageProcessor();
    }

    public function test_can_process_valid_query(): void
    {
        $this->assertTrue($this->processor->canProcess('name contains john'));
        $this->assertTrue($this->processor->canProcess('users created after 2023'));
    }

    public function test_cannot_process_invalid_query(): void
    {
        $this->assertFalse($this->processor->canProcess(''));
        $this->assertFalse($this->processor->canProcess('ab'));
        $this->assertFalse($this->processor->canProcess('   '));
    }

    public function test_get_supported_filter_types(): void
    {
        $types = $this->processor->getSupportedFilterTypes();

        $this->assertIsArray($types);
        $this->assertContains('equals', $types);
        $this->assertContains('contains', $types);
        $this->assertContains('date_after', $types);
    }
}
