<x-app-layout>
    <div class="max-w-7xl mx-auto">
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
            <button onclick="window.print()" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Report
            </button>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Customers -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Customers</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            {{ number_format($totalCustomers) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
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
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            {{ number_format($activeCustomers) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                    {{ number_format(($activeCustomers / $totalCustomers) * 100, 1) }}% retention
                </p>
            </div>

            <!-- Avg Lifetime Value -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Lifetime Value</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            ₱{{ number_format($avgLifetimeValue, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
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
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            {{ number_format($avgVisitCount, 1) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-orange-100 dark:bg-orange-900/20 rounded-lg flex items-center justify-center">
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
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <!-- Date Range -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Start Date
                        </label>
                        <input type="date" name="start_date"
                               value="{{ $startDate->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            End Date
                        </label>
                        <input type="date" name="end_date"
                               value="{{ $endDate->format('Y-m-d') }}"
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
                    </div>

                    <!-- Segment Filter -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Customer Segment
                        </label>
                        <select name="segment" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
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
                    <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md font-medium text-sm">
                        Apply Filters
                    </button>
                    <a href="{{ route('analytics.customers') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 rounded-md font-medium text-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Customer Segments & Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Segment Breakdown -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Customer Segments
                </h2>

                <div class="space-y-3 mb-6">
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
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300 capitalize">
                                    {{ str_replace('_', ' ', $segment) }}
                                </span>
                                <span class="text-sm text-gray-600 dark:text-gray-400">
                                    {{ $count }} ({{ number_format($percentage, 1) }}%)
                                </span>
                            </div>
                            <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                <div class="bg-{{ $color }}-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div style="height: 250px;">
                    <canvas id="segmentChart"></canvas>
                </div>
            </div>

            <!-- CLV Distribution -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Lifetime Value Distribution
                </h2>
                <div style="height: 300px;">
                    <canvas id="clvChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Top 100 Customers -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Top Customers
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Rank</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Customer</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Segment</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Spent</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Visits</th>
                            <th class="px-4 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">RFM Score</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Last Visit</th>
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
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                ₱{{ number_format($customer->total_spent, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
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

        <!-- Additional Charts Row -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Customer Acquisition Trend -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    Customer Acquisition Trend
                </h2>
                <div style="height: 250px;">
                    <canvas id="acquisitionChart"></canvas>
                </div>
            </div>

            <!-- RFM Score Distribution -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                    RFM Score Distribution
                </h2>
                <div style="height: 250px;">
                    <canvas id="rfmChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Cohort Analysis -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Monthly Cohort Analysis
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Cohort</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Customers</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total Revenue</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Avg Visits</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($cohorts as $cohort)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ \Carbon\Carbon::parse($cohort->cohort . '-01')->format('F Y') }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                {{ number_format($cohort->customers) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                ₱{{ number_format($cohort->total_revenue, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
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
    </script>
</x-app-layout>
