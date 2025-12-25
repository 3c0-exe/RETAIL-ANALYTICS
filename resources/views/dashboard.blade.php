<x-app-layout>
    <div class="mx-auto max-w-7xl">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="mt-2 text-sm text-gray-600 sm:text-base dark:text-gray-400">
                Here's what's happening with your retail analytics today.
            </p>
        </div>

        <!-- Filters Section -->
        <div class="mb-6 bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4">
            <form method="GET" action="{{ route('dashboard') }}" class="space-y-4">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Date Range Filter -->
                    <div class="w-full">
                        <label for="date_range" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Time Period
                        </label>
                        <select name="date_range" id="date_range"
                                onchange="this.form.submit()"
                                class="w-full px-3 py-2 text-sm border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <option value="today" {{ $dateRange === 'today' ? 'selected' : '' }}>Today</option>
                            <option value="last_7_days" {{ $dateRange === 'last_7_days' ? 'selected' : '' }}>Last 7 Days</option>
                            <option value="last_30_days" {{ $dateRange === 'last_30_days' ? 'selected' : '' }}>Last 30 Days</option>
                            <option value="this_month" {{ $dateRange === 'this_month' ? 'selected' : '' }}>This Month</option>
                            <option value="last_month" {{ $dateRange === 'last_month' ? 'selected' : '' }}>Last Month</option>
                            <option value="this_year" {{ $dateRange === 'this_year' ? 'selected' : '' }}>This Year</option>
                        </select>
                    </div>

                    <!-- Branch Filter (Admin Only) -->
                    @if(auth()->user()->isAdmin())
                    <div class="w-full">
                        <label for="branch_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Branch
                        </label>
                        <select name="branch_id" id="branch_id"
                                onchange="this.form.submit()"
                                class="w-full px-3 py-2 text-sm border-gray-300 rounded-md shadow-sm dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 focus:border-purple-500 focus:ring-purple-500">
                            <option value="all" {{ $selectedBranchId === 'all' ? 'selected' : '' }}>All Branches</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @endif

                    <!-- Active Filter Display -->
                    <div class="flex items-end w-full">
                        <div class="text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                            <span class="font-medium">Showing:</span>
                            <span class="block mt-1 text-gray-900 dark:text-gray-100">
                                {{ $startDate->format('M d, Y') }} - {{ $endDate->format('M d, Y') }}
                            </span>
                            @if(auth()->user()->isAdmin() && $selectedBranchId !== 'all')
                                <span class="inline-block px-2 py-1 mt-1 text-xs text-purple-700 bg-purple-100 rounded dark:bg-purple-900/20 dark:text-purple-300">
                                    {{ $branches->firstWhere('id', $selectedBranchId)?->name }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Top Branch (Admin Only) -->
        @if(auth()->user()->isAdmin() && $topBranch && $selectedBranchId === 'all')
        <div class="p-4 mb-6 rounded-lg sm:p-6 bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700">
            <div class="flex flex-col items-start gap-4 text-white sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-xs font-medium opacity-90 sm:text-sm">🏆 Top Performing Branch (Selected Period)</p>
                    <p class="mt-2 text-2xl font-bold sm:text-3xl">{{ $topBranch->name }}</p>
                    <p class="mt-1 text-base opacity-90 sm:text-lg">₱{{ number_format($topBranch->total, 2) }}</p>
                </div>
                <div class="flex items-center justify-center w-12 h-12 rounded-lg sm:w-16 sm:h-16 bg-white/20">
                    <svg class="w-6 h-6 sm:w-8 sm:h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 lg:grid-cols-4 sm:gap-6">
            <!-- Card 1: Total Sales Today -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Total Sales Today</p>
                        <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                            ₱{{ number_format($todaySales, 2) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg sm:w-12 sm:h-12 dark:bg-purple-900/20">
                        <svg class="w-5 h-5 text-purple-600 sm:w-6 sm:h-6 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center mt-3 text-xs sm:text-sm">
                    <span class="text-gray-500 dark:text-gray-400">{{ $todayTransactions }} transactions today</span>
                </div>
            </div>

            <!-- Card 2: Period Sales -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Period Sales</p>
                        <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                            ₱{{ number_format($periodSales, 2) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-10 h-10 bg-blue-100 rounded-lg sm:w-12 sm:h-12 dark:bg-blue-900/20">
                        <svg class="w-5 h-5 text-blue-600 sm:w-6 sm:h-6 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center mt-3 text-xs sm:text-sm">
                    <span class="text-gray-500 dark:text-gray-400">
                        {{ ucfirst(str_replace('_', ' ', $dateRange)) }}
                    </span>
                </div>
            </div>

            <!-- Card 3: Active Products -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Active Products</p>
                        <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                            {{ number_format($productCount) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-10 h-10 bg-green-100 rounded-lg sm:w-12 sm:h-12 dark:bg-green-900/20">
                        <svg class="w-5 h-5 text-green-600 sm:w-6 sm:h-6 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center mt-3 text-xs sm:text-sm">
                    @if($productCount == 0)
                        <span class="text-gray-500 dark:text-gray-400">Add products in Phase 3</span>
                    @else
                        <span class="text-green-600 dark:text-green-400">In your catalog</span>
                    @endif
                </div>
            </div>

            <!-- Card 4: Avg Transaction -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Avg Transaction</p>
                        <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                            ₱{{ number_format($avgTransaction, 2) }}
                        </p>
                    </div>
                    <div class="flex items-center justify-center w-10 h-10 bg-purple-100 rounded-lg sm:w-12 sm:h-12 dark:bg-purple-900/20">
                        <svg class="w-5 h-5 text-purple-600 sm:w-6 sm:h-6 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                </div>
                <div class="flex items-center mt-3 text-xs sm:text-sm">
                    <span class="text-gray-500 dark:text-gray-400">Selected period</span>
                </div>
            </div>
        </div>

        <!-- Additional Stats Row -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 md:grid-cols-3 sm:gap-6">
            <!-- Monthly Sales -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Monthly Sales</p>
                <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                    ₱{{ number_format($monthlySales, 2) }}
                </p>
                <p class="mt-2 text-xs text-gray-500 sm:text-sm dark:text-gray-400">{{ now()->format('F Y') }}</p>
            </div>

            <!-- YTD Sales -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Year to Date Sales</p>
                <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                    ₱{{ number_format($ytdSales, 2) }}
                </p>
                <p class="mt-2 text-xs text-gray-500 sm:text-sm dark:text-gray-400">{{ now()->year }}</p>
            </div>

            <!-- Total Transactions -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <p class="text-xs font-medium text-gray-600 sm:text-sm dark:text-gray-400">Total Transactions</p>
                <p class="mt-2 text-xl font-bold text-gray-900 sm:text-2xl dark:text-gray-100">
                    {{ number_format($totalTransactions) }}
                </p>
                <p class="mt-2 text-xs text-gray-500 sm:text-sm dark:text-gray-400">All time</p>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-4 mb-6 sm:gap-6 lg:grid-cols-2">
            <!-- Sales Trend Chart -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <h3 class="mb-4 text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">
                    Sales Trend ({{ ucfirst(str_replace('_', ' ', $dateRange)) }})
                </h3>
                <div class="relative" style="height: 250px;">
                    <canvas id="salesTrendChart"></canvas>
                </div>
            </div>

            <!-- Top Products Chart -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <h3 class="mb-4 text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">
                    Top 5 Products ({{ ucfirst(str_replace('_', ' ', $dateRange)) }})
                </h3>
                <div class="relative" style="height: 250px;">
                    <canvas id="topProductsChart"></canvas>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-4 mb-6 sm:gap-6 lg:grid-cols-2">
            <!-- Payment Methods Chart -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <h3 class="mb-4 text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">
                    Payment Methods ({{ ucfirst(str_replace('_', ' ', $dateRange)) }})
                </h3>
                <div class="relative" style="height: 250px;">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>

            <!-- Quick Stats Table -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <h3 class="mb-4 text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">
                    Top Products by Sales
                </h3>
                <div class="overflow-x-auto -mx-4 sm:mx-0">
                    <div class="inline-block min-w-full align-middle">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                            <thead>
                                <tr>
                                    <th class="px-3 py-2 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                                        Product
                                    </th>
                                    <th class="px-3 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">
                                        Qty
                                    </th>
                                    <th class="px-3 py-2 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">
                                        Sales
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                                @forelse($topProducts as $product)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                    <td class="px-3 py-3 text-xs text-gray-900 sm:text-sm dark:text-gray-100">
                                        {{ Str::limit($product->product_name, 25) }}
                                    </td>
                                    <td class="px-3 py-3 text-xs text-right text-gray-600 sm:text-sm dark:text-gray-400">
                                        {{ number_format($product->total_quantity) }}
                                    </td>
                                    <td class="px-3 py-3 text-xs font-medium text-right text-gray-900 sm:text-sm dark:text-gray-100">
                                        ₱{{ number_format($product->total_sales, 2) }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="px-3 py-8 text-xs text-center text-gray-500 sm:text-sm dark:text-gray-400">
                                        No sales data for selected period. Try a different date range.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Info -->
        <div class="p-4 border border-purple-200 rounded-lg sm:p-6 bg-purple-50 dark:bg-purple-900/10 dark:border-purple-800">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-purple-900 dark:text-purple-100">Your Role: {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</h3>
                    <p class="mt-1 text-xs text-purple-700 sm:text-sm dark:text-purple-300">
                        @if(auth()->user()->isAdmin())
                            You have full access to all features including branch and user management.
                        @elseif(auth()->user()->isBranchManager())
                            You can manage your branch ({{ auth()->user()->branch->name }}) and view branch-specific reports.
                        @elseif(auth()->user()->isAnalyst())
                            You can view analytics and reports across all branches.
                        @else
                            You can view reports for {{ auth()->user()->branch->name }}.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Scripts with Mobile-Responsive Configuration -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>
        <script>
          // ========================================
            // DYNAMIC DARK MODE CHART CONFIGURATION
            // Phase 9.2 - Auto-updates on theme toggle
            // ========================================

            // Store chart instances globally so we can update them
            let salesTrendChart, topProductsChart, paymentMethodsChart;

            // Function to get current theme colors
            function getThemeColors() {
                const isDarkMode = document.documentElement.classList.contains('dark');
                return {
                    text: isDarkMode ? '#fafafa' : '#111827',
                    textSecondary: isDarkMode ? '#a3a3a3' : '#6b7280',
                    grid: isDarkMode ? '#262626' : '#e5e7eb',
                    accent: isDarkMode ? '#a78bfa' : '#8b5cf6',
                    success: isDarkMode ? '#34d399' : '#10b981',
                    warning: isDarkMode ? '#fbbf24' : '#f59e0b',
                    danger: isDarkMode ? '#f87171' : '#ef4444',
                    info: isDarkMode ? '#60a5fa' : '#3b82f6',
                    tooltipBg: isDarkMode ? '#262626' : '#ffffff',
                    tooltipBorder: isDarkMode ? '#404040' : '#e5e7eb',
                };
            }

            // Function to update all charts with new theme
            function updateChartsTheme() {
                const colors = getThemeColors();

                // Update Chart.js defaults
                Chart.defaults.color = colors.text;
                Chart.defaults.borderColor = colors.grid;

                // Update each chart instance
                [salesTrendChart, topProductsChart, paymentMethodsChart].forEach(chart => {
                    if (!chart) return;

                    // Update scales
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

                    // Update tooltip colors
                    if (chart.options.plugins?.tooltip) {
                        chart.options.plugins.tooltip.backgroundColor = colors.tooltipBg;
                        chart.options.plugins.tooltip.titleColor = colors.text;
                        chart.options.plugins.tooltip.bodyColor = colors.text;
                        chart.options.plugins.tooltip.borderColor = colors.tooltipBorder;
                    }

                    // Update legend colors
                    if (chart.options.plugins?.legend?.labels) {
                        chart.options.plugins.legend.labels.color = colors.text;
                    }

                    // Re-render chart
                    chart.update('none'); // 'none' for instant update without animation
                });
            }

            // Initialize charts (call this where your current chart code is)
            function initializeCharts() {
                const colors = getThemeColors();
                const isMobile = window.innerWidth < 768;
                const isTablet = window.innerWidth >= 768 && window.innerWidth < 1024;

                // Chart.js global defaults
                Chart.defaults.color = colors.text;
                Chart.defaults.borderColor = colors.grid;
                Chart.defaults.font.family = 'Inter, system-ui, -apple-system, sans-serif';
                Chart.defaults.font.size = isMobile ? 11 : 12;

                // Responsive scale configuration
                const responsiveScales = {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: colors.grid,
                            drawBorder: false,
                            lineWidth: 1
                        },
                        ticks: {
                            color: colors.text,
                            font: { size: isMobile ? 10 : 11 },
                            padding: isMobile ? 4 : 8,
                            maxTicksLimit: isMobile ? 5 : 8,
                        }
                    },
                    x: {
                        grid: {
                            display: false,
                            drawBorder: false
                        },
                        ticks: {
                            color: colors.text,
                            font: { size: isMobile ? 10 : 11 },
                            maxRotation: isMobile ? 45 : 0,
                            minRotation: 0,
                            padding: isMobile ? 4 : 8,
                        }
                    }
                };

                // Destroy existing charts before recreating
                if (salesTrendChart) salesTrendChart.destroy();
                if (topProductsChart) topProductsChart.destroy();
                if (paymentMethodsChart) paymentMethodsChart.destroy();

                // 1. Sales Trend Chart
                const salesTrendCtx = document.getElementById('salesTrendChart');
                if (salesTrendCtx) {
                    const salesData = @json($salesTrend);
                    salesTrendChart = new Chart(salesTrendCtx, {
                        type: 'line',
                        data: {
                            labels: salesData.map(d => {
                                const date = new Date(d.date);
                                return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                            }),
                            datasets: [{
                                label: 'Daily Sales',
                                data: salesData.map(d => d.total),
                                borderColor: colors.accent,
                                backgroundColor: `${colors.accent}20`,
                                borderWidth: 2,
                                tension: 0.4,
                                fill: true,
                                pointRadius: 3,
                                pointHoverRadius: isMobile ? 6 : 5,
                                pointBackgroundColor: colors.accent,
                                pointBorderColor: colors.tooltipBg,
                                pointBorderWidth: 2,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: {
                                mode: 'index',
                                intersect: false,
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: colors.tooltipBg,
                                    titleColor: colors.text,
                                    bodyColor: colors.text,
                                    borderColor: colors.tooltipBorder,
                                    borderWidth: 1,
                                    padding: isMobile ? 12 : 10,
                                    callbacks: {
                                        label: (context) => `₱${context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })}`
                                    }
                                }
                            },
                            scales: {
                                y: {
                                    ...responsiveScales.y,
                                    ticks: {
                                        ...responsiveScales.y.ticks,
                                        callback: (value) => '₱' + value.toLocaleString('en-US', { maximumFractionDigits: 0 })
                                    }
                                },
                                x: responsiveScales.x
                            }
                        }
                    });
                }

                // 2. Top Products Chart
                const topProductsCtx = document.getElementById('topProductsChart');
                if (topProductsCtx) {
                    const productsData = @json($topProducts);
                    topProductsChart = new Chart(topProductsCtx, {
                        type: 'bar',
                        data: {
                            labels: productsData.map(p => {
                                const name = p.product_name;
                                return isMobile ? name.substring(0, 15) + (name.length > 15 ? '...' : '') : name.substring(0, 20);
                            }),
                            datasets: [{
                                label: 'Sales',
                                data: productsData.map(p => p.total_sales),
                                backgroundColor: [colors.accent, colors.success, colors.info, colors.warning, colors.danger],
                                borderRadius: 6,
                                borderSkipped: false,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            indexAxis: isMobile ? 'y' : 'x',
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: colors.tooltipBg,
                                    titleColor: colors.text,
                                    bodyColor: colors.text,
                                    borderColor: colors.tooltipBorder,
                                    borderWidth: 1,
                                    callbacks: {
                                        label: (context) => `₱${context.parsed.x || context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })}`
                                    }
                                }
                            },
                            scales: isMobile ? {
                                x: {
                                    ...responsiveScales.y,
                                    ticks: {
                                        ...responsiveScales.y.ticks,
                                        callback: (value) => '₱' + value.toLocaleString('en-US', { maximumFractionDigits: 0 })
                                    }
                                },
                                y: responsiveScales.x
                            } : {
                                y: {
                                    ...responsiveScales.y,
                                    ticks: {
                                        ...responsiveScales.y.ticks,
                                        callback: (value) => '₱' + value.toLocaleString('en-US', { maximumFractionDigits: 0 })
                                    }
                                },
                                x: responsiveScales.x
                            }
                        }
                    });
                }

                // 3. Payment Methods Chart
                const paymentMethodsCtx = document.getElementById('paymentMethodsChart');
                if (paymentMethodsCtx) {
                    const paymentData = @json($paymentMethods);
                    paymentMethodsChart = new Chart(paymentMethodsCtx, {
                        type: 'doughnut',
                        data: {
                            labels: paymentData.map(p => p.payment_method.replace('_', ' ').toUpperCase()),
                            datasets: [{
                                data: paymentData.map(p => p.total),
                                backgroundColor: [colors.accent, colors.success, colors.info, colors.warning],
                                borderWidth: 0,
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            plugins: {
                                legend: {
                                    position: isMobile ? 'bottom' : 'right',
                                    labels: {
                                        padding: isMobile ? 8 : 15,
                                        usePointStyle: true,
                                        pointStyle: 'circle',
                                        font: { size: isMobile ? 10 : 11 },
                                        color: colors.text,
                                    }
                                },
                                tooltip: {
                                    backgroundColor: colors.tooltipBg,
                                    titleColor: colors.text,
                                    bodyColor: colors.text,
                                    borderColor: colors.tooltipBorder,
                                    borderWidth: 1,
                                    callbacks: {
                                        label: (context) => {
                                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                            const percentage = ((context.parsed / total) * 100).toFixed(1);
                                            return `${context.label}: ₱${context.parsed.toLocaleString('en-US', { minimumFractionDigits: 2 })} (${percentage}%)`;
                                        }
                                    }
                                }
                            }
                        }
                    });
                }
            }

            // Listen for theme changes (customize this based on your theme toggle implementation)
            // Option 1: If you're using a custom event
            document.addEventListener('theme-changed', () => {
                updateChartsTheme();
            });

            // Option 2: Watch for class changes on html element
            const observer = new MutationObserver((mutations) => {
                mutations.forEach((mutation) => {
                    if (mutation.attributeName === 'class') {
                        updateChartsTheme();
                    }
                });
            });
            observer.observe(document.documentElement, { attributes: true });

            // Initialize charts on page load
            document.addEventListener('DOMContentLoaded', initializeCharts);

            // Re-initialize on window resize for responsiveness
            let resizeTimer;
            window.addEventListener('resize', () => {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(initializeCharts, 250);
            });
        </script>

        <!-- Role Info - Moved to bottom -->
        <div class="p-6 mt-8 border border-purple-200 rounded-lg bg-purple-50 dark:bg-purple-900/10 dark:border-purple-800">
            <div class="flex items-start gap-3">
                <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-semibold text-purple-900 dark:text-purple-100">Your Role: {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}</h3>
                    <p class="mt-1 text-sm text-purple-700 dark:text-purple-300">
                        @if(auth()->user()->isAdmin())
                            You have full access to all features including branch and user management.
                        @elseif(auth()->user()->isBranchManager())
                            You can manage your branch ({{ auth()->user()->branch->name }}) and view branch-specific reports.
                        @elseif(auth()->user()->isAnalyst())
                            You can view analytics and reports across all branches.
                        @else
                            You can view reports for {{ auth()->user()->branch->name }}.
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
