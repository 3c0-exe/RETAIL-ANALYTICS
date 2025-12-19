<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Sales Forecasting') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('forecasts.index') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">

                        @if(auth()->user()->role === 'admin')
                        <div>
                            <label for="branch_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Branch
                            </label>
                            <select name="branch_id" id="branch_id"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        <div>
                            <label for="forecast_period" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Forecast Period
                            </label>
                            <select name="forecast_period" id="forecast_period"
                                    class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                <option value="7" {{ $forecastPeriod == '7' ? 'selected' : '' }}>7 Days</option>
                                <option value="30" {{ $forecastPeriod == '30' ? 'selected' : '' }}>30 Days</option>
                                <option value="90" {{ $forecastPeriod == '90' ? 'selected' : '' }}>90 Days</option>
                            </select>
                        </div>

                        <div class="flex items-end">
                            <button type="submit"
                                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-2 px-4 rounded-md transition">
                                Apply Filters
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Average Accuracy (Last {{ $forecastPeriod }} Days)</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ $accuracy !== null ? number_format($accuracy, 1) . '%' : 'N/A' }}
                    </p>
                    @if($accuracy === null)
                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                        Need {{ $forecastPeriod }} days of historical data
                    </p>
                    @endif
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Forecasts Evaluated</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        {{ count($comparisonForecasts ?? []) }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-2">Model Version</h3>
                    <p class="text-lg font-semibold text-gray-900 dark:text-white">
                        Holt-Winters (Seasonality)
                    </p>
                </div>
            </div>

            <div class="mb-6">
                <form method="POST" action="{{ route('forecasts.regenerate') }}" class="inline">
                    @csrf
                    <button type="submit"
                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md transition inline-flex items-center">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                        </svg>
                        Regenerate Forecasts
                    </button>
                </form>
            </div>

            @if(count($forecastDates ?? []) > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        📈 Sales Forecast (Next {{ $forecastPeriod }} Days)
                    </h3>
                    <div class="relative w-full" style="height: 400px;">
                        <canvas id="futureForecastChart"></canvas>
                    </div>
                </div>
            </div>
            @else
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <div class="text-center py-12">
                        <p class="mt-4 text-gray-500 dark:text-gray-400">No forecasts available yet.</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mb-4">Click "Regenerate Forecasts" to generate predictions.</p>
                    </div>
                </div>
            </div>
            @endif

            @if(count($comparisonDates ?? []) > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        🎯 Forecast vs Actual (Last {{ $forecastPeriod }} Days)
                    </h3>
                    @if(count($comparisonActuals ?? []) > 0 && count($comparisonForecasts ?? []) > 0)
                        <div class="relative w-full" style="height: 400px;">
                            <canvas id="forecastChart"></canvas>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <p class="mt-4 text-gray-500 dark:text-gray-400">Accuracy data not yet available</p>
                            <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Check back tomorrow!</p>
                        </div>
                    @endif
                </div>
            </div>
            @endif

            @if($topProductsForecasts->count() > 0)
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        Top 10 Products by Forecasted Sales
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-900">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Product</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">SKU</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Forecasted Sales</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($topProductsForecasts as $forecast)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ $forecast->product->name ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ $forecast->product->sku ?? 'N/A' }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white text-right">₱{{ number_format($forecast->total_forecast, 2) }}</td>
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
            console.log('✅ Chart Script Running...');

            const isDark = document.documentElement.classList.contains('dark');
            const textColor = isDark ? '#fafafa' : '#111827';
            const gridColor = isDark ? '#262626' : '#e5e7eb';

            // 1. Prepare Data
            const rawDates = {!! json_encode($forecastDates ?? []) !!};
            // Convert strings to numbers just to be safe
            const rawValues = {!! json_encode($forecastValues ?? []) !!}.map(Number);

            // 2. Render Future Chart
            const futureCtx = document.getElementById('futureForecastChart');
            if (futureCtx && rawDates.length > 0) {
                new Chart(futureCtx, {
                    type: 'line',
                    data: {
                        labels: rawDates,
                        datasets: [{
                            label: 'Projected Sales',
                            data: rawValues,
                            borderColor: '#7c3aed',
                            backgroundColor: 'rgba(124, 58, 237, 0.2)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointRadius: 4,
                            pointBackgroundColor: '#fff'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { labels: { color: textColor } },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        return 'Forecast: ₱' + context.parsed.y.toLocaleString('en-PH', {minimumFractionDigits: 2});
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
                                    callback: function(value) { return '₱' + value.toLocaleString(); }
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

            // 3. Render Accuracy Chart (if exists)
            const pastDates = {!! json_encode($comparisonDates ?? []) !!};
            if (pastDates.length > 0) {
                const accuracyCtx = document.getElementById('forecastChart');
                if (accuracyCtx) {
                    new Chart(accuracyCtx, {
                        type: 'line',
                        data: {
                            labels: pastDates,
                            datasets: [
                                {
                                    label: 'Actual',
                                    data: {!! json_encode($comparisonActuals ?? []) !!}.map(Number),
                                    borderColor: '#10b981',
                                    tension: 0.3
                                },
                                {
                                    label: 'Forecast',
                                    data: {!! json_encode($comparisonForecasts ?? []) !!}.map(Number),
                                    borderColor: '#6b7280',
                                    borderDash: [5, 5],
                                    tension: 0.3
                                }
                            ]
                        },
                        options: { responsive: true, maintainAspectRatio: false }
                    });
                }
            }
        });
    </script>

</x-app-layout>
