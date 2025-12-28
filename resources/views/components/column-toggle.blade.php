{{-- Column Visibility Toggle Component --}}
{{-- Place this in resources/views/components/column-toggle.blade.php --}}

@props(['storageKey' => 'table_columns', 'columns' => []])

<div x-data="columnToggle('{{ $storageKey }}', @js($columns))" class="relative">
    <button @click="open = !open"
            type="button"
            class="inline-flex items-center px-3 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-800 dark:border-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
        </svg>
        Columns
        <svg class="w-4 h-4 ml-2" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="transform opacity-0 scale-95"
         x-transition:enter-end="transform opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="transform opacity-100 scale-100"
         x-transition:leave-end="transform opacity-0 scale-95"
         class="absolute right-0 z-10 mt-2 w-56 origin-top-right rounded-lg bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 shadow-lg">

        <div class="p-3">
            <div class="flex items-center justify-between mb-3 pb-2 border-b border-gray-200 dark:border-gray-700">
                <span class="text-sm font-medium text-gray-900 dark:text-gray-100">Show/Hide Columns</span>
                <button @click="resetColumns()"
                        type="button"
                        class="text-xs text-primary-600 dark:text-primary-400 hover:text-primary-700">
                    Reset
                </button>
            </div>

            <div class="space-y-2 max-h-64 overflow-y-auto">
                <template x-for="column in visibleColumns" :key="column.key">
                    <label class="flex items-center cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 p-2 rounded transition">
                        <input type="checkbox"
                               x-model="column.visible"
                               @change="savePreference()"
                               :disabled="column.required"
                               class="w-4 h-4 text-primary-600 border-gray-300 rounded focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700">
                        <span class="ml-3 text-sm text-gray-700 dark:text-gray-300" x-text="column.label"></span>
                        <span x-show="column.required" class="ml-auto text-xs text-gray-400">(Required)</span>
                    </label>
                </template>
            </div>

            <!-- Mobile-only quick toggle -->
            <div class="mt-3 pt-3 border-t border-gray-200 dark:border-gray-700 md:hidden">
                <button @click="mobileMinimalView()"
                        type="button"
                        class="w-full px-3 py-2 text-xs font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                    📱 Mobile Minimal View
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function columnToggle(storageKey, defaultColumns) {
    return {
        open: false,
        visibleColumns: defaultColumns,

        init() {
            // Load saved preferences from localStorage
            const saved = localStorage.getItem(storageKey);
            if (saved) {
                try {
                    const savedCols = JSON.parse(saved);
                    this.visibleColumns = this.visibleColumns.map(col => {
                        const savedCol = savedCols.find(s => s.key === col.key);
                        return savedCol ? {...col, visible: savedCol.visible} : col;
                    });
                } catch (e) {
                    console.error('Failed to load column preferences:', e);
                }
            }

            // Apply visibility to table
            this.applyVisibility();
        },

        savePreference() {
            localStorage.setItem(storageKey, JSON.stringify(this.visibleColumns));
            this.applyVisibility();
        },

        applyVisibility() {
            this.visibleColumns.forEach(col => {
                const elements = document.querySelectorAll(`[data-column="${col.key}"]`);
                elements.forEach(el => {
                    el.style.display = col.visible ? '' : 'none';
                });
            });
        },

        resetColumns() {
            this.visibleColumns = defaultColumns.map(col => ({...col}));
            this.savePreference();
        },

        mobileMinimalView() {
            // Show only essential columns for mobile
            this.visibleColumns = this.visibleColumns.map(col => ({
                ...col,
                visible: col.required || ['name', 'price', 'status'].includes(col.key)
            }));
            this.savePreference();
            this.open = false;
        }
    }
}
</script>
