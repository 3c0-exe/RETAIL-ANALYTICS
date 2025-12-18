<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            Sales Forecasting
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl space-y-6 sm:px-6 lg:px-8">

            {{-- Filters --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="GET" action="{{ route('forecasts.index') }}" class="flex flex-wrap gap-4 items-end">
                        {{-- Branch Filter --}}
                        @if(auth()->user()->role === 'admin')
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Branch</label>
                            <select name="branch_id" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                @foreach($branches as $branch)
                                    <option value="{{ $branch->id }}" {{ $selectedBranch->id == $branch->id ? 'selected' : '' }}>
                                        {{ $branch->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        @endif

                        {{-- Period Filter --}}
                        <div class="flex-1 min-w-[200px]">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Forecast Period</label>
                            <select name="period" class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                                <option value="7" {{ $period == 7 ? 'selected' : '' }}>7 Days</option>
                                <option value="30" {{ $period == 30 ? 'selected' : '' }}>30 Days</option>
                                <option value="90" {{ $period == 90 ? 'selected' : '' }}>90 Days</option>
                            </select>
                        </div>

                        <button type="submit" class="px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-md">
                            Apply Filters
                        </button>
                    </form>
                </div>
            </div>

            {{-- Accuracy Metrics --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Forecast Accuracy</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Average Accuracy (Last 7 Days)</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ $accuracy['average_accuracy'] ? number_format($accuracy['average_accuracy'], 1) . '%' : 'N/A' }}
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Forecasts Evaluated</div>
                            <div class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ $accuracy['forecasts_evaluated'] }}
                            </div>
                        </div>
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg p-4">
                            <div class="text-sm text-gray-600 dark:text-gray-400">Model Version</div>
                            <div class="text-sm font-mono text-gray-900 dark:text-white mt-1">
                                Exponential Smoothing v1
                            </div>
                        </div>
                    </div>

                    @if(auth()->user()->role === 'admin')
                    <div class="mt-4">
                        <form method="POST" action="{{ route('forecasts.regenerate') }}">
                            @csrf
                            <input type="hidden" name="branch_id" value="{{ $selectedBranch->id }}">
                            <button type="submit" class="px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-md text-sm">
                                🔄 Regenerate Forecasts
                            </button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>

            {{-- Forecast vs Actual Comparison --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Forecast vs Actual (Last 7 Days)</h3>
                    <canvas id="comparisonChart" height="80"></canvas>
                </div>
            </div>

            {{-- Sales Forecast Chart --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        {{ $period }}-Day Sales Forecast
                    </h3>
                    <canvas id="forecastChart" height="80"></canvas>
                </div>
            </div>

            {{-- Top Products Forecast --}}
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">
                        Top 10 Products - {{ $period }}-Day Forecast
                    </h3>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm text-left">
                            <thead class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                <tr>
                                    <th class="px-4 py-3">Product</th>
                                    <th class="px-4 py-3">SKU</th>
                                    <th class="px-4 py-3 text-right">Predicted Sales</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProductsForecasts as $item)
                                <tr class="border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-white">
                                        {{ $item['product']->name }}
                                    </td>
                                    <td class="px-4 py-3 text-gray-600 dark:text-gray-400">
                                        {{ $item['product']->sku }}
                                    </td>
                                    <td class="px-4 py-3 text-right font-semibold text-gray-900 dark:text-white">
                                        {{ $selectedBranch->currency }} {{ number_format($item['total_forecast'], 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                        No product forecasts available yet.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        // Comparison Chart
        const comparisonCtx = document.getElementById('comparisonChart').getContext('2d');
        new Chart(comparisonCtx, {
            type: 'line',
            data: {
                labels: @json($comparison['dates']),
                datasets: [
                    {
                        label: 'Actual Sales',
                        data: @json($comparison['actuals']),
                        borderColor: '#10b981',
                        backgroundColor: 'rgba(16, 185, 129, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                    },
                    {
                        label: 'Forecasted Sales',
                        data: @json($comparison['forecasts']),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        tension: 0.4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#999' : '#666',
                            callback: function(value) {
                                return '{{ $selectedBranch->currency }} ' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#333' : '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#999' : '#666'
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#333' : '#e5e7eb'
                        }
                    }
                }
            }
        });

        // Forecast Chart
        const forecastCtx = document.getElementById('forecastChart').getContext('2d');
        new Chart(forecastCtx, {
            type: 'line',
            data: {
                labels: @json($forecasts->pluck('forecast_date')->map(fn($d) => \Carbon\Carbon::parse($d)->format('M d'))),
                datasets: [
                    {
                        label: 'Predicted Sales',
                        data: @json($forecasts->pluck('predicted_sales')),
                        borderColor: '#8b5cf6',
                        backgroundColor: 'rgba(139, 92, 246, 0.1)',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                    },
                    {
                        label: 'Upper Confidence',
                        data: @json($forecasts->pluck('confidence_upper')),
                        borderColor: '#c4b5fd',
                        backgroundColor: 'rgba(196, 181, 253, 0.05)',
                        borderWidth: 1,
                        borderDash: [3, 3],
                        fill: false,
                        tension: 0.4,
                    },
                    {
                        label: 'Lower Confidence',
                        data: @json($forecasts->pluck('confidence_lower')),
                        borderColor: '#c4b5fd',
                        backgroundColor: 'rgba(196, 181, 253, 0.05)',
                        borderWidth: 1,
                        borderDash: [3, 3],
                        fill: false,
                        tension: 0.4,
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        labels: {
                            color: document.documentElement.classList.contains('dark') ? '#fff' : '#000'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#999' : '#666',
                            callback: function(value) {
                                return '{{ $selectedBranch->currency }} ' + value.toLocaleString();
                            }
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#333' : '#e5e7eb'
                        }
                    },
                    x: {
                        ticks: {
                            color: document.documentElement.classList.contains('dark') ? '#999' : '#666'
                        },
                        grid: {
                            color: document.documentElement.classList.contains('dark') ? '#333' : '#e5e7eb'
                        }
                    }
                }
            }
        });
    </script>
    @endpush
</x-app-layout>
