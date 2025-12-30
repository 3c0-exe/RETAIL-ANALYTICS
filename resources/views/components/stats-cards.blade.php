{{-- resources/views/partials/stats-cards.blade.php --}}

<!-- Card 1: Total Sales Today -->
<div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 transition-all duration-200 hover:shadow-md">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Total Sales Today</p>
            <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                ₱{{ number_format($todaySales, 2) }}
            </p>
        </div>
        <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg sm:w-12 sm:h-12 dark:bg-purple-900/20">
            <svg class="w-5 h-5 text-purple-600 sm:w-6 sm:h-6 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </div>
    </div>
    <div class="flex items-center mt-3 text-xs sm:text-sm">
        <span class="text-gray-500 dark:text-gray-400">{{ $todayTransactions }} transactions today</span>
    </div>
</div>

<!-- Card 2: Period Sales -->
<div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 transition-all duration-200 hover:shadow-md">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Period Sales</p>
            <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                ₱{{ number_format($periodSales, 2) }}
            </p>
        </div>
        <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg sm:w-12 sm:h-12 dark:bg-blue-900/20">
            <svg class="w-5 h-5 text-blue-600 sm:w-6 sm:h-6 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
            </svg>
        </div>
    </div>
    <div class="flex items-center mt-3 text-xs sm:text-sm">
        <span class="text-gray-500 dark:text-gray-400">
            {{ ucfirst(str_replace('_', ' ', $dateRange)) }}
        </span>
    </div>
</div>

<!-- Card 3: Active Products -->
<div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 transition-all duration-200 hover:shadow-md">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Active Products</p>
            <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                {{ number_format($productCount) }}
            </p>
        </div>
        <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg sm:w-12 sm:h-12 dark:bg-green-900/20">
            <svg class="w-5 h-5 text-green-600 sm:w-6 sm:h-6 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
            </svg>
        </div>
    </div>
    <div class="flex items-center mt-3 text-xs sm:text-sm">
        @if($productCount == 0)
            <span class="text-gray-500 dark:text-gray-400">Add products in Phase 3</span>
        @else
            <span class="text-green-600 dark:text-green-400">In your catalog</span>
        @endif
    </div>
</div>

<!-- Card 4: Avg Transaction -->
<div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 transition-all duration-200 hover:shadow-md">
    <div class="flex items-center justify-between">
        <div>
            <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Avg Transaction</p>
            <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                ₱{{ number_format($avgTransaction, 2) }}
            </p>
        </div>
        <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg sm:w-12 sm:h-12 dark:bg-purple-900/20">
            <svg class="w-5 h-5 text-purple-600 sm:w-6 sm:h-6 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
            </svg>
        </div>
    </div>
    <div class="flex items-center mt-3 text-xs sm:text-sm">
        <span class="text-gray-500 dark:text-gray-400">Selected period</span>
    </div>
</div>
