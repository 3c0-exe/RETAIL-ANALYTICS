<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100">
                    Customer Analytics
                </h1>
                <p class="mt-1 text-sm text-gray-600 sm:mt-2 dark:text-gray-400">
                    RFM analysis, segmentation, and lifetime value insights
                </p>
            </div>
            <button onclick="window.print()"
                    class="inline-flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium text-white transition-all duration-200 rounded-md sm:w-auto bg-primary-600 hover:bg-primary-700">
                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span>Print Report</span>
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4 sm:gap-6">
            <!-- Total Customers -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Total Customers</p>
                        <p class="mt-1 text-xl font-bold text-gray-900 sm:mt-2 sm:text-2xl dark:text-gray-100">
                            {{ number_format($totalCustomers) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg sm:w-12 sm:h-12 dark:bg-purple-900/20">
                        <svg class="w-5 h-5 text-purple-600 sm:w-6 sm:h-6 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Customers -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Active Customers</p>
                        <p class="mt-1 text-xl font-bold text-gray-900 sm:mt-2 sm:text-2xl dark:text-gray-100">
                            {{ number_format($activeCustomers) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg sm:w-12 sm:h-12 dark:bg-green-900/20">
                        <svg class="w-5 h-5 text-green-600 sm:w-6 sm:h-6 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-1 text-xs text-gray-500 sm:mt-2 sm:text-sm dark:text-gray-400">
                    {{ number_format(($totalCustomers > 0 ? ($activeCustomers / $totalCustomers) : 0) * 100, 1) }}% retention
                </p>
            </div>

            <!-- Avg Lifetime Value -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Avg Lifetime Value</p>
                        <p class="mt-1 text-xl font-bold text-gray-900 sm:mt-2 sm:text-2xl dark:text-gray-100">
                            ₱{{ number_format($avgLifetimeValue, 2) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg sm:w-12 sm:h-12 dark:bg-blue-900/20">
                        <svg class="w-5 h-5 text-blue-600 sm:w-6 sm:h-6 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Avg Visit Count -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Avg Visits</p>
                        <p class="mt-1 text-xl font-bold text-gray-900 sm:mt-2 sm:text-2xl dark:text-gray-100">
                            {{ number_format($avgVisitCount, 1) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-10 h-10 bg-orange-100 rounded-lg sm:w-12 sm:h-12 dark:bg-orange-900/20">
                        <svg class="w-5 h-5 text-orange-600 sm:w-6 sm:h-6 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('analytics.customers') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                    <div>
                        <label class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-300">
                            Start Date
                        </label>
                        <input type="date" name="start_date"
                               value="{{ $startDate->format('Y-m-d') }}"
                               class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-300">
                            End Date
                        </label>
                        <input type="date" name="end_date"
                               value="{{ $endDate->format('Y-m-d') }}"
                               class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block mb-2 text-xs font-medium text-gray-700 sm:text-sm dark:text-gray-300">
                            Customer Segment
                        </label>
                        <select name="segment" class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">All Segments</option>
                            @foreach($segments as $key => $label)
                                <option value="{{ $key }}" {{ $segmentFilter == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:gap-3">
                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white rounded-md sm:w-auto bg-primary-600 hover:bg-primary-700">
                        Apply Filters
                    </button>
                    <a href="{{ route('analytics.customers') }}" class="w-full px-4 py-2 text-sm font-medium text-center text-gray-900 bg-gray-200 rounded-md sm:w-auto hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Export Button -->
        <div class="flex justify-end mb-6">
            <form method="POST" action="{{ route('export.customers.csv') }}" class="w-full sm:w-auto">
                @csrf
                <input type="hidden" name="segment" value="{{ request('segment') }}">
                <button type="submit" class="flex items-center justify-center w-full gap-2 px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-md sm:w-auto hover:bg-green-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
            </form>
        </div>


        <!-- Customer Segments & Charts Section -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:gap-6 lg:grid-cols-2">
            <!-- Segment Breakdown -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 flex flex-col">
                <h2 class="mb-4 text-base font-semibold text-gray-900 sm:text-lg lg:text-xl dark:text-gray-100">
                    Customer Segments
                </h2>

                <div class="mb-6 space-y-3">
                    @foreach($segmentData as $segment => $count)
                        @php
                            $colors = [
                                'vip' => 'purple',
                                'loyal' => 'green',
                                'regular' => 'blue',
                                'at_risk' => 'orange',
                                'new' => 'cyan',
                                'dormant' => 'gray'
                            ];
                            $color = $colors[$segment] ?? 'gray';
                            $percentage = $totalCustomers > 0 ? ($count / $totalCustomers) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-1">
                                <span class="text-xs font-medium text-gray-700 capitalize sm:text-sm dark:text-gray-300">
                                    {{ str_replace('_', ' ', $segment) }}
                                </span>
                                <span class="text-xs text-gray-600 sm:text-sm dark:text-gray-400">
                                    {{ $count }} ({{ number_format($percentage, 1) }}%)
                                </span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-{{ $color }}-500 h-2 rounded-full transition-all duration-300" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Doughnut Chart Container - Flexible to Fill Remaining Space -->
                <div class="flex items-center justify-center flex-1 w-full min-h-0">
                    <div class="w-full h-full max-w-md">
                        <canvas id="segmentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- CLV Distribution -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 flex flex-col">
                <h2 class="mb-4 text-base font-semibold text-gray-900 sm:text-lg lg:text-xl dark:text-gray-100">
                    Lifetime Value Distribution
                </h2>

                @php
                    // Check if there's any customer data
                    $hasCustomerData = $topCustomers->count() > 0;
                @endphp

                @if($hasCustomerData)
                    <!-- Chart Container - Flexible Height to Fill Card -->
                    <div class="flex items-center justify-center flex-1 w-full min-h-0">
                        <div class="w-full h-full max-w-md">
                            <canvas id="clvChart"></canvas>
                        </div>
                    </div>
                @else
                    <!-- Empty State -->
                    <div class="flex flex-col items-center justify-center flex-1 py-12 sm:py-16">
                        <svg class="w-16 h-16 mb-4 text-gray-400 sm:w-20 sm:h-20 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        <p class="mb-1 text-sm font-medium text-gray-900 sm:text-base dark:text-gray-100">No Customer Data Available</p>
                        <p class="text-xs text-gray-500 sm:text-sm dark:text-gray-400">Import transactions to see lifetime value distribution</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Top Customers Section - Mobile Optimized -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <!-- Header with Entries Selector -->
            <div class="flex flex-col gap-3 mb-4 sm:flex-row sm:items-center sm:justify-between sm:gap-4">
                <h2 class="text-lg font-semibold text-gray-900 sm:text-xl dark:text-gray-100">
                    Top Customers
                </h2>

                <!-- Entries Per Page Selector - Compact Mobile Layout -->
                <div class="flex items-center gap-2 sm:gap-3">
                    <label class="text-xs text-gray-600 sm:text-sm dark:text-gray-400">Show:</label>
                    <select id="entriesPerPage" onchange="updateTableEntries()"
                            class="px-2.5 py-1.5 text-xs sm:text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 min-w-[60px] sm:min-w-[70px]">
                        <option value="5">5</option>
                        <option value="10" selected>10</option>
                        <option value="20">20</option>
                        <option value="50">50</option>
                    </select>
                    <span class="text-xs text-gray-600 sm:text-sm dark:text-gray-400">entries</span>
                </div>
            </div>

            <!-- Table Container - Improved Mobile Scroll -->
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <div class="overflow-hidden">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <!-- Rank - Sticky on Mobile -->
                                    <th class="sticky left-0 z-20 px-3 py-3 text-xs font-medium text-left text-gray-500 uppercase bg-gray-50 sm:px-4 dark:bg-gray-900 dark:text-gray-400">
                                        Rank
                                    </th>
                                    <!-- Customer Info -->
                                    <th class="px-3 py-3 text-xs font-medium text-left text-gray-500 uppercase whitespace-nowrap sm:px-4 dark:text-gray-400">
                                        Customer
                                    </th>
                                    <!-- Segment - Hidden on Mobile -->
                                    <th class="hidden px-3 py-3 text-xs font-medium text-left text-gray-500 uppercase md:table-cell sm:px-4 dark:text-gray-400">
                                        Segment
                                    </th>
                                    <!-- Spent -->
                                    <th class="px-3 py-3 text-xs font-medium text-right text-gray-500 uppercase whitespace-nowrap sm:px-4 dark:text-gray-400">
                                        Spent
                                    </th>
                                    <!-- Visits - Hidden on Small Mobile -->
                                    <th class="hidden px-3 py-3 text-xs font-medium text-right text-gray-500 uppercase sm:table-cell sm:px-4 dark:text-gray-400">
                                        Visits
                                    </th>
                                    <!-- RFM - Hidden on Mobile/Tablet -->
                                    <th class="hidden px-3 py-3 text-xs font-medium text-center text-gray-500 uppercase lg:table-cell whitespace-nowrap sm:px-4 dark:text-gray-400">
                                        RFM
                                    </th>
                                    <!-- Last Visit - Hidden on Mobile/Tablet -->
                                    <th class="hidden px-3 py-3 text-xs font-medium text-left text-gray-500 uppercase xl:table-cell whitespace-nowrap sm:px-4 dark:text-gray-400">
                                        Last Visit
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-[#171717] dark:divide-gray-800">
                                @forelse($topCustomers as $index => $customer)
                                <tr class="customer-row hover:bg-gray-50 dark:hover:bg-gray-800/50" data-index="{{ $index }}">
                                    <!-- Rank Column - Sticky & Centered -->
                                    <td class="sticky left-0 z-10 w-12 px-2 py-3 text-sm font-bold text-center text-gray-900 bg-white sm:w-16 sm:px-3 dark:bg-[#171717] dark:text-gray-100">
                                        @if($index === 0) 🥇
                                        @elseif($index === 1) 🥈
                                        @elseif($index === 2) 🥉
                                        @else {{ $index + 1 }}
                                        @endif
                                    </td>

                                    <!-- Customer Info -->
                                    <td class="px-3 py-3 sm:px-4">
                                        <div class="flex flex-col gap-1">
                                            <!-- Name - Responsive truncation -->
                                            <div class="text-xs font-medium text-gray-900 sm:text-sm dark:text-gray-100">
                                                {{ Str::limit($customer->name, 25) }}
                                            </div>
                                            <!-- Email - More truncation on mobile -->
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                <span class="inline sm:hidden">{{ Str::limit($customer->email, 20) }}</span>
                                                <span class="hidden sm:inline">{{ Str::limit($customer->email, 30) }}</span>
                                            </div>
                                            <!-- Segment Badge - Show on Mobile -->
                                            <div class="mt-1 md:hidden">
                                                @php
                                                    $segmentColors = [
                                                        'vip' => 'purple',
                                                        'loyal' => 'green',
                                                        'regular' => 'blue',
                                                        'at_risk' => 'orange',
                                                        'new' => 'cyan',
                                                        'dormant' => 'gray'
                                                    ];
                                                    $badgeColor = $segmentColors[$customer->segment] ?? 'gray';
                                                @endphp
                                                <span class="inline-flex items-center px-2 py-0.5 text-xs font-medium rounded-full bg-{{ $badgeColor }}-100 dark:bg-{{ $badgeColor }}-900/20 text-{{ $badgeColor }}-700 dark:text-{{ $badgeColor }}-300">
                                                    {{ ucfirst(str_replace('_', ' ', $customer->segment)) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Segment - Desktop Only -->
                                    <td class="hidden px-3 py-3 md:table-cell sm:px-4">
                                        @php
                                            $segmentColors = [
                                                'vip' => 'purple',
                                                'loyal' => 'green',
                                                'regular' => 'blue',
                                                'at_risk' => 'orange',
                                                'new' => 'cyan',
                                                'dormant' => 'gray'
                                            ];
                                            $badgeColor = $segmentColors[$customer->segment] ?? 'gray';
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-medium rounded-full whitespace-nowrap bg-{{ $badgeColor }}-100 dark:bg-{{ $badgeColor }}-900/20 text-{{ $badgeColor }}-700 dark:text-{{ $badgeColor }}-300">
                                            {{ ucfirst(str_replace('_', ' ', $customer->segment)) }}
                                        </span>
                                    </td>

                                    <!-- Spent -->
                                    <td class="px-3 py-3 text-xs font-semibold text-right text-gray-900 whitespace-nowrap sm:px-4 sm:text-sm dark:text-gray-100">
                                        <div class="flex flex-col gap-1">
                                            <span>₱{{ number_format($customer->total_spent, 0) }}</span>
                                            <!-- Show visits on small mobile -->
                                            <span class="text-xs font-normal text-gray-500 sm:hidden dark:text-gray-400">
                                                {{ number_format($customer->visit_count) }} visits
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Visits - Hidden on Small Mobile -->
                                    <td class="hidden px-3 py-3 text-xs text-right text-gray-600 sm:table-cell sm:px-4 sm:text-sm dark:text-gray-400">
                                        {{ number_format($customer->visit_count) }}
                                    </td>

                                    <!-- RFM Scores - Desktop Only -->
                                    <td class="hidden px-3 py-3 text-center lg:table-cell sm:px-4">
                                        <div class="flex items-center justify-center gap-1 text-xs">
                                            <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded whitespace-nowrap font-medium">
                                                R:{{ $customer->getRecencyScore() }}
                                            </span>
                                            <span class="px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded whitespace-nowrap font-medium">
                                                F:{{ $customer->getFrequencyScore() }}
                                            </span>
                                            <span class="px-1.5 py-0.5 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded whitespace-nowrap font-medium">
                                                M:{{ $customer->getMonetaryScore() }}
                                            </span>
                                        </div>
                                    </td>

                                    <!-- Last Visit - Large Desktop Only -->
                                    <td class="hidden px-3 py-3 text-xs text-gray-600 xl:table-cell whitespace-nowrap sm:px-4 sm:text-sm dark:text-gray-400">
                                        {{ $customer->last_visit_date ? $customer->last_visit_date->format('M d, Y') : 'N/A' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-sm text-center text-gray-500 dark:text-gray-400">
                                        <div class="flex flex-col items-center gap-2">
                                            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                            </svg>
                                            <p class="font-medium">No customers found</p>
                                            <p class="text-xs text-gray-400">Try adjusting your filters</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Pagination Controls - Improved Mobile Layout -->
            <div class="flex flex-col items-center justify-between gap-4 mt-6 sm:flex-row">
                <!-- Showing Info -->
                <div class="text-xs text-center text-gray-600 sm:text-left sm:text-sm dark:text-gray-400">
                    Showing <span id="showingStart" class="font-medium text-gray-900 dark:text-gray-100">1</span>
                    to <span id="showingEnd" class="font-medium text-gray-900 dark:text-gray-100">10</span>
                    of <span id="totalEntries" class="font-medium text-gray-900 dark:text-gray-100">{{ $topCustomers->count() }}</span> entries
                </div>

                <!-- Pagination Buttons -->
                <div class="flex gap-2">
                    <button onclick="previousPage()" id="prevBtn"
                            class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                        <span class="hidden sm:inline">Previous</span>
                        <span class="sm:hidden">Prev</span>
                    </button>
                    <button onclick="nextPage()" id="nextBtn"
                            class="px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">
                        Next
                    </button>
                </div>
            </div>
        </div>






        <!-- DEMOGRAPHICS SECTION -->
        <div class="mt-8 mb-6">
            <h2 class="mb-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                📊 Purchase Patterns by Demographics
            </h2>
            <p class="text-xs text-gray-600 sm:text-sm dark:text-gray-400">
                Analyze customer behavior across branches, age groups, and gender
            </p>
        </div>

        <!-- By Branch -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
                <h3 class="flex items-center gap-2 mb-4 text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">
                    <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                    </svg>
                    Purchase Patterns by Branch
                </h3>

                <div class="space-y-6">
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                        <div class="inline-block min-w-full align-middle">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-2 py-3 text-xs font-medium text-left text-gray-500 uppercase sm:px-4 dark:text-gray-400">Branch</th>
                                        <th class="hidden px-2 py-3 text-xs font-medium text-right text-gray-500 uppercase sm:table-cell sm:px-4 dark:text-gray-400">Customers</th>
                                        <th class="px-2 py-3 text-xs font-medium text-right text-gray-500 uppercase sm:px-4 dark:text-gray-400">Sales</th>
                                        <th class="hidden px-2 py-3 text-xs font-medium text-right text-gray-500 uppercase md:table-cell sm:px-4 dark:text-gray-400">Avg</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-[#171717] dark:divide-gray-800">
                                    @forelse($purchasesByBranch as $branch)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                        <td class="px-2 py-3 text-xs font-medium text-gray-900 sm:px-4 sm:text-sm dark:text-gray-100">
                                            <div>{{ $branch->branch_name }}</div>
                                            <div class="mt-1 text-xs text-gray-500 sm:hidden dark:text-gray-400">
                                                {{ number_format($branch->customer_count) }} customers
                                            </div>
                                        </td>
                                        <td class="hidden px-2 py-3 text-xs text-right text-gray-600 sm:table-cell sm:px-4 sm:text-sm dark:text-gray-400">
                                            {{ number_format($branch->customer_count) }}
                                        </td>
                                        <td class="px-2 py-3 text-xs font-medium text-right text-gray-900 whitespace-nowrap sm:px-4 sm:text-sm dark:text-gray-100">
                                            <div>₱{{ number_format($branch->total_sales, 0) }}</div>
                                            <div class="mt-1 text-xs text-gray-500 md:hidden dark:text-gray-400">
                                                Avg: ₱{{ number_format($branch->avg_transaction, 0) }}
                                            </div>
                                        </td>
                                        <td class="hidden px-2 py-3 text-xs text-right text-gray-600 md:table-cell whitespace-nowrap sm:px-4 sm:text-sm dark:text-gray-400">
                                            ₱{{ number_format($branch->avg_transaction, 2) }}
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="px-4 py-8 text-sm text-center text-gray-500 dark:text-gray-400">No branch data available</td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Chart Container - Horizontal Bar Chart (Mobile Friendly) -->
                    <div class="w-full">
                        <div class="h-80 sm:h-96">
                            <canvas id="branchPatternChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>

        <!-- By Age Group -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <h3 class="flex items-center gap-2 mb-4 text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Purchase Patterns by Age Group
            </h3>

            <div class="space-y-6">
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-2 py-3 text-xs font-medium text-left text-gray-500 uppercase sm:px-4 dark:text-gray-400">Age Group</th>
                                    <th class="hidden px-2 py-3 text-xs font-medium text-right text-gray-500 uppercase sm:table-cell sm:px-4 dark:text-gray-400">Customers</th>
                                    <th class="px-2 py-3 text-xs font-medium text-right text-gray-500 uppercase sm:px-4 dark:text-gray-400">Sales</th>
                                    <th class="hidden px-2 py-3 text-xs font-medium text-right text-gray-500 uppercase md:table-cell sm:px-4 dark:text-gray-400">Avg</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-[#171717] dark:divide-gray-800">
                                @forelse($purchasesByAge as $ageGroup)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-2 py-3 text-xs font-medium text-gray-900 sm:px-4 sm:text-sm dark:text-gray-100">
                                        <div>{{ $ageGroup->age_group }}</div>
                                        <div class="mt-1 text-xs text-gray-500 sm:hidden dark:text-gray-400">
                                            {{ number_format($ageGroup->customer_count) }} customers
                                        </div>
                                    </td>
                                    <td class="hidden px-2 py-3 text-xs text-right text-gray-600 sm:table-cell sm:px-4 sm:text-sm dark:text-gray-400">
                                        {{ number_format($ageGroup->customer_count) }}
                                    </td>
                                    <td class="px-2 py-3 text-xs font-medium text-right text-gray-900 whitespace-nowrap sm:px-4 sm:text-sm dark:text-gray-100">
                                        <div>₱{{ number_format($ageGroup->total_sales, 0) }}</div>
                                        <div class="mt-1 text-xs text-gray-500 md:hidden dark:text-gray-400">
                                            Avg: ₱{{ number_format($ageGroup->avg_transaction, 0) }}
                                        </div>
                                    </td>
                                    <td class="hidden px-2 py-3 text-xs text-right text-gray-600 md:table-cell whitespace-nowrap sm:px-4 sm:text-sm dark:text-gray-400">
                                        ₱{{ number_format($ageGroup->avg_transaction, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-8 text-sm text-center text-gray-500 dark:text-gray-400">No age data available</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    </div>

                <div class="h-72 sm:h-80 md:h-96">
                    <canvas id="agePatternChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Continue with Gender, Top Products, Additional Charts, Cohort Analysis -->
        <!-- Then add the JavaScript at the bottom -->

        <!-- Chart.js Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>

        <!-- Pagination Script -->
        <script>
                // Enhanced Pagination Logic - Mobile Optimized
                let currentPage = 1;
                let entriesPerPage = 10;
                let totalRows = 0;

                function updateTableEntries() {
                    entriesPerPage = parseInt(document.getElementById('entriesPerPage').value);
                    currentPage = 1;
                    paginateTable();
                }

                function paginateTable() {
                    const rows = document.querySelectorAll('.customer-row');
                    totalRows = rows.length;

                    const startIndex = (currentPage - 1) * entriesPerPage;
                    const endIndex = startIndex + entriesPerPage;

                    rows.forEach((row, index) => {
                        row.style.display = (index >= startIndex && index < endIndex) ? '' : 'none';
                    });

                    // Update pagination info
                    document.getElementById('showingStart').textContent = totalRows > 0 ? startIndex + 1 : 0;
                    document.getElementById('showingEnd').textContent = Math.min(endIndex, totalRows);
                    document.getElementById('totalEntries').textContent = totalRows;

                    // Update button states
                    const prevBtn = document.getElementById('prevBtn');
                    const nextBtn = document.getElementById('nextBtn');

                    prevBtn.disabled = currentPage === 1;
                    nextBtn.disabled = endIndex >= totalRows;

                    // Scroll to table top on mobile
                    if (window.innerWidth < 640) {
                        const table = document.querySelector('.overflow-x-auto');
                        if (table) {
                            table.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
                        }
                    }
                }

                function previousPage() {
                    if (currentPage > 1) {
                        currentPage--;
                        paginateTable();
                    }
                }

                function nextPage() {
                    const maxPage = Math.ceil(totalRows / entriesPerPage);
                    if (currentPage < maxPage) {
                        currentPage++;
                        paginateTable();
                    }
                }

                // Initialize on page load
                document.addEventListener('DOMContentLoaded', function() {
                    paginateTable();
                });
                </script>

                <!-- Chart Initialization Script -->
                <script>
                // Chart Configuration
            const chartColors = {
                vip: '#a855f7',
                loyal: '#10b981',
                regular: '#3b82f6',
                at_risk: '#f59e0b',
                new: '#06b6d4',
                dormant: '#6b7280'
            };

            // Detect theme and screen size
            const isDarkMode = document.documentElement.classList.contains('dark');
            const textColor = isDarkMode ? '#a3a3a3' : '#6b7280';
            const gridColor = isDarkMode ? '#262626' : '#e5e7eb';
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;

            // Set Chart.js defaults
            Chart.defaults.color = textColor;
            Chart.defaults.borderColor = gridColor;
            Chart.defaults.font.size = isMobile ? 10 : 12;

            // 1. Customer Segments Chart (Doughnut)
            const segmentCtx = document.getElementById('segmentChart');
            if (segmentCtx) {
                const segmentData = {!! json_encode($segmentData) !!};

                new Chart(segmentCtx, {
                    type: 'doughnut',
                    data: {
                        labels: Object.keys(segmentData).map(key =>
                            key.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ')
                        ),
                        datasets: [{
                            data: Object.values(segmentData),
                            backgroundColor: Object.keys(segmentData).map(key => chartColors[key] || '#6b7280'),
                            borderWidth: 2,
                            borderColor: isDarkMode ? '#171717' : '#ffffff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom',
                                labels: {
                                    padding: isMobile ? 10 : 15,
                                    font: { size: isMobile ? 10 : 12 },
                                    color: textColor,
                                    boxWidth: isMobile ? 12 : 15
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        const label = context.label || '';
                                        const value = context.parsed || 0;
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = ((value / total) * 100).toFixed(1);
                                        return `${label}: ${value.toLocaleString()} (${percentage}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }

            // 2. CLV Distribution Chart (Bar) - FIXED VERSION
            const clvCtx = document.getElementById('clvChart');
            if (clvCtx) {
                // Get customer data from Laravel
                const customers = {!! json_encode($topCustomers->map(function($c) {
                    return $c->total_spent;
                })) !!};

                // Only render if there's data
                if (customers.length === 0) {
                    console.log('No customer data available for CLV chart');
                } else {
                    // Define CLV ranges
                    const ranges = [
                        { label: '₱0-5k', min: 0, max: 5000, count: 0 },
                        { label: '₱5k-10k', min: 5000, max: 10000, count: 0 },
                        { label: '₱10k-25k', min: 10000, max: 25000, count: 0 },
                        { label: '₱25k-50k', min: 25000, max: 50000, count: 0 },
                        { label: '₱50k+', min: 50000, max: Infinity, count: 0 }
                    ];

                    // Mobile-friendly labels
                    const mobileLabels = ['0-5k', '5-10k', '10-25k', '25-50k', '50k+'];

                    // Count customers in each range
                    customers.forEach(spent => {
                        for (let i = 0; i < ranges.length; i++) {
                            if (spent >= ranges[i].min && spent < ranges[i].max) {
                                ranges[i].count++;
                                break;
                            }
                        }
                    });

                  
                    const clvCounts = ranges.map(r => r.count);
                    const clvLabels = isMobile ? mobileLabels : ranges.map(r => r.label);

                    new Chart(clvCtx, {
                        type: 'bar',
                        data: {
                            labels: clvLabels,
                            datasets: [{
                                label: 'Customers',
                                data: clvCounts,
                                backgroundColor: '#8b5cf6',
                                borderRadius: 6,
                                borderSkipped: false,
                                maxBarThickness: 80
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    display: false
                                },
                                tooltip: {
                                    callbacks: {
                                        title: function(context) {
                                            return ranges[context[0].dataIndex].label;
                                        },
                                        label: function(context) {
                                            const count = context.parsed.y;
                                            const total = clvCounts.reduce((a, b) => a + b, 0);
                                            const percentage = total > 0 ? ((count / total) * 100).toFixed(1) : 0;
                                            return `Customers: ${count.toLocaleString()} (${percentage}%)`;
                                        }
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    ticks: {
                                        precision: 0,
                                        color: textColor,
                                        font: { size: isMobile ? 9 : 11 }
                                    },
                                    grid: {
                                        color: gridColor,
                                        drawBorder: false
                                    },
                                    title: {
                                        display: !isMobile,
                                        text: 'Number of Customers',
                                        color: textColor,
                                        font: { size: 11 }
                                    }
                                },
                                x: {
                                    ticks: {
                                        color: textColor,
                                        font: { size: isMobile ? 9 : 11 }
                                    },
                                    grid: {
                                        display: false
                                    },
                                    title: {
                                        display: !isMobile,
                                        text: 'Lifetime Value Range',
                                        color: textColor,
                                        font: { size: 11 }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Update charts on theme change
            document.addEventListener('theme-changed', function() {
                location.reload();
            });

            // 3. Branch Pattern Chart (Bar)
            const branchCtx = document.getElementById('branchPatternChart');
            if (branchCtx) {
                const branchData = {!! json_encode($purchasesByBranch) !!};

                // Simplify labels for mobile
                const branchLabels = branchData.map(b => {
                    if (isMobile) {
                        return b.branch_name.replace(' Branch', '').substring(0, 6);
                    }
                    return b.branch_name;
                });

                new Chart(branchCtx, {
                    type: 'bar',
                    data: {
                        labels: branchLabels,
                        datasets: [
                            {
                                label: 'Total Sales',
                                data: branchData.map(b => b.total_sales),
                                backgroundColor: '#3b82f6',
                                borderRadius: isMobile ? 4 : 6,
                                yAxisID: 'y',
                            },
                            {
                                label: 'Customers',
                                data: branchData.map(b => b.customer_count),
                                backgroundColor: '#10b981',
                                borderRadius: isMobile ? 4 : 6,
                                yAxisID: 'y1',
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false,
                        },
                        plugins: {
                            legend: {
                                position: 'top',
                                labels: {
                                    color: textColor,
                                    padding: isMobile ? 8 : 15,
                                    font: { size: isMobile ? 10 : 12 },
                                    boxWidth: isMobile ? 12 : 15
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    title: function(context) {
                                        // Show full branch name in tooltip even on mobile
                                        return branchData[context[0].dataIndex].branch_name;
                                    },
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) label += ': ';

                                        if (context.datasetIndex === 0) {
                                            label += '₱' + context.parsed.y.toLocaleString('en-PH', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            });
                                        } else {
                                            label += context.parsed.y.toLocaleString();
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                type: 'linear',
                                display: true,
                                position: 'left',
                                title: {
                                    display: !isMobile,
                                    text: 'Sales (₱)',
                                    color: textColor,
                                    font: { size: isMobile ? 9 : 11 }
                                },
                                ticks: {
                                    color: textColor,
                                    font: { size: isMobile ? 8 : 10 },
                                    callback: function(value) {
                                        if (isMobile) {
                                            return '₱' + (value / 1000).toFixed(0) + 'k';
                                        }
                                        return '₱' + value.toLocaleString('en-PH', {
                                            minimumFractionDigits: 0
                                        });
                                    }
                                },
                                grid: { color: gridColor }
                            },
                            y1: {
                                type: 'linear',
                                display: !isMobile,
                                position: 'right',
                                title: {
                                    display: !isMobile,
                                    text: 'Customers',
                                    color: textColor,
                                    font: { size: isMobile ? 9 : 11 }
                                },
                                ticks: {
                                    color: textColor,
                                    font: { size: isMobile ? 8 : 10 },
                                    precision: 0
                                },
                                grid: { display: false }
                            },
                            x: {
                                ticks: {
                                    color: textColor,
                                    font: { size: isMobile ? 9 : 11 }
                                },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // 4. Age Pattern Chart (Bar)
            const ageCtx = document.getElementById('agePatternChart');
            if (ageCtx) {
                const ageData = {!! json_encode($purchasesByAge) !!};

                new Chart(ageCtx, {
                    type: 'bar',
                    data: {
                        labels: ageData.map(a => a.age_group),
                        datasets: [{
                            label: 'Total Sales',
                            data: ageData.map(a => a.total_sales),
                            backgroundColor: [
                                '#06b6d4', // 18-24
                                '#3b82f6', // 25-34
                                '#8b5cf6', // 35-44
                                '#f59e0b', // 45-54
                                '#ef4444'  // 55+
                            ],
                            borderRadius: isMobile ? 4 : 6,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { display: false },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Sales: ₱' + context.parsed.y.toLocaleString('en-PH', {
                                            minimumFractionDigits: 2,
                                            maximumFractionDigits: 2
                                        });
                                    },
                                    afterLabel: function(context) {
                                        const index = context.dataIndex;
                                        const customers = ageData[index].customer_count;
                                        const avg = ageData[index].avg_transaction;
                                        return [
                                            `Customers: ${customers.toLocaleString()}`,
                                            `Avg: ₱${parseFloat(avg).toLocaleString('en-PH', {
                                                minimumFractionDigits: 2,
                                                maximumFractionDigits: 2
                                            })}`
                                        ];
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    color: textColor,
                                    font: { size: isMobile ? 8 : 10 },
                                    callback: function(value) {
                                        if (isMobile) {
                                            return '₱' + (value / 1000000).toFixed(1) + 'M';
                                        }
                                        return '₱' + value.toLocaleString('en-PH', {
                                            minimumFractionDigits: 0
                                        });
                                    }
                                },
                                grid: { color: gridColor }
                            },
                            x: {
                                ticks: {
                                    color: textColor,
                                    font: { size: isMobile ? 9 : 11 }
                                },
                                grid: { display: false }
                            }
                        }
                    }
                });
            }

            // Update charts on theme change
            document.addEventListener('theme-changed', function() {
                location.reload(); // Simple approach - reload page on theme change
                // Alternative: You could store chart instances and update them dynamically
            });
        </script>
    </div>
</x-app-layout>
