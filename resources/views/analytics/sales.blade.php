<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Sales Analytics
                </h1>
                <p class="mt-2 text-sm sm:text-base text-gray-600 dark:text-gray-400">
                    Comprehensive sales insights and performance metrics
                </p>
            </div>
            <button onclick="window.print()"
                    class="inline-flex items-center justify-center px-4 py-2 font-medium text-white transition-all duration-200 rounded-md bg-primary-600 hover:bg-primary-700 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Report
            </button>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <form method="GET" action="{{ route('analytics.sales') }}" class="space-y-4" id="filterForm">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <!-- Date Range -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Start Date
                        </label>
                        <input type="date" name="start_date" id="startDate"
                               value="{{ $startDate->format('Y-m-d') }}"
                               class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            End Date
                        </label>
                        <input type="date" name="end_date" id="endDate"
                               value="{{ $endDate->format('Y-m-d') }}"
                               class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                    </div>

                    <!-- Branch Filter (Admin Only) -->
                    @if(auth()->user()->isAdmin())
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Branch
                        </label>
                        <select name="branch_id" id="branchId" class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Category Filter -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category
                        </label>
                        <select name="category_id" class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col sm:flex-row gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                        Apply Filters
                    </button>
                    <a href="{{ route('analytics.sales') }}" class="px-4 py-2 text-sm font-medium text-center text-gray-900 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 dark:text-gray-100">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Export Dropdown -->
        <div class="flex justify-end mb-4">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" type="button"
                        class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-all duration-200 bg-green-600 rounded-md hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export
                </button>

                <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-md shadow-lg py-1 z-10">
                    <form method="POST" action="{{ route('export.sales.csv') }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">
                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Export as CSV
                        </button>
                    </form>

                    <form method="POST" action="{{ route('export.sales.excel') }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">
                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Export as Excel
                        </button>
                    </form>

                    <form method="POST" action="{{ route('export.sales.pdf') }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">
                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Export as PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 1. SALES BY BRANCH -->
        @if(auth()->user()->isAdmin())
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <h2 class="mb-4 text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">
                Sales by Branch
            </h2>

            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead>
                            <tr>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Branch</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">Trans.</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400 hidden sm:table-cell">Total Sales</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400 hidden md:table-cell">Avg Trans.</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400 hidden lg:table-cell">Growth</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($salesByBranch as $branch)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-3 sm:px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $branch->name }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ number_format($branch->transaction_count) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100 hidden sm:table-cell">₱{{ number_format($branch->total_sales, 2) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400 hidden md:table-cell">₱{{ number_format($branch->avg_transaction, 2) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-right hidden lg:table-cell">
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $branch->growth >= 0 ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300' }}">
                                        @if($branch->growth >= 0)
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                                        @else
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                                        @endif
                                        {{ number_format(abs($branch->growth), 1) }}%
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No sales data for selected period</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mt-6" style="height: 250px;">
                <canvas id="branchChart" data-chart-data='@json($salesByBranch)'></canvas>
            </div>
        </div>
        @endif

        <!-- 2. SALES BY CATEGORY -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <h2 class="mb-4 text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">Sales by Category</h2>
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead>
                                <tr>
                                    <th class="px-3 sm:px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Category</th>
                                    <th class="px-3 sm:px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400 hidden sm:table-cell">Units</th>
                                    <th class="px-3 sm:px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Sales</th>
                                    <th class="px-3 sm:px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">%</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($salesByCategory->take(10) as $category)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-3 sm:px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ Str::limit($category->category_name, 20) }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400 hidden sm:table-cell">{{ number_format($category->total_quantity) }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100">₱{{ number_format($category->total_sales, 2) }}</td>
                                    <td class="px-3 sm:px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ number_format($category->percentage, 1) }}%</td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No category data available</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                <div style="height: 250px;"><canvas id="categoryChart" data-chart-data='@json($salesByCategory->take(5))' ></canvas></div>
            </div>
        </div>

        <!-- 3. TOP 20 PRODUCTS -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <h2 class="mb-4 text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">Top 20 Products</h2>
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead>
                            <tr>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Product</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400 hidden lg:table-cell">Category</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">Units</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">Revenue</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400 hidden md:table-cell">Margin</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($topProducts as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-3 sm:px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ Str::limit($product->product_name, 30) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right hidden lg:table-cell">{{ $product->category_name }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400">{{ number_format($product->units_sold) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100">₱{{ number_format($product->revenue, 0) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-right hidden md:table-cell">
                                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $product->avg_margin > 0 ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400' }}">
                                        ₱{{ number_format($product->avg_margin, 2) }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No product data available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- 4. SALES HEATMAP - MOBILE OPTIMIZED -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <h2 class="mb-3 text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">
                Sales Heatmap (Hour × Day)
            </h2>
            <p class="mb-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                Tap any cell to see detailed breakdown. Darker colors = higher sales.
            </p>

            <!-- Mobile: Scrollable container -->
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-max px-4 sm:px-0">
                    <!-- Hour labels -->
                    <div class="flex mb-2">
                        <div class="w-10 sm:w-12"></div>
                        <div class="flex gap-1">
                            @for($hour = 0; $hour < 24; $hour++)
                                <div class="w-6 sm:w-8 md:w-10 text-center text-[10px] sm:text-xs text-gray-600 dark:text-gray-400 font-medium">
                                    {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}
                                </div>
                            @endfor
                        </div>
                    </div>

                    <!-- Day rows -->
                    @php $days = ['', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat']; @endphp
                    @for($day = 1; $day <= 7; $day++)
                        <div class="flex items-center mb-1">
                            <!-- Day label -->
                            <div class="w-10 sm:w-12 pr-2 text-right text-xs sm:text-sm font-medium text-gray-600 dark:text-gray-400">
                                {{ $days[$day] }}
                            </div>
                            <!-- Hour cells -->
                            <div class="flex gap-1">
                                @for($hour = 0; $hour < 24; $hour++)
                                    @php
                                        $cell = $heatmap[$day][$hour] ?? ['sales' => 0, 'count' => 0];
                                        $intensity = $maxSales > 0 ? ($cell['sales'] / $maxSales) : 0;
                                        $opacity = max(0.1, $intensity);
                                    @endphp
                                    <div
                                        class="w-6 h-6 sm:w-8 sm:h-8 md:w-10 md:h-10 rounded cursor-pointer transition-all hover:ring-2 hover:ring-primary-500 active:scale-95"
                                        style="background-color: rgba(139, 92, 246, {{ $opacity }})"
                                        onclick="openHeatmapModal({{ $day }}, {{ $hour }}, '{{ $days[$day] }}')">
                                    </div>
                                @endfor
                            </div>
                        </div>
                    @endfor

                    <!-- Legend -->
                    <div class="flex items-center justify-center gap-2 mt-4">
                        <span class="text-[10px] sm:text-xs text-gray-600 dark:text-gray-400">Low</span>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="w-5 h-5 sm:w-6 sm:h-6 rounded" style="background-color: rgba(139, 92, 246, {{ $i * 0.2 }})"></div>
                            @endfor
                        </div>
                        <span class="text-[10px] sm:text-xs text-gray-600 dark:text-gray-400">High</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. SALES BY CASHIER -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 mb-6">
            <h2 class="mb-4 text-lg sm:text-xl font-semibold text-gray-900 dark:text-gray-100">Top Performers (Cashiers)</h2>
            <div class="overflow-x-auto -mx-4 sm:mx-0">
                <div class="inline-block min-w-full align-middle">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead>
                            <tr>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Rank</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Cashier</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400 hidden sm:table-cell">Trans.</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">Total Sales</th>
                                <th class="px-3 sm:px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400 hidden md:table-cell">Avg Trans.</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($salesByCashier as $index => $cashier)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-3 sm:px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-100">
                                    @if($index === 0) 🥇 @elseif($index === 1) 🥈 @elseif($index === 2) 🥉 @else {{ $index + 1 }} @endif
                                </td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-gray-900 dark:text-gray-100">{{ Str::limit($cashier->cashier_name, 25) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400 hidden sm:table-cell">{{ number_format($cashier->transaction_count) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm font-medium text-right text-gray-900 dark:text-gray-100">₱{{ number_format($cashier->total_sales, 0) }}</td>
                                <td class="px-3 sm:px-4 py-3 text-sm text-right text-gray-600 dark:text-gray-400 hidden md:table-cell">₱{{ number_format($cashier->avg_transaction, 2) }}</td>
                            </tr>
                            @empty
                            <tr><td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">No cashier data available</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- HEATMAP DETAIL MODAL - FIXED POSITIONING -->
    <div id="heatmapModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
            <!-- Background overlay -->
            <div class="fixed inset-0 transition-opacity bg-gray-500/75 dark:bg-gray-900/75" onclick="closeHeatmapModal()"></div>

            <!-- Center alignment helper -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <!-- Modal panel - FIXED -->
            <div class="relative inline-block w-full max-w-lg px-4 pt-5 pb-4 overflow-hidden text-left align-bottom transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:my-8 sm:align-middle sm:p-6">
                <!-- Header -->
                <div class="flex items-start justify-between mb-4">
                    <h3 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100" id="modalTitle">
                        Sales Detail
                    </h3>
                    <button onclick="closeHeatmapModal()" class="text-gray-400 hover:text-gray-500 dark:hover:text-gray-300 -mr-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Content -->
                <div id="modalContent" class="space-y-4">
                    <!-- Loading spinner -->
                    <div class="flex items-center justify-center py-8">
                        <svg class="w-8 h-8 text-primary-600 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
    <script>
        let branchChart, categoryChart;

        function getThemeColors() {
            const isDarkMode = document.documentElement.classList.contains('dark');
            return {
                text: isDarkMode ? '#fafafa' : '#111827',
                textSecondary: isDarkMode ? '#a3a3a3' : '#6b7280',
                grid: isDarkMode ? '#262626' : '#e5e7eb',
                accent: isDarkMode ? '#a78bfa' : '#8b5cf6',
                success: isDarkMode ? '#34d399' : '#10b981',
                info: isDarkMode ? '#60a5fa' : '#3b82f6',
                warning: isDarkMode ? '#fbbf24' : '#f59e0b',
                danger: isDarkMode ? '#f87171' : '#ef4444',
                tooltipBg: isDarkMode ? '#262626' : '#ffffff',
                tooltipBorder: isDarkMode ? '#404040' : '#e5e7eb',
            };
        }

        function updateChartsTheme() {
            const colors = getThemeColors();
            Chart.defaults.color = colors.text;
            Chart.defaults.borderColor = colors.grid;

            [branchChart, categoryChart].forEach(chart => {
                if (!chart) return;
                if (chart.options.scales) {
                    Object.keys(chart.options.scales).forEach(scale => {
                        if (chart.options.scales[scale].ticks) {
                            chart.options.scales[scale].ticks.color = colors.text;
                        }
                        if (chart.options.scales[scale].grid) {
                            chart.options.scales[scale].grid.color = colors.grid;
                        }
                    });
                }
                if (chart.options.plugins?.tooltip) {
                    chart.options.plugins.tooltip.backgroundColor = colors.tooltipBg;
                    chart.options.plugins.tooltip.titleColor = colors.text;
                    chart.options.plugins.tooltip.bodyColor = colors.text;
                    chart.options.plugins.tooltip.borderColor = colors.tooltipBorder;
                }
                if (chart.options.plugins?.legend?.labels) {
                    chart.options.plugins.legend.labels.color = colors.text;
                }
                chart.update('none');
            });
        }

        function initializeSalesCharts() {
            const colors = getThemeColors();
            const isMobile = window.innerWidth < 640;
            const isTablet = window.innerWidth >= 640 && window.innerWidth < 1024;

            Chart.defaults.color = colors.text;
            Chart.defaults.borderColor = colors.grid;
            Chart.defaults.font.family = 'Inter, system-ui, -apple-system, sans-serif';
            Chart.defaults.font.size = isMobile ? 10 : isTablet ? 11 : 12;

            if (branchChart) branchChart.destroy();
            if (categoryChart) categoryChart.destroy();

            const commonOptions = {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    tooltip: {
                        backgroundColor: colors.tooltipBg,
                        titleColor: colors.text,
                        bodyColor: colors.text,
                        borderColor: colors.tooltipBorder,
                        borderWidth: 1,
                        padding: isMobile ? 8 : 10,
                        titleFont: { size: isMobile ? 11 : 12 },
                        bodyFont: { size: isMobile ? 10 : 11 },
                    }
                }
            };

            // Branch Chart
            const branchCtx = document.getElementById('branchChart');
            if (branchCtx) {
                const branchData = JSON.parse(branchCtx.dataset.chartData || '[]');
                branchChart = new Chart(branchCtx, {
                    type: 'bar',
                    data: {
                        labels: branchData.map(b => b.name),
                        datasets: [{
                            label: 'Total Sales',
                            data: branchData.map(b => b.total_sales),
                            backgroundColor: colors.accent,
                            borderRadius: 6,
                            borderSkipped: false,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        indexAxis: isMobile ? 'y' : 'x',
                        plugins: {
                            ...commonOptions.plugins,
                            legend: { display: false },
                            tooltip: {
                                ...commonOptions.plugins.tooltip,
                                callbacks: {
                                    label: (ctx) => {
                                        const val = isMobile ? ctx.parsed.x : ctx.parsed.y;
                                        return `₱${val.toLocaleString('en-US', {minimumFractionDigits: 2})}`;
                                    }
                                }
                            }
                        },
                        scales: isMobile ? {
                            x: {
                                beginAtZero: true,
                                grid: { color: colors.grid },
                                ticks: { color: colors.text, font: { size: 9 }, callback: (v) => '₱' + (v/1000).toFixed(0) + 'k' }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { color: colors.text, font: { size: 9 } }
                            }
                        } : {
                            y: {
                                beginAtZero: true,
                                grid: { color: colors.grid },
                                ticks: { color: colors.text, font: { size: 10 }, callback: (v) => '₱' + v.toLocaleString() }
                            },
                            x: {
                                grid: { display: false },
                                ticks: { color: colors.text, font: { size: 10 }, maxRotation: 0 }
                            }
                        }
                    }
                });
            }

            // Category Chart
            const categoryCtx = document.getElementById('categoryChart');
            if (categoryCtx) {
                const categoryData = JSON.parse(categoryCtx.dataset.chartData || '[]');
                categoryChart = new Chart(categoryCtx, {
                    type: 'doughnut',
                    data: {
                        labels: categoryData.map(c => c.category_name),
                        datasets: [{
                            data: categoryData.map(c => c.total_sales),
                            backgroundColor: [colors.accent, colors.success, colors.info, colors.warning, colors.danger],
                            borderWidth: 0,
                        }]
                    },
                    options: {
                        ...commonOptions,
                        plugins: {
                            ...commonOptions.plugins,
                            legend: {
                                position: isMobile ? 'bottom' : 'right',
                                labels: {
                                    padding: isMobile ? 6 : 12,
                                    usePointStyle: true,
                                    pointStyle: 'circle',
                                    font: { size: isMobile ? 9 : 10 },
                                    color: colors.text,
                                }
                            },
                            tooltip: {
                                ...commonOptions.plugins.tooltip,
                                callbacks: {
                                    label: (ctx) => {
                                        const total = ctx.dataset.data.reduce((a,b) => a+b, 0);
                                        const pct = ((ctx.parsed / total) * 100).toFixed(1);
                                        return `${ctx.label}: ₱${ctx.parsed.toLocaleString('en-US', {minimumFractionDigits: 2})} (${pct}%)`;
                                    }
                                }
                            }
                        }
                    }
                });
            }
        }

        // Heatmap Modal Functions
        function openHeatmapModal(day, hour, dayName) {
            const modal = document.getElementById('heatmapModal');
            const title = document.getElementById('modalTitle');
            const content = document.getElementById('modalContent');

            const timeStr = String(hour).padStart(2, '0') + ':00';
            title.textContent = `${dayName} at ${timeStr}`;
            modal.classList.remove('hidden');

            content.innerHTML = `
                <div class="flex items-center justify-center py-8">
                    <svg class="w-8 h-8 text-primary-600 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            `;

            const startDate = document.getElementById('startDate').value;
            const endDate = document.getElementById('endDate').value;
            const branchIdEl = document.getElementById('branchId');
            const branchId = branchIdEl ? branchIdEl.value : '';

            fetch(`/analytics/sales/heatmap-detail?day=${day}&hour=${hour}&start_date=${startDate}&end_date=${endDate}&branch_id=${branchId}`)
                .then(response => response.json())
                .then(data => {
                    if (data.transactionCount === 0) {
                        content.innerHTML = `
                            <div class="py-8 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                                <p class="text-sm">No sales during this time period</p>
                            </div>
                        `;
                        return;
                    }

                    content.innerHTML = `
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-4">
                            <div class="p-3 text-center rounded-lg bg-purple-50 dark:bg-purple-900/20">
                                <p class="mb-1 text-xs text-gray-600 dark:text-gray-400">Total Sales</p>
                                <p class="text-lg sm:text-xl font-bold text-purple-600 dark:text-purple-400">₱${data.totalSales.toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                            </div>
                            <div class="p-3 text-center rounded-lg bg-blue-50 dark:bg-blue-900/20">
                                <p class="mb-1 text-xs text-gray-600 dark:text-gray-400">Transactions</p>
                                <p class="text-lg sm:text-xl font-bold text-blue-600 dark:text-blue-400">${data.transactionCount}</p>
                            </div>
                            <div class="p-3 text-center rounded-lg bg-green-50 dark:bg-green-900/20">
                                <p class="mb-1 text-xs text-gray-600 dark:text-gray-400">Avg Transaction</p>
                                <p class="text-lg sm:text-xl font-bold text-green-600 dark:text-green-400">₱${data.avgTransaction.toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                            </div>
                        </div>

                        <div>
                            <h4 class="mb-3 text-sm font-semibold text-gray-900 dark:text-gray-100">Top 3 Products</h4>
                            ${data.topProducts.length > 0 ? `
                                <div class="space-y-2">
                                    ${data.topProducts.map((product, index) => `
                                        <div class="flex items-center justify-between p-2 sm:p-3 rounded-lg bg-gray-50 dark:bg-gray-900/50">
                                            <div class="flex items-center gap-2 flex-1 min-w-0">
                                                <span class="flex-shrink-0 flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-purple-600 rounded-full">${index + 1}</span>
                                                <div class="min-w-0 flex-1">
                                                    <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100 truncate">${product.name}</p>
                                                    <p class="text-[10px] sm:text-xs text-gray-500 dark:text-gray-400">${product.total_qty} units</p>
                                                </div>
                                            </div>
                                            <p class="text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-100 ml-2">₱${product.total_revenue.toLocaleString('en-US', {minimumFractionDigits: 2})}</p>
                                        </div>
                                    `).join('')}
                                </div>
                            ` : '<p class="py-4 text-sm text-center text-gray-500 dark:text-gray-400">No product data available</p>'}
                        </div>
                    `;
                })
                .catch(error => {
                    console.error('Error:', error);
                    content.innerHTML = `
                        <div class="py-8 text-center text-red-600 dark:text-red-400">
                            <p class="text-sm">Failed to load details. Please try again.</p>
                        </div>
                    `;
                });
        }

        function closeHeatmapModal() {
            document.getElementById('heatmapModal').classList.add('hidden');
        }

        // Watch for theme changes
        const observer = new MutationObserver((mutations) => {
            mutations.forEach((mutation) => {
                if (mutation.attributeName === 'class') {
                    updateChartsTheme();
                }
            });
        });
        observer.observe(document.documentElement, { attributes: true });

        // Initialize on load
        document.addEventListener('DOMContentLoaded', initializeSalesCharts);

        // Reinitialize on resize (debounced)
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(initializeSalesCharts, 250);
        });
    </script>
</x-app-layout>
