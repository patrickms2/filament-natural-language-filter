<!-- Natural Language Filter Help Modal -->
<div x-data="{ 
    showModal: false,
    operations: [],
    examples: {},
    init() {
        this.$nextTick(() => {
            window.addEventListener('open-natural-language-help', (e) => {
                this.operations = e.detail?.operations || [];
                this.examples = e.detail?.examples || {};
                this.showModal = true;
            });
        });
    }
}">

    <!-- Modal Backdrop and Content -->
    <template x-if="showModal">
        <div class="fixed inset-0 z-50 overflow-y-auto">
            <!-- Backdrop -->
            <div class="fixed inset-0 bg-black bg-opacity-50" style="z-index: 51;" @click="showModal = false"></div>

            <!-- Modal Container -->
            <div class="flex items-center justify-center min-h-screen p-4" style="z-index: 52; position: relative;">
                <!-- Modal Content -->
                <div class="bg-white dark:bg-gray-900 rounded-lg shadow-xl max-w-4xl w-full max-h-[90vh] overflow-y-auto"
                    style="z-index: 53; position: relative;" @click.stop
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 transform scale-95"
                    x-transition:enter-end="opacity-100 transform scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 transform scale-100"
                    x-transition:leave-end="opacity-0 transform scale-95">

                    <!-- Header -->
                    <div class="flex items-center justify-between p-4 border-b border-gray-200 dark:border-gray-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-primary-600" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"
                                    clip-rule="evenodd"></path>
                            </svg>
                            <h2 class="text-base font-semibold text-gray-900 dark:text-gray-100">
                                Natural Language Filter Guide
                            </h2>
                        </div>
                        <button @click="showModal = false"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="p-4 space-y-6">
                        <!-- Available Keywords -->
                        <div>
                            <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                Available Keywords
                            </h3>
                            <template x-if="operations.length > 0">
                                <div class="grid gap-2 md:grid-cols-3 lg:grid-cols-4">
                                    <template x-for="operation in operations" :key="operation.name">
                                        <div
                                            class="p-2 border border-gray-200 rounded bg-gray-50 dark:bg-gray-800 dark:border-gray-700">
                                            <h4 class="mb-1 text-xs font-medium text-gray-900 dark:text-gray-100"
                                                x-text="operation.name"></h4>
                                            <div class="flex flex-wrap gap-1">
                                                <template x-for="keyword in operation.keywords.slice(0, 2)"
                                                    :key="keyword">
                                                    <code
                                                        class="px-1 py-0.5 text-xs text-blue-700 bg-blue-100 rounded dark:bg-blue-900 dark:text-blue-200"
                                                        x-text="keyword"></code>
                                                </template>
                                                <template x-if="operation.keywords.length > 2">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">+more</span>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </template>

                            <template x-if="operations.length === 0">
                                <div
                                    class="p-4 text-center border-2 border-gray-300 border-dashed rounded-lg dark:border-gray-600">
                                    <p class="text-xs text-gray-500 dark:text-gray-400">No operations found in patterns
                                    </p>
                                </div>
                            </template>
                        </div>

                        <!-- Example Sentences (only show if we have examples) -->
                        <template x-if="Object.keys(examples).length > 0">
                            <div>
                                <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                                    Example Search Sentences
                                </h3>
                                <div class="grid gap-3 md:grid-cols-2">
                                    <!-- Text Search Examples -->
                                    <template x-if="examples.textSearch && examples.textSearch.length > 0">
                                        <div
                                            class="p-3 border border-gray-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800">
                                            <h4 class="mb-2 text-xs font-medium text-blue-900 dark:text-blue-200">Text
                                                Search</h4>
                                            <div class="space-y-1">
                                                <template x-for="example in examples.textSearch" :key="example">
                                                    <code class="block text-xs text-gray-700 dark:text-gray-300"
                                                        x-text="example"></code>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Date Filter Examples -->
                                    <template x-if="examples.dateFilters && examples.dateFilters.length > 0">
                                        <div
                                            class="p-3 border border-gray-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800">
                                            <h4 class="mb-2 text-xs font-medium text-green-900 dark:text-green-200">Date
                                                Filters</h4>
                                            <div class="space-y-1">
                                                <template x-for="example in examples.dateFilters" :key="example">
                                                    <code class="block text-xs text-gray-700 dark:text-gray-300"
                                                        x-text="example"></code>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Numeric Examples -->
                                    <template x-if="examples.numbers && examples.numbers.length > 0">
                                        <div
                                            class="p-3 border border-gray-200 rounded-lg bg-purple-50 dark:bg-purple-900/20 dark:border-purple-800">
                                            <h4 class="mb-2 text-xs font-medium text-purple-900 dark:text-purple-200">
                                                Numbers</h4>
                                            <div class="space-y-1">
                                                <template x-for="example in examples.numbers" :key="example">
                                                    <code class="block text-xs text-gray-700 dark:text-gray-300"
                                                        x-text="example"></code>
                                                </template>
                                            </div>
                                        </div>
                                    </template>

                                    <!-- Quick Search Examples -->
                                    <template x-if="examples.quickSearch && examples.quickSearch.length > 0">
                                        <div
                                            class="p-3 border border-gray-200 rounded-lg bg-orange-50 dark:bg-orange-900/20 dark:border-orange-800">
                                            <h4 class="mb-2 text-xs font-medium text-orange-900 dark:text-orange-200">
                                                Quick
                                                Search</h4>
                                            <div class="space-y-1">
                                                <template x-for="example in examples.quickSearch" :key="example">
                                                    <code class="block text-xs text-gray-700 dark:text-gray-300"
                                                        x-text="example"></code>
                                                </template>
                                            </div>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        <!-- No examples message -->
                        <template x-if="Object.keys(examples).length === 0">
                            <div
                                class="p-6 text-center border-2 border-gray-300 border-dashed rounded-lg dark:border-gray-600">
                                <p class="text-sm text-gray-500 dark:text-gray-400">No example queries found</p>
                                <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">Add descriptions to patterns in
                                    patterns.php to show examples</p>
                            </div>
                        </template>
                    </div>

                    <!-- Footer -->
                    <div class="flex justify-end p-4 border-t border-gray-200 dark:border-gray-700">
                        <button @click="showModal = false"
                            class="px-3 py-1.5 text-xs font-medium text-gray-700 bg-gray-100 border border-gray-300 rounded-md hover:bg-gray-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </template>
</div>