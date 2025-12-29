@props(['tableId', 'columns'])

<div x-data="columnToggle('{{ $tableId }}', {{ json_encode($columns) }})" class="relative">
    <!-- Toggle Button - Mobile Optimized -->
    <button @click="open = !open" type="button"
            class="inline-flex items-center gap-2 px-3 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors w-full sm:w-auto justify-center sm:justify-start">
        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17V7m0 10a2 2 0 01-2 2H5a2 2 0 01-2-2V7a2 2 0 012-2h2a2 2 0 012 2m0 10a2 2 0 002 2h2a2 2 0 002-2M9 7a2 2 0 012-2h2a2 2 0 012 2m0 10V7m0 10a2 2 0 002 2h2a2 2 0 002-2V7a2 2 0 00-2-2h-2a2 2 0 00-2 2"></path>
        </svg>
        <span>Columns</span>
        <span class="text-xs text-gray-500 dark:text-gray-400">(<span x-text="visibleCount"></span>/<span x-text="columns.length"></span>)</span>
        <svg class="w-4 h-4 transition-transform flex-shrink-0" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown Panel - Mobile Fullscreen on Small Screens -->
    <div x-show="open"
         @click.away="open = false"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="fixed inset-x-0 bottom-0 z-50 bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 rounded-t-2xl shadow-2xl sm:absolute sm:right-0 sm:left-auto sm:bottom-auto sm:top-full sm:mt-2 sm:w-72 sm:rounded-lg sm:border"
         style="display: none;"
         x-cloak>

        <!-- Mobile Handle Bar -->
        <div class="flex justify-center pt-3 pb-2 sm:hidden">
            <div class="w-12 h-1 bg-gray-300 dark:bg-gray-600 rounded-full"></div>
        </div>

        <!-- Header -->
        <div class="px-4 sm:px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm sm:text-base font-semibold text-gray-900 dark:text-gray-100">
                        Show/Hide Columns
                    </h3>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
                        <span x-text="visibleCount"></span> of <span x-text="columns.length"></span> visible
                    </p>
                </div>
                <button @click="resetColumns()"
                        class="px-3 py-1.5 text-xs sm:text-sm text-purple-600 hover:text-purple-700 dark:text-purple-400 dark:hover:text-purple-300 font-medium bg-purple-50 dark:bg-purple-900/20 rounded-lg transition-colors">
                    Reset
                </button>
            </div>
        </div>

        <!-- Column Checkboxes - Scrollable -->
        <div class="max-h-[50vh] sm:max-h-96 overflow-y-auto py-2">
            <template x-for="(column, index) in columns" :key="column.key">
                <label class="flex items-center px-4 sm:px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition-colors group active:bg-gray-100 dark:active:bg-gray-700"
                       :class="column.locked ? 'opacity-60 cursor-not-allowed' : ''">

                    <!-- Checkbox -->
                    <input type="checkbox"
                           :checked="column.visible"
                           @change="toggleColumn(column.key)"
                           :disabled="column.locked"
                           class="w-5 h-5 text-purple-600 border-gray-300 rounded focus:ring-purple-500 focus:ring-2 dark:border-gray-600 dark:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed">

                    <!-- Label -->
                    <span class="ml-3 text-sm sm:text-base text-gray-700 dark:text-gray-300 group-hover:text-gray-900 dark:group-hover:text-gray-100 flex-1"
                          :class="column.locked ? 'opacity-60' : ''">
                        <span x-text="column.label"></span>
                        <span x-show="column.locked" class="ml-2 text-xs text-gray-500 dark:text-gray-400">(Required)</span>
                    </span>

                    <!-- Visibility Icon -->
                    <div class="flex-shrink-0 ml-2">
                        <svg x-show="column.visible && !column.locked" class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        <svg x-show="!column.visible && !column.locked" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                        </svg>
                        <svg x-show="column.locked" class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                </label>
            </template>
        </div>

        <!-- Footer - Mobile Close Button -->
        <div class="px-4 py-3 bg-gray-50 dark:bg-gray-900 border-t border-gray-200 dark:border-gray-700 sm:hidden">
            <button @click="open = false"
                    class="w-full px-4 py-2.5 text-sm font-medium text-white bg-purple-600 hover:bg-purple-700 rounded-lg transition-colors">
                Done
            </button>
        </div>
    </div>
</div>

<script>
function columnToggle(tableId, initialColumns) {
    return {
        open: false,
        columns: [],

        init() {
            // Load saved preferences from localStorage
            const savedPrefs = localStorage.getItem(`table_columns_${tableId}`);

            if (savedPrefs) {
                try {
                    const prefs = JSON.parse(savedPrefs);
                    this.columns = initialColumns.map(col => ({
                        ...col,
                        visible: prefs[col.key] !== undefined ? prefs[col.key] : col.visible
                    }));
                } catch (e) {
                    this.columns = initialColumns;
                }
            } else {
                this.columns = initialColumns;
            }

            // Apply initial visibility
            this.applyVisibility();
        },

        toggleColumn(key) {
            const column = this.columns.find(c => c.key === key);
            if (column && !column.locked) {
                column.visible = !column.visible;
                this.savePreferences();
                this.applyVisibility();
            }
        },

        resetColumns() {
            this.columns = initialColumns.map(col => ({ ...col }));
            localStorage.removeItem(`table_columns_${tableId}`);
            this.applyVisibility();
        },

        savePreferences() {
            const prefs = {};
            this.columns.forEach(col => {
                prefs[col.key] = col.visible;
            });
            localStorage.setItem(`table_columns_${tableId}`, JSON.stringify(prefs));
        },

        applyVisibility() {
            this.columns.forEach(column => {
                const cells = document.querySelectorAll(`[data-column="${column.key}"]`);
                cells.forEach(cell => {
                    if (column.visible) {
                        cell.classList.remove('hidden');
                    } else {
                        cell.classList.add('hidden');
                    }
                });
            });
        },

        get visibleCount() {
            return this.columns.filter(c => c.visible).length;
        }
    }
}
</script>

<style>
/* Smooth transitions */
[data-column] {
    transition: opacity 0.15s ease-in-out;
}

[data-column].hidden {
    opacity: 0;
}

/* Prevent body scroll when mobile dropdown is open */
body:has([x-show="open"]:not([style*="display: none"])) {
    overflow: hidden;
}

@media (min-width: 640px) {
    body:has([x-show="open"]:not([style*="display: none"])) {
        overflow: auto;
    }
}
</style>
