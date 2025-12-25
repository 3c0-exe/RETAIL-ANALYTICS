<x-app-layout>
    <div class="mx-auto max-w-7xl">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Customer Analytics
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    RFM analysis, segmentation, and lifetime value insights
                </p>
            </div>
            <button onclick="window.print()"
                    class="inline-flex items-center justify-center px-3 py-3 font-medium text-white transition-all duration-200 rounded-full bg-primary-600 hover:bg-primary-700 md:px-4 md:py-2 md:rounded-md">
                <svg class="flex-shrink-0 w-4 h-4 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                <span class="hidden text-sm md:inline">Print Report</span>
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 gap-4 mb-8 kpi-grid sm:grid-cols-2 lg:grid-cols-4 sm:gap-6">
            <!-- Total Customers -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Customers</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($totalCustomers) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-purple-100 rounded-lg dark:bg-purple-900/20">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Active Customers -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Customers</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($activeCustomers) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-green-100 rounded-lg dark:bg-green-900/20">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    {{ number_format(($activeCustomers / $totalCustomers) * 100, 1) }}% retention
                </p>
            </div>

            <!-- Avg Lifetime Value -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Lifetime Value</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                            ₱{{ number_format($avgLifetimeValue, 2) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-blue-100 rounded-lg dark:bg-blue-900/20">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Avg Visit Count -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Visits</p>
                        <p class="mt-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                            {{ number_format($avgVisitCount, 1) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-12 h-12 bg-orange-100 rounded-lg dark:bg-orange-900/20">
                        <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('analytics.customers') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <!-- Date Range -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Start Date
                        </label>
                        <input type="date" name="start_date"
                               value="{{ $startDate->format('Y-m-d') }}"
                               class="w-full px-3 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            End Date
                        </label>
                        <input type="date" name="end_date"
                               value="{{ $endDate->format('Y-m-d') }}"
                               class="w-full px-3 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <!-- Segment Filter -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Customer Segment
                        </label>
                        <select name="segment" class="w-full px-3 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">All Segments</option>
                            @foreach($segments as $key => $label)
                                <option value="{{ $key }}" {{ $segmentFilter == $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                        Apply Filters
                    </button>
                    <a href="{{ route('analytics.customers') }}" class="px-4 py-2 text-sm font-medium text-gray-900 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Export Button -->
        <div class="flex justify-end mb-6">
    <form method="POST" action="{{ route('export.customers.csv') }}" class="inline">
        @csrf
        <input type="hidden" name="segment" value="{{ request('segment') }}">
        <button type="submit" class="flex items-center gap-2 px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-md hover:bg-green-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
            Export CSV
        </button>
    </form>
</div>


        <!-- Customer Segments & Charts -->
        <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
            <!-- Segment Breakdown -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">
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
                                <span class="text-sm font-medium text-gray-700 capitalize dark:text-gray-300">
                                    {{ str_replace('_', ' ', $segment) }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $count }} ({{ number_format($percentage, 1) }}%)
                                </span>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="chart-container">
                    <div style="height: 250px;">
                        <canvas id="segmentChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- CLV Distribution -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">
                    Lifetime Value Distribution
                </h2>
                <div style="height: 300px;">
                    <canvas id="clvChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 100 Customers -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">
                Top Customers
            </h2>


            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Rank</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Customer</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Segment</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Total Spent</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Visits</th>
                            <th class="px-4 py-3 text-xs font-medium text-center text-gray-500 uppercase dark:text-gray-400">RFM Score</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Last Visit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($topCustomers as $index => $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-100">
                                @if($index === 0) 🥇
                                @elseif($index === 1) 🥈
                                @elseif($index === 2) 🥉
                                @else {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                    {{ $customer->name }}
                                </div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                    {{ $customer->email }}
                                </div>
                            </td>
                            <td class="px-4 py-3">
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
                                <span class="px-2 py-1 text-xs font-medium rounded-full bg-{{ $badgeColor }}-100 dark:bg-{{ $badgeColor }}-900/20 text-{{ $badgeColor }}-700 dark:text-{{ $badgeColor }}-300">
                                    {{ ucfirst($customer->segment) }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100">
                                ₱{{ number_format($customer->total_spent, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">
                                {{ number_format($customer->visit_count) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1 text-xs">
                                    <span class="px-1.5 py-0.5 bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300 rounded">
                                        R:{{ $customer->getRecencyScore() }}
                                    </span>
                                    <span class="px-1.5 py-0.5 bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300 rounded">
                                        F:{{ $customer->getFrequencyScore() }}
                                    </span>
                                    <span class="px-1.5 py-0.5 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded">
                                        M:{{ $customer->getMonetaryScore() }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $customer->last_visit_date ? $customer->last_visit_date->format('M d, Y') : 'N/A' }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No customers found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- DEMOGRAPHICS SECTION -->
        <div class="mt-8 mb-6">
            <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                📊 Purchase Patterns by Demographics
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400">
                Analyze customer behavior across branches, age groups, and gender
            </p>
        </div>

        <!-- By Branch -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h3 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Purchase Patterns by Branch
            </h3>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Branch</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Customers</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Sales</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Avg</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($purchasesByBranch as $branch)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $branch->branch_name }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ number_format($branch->customer_count) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100">₱{{ number_format($branch->total_sales, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">₱{{ number_format($branch->avg_transaction, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No branch data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="height: 300px;">
                    <canvas id="branchPatternChart"></canvas>
                </div>
            </div>
        </div>

        <!-- By Age Group -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h3 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                Purchase Patterns by Age Group
            </h3>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Age Group</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Customers</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Sales</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Avg</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($purchasesByAge as $ageGroup)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $ageGroup->age_group }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ number_format($ageGroup->customer_count) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100">₱{{ number_format($ageGroup->total_sales, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">₱{{ number_format($ageGroup->avg_transaction, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No age data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="height: 300px;">
                    <canvas id="agePatternChart"></canvas>
                </div>
            </div>
        </div>

        <!-- By Gender -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h3 class="flex items-center gap-2 mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                Purchase Patterns by Gender
            </h3>
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Gender</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Customers</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Sales</th>
                                <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Avg</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($purchasesByGender as $gender)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    @if($gender->gender === 'male') 👨 Male
                                    @elseif($gender->gender === 'female') 👩 Female
                                    @else ⚧ Other
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ number_format($gender->customer_count) }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100">₱{{ number_format($gender->total_sales, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">₱{{ number_format($gender->avg_transaction, 2) }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No gender data available</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div style="height: 300px;">
                    <canvas id="genderPatternChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top Products by Demographics -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <div class="mb-6">
                <h2 class="mb-2 text-2xl font-bold text-gray-900 dark:text-gray-100">
                    🛍️ Top Products by Demographics
                </h2>
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Most popular products for each demographic segment
                </p>
            </div>

            <!-- Tabs -->
            <div class="mb-6 border-b border-gray-200 dark:border-gray-700">
                <nav class="flex -mb-px space-x-8">
                    <button onclick="showProductTab('age')" id="tab-products-age"
                            class="px-1 py-4 text-sm font-medium text-blue-600 border-b-2 border-blue-500 product-tab-btn dark:text-blue-400">
                        By Age Group
                    </button>
                    <button onclick="showProductTab('gender')" id="tab-products-gender"
                            class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent product-tab-btn hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        By Gender
                    </button>
                    <button onclick="showProductTab('branch')" id="tab-products-branch"
                            class="px-1 py-4 text-sm font-medium text-gray-500 border-b-2 border-transparent product-tab-btn hover:text-gray-700 hover:border-gray-300 dark:text-gray-400 dark:hover:text-gray-300">
                        By Branch
                    </button>
                </nav>
            </div>

            <!-- Tab Content: By Age Group -->
            <div id="products-tab-age" class="product-tab-content">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-5">
                    @foreach(['18-25', '26-35', '36-45', '46-55', '56+'] as $ageGroup)
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                            <h3 class="flex items-center gap-2 mb-3 text-base font-semibold text-gray-900 dark:text-gray-100">
                                <span class="text-xl">{{ ['18-25' => '🎮', '26-35' => '💼', '36-45' => '👨‍👩‍👧', '46-55' => '🏡', '56+' => '🧓'][$ageGroup] }}</span>
                                <span class="text-sm">{{ $ageGroup }}</span>
                            </h3>
                            <div class="space-y-2">
                                @if(isset($topProductsByDemographic['by_age'][$ageGroup]) && $topProductsByDemographic['by_age'][$ageGroup]->isNotEmpty())
                                    @foreach($topProductsByDemographic['by_age'][$ageGroup] as $index => $product)
                                        <div class="flex items-start gap-2 p-2 bg-white border border-gray-200 rounded dark:bg-gray-800 dark:border-gray-700">
                                            <div class="flex items-center justify-center flex-shrink-0 w-5 h-5 text-xs font-bold text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900/30 dark:text-blue-400">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-xs font-medium text-gray-900 truncate dark:text-gray-100" title="{{ $product->product_name }}">
                                                    {{ $product->product_name }}
                                                </p>
                                                <div class="flex items-center justify-between mt-1">
                                                    <span class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ number_format($product->total_quantity) }} sold
                                                    </span>
                                                    <span class="text-xs font-semibold text-green-600 dark:text-green-400">
                                                        ₱{{ number_format($product->total_sales, 0) }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="py-4 text-xs italic text-center text-gray-500 dark:text-gray-400">No data</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab Content: By Gender -->
            <div id="products-tab-gender" class="hidden product-tab-content">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    @foreach(['female', 'male', 'other'] as $gender)
                        <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                            <h3 class="flex items-center gap-2 mb-3 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                <span class="text-2xl">{{ ['female' => '👩', 'male' => '👨', 'other' => '🧑'][$gender] }}</span>
                                {{ ucfirst($gender) }}
                            </h3>
                            <div class="space-y-2">
                                @if(isset($topProductsByDemographic['by_gender'][$gender]) && $topProductsByDemographic['by_gender'][$gender]->isNotEmpty())
                                    @foreach($topProductsByDemographic['by_gender'][$gender] as $index => $product)
                                        <div class="flex items-start gap-3 p-3 bg-white border border-gray-200 rounded dark:bg-gray-800 dark:border-gray-700">
                                            <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-xs font-bold text-pink-600 bg-pink-100 rounded-full dark:bg-pink-900/30 dark:text-pink-400">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-100">
                                                    {{ $product->product_name }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ number_format($product->total_quantity) }} sold • {{ $product->purchase_count }} purchases
                                                </p>
                                                <p class="mt-1 text-sm font-semibold text-green-600 dark:text-green-400">
                                                    ₱{{ number_format($product->total_sales, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="py-8 text-sm italic text-center text-gray-500 dark:text-gray-400">No data available</p>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Tab Content: By Branch -->
            <div id="products-tab-branch" class="hidden product-tab-content">
                <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                    @if(isset($topProductsByDemographic['by_branch']) && $topProductsByDemographic['by_branch']->isNotEmpty())
                        @foreach($topProductsByDemographic['by_branch'] as $branchName => $products)
                            <div class="p-4 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                                <h3 class="flex items-center gap-2 mb-3 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                    <span class="text-2xl">🏪</span>
                                    {{ $branchName }}
                                </h3>
                                <div class="space-y-2">
                                    @foreach($products as $index => $product)
                                        <div class="flex items-start gap-3 p-3 bg-white border border-gray-200 rounded dark:bg-gray-800 dark:border-gray-700">
                                            <div class="flex items-center justify-center flex-shrink-0 w-6 h-6 text-xs font-bold text-green-600 bg-green-100 rounded-full dark:bg-green-900/30 dark:text-green-400">
                                                {{ $index + 1 }}
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-100">
                                                    {{ $product->product_name }}
                                                </p>
                                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                                                    {{ number_format($product->total_quantity) }} sold • {{ $product->purchase_count }} purchases
                                                </p>
                                                <p class="mt-1 text-sm font-semibold text-green-600 dark:text-green-400">
                                                    ₱{{ number_format($product->total_sales, 2) }}
                                                </p>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-span-3 py-8 text-center">
                            <p class="text-gray-500 dark:text-gray-400">No branch data available</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
        function showProductTab(tabName) {
            document.querySelectorAll('.product-tab-content').forEach(content => {
                content.classList.add('hidden');
            });
            document.querySelectorAll('.product-tab-btn').forEach(tab => {
                tab.classList.remove('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
                tab.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            });
            document.getElementById('products-tab-' + tabName).classList.remove('hidden');
            const activeTab = document.getElementById('tab-products-' + tabName);
            activeTab.classList.add('border-blue-500', 'text-blue-600', 'dark:text-blue-400');
            activeTab.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        }
        </script>

        <!-- Additional Charts Row -->
        <div class="grid grid-cols-1 gap-6 mb-6 lg:grid-cols-2">
            <!-- Customer Acquisition Trend -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">
                    Customer Acquisition Trend
                </h2>
                <div style="height: 250px;">
                    <canvas id="acquisitionChart"></canvas>
                </div>
            </div>

            <!-- RFM Score Distribution -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">
                    RFM Score Distribution
                </h2>
                <div style="height: 250px;">
                    <canvas id="rfmChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Cohort Analysis -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
            <h2 class="mb-4 text-xl font-semibold text-gray-900 dark:text-gray-100">
                Monthly Cohort Analysis
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Cohort</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Customers</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Total Revenue</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Avg Visits</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($cohorts as $cohort)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($cohort->cohort . '-01')->format('F Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">
                                {{ number_format($cohort->customers) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100">
                                ₱{{ number_format($cohort->total_revenue, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">
                                {{ number_format($cohort->avg_visits, 1) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No cohort data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <script>
        const isDarkMode = document.documentElement.classList.contains('dark');
        const colors = {
            text: isDarkMode ? '#fafafa' : '#111827',
            textSecondary: isDarkMode ? '#a3a3a3' : '#6b7280',
            grid: isDarkMode ? '#262626' : '#e5e7eb',
            purple: isDarkMode ? '#a78bfa' : '#8b5cf6',
            green: isDarkMode ? '#34d399' : '#10b981',
            blue: isDarkMode ? '#60a5fa' : '#3b82f6',
            orange: isDarkMode ? '#fbbf24' : '#f59e0b',
            cyan: isDarkMode ? '#22d3ee' : '#06b6d4',
            gray: isDarkMode ? '#9ca3af' : '#6b7280',
        };

        Chart.defaults.color = colors.text;
        Chart.defaults.borderColor = colors.grid;

        // 1. Segment Pie Chart
        const segmentCtx = document.getElementById('segmentChart');
        if (segmentCtx) {
            const segmentData = @json($segmentData);
            new Chart(segmentCtx, {
                type: 'doughnut',
                data: {
                    labels: Object.keys(segmentData).map(s => s.charAt(0).toUpperCase() + s.slice(1).replace('_', ' ')),
                    datasets: [{
                        data: Object.values(segmentData),
                        backgroundColor: [colors.purple, colors.green, colors.blue, colors.orange, colors.cyan, colors.gray],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#262626' : '#ffffff',
                            titleColor: colors.text,
                            bodyColor: colors.text,
                            borderColor: colors.grid,
                            borderWidth: 1,
                        }
                    }
                }
            });
        }

        // 2. CLV Distribution
        const clvCtx = document.getElementById('clvChart');
        if (clvCtx) {
            const clvData = @json($clvDistribution);
            new Chart(clvCtx, {
                type: 'bar',
                data: {
                    labels: clvData.map(d => d.bracket),
                    datasets: [{
                        label: 'Customers',
                        data: clvData.map(d => d.count),
                        backgroundColor: colors.blue,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: colors.grid } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // 3. Customer Acquisition
        const acquisitionCtx = document.getElementById('acquisitionChart');
        if (acquisitionCtx) {
            const acquisitionData = @json($customerAcquisition);
            new Chart(acquisitionCtx, {
                type: 'line',
                data: {
                    labels: acquisitionData.map(d => d.month),
                    datasets: [{
                        label: 'New Customers',
                        data: acquisitionData.map(d => d.count),
                        borderColor: colors.green,
                        backgroundColor: colors.green + '20',
                        tension: 0.4,
                        fill: true,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: colors.grid } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        // 4. RFM Distribution
        const rfmCtx = document.getElementById('rfmChart');
        if (rfmCtx) {
            const rfmData = @json($rfmDistribution);
            new Chart(rfmCtx, {
                type: 'bar',
                data: {
                    labels: Object.keys(rfmData),
                    datasets: [{
                        label: 'Customers',
                        data: Object.values(rfmData),
                        backgroundColor: [colors.gray, colors.orange, colors.blue, colors.green, colors.purple],
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { beginAtZero: true, grid: { color: colors.grid } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
        // Demographics Charts
        const branchPatternCtx = document.getElementById('branchPatternChart');
        if (branchPatternCtx) {
            const branchData = @json($purchasesByBranch);
            new Chart(branchPatternCtx, {
                type: 'bar',
                data: {
                    labels: branchData.map(b => b.branch_name),
                    datasets: [{
                        label: 'Total Sales',
                        data: branchData.map(b => b.total_sales),
                        backgroundColor: colors.purple,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#262626' : '#ffffff',
                            titleColor: colors.text,
                            bodyColor: colors.text,
                            borderColor: colors.grid,
                            borderWidth: 1,
                            callbacks: {
                                label: (context) => '₱' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: colors.grid }, ticks: { callback: (value) => '₱' + value.toLocaleString('en-US') } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        const agePatternCtx = document.getElementById('agePatternChart');
        if (agePatternCtx) {
            const ageData = @json($purchasesByAge);
            new Chart(agePatternCtx, {
                type: 'bar',
                data: {
                    labels: ageData.map(a => a.age_group),
                    datasets: [{
                        label: 'Total Sales',
                        data: ageData.map(a => a.total_sales),
                        backgroundColor: colors.blue,
                        borderRadius: 6,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#262626' : '#ffffff',
                            titleColor: colors.text,
                            bodyColor: colors.text,
                            borderColor: colors.grid,
                            borderWidth: 1,
                            callbacks: {
                                label: (context) => '₱' + context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })
                            }
                        }
                    },
                    scales: {
                        y: { beginAtZero: true, grid: { color: colors.grid }, ticks: { callback: (value) => '₱' + value.toLocaleString('en-US') } },
                        x: { grid: { display: false } }
                    }
                }
            });
        }

        const genderPatternCtx = document.getElementById('genderPatternChart');
        if (genderPatternCtx) {
            const genderData = @json($purchasesByGender);
            new Chart(genderPatternCtx, {
                type: 'doughnut',
                data: {
                    labels: genderData.map(g => g.gender.charAt(0).toUpperCase() + g.gender.slice(1)),
                    datasets: [{
                        data: genderData.map(g => g.total_sales),
                        backgroundColor: [colors.green, colors.blue, colors.orange],
                        borderWidth: 0,
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'bottom', labels: { padding: 15, usePointStyle: true } },
                        tooltip: {
                            backgroundColor: isDarkMode ? '#262626' : '#ffffff',
                            titleColor: colors.text,
                            bodyColor: colors.text,
                            borderColor: colors.grid,
                            borderWidth: 1,
                            callbacks: {
                                label: (context) => context.label + ': ₱' + context.parsed.toLocaleString('en-US', { minimumFractionDigits: 2 })
                            }
                        }
                    }
                }
            });
        }
    </script>
</x-app-layout>
