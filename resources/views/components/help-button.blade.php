<button type="button"
    class="inline-flex items-center justify-center w-5 h-5 text-gray-400 cursor-pointer hover:text-primary-600 dark:text-gray-500 dark:hover:text-primary-400"
    onclick="showFilterHelp()" title="Show Natural Language Filter Examples">
    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
        <path fill-rule="evenodd"
            d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
            clip-rule="evenodd"></path>
    </svg>
</button>

<script>
    function showFilterHelp() {
        const patterns = {!! json_encode($patterns) !!};
        
        const helpData = {
            operations: extractOperationsFromPatterns(patterns),
            examples: generateExamplesFromPatterns(patterns)
        };
        
        window.dispatchEvent(new CustomEvent('open-natural-language-help', {
            detail: helpData
        }));
    }
    
    function extractOperationsFromPatterns(patterns) {
        const operations = [];
        
        // Extract from English patterns (fallback to first available language)
        const lang = patterns.en || Object.values(patterns)[0] || {};
        const operators = lang.operators || {};
        
        Object.entries(operators).forEach(([operation, data]) => {
            if (data.primary && Array.isArray(data.primary)) {
                operations.push({
                    name: operation.replace(/_/g, " ").replace(/\b\w/g, l => l.toUpperCase()),
                    keywords: data.primary
                });
            }
        });
        
        return operations;
    }
    
    function generateExamplesFromPatterns(patterns) {
        const lang = patterns.en || Object.values(patterns)[0] || {};
        const patternDefs = lang.patterns || {};
        
        const examples = {
            textSearch: [],
            dateFilters: [],
            numbers: [],
            quickSearch: []
        };
        
        // Extract examples directly from pattern descriptions
        Object.entries(patternDefs).forEach(([key, pattern]) => {
            if (pattern.description) {
                // Extract ALL quoted examples from description
                const matches = pattern.description.matchAll(/"([^"]+)"/g);
                for (const match of matches) {
                    const example = `"${match[1]}"`;
                    
                    // Categorize based on pattern type or operator
                    if (key.includes('date') || key.includes('created') || key.includes('before') || key.includes('after') || 
                        pattern.operator === 'date_after' || pattern.operator === 'date_before') {
                        examples.dateFilters.push(example);
                    } else if (key.includes('greater') || key.includes('less') || key.includes('between') || 
                               pattern.operator === 'greater_than' || pattern.operator === 'less_than' || pattern.operator === 'between') {
                        examples.numbers.push(example);
                    } else if (key.includes('which') || key.includes('direct') || example.includes('which')) {
                        examples.quickSearch.push(example);
                    } else {
                        examples.textSearch.push(example);
                    }
                }
            }
        });
        
        // Remove duplicates
        examples.textSearch = [...new Set(examples.textSearch)];
        examples.dateFilters = [...new Set(examples.dateFilters)];
        examples.numbers = [...new Set(examples.numbers)];
        examples.quickSearch = [...new Set(examples.quickSearch)];
        
        // Only return categories that have examples
        const filteredExamples = {};
        if (examples.textSearch.length > 0) filteredExamples.textSearch = examples.textSearch;
        if (examples.dateFilters.length > 0) filteredExamples.dateFilters = examples.dateFilters;
        if (examples.numbers.length > 0) filteredExamples.numbers = examples.numbers;
        if (examples.quickSearch.length > 0) filteredExamples.quickSearch = examples.quickSearch;
        
        return filteredExamples;
    }
</script>