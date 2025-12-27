<x-app-layout>
    <x-slot name="header">
        <h2 class="text-lg font-semibold leading-tight text-gray-800 sm:text-xl dark:text-gray-200">
            {{ __('Sales Forecasting') }}
        </h2>
    </x-slot>

    <!-- Header Section - Mobile First -->
    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-xl font-bold text-gray-900 sm:text-2xl lg:text-3xl dark:text-gray-100">
                    Forecasting
                </h1>
                <p class="mt-1 text-sm text-gray-600 sm:text-base dark:text-gray-400">
                    AI-powered sales predictions
                </p>
            </div>
            <button onclick="window.print()"
                    class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white transition rounded-lg bg-primary-600 hover:bg-primary-700 sm:text-base">
                <svg class="flex-shrink-0 w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Report
            </button>
        </div>
    </div>

    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8 sm:py-8">

        <!-- Filter Section - Large Touch Targets -->
        <div class="mb-4 overflow-hidden bg-white shadow-sm sm:mb-6 dark:bg-gray-800 rounded-xl">
            <div class="p-5 sm:p-6">
                <form method="GET" action="{{ route('forecasts.index') }}" class="space-y-4">
                    <!-- Branch Filter (Admin Only) -->
                    @if(auth()->user()->role === 'admin')
                    <div>
                        <label class="block mb-2 text-sm font-semibold text-gray-700 sm:text-base dark:text-gray-300">Branch</label>
                        <select name="branch_id" class="w-full px-4 py-3 text-base border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-purple-500">
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- History & Forecast Periods -->
                    <div class="space-y-4 sm:grid sm:grid-cols-2 sm:gap-4 sm:space-y-0">
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 sm:text-base dark:text-gray-300">History Context</label>
                            <select name="history_period" class="w-full px-4 py-3 text-base border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-purple-500">
                                <option value="7" {{ $historyPeriod == '7' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="14" {{ $historyPeriod == '14' ? 'selected' : '' }}>Last 14 Days</option>
                                <option value="30" {{ $historyPeriod == '30' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="60" {{ $historyPeriod == '60' ? 'selected' : '' }}>Last 60 Days</option>
                            </select>
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700 sm:text-base dark:text-gray-300">Forecast Horizon</label>
                            <select name="forecast_period" class="w-full px-4 py-3 text-base border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:ring-2 focus:ring-purple-500">
                                <option value="7" {{ $forecastPeriod == '7' ? 'selected' : '' }}>Next 7 Days</option>
                                <option value="14" {{ $forecastPeriod == '14' ? 'selected' : '' }}>Next 14 Days</option>
                                <option value="30" {{ $forecastPeriod == '30' ? 'selected' : '' }}>Next 30 Days</option>
                            </select>
                        </div>
                    </div>

                    <!-- Submit Button - Large and Clear -->
                    <button type="submit" class="w-full px-5 py-3.5 text-base font-semibold text-white transition bg-purple-600 rounded-lg hover:bg-purple-700 sm:text-lg">
                        Apply View
                    </button>
                </form>
            </div>
        </div>

        <!-- KPI Cards - Larger Text on Mobile -->
        <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-3 sm:mb-6">
            <div class="p-5 overflow-hidden bg-white shadow-sm sm:p-6 dark:bg-gray-800 rounded-xl">
                <h3 class="mb-3 text-sm font-semibold text-gray-500 sm:text-base dark:text-gray-400">Model Accuracy</h3>
                <p class="text-3xl font-bold text-gray-900 sm:text-4xl dark:text-white">
                    {{ $accuracy !== null ? number_format($accuracy, 1) . '%' : 'N/A' }}
                </p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">MAPE score</p>
            </div>
            <div class="p-5 overflow-hidden bg-white shadow-sm sm:p-6 dark:bg-gray-800 rounded-xl">
                <h3 class="mb-3 text-sm font-semibold text-gray-500 sm:text-base dark:text-gray-400">History Data</h3>
                <p class="text-3xl font-bold text-gray-900 sm:text-4xl dark:text-white">{{ count($historyValues ?? []) }}</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Days analyzed</p>
            </div>
            <div class="p-5 overflow-hidden bg-white shadow-sm sm:p-6 dark:bg-gray-800 rounded-xl">
                <h3 class="mb-3 text-sm font-semibold text-gray-500 sm:text-base dark:text-gray-400">Model Type</h3>
                <p class="text-xl font-bold text-gray-900 sm:text-2xl dark:text-white">Holt-Winters</p>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Triple smoothing</p>
            </div>
        </div>

        <!-- Regenerate Button - Touch Friendly -->
        <div class="mb-4 sm:mb-6">
            <form method="POST" action="{{ route('forecasts.regenerate') }}">
                @csrf
                <button type="submit" class="inline-flex items-center justify-center w-full px-5 py-3 text-base font-semibold text-white transition bg-blue-600 rounded-lg sm:w-auto hover:bg-blue-700">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Regenerate Predictions
                </button>
            </form>
        </div>

        <!-- Future Forecast Chart - Optimized for Mobile -->
        @if(count($forecastDates ?? []) > 0)
        <div class="mb-4 overflow-hidden bg-white shadow-sm sm:mb-6 dark:bg-gray-800 rounded-xl">
            <div class="p-5 sm:p-6">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-gray-900 sm:text-lg dark:text-white">
                        📈 Sales Forecast
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Next {{ $forecastPeriod }} days • 95% confidence interval
                    </p>
                </div>
                <!-- Taller on mobile for better readability -->
                <div class="relative w-full h-80 sm:h-96">
                    <canvas id="futureChart"></canvas>
                </div>
            </div>
        </div>
        @else
        <div class="p-8 mb-4 text-base text-center text-gray-500 bg-white rounded-xl sm:mb-6 dark:bg-gray-800">
            No forecasts available yet
        </div>
        @endif

        <!-- Forecast vs Actual Chart -->
        @if(count($historyDates ?? []) > 0)
        <div class="mb-4 overflow-hidden bg-white shadow-sm sm:mb-6 dark:bg-gray-800 rounded-xl">
            <div class="p-5 sm:p-6">
                <div class="mb-4">
                    <h3 class="text-base font-bold text-gray-900 sm:text-lg dark:text-white">
                        🎯 Forecast vs Actual
                    </h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        Last {{ $historyPeriod }} days comparison
                    </p>
                </div>
                <div class="relative w-full h-80 sm:h-96">
                    <canvas id="historyChart"></canvas>
                </div>
            </div>
        </div>
        @endif

        <!-- Top Products Table - Card View on Mobile -->
        @if($topProductsForecasts->count() > 0)
        <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 rounded-xl">
            <div class="p-5 sm:p-6">
                <h3 class="mb-4 text-base font-bold text-gray-900 sm:text-lg dark:text-white">
                    Top 10 Products Forecast
                </h3>

                <!-- Mobile: Card View -->
                <div class="space-y-3 sm:hidden">
                    @foreach($topProductsForecasts as $forecast)
                    <div class="p-4 border border-gray-200 rounded-lg dark:border-gray-700">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <p class="text-base font-semibold text-gray-900 dark:text-white">
                                    {{ $forecast->product->name ?? 'N/A' }}
                                </p>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                                    SKU: {{ $forecast->product->sku ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="ml-4 text-right">
                                <p class="text-lg font-bold text-purple-600 dark:text-purple-400">
                                    ₱{{ number_format($forecast->total_forecast, 0) }}
                                </p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">Forecast</p>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Tablet+: Table View -->
                <div class="hidden overflow-x-auto sm:block">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-5 py-3 text-sm font-semibold text-left text-gray-700 uppercase dark:text-gray-300">Product</th>
                                <th class="px-5 py-3 text-sm font-semibold text-left text-gray-700 uppercase dark:text-gray-300">SKU</th>
                                <th class="px-5 py-3 text-sm font-semibold text-right text-gray-700 uppercase dark:text-gray-300">Forecast</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                            @foreach($topProductsForecasts as $forecast)
                            <tr>
                                <td class="px-5 py-4 text-base text-gray-900 dark:text-white">{{ $forecast->product->name ?? 'N/A' }}</td>
                                <td class="px-5 py-4 text-sm text-gray-500 whitespace-nowrap">{{ $forecast->product->sku ?? 'N/A' }}</td>
                                <td class="px-5 py-4 text-base font-semibold text-right text-gray-900 whitespace-nowrap dark:text-white">₱{{ number_format($forecast->total_forecast, 2) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#e5e7eb' : '#374151';
            const gridColor = isDark ? '#374151' : '#e5e7eb';
            const isMobile = window.innerWidth < 640;

            // Get actual data point count for smart display
            const forecastCount = {!! count($forecastDates ?? []) !!};
            const historyCount = {!! count($historyDates ?? []) !!};

            // Dynamic tick limits based on data density
            const forecastTickLimit = isMobile ? Math.min(6, Math.ceil(forecastCount / 3)) : Math.min(15, forecastCount);
            const historyTickLimit = isMobile ? Math.min(6, Math.ceil(historyCount / 5)) : Math.min(20, historyCount);

            // Common chart configuration with system fonts
            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false
                },
                plugins: {
                    legend: {
                        labels: {
                            color: textColor,
                            font: {
                                family: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",
                                size: isMobile ? 11 : 13,
                                weight: '600'
                            },
                            padding: isMobile ? 10 : 15,
                            boxWidth: isMobile ? 12 : 15,
                            boxHeight: isMobile ? 12 : 15,
                            usePointStyle: true,
                            pointStyle: 'circle'
                        },
                        onClick: function(e, legendItem, legend) {
                            const index = legendItem.datasetIndex;
                            const ci = legend.chart;
                            const meta = ci.getDatasetMeta(index);
                            meta.hidden = meta.hidden === null ? !ci.data.datasets[index].hidden : null;
                            ci.update();
                        }
                    },
                    tooltip: {
                        backgroundColor: isDark ? '#1f2937' : '#ffffff',
                        titleColor: textColor,
                        bodyColor: textColor,
                        borderColor: gridColor,
                        borderWidth: 1,
                        titleFont: {
                            family: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",
                            size: isMobile ? 12 : 14,
                            weight: '600'
                        },
                        bodyFont: {
                            family: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",
                            size: isMobile ? 11 : 13
                        },
                        padding: isMobile ? 10 : 12,
                        cornerRadius: 6,
                        displayColors: true,
                        boxWidth: 10,
                        boxHeight: 10,
                        boxPadding: 4,
                        callbacks: {
                            label: function(context) {
                                let label = context.dataset.label || '';
                                if (label) {
                                    label += ': ';
                                }
                                if (context.parsed.y !== null) {
                                    const value = context.parsed.y;
                                    if (value >= 1000000) {
                                        label += '₱' + (value/1000000).toFixed(1) + 'M';
                                    } else if (value >= 1000) {
                                        label += '₱' + (value/1000).toFixed(1) + 'k';
                                    } else {
                                        label += '₱' + value.toLocaleString('en-US', {minimumFractionDigits: 0, maximumFractionDigits: 0});
                                    }
                                }
                                return label;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: gridColor,
                            lineWidth: 1,
                            drawBorder: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                family: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",
                                size: isMobile ? 10 : 12,
                                weight: '400'
                            },
                            maxTicksLimit: isMobile ? 5 : 8,
                            padding: isMobile ? 8 : 10,
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return '₱' + (value/1000000).toFixed(1) + 'M';
                                } else if (value >= 1000) {
                                    return '₱' + (value/1000).toFixed(0) + 'k';
                                }
                                return '₱' + value.toLocaleString();
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        border: {
                            display: false
                        },
                        ticks: {
                            color: textColor,
                            font: {
                                family: "-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif",
                                size: isMobile ? 9 : 11,
                                weight: '400'
                            },
                            autoSkip: true,
                            maxRotation: 0,
                            minRotation: 0,
                            padding: isMobile ? 8 : 10
                        }
                    }
                }
            };

            // 1. FUTURE FORECAST CHART
            const futureCtx = document.getElementById('futureChart');
            if (futureCtx) {
                const forecastDates = {!! json_encode($forecastDates ?? []) !!};
                const forecastValues = {!! json_encode($forecastValues ?? []) !!}.map(Number);
                const forecastLower = {!! json_encode($forecastLower ?? []) !!}.map(Number);
                const forecastUpper = {!! json_encode($forecastUpper ?? []) !!}.map(Number);

                const futureOptions = JSON.parse(JSON.stringify(commonOptions));
                futureOptions.scales.x.ticks.maxTicksLimit = forecastTickLimit;

                new Chart(futureCtx, {
                    type: 'line',
                    data: {
                        labels: forecastDates,
                        datasets: [
                            {
                                label: 'Projected',
                                data: forecastValues,
                                borderColor: '#8b5cf6',
                                backgroundColor: 'rgba(139, 92, 246, 0.1)',
                                borderWidth: isMobile ? 2 : 2.5,
                                fill: false,
                                tension: 0.3,
                                pointRadius: isMobile ? 2 : 4,
                                pointHoverRadius: isMobile ? 5 : 6,
                                pointBackgroundColor: '#8b5cf6',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 1.5
                            },
                            {
                                label: 'Upper',
                                data: forecastUpper,
                                borderColor: 'rgba(139, 92, 246, 0.3)',
                                backgroundColor: 'rgba(139, 92, 246, 0.08)',
                                borderWidth: isMobile ? 1 : 1.5,
                                borderDash: [4, 4],
                                fill: '+1',
                                tension: 0.3,
                                pointRadius: 0,
                                pointHoverRadius: 0
                            },
                            {
                                label: 'Lower',
                                data: forecastLower,
                                borderColor: 'rgba(139, 92, 246, 0.3)',
                                backgroundColor: 'rgba(139, 92, 246, 0.08)',
                                borderWidth: isMobile ? 1 : 1.5,
                                borderDash: [4, 4],
                                fill: false,
                                tension: 0.3,
                                pointRadius: 0,
                                pointHoverRadius: 0
                            }
                        ]
                    },
                    options: futureOptions
                });
            }

            // 2. HISTORY CHART
            const historyCtx = document.getElementById('historyChart');
            if (historyCtx) {
                const historyOptions = JSON.parse(JSON.stringify(commonOptions));
                historyOptions.scales.x.ticks.maxTicksLimit = historyTickLimit;

                new Chart(historyCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($historyDates ?? []) !!},
                        datasets: [
                            {
                                label: 'Actual',
                                data: {!! json_encode($historyValues ?? []) !!}.map(Number),
                                borderColor: '#10b981',
                                backgroundColor: 'rgba(16, 185, 129, 0.1)',
                                borderWidth: isMobile ? 2 : 2.5,
                                tension: 0.3,
                                fill: false,
                                pointRadius: isMobile ? 2 : 4,
                                pointHoverRadius: isMobile ? 5 : 6,
                                pointBackgroundColor: '#10b981',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 1.5
                            },
                            {
                                label: 'Forecast',
                                data: {!! json_encode($historyForecastValues ?? []) !!}.map(Number),
                                borderColor: '#6b7280',
                                borderWidth: isMobile ? 2 : 2.5,
                                borderDash: [4, 4],
                                tension: 0.3,
                                fill: false,
                                pointRadius: isMobile ? 2 : 4,
                                pointHoverRadius: isMobile ? 5 : 6,
                                pointBackgroundColor: '#6b7280',
                                pointBorderColor: '#fff',
                                pointBorderWidth: 1.5
                            }
                        ]
                    },
                    options: historyOptions
                });
            }
        });
    </script>
</x-app-layout>
