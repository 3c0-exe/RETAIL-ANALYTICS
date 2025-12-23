<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Sales Forecasting') }}
        </h2>
    </x-slot>

    <div class="flex items-center justify-between mx-auto lg:px-8 max-w-7xl">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                Forecasting
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                AI-powered sales predictions with confidence intervals
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

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Filter Section -->
            <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('forecasts.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                        @if(auth()->user()->role === 'admin')
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Branch</label>
                            <select name="branch_id" class="w-full border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">History Context</label>
                            <select name="history_period" class="w-full border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="7" {{ $historyPeriod == '7' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="14" {{ $historyPeriod == '14' ? 'selected' : '' }}>Last 14 Days</option>
                                <option value="30" {{ $historyPeriod == '30' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="60" {{ $historyPeriod == '60' ? 'selected' : '' }}>Last 60 Days</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Forecast Horizon</label>
                            <select name="forecast_period" class="w-full border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="7" {{ $forecastPeriod == '7' ? 'selected' : '' }}>Next 7 Days</option>
                                <option value="14" {{ $forecastPeriod == '14' ? 'selected' : '' }}>Next 14 Days</option>
                                <option value="30" {{ $forecastPeriod == '30' ? 'selected' : '' }}>Next 30 Days</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full px-4 py-2 font-semibold text-white transition bg-purple-600 rounded-md hover:bg-purple-700">
                                Apply View
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- KPI Cards -->
            <div class="grid grid-cols-1 gap-6 mb-6 md:grid-cols-3">
                <div class="p-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <h3 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Model Accuracy (MAPE)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $accuracy !== null ? number_format($accuracy, 1) . '%' : 'N/A' }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Based on past predictions</p>
                </div>
                <div class="p-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <h3 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">History Evaluated</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ count($historyValues ?? []) }} Days</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Training data points</p>
                </div>
                <div class="p-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <h3 class="mb-2 text-sm font-medium text-gray-500 dark:text-gray-400">Model Version</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">Holt-Winters</p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Triple exponential smoothing</p>
                </div>
            </div>

            <!-- Regenerate Button -->
            <div class="mb-6">
                <form method="POST" action="{{ route('forecasts.regenerate') }}" class="inline">
                    @csrf
                    <button type="submit" class="inline-flex items-center px-4 py-2 font-semibold text-white transition bg-blue-600 rounded-md hover:bg-blue-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Regenerate Predictions
                    </button>
                </form>
            </div>

            <!-- Future Forecast Chart with Confidence Intervals -->
            @if(count($forecastDates ?? []) > 0)
            <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                            📈 Sales Forecast (Next {{ $forecastPeriod }} Days)
                        </h3>
                        <span class="text-xs text-gray-500 dark:text-gray-400">
                            Shaded area = 95% confidence interval
                        </span>
                    </div>
                    <div class="relative w-full" style="height: 350px;">
                        <canvas id="futureChart"></canvas>
                    </div>
                </div>
            </div>
            @else
            <div class="p-6 mb-6 text-center text-gray-500 bg-white rounded-lg dark:bg-gray-800">No forecasts available.</div>
            @endif

            <!-- Forecast vs Actual Chart -->
            @if(count($historyDates ?? []) > 0)
            <div class="mb-6 overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">
                        🎯 Forecast vs Actual (Last {{ $historyPeriod }} Days)
                    </h3>

                    <div class="relative w-full" style="height: 350px;">
                        <canvas id="historyChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            <!-- Top Products Table -->
            @if($topProductsForecasts->count() > 0)
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6">
                    <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-white">Top 10 Products Forecast</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">Product</th>
                                    <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase">SKU</th>
                                    <th class="px-6 py-3 text-xs font-medium text-right text-gray-500 uppercase">Forecast</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                @foreach($topProductsForecasts as $forecast)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $forecast->product->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $forecast->product->sku ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-right text-gray-900 dark:text-white">₱{{ number_format($forecast->total_forecast, 2) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#fafafa' : '#111827';
            const gridColor = isDark ? '#262626' : '#e5e7eb';

            // 1. FUTURE CHART WITH CONFIDENCE INTERVALS
            const futureCtx = document.getElementById('futureChart');
            if (futureCtx) {
                const forecastDates = {!! json_encode($forecastDates ?? []) !!};
                const forecastValues = {!! json_encode($forecastValues ?? []) !!}.map(Number);
                const forecastLower = {!! json_encode($forecastLower ?? []) !!}.map(Number);
                const forecastUpper = {!! json_encode($forecastUpper ?? []) !!}.map(Number);

                new Chart(futureCtx, {
                    type: 'line',
                    data: {
                        labels: forecastDates,
                        datasets: [
                            {
                                label: 'Projected Sales',
                                data: forecastValues,
                                borderColor: '#7c3aed',
                                backgroundColor: 'rgba(124, 58, 237, 0.1)',
                                borderWidth: 2,
                                fill: false,
                                tension: 0.4,
                                pointRadius: 4,
                                pointHoverRadius: 6
                            },
                            {
                                label: 'Upper Bound (95% CI)',
                                data: forecastUpper,
                                borderColor: 'rgba(124, 58, 237, 0.3)',
                                backgroundColor: 'rgba(124, 58, 237, 0.15)',
                                borderWidth: 1,
                                borderDash: [5, 5],
                                fill: '+1',
                                tension: 0.4,
                                pointRadius: 0
                            },
                            {
                                label: 'Lower Bound (95% CI)',
                                data: forecastLower,
                                borderColor: 'rgba(124, 58, 237, 0.3)',
                                backgroundColor: 'rgba(124, 58, 237, 0.15)',
                                borderWidth: 1,
                                borderDash: [5, 5],
                                fill: false,
                                tension: 0.4,
                                pointRadius: 0
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: {
                                labels: { color: textColor },
                                onClick: function(e, legendItem, legend) {
                                    const index = legendItem.datasetIndex;
                                    const ci = legend.chart;
                                    const meta = ci.getDatasetMeta(index);
                                    meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
                                    ci.update();
                                }
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += '₱' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: {
                                    color: textColor,
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            },
                            x: {
                                grid: { color: gridColor },
                                ticks: { color: textColor }
                            }
                        }
                    }
                });
            }

            // 2. HISTORY CHART (FORECAST VS ACTUAL)
            const historyCtx = document.getElementById('historyChart');
            if (historyCtx) {
                new Chart(historyCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($historyDates ?? []) !!},
                        datasets: [
                            {
                                label: 'Actual Sales',
                                data: {!! json_encode($historyValues ?? []) !!}.map(Number),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: 2,
                                tension: 0.3,
                                fill: false,
                                pointRadius: 3
                            },
                            {
                                label: 'Forecasted (Past)',
                                data: {!! json_encode($historyForecastValues ?? []) !!}.map(Number),
                                borderColor: '#6b7280',
                                borderWidth: 2,
                                borderDash: [5, 5],
                                tension: 0.3,
                                fill: false,
                                pointRadius: 3
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        interaction: {
                            mode: 'index',
                            intersect: false
                        },
                        plugins: {
                            legend: { labels: { color: textColor } },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.dataset.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        if (context.parsed.y !== null) {
                                            label += '₱' + context.parsed.y.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                                        }
                                        return label;
                                    }
                                }
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                grid: { color: gridColor },
                                ticks: {
                                    color: textColor,
                                    callback: function(value) {
                                        return '₱' + value.toLocaleString();
                                    }
                                }
                            },
                            x: {
                                grid: { color: gridColor },
                                ticks: { color: textColor }
                            }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
