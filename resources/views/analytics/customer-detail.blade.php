<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Back Button -->
        <div class="mb-6">
            <a href="{{ route('analytics.customers') }}" class="inline-flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
                Back to Customer Analytics
            </a>
        </div>

        <!-- Customer Header -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <div class="flex items-start justify-between">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $customer->name }}
                    </h1>
                    <div class="mt-2 space-y-1">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Email:</span> {{ $customer->email ?? 'N/A' }}
                        </p>
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Phone:</span> {{ $customer->phone ?? 'N/A' }}
                        </p>
                        @if($customer->loyalty_id)
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Loyalty ID:</span> {{ $customer->loyalty_id }}
                        </p>
                        @endif
                    </div>
                </div>

                <!-- Segment Badge -->
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
                <span class="px-4 py-2 text-sm font-medium rounded-full bg-{{ $badgeColor }}-100 dark:bg-{{ $badgeColor }}-900/20 text-{{ $badgeColor }}-700 dark:text-{{ $badgeColor }}-300">
                    {{ ucfirst($customer->segment) }} Customer
                </span>
            </div>
        </div>

        <!-- Key Metrics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
            <!-- Total Spent -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Spent</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                    ₱{{ number_format($customer->total_spent, 2) }}
                </p>
            </div>

            <!-- Visit Count -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Visits</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                    {{ number_format($customer->visit_count) }}
                </p>
            </div>

            <!-- Average Order Value -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Order Value</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                    ₱{{ number_format($avgOrderValue, 2) }}
                </p>
            </div>

            <!-- Days Since Last Purchase -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Last Purchase</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                    @if($daysSinceLastPurchase !== null)
                        {{ $daysSinceLastPurchase }} days ago
                    @else
                        Never
                    @endif
                </p>
                @if($customer->last_visit_date)
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                    {{ $customer->last_visit_date->format('M d, Y') }}
                </p>
                @endif
            </div>
        </div>

        <!-- RFM Score Card -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                RFM Score Analysis
            </h2>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Recency</p>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-red-600 dark:text-red-400">
                            {{ $customer->getRecencyScore() }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 mb-1">/ 5</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">How recently purchased</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Frequency</p>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">
                            {{ $customer->getFrequencyScore() }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 mb-1">/ 5</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">How often purchases</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Monetary</p>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-green-600 dark:text-green-400">
                            {{ $customer->getMonetaryScore() }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 mb-1">/ 5</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">How much spends</p>
                </div>

                <div>
                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400 mb-2">Total Score</p>
                    <div class="flex items-end gap-2">
                        <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            {{ $customer->getTotalRfmScore() }}
                        </span>
                        <span class="text-sm text-gray-500 dark:text-gray-400 mb-1">/ 15</span>
                    </div>
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Overall customer value</p>
                </div>
            </div>
        </div>

        <!-- Monthly Purchase Trend -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Purchase Trend Over Time
            </h2>
            <div style="height: 300px;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- Transaction History -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Recent Transactions
            </h2>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Transaction ID</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Items</th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Total</th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Payment</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($customer->transactions->take(50) as $transaction)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $transaction->transaction_date->format('M d, Y h:i A') }}
                            </td>
                            <td class="px-4 py-3 text-sm font-mono text-gray-900 dark:text-gray-100">
                                {{ $transaction->transaction_code }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                {{ $transaction->items->count() }} items
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                ₱{{ number_format($transaction->total, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No transactions found
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <script>
        const isDarkMode = document.documentElement.classList.contains('dark');
        const colors = {
            text: isDarkMode ? '#fafafa' : '#111827',
            grid: isDarkMode ? '#262626' : '#e5e7eb',
            accent: isDarkMode ? '#a78bfa' : '#8b5cf6',
        };

        Chart.defaults.color = colors.text;
        Chart.defaults.borderColor = colors.grid;

        const trendCtx = document.getElementById('trendChart');
        if (trendCtx) {
            const trendData = @json($monthlyTrend);
            new Chart(trendCtx, {
                type: 'line',
                data: {
                    labels: trendData.map(d => d.month),
                    datasets: [{
                        label: 'Monthly Spending',
                        data: trendData.map(d => d.total),
                        borderColor: colors.accent,
                        backgroundColor: colors.accent + '20',
                        tension: 0.4,
                        fill: true,
                        pointRadius: 4,
                        pointHoverRadius: 6,
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
                            padding: 12,
                            callbacks: {
                                label: (context) => `₱${context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: { color: colors.grid },
                            ticks: {
                                callback: (value) => '₱' + value.toLocaleString('en-US')
                            }
                        },
                        x: { grid: { display: false } }
                    }
                }
            });
        }
    </script>
</x-app-layout>
