<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sales Forecasting') }}
        </h2>
    </x-slot>

<div class="flex items-center justify-between lg:px-8 max-w-7xl mx-auto">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Forecasting
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

    <div class="py-12">



        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('forecasts.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        @if(auth()->user()->role === 'admin')
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Branch</label>
                            <select name="branch_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">History Context</label>
                            <select name="history_period" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="7" {{ $historyPeriod == '7' ? 'selected' : '' }}>Last 7 Days</option>
                                <option value="14" {{ $historyPeriod == '14' ? 'selected' : '' }}>Last 14 Days</option>
                                <option value="30" {{ $historyPeriod == '30' ? 'selected' : '' }}>Last 30 Days</option>
                                <option value="60" {{ $historyPeriod == '60' ? 'selected' : '' }}>Last 60 Days</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Forecast Horizon</label>
                            <select name="forecast_period" class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="7" {{ $forecastPeriod == '7' ? 'selected' : '' }}>Next 7 Days</option>
                                <option value="14" {{ $forecastPeriod == '14' ? 'selected' : '' }}>Next 14 Days</option>
                                <option value="30" {{ $forecastPeriod == '30' ? 'selected' : '' }}>Next 30 Days</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-md transition">
                                Apply View
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Model Accuracy (MAPE)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $accuracy !== null ? number_format($accuracy, 1) . '%' : 'N/A' }}
                    </p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">History Evaluated</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ count($historyValues ?? []) }} Days</p>
                </div>
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Model Version</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">Holt-Winters</p>
                </div>
            </div>

            <div class="mb-6">
                <form method="POST" action="{{ route('forecasts.regenerate') }}" class="inline">
                    @csrf
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                        Regenerate Predictions
                    </button>
                </form>
            </div>

            @if(count($forecastDates ?? []) > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        📈 Sales Forecast (Next {{ $forecastPeriod }} Days)
                    </h3>
                    <div class="relative w-full" style="height: 350px;">
                        <canvas id="futureChart"></canvas>
                    </div>
                </div>
            </div>
            @else
            <div class="p-6 bg-white dark:bg-gray-800 rounded-lg mb-6 text-center text-gray-500">No forecasts available.</div>
            @endif

            @if(count($historyDates ?? []) > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        🎯 Forecast vs Actual (Last {{ $historyPeriod }} Days)
                    </h3>
                    <div class="relative w-full" style="height: 350px;">
                        <canvas id="historyChart"></canvas>
                    </div>
                </div>
            </div>
            @endif

            @if($topProductsForecasts->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top 10 Products Forecast</h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">SKU</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Forecast</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($topProductsForecasts as $forecast)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $forecast->product->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-500">{{ $forecast->product->sku ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-white text-right">₱{{ number_format($forecast->total_forecast, 2) }}</td>
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

            // 1. FUTURE CHART
            const futureCtx = document.getElementById('futureChart');
            if (futureCtx) {
                new Chart(futureCtx, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($forecastDates ?? []) !!},
                        datasets: [{
                            label: 'Projected Sales',
                            data: {!! json_encode($forecastValues ?? []) !!}.map(Number),
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124, 58, 237, 0.2)',
                            fill: true,
                            tension: 0.4
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { labels: { color: textColor } } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                            x: { grid: { color: gridColor }, ticks: { color: textColor } }
                        }
                    }
                });
            }

            // 2. HISTORY CHART
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
                                borderColor: '#10b981', // Green
                                tension: 0.3,
                                fill: false
                            },
                            {
                                label: 'Forecasted (Past)',
                                data: {!! json_encode($historyForecastValues ?? []) !!}.map(Number),
                                borderColor: '#6b7280', // Gray
                                borderDash: [5, 5],
                                tension: 0.3,
                                fill: false
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { labels: { color: textColor } } },
                        scales: {
                            y: { beginAtZero: true, grid: { color: gridColor }, ticks: { color: textColor } },
                            x: { grid: { color: gridColor }, ticks: { color: textColor } }
                        }
                    }
                });
            }
        });
    </script>
</x-app-layout>
