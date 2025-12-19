<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                Welcome back, {{ auth()->user()->name }}! 👋
            </h1>
            <p class="mt-2 text-gray-600 dark:text-gray-400">
                Here's what's happening with your retail analytics today.
            </p>
        </div>

         <!-- Role Info -->
        <div class="mt-6 mb-6 bg-purple-50 dark:bg-purple-900/10 border border-purple-200 dark:border-purple-800 rounded-lg p-6">
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


        <!-- Quick Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Card 1: Total Sales Today -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Sales Today</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            ₱{{ number_format($todaySales, 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    <span class="text-gray-500 dark:text-gray-400">{{ $todayTransactions }} transactions today</span>
                </div>
            </div>

            <!-- Card 2: Transactions -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Transactions</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            {{ number_format($totalTransactions) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    @if($totalTransactions == 0)
                        <span class="text-gray-500 dark:text-gray-400">Import data in Phase 4</span>
                    @else
                        <span class="text-green-600 dark:text-green-400">All time</span>
                    @endif
                </div>
            </div>

            <!-- Card 3: Active Products -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Active Products</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            {{ number_format($productCount) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    @if($productCount == 0)
                        <span class="text-gray-500 dark:text-gray-400">Add products in Phase 3</span>
                    @else
                        <span class="text-green-600 dark:text-green-400">In your catalog</span>
                    @endif
                </div>
            </div>

            <!-- Card 4: Customers -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Customers</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                            {{ number_format($customerCount) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/20 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                </div>
                <div class="mt-4 flex items-center text-sm">
                    @if($customerCount == 0)
                        <span class="text-gray-500 dark:text-gray-400">Imported with transactions</span>
                    @else
                        <span class="text-green-600 dark:text-green-400">In database</span>
                    @endif
                </div>
            </div>
        </div>

        <!-- Additional Stats Row -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Monthly Sales -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Monthly Sales</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                    ₱{{ number_format($monthlySales, 2) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ now()->format('F Y') }}</p>
            </div>

            <!-- YTD Sales -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Year to Date Sales</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                    ₱{{ number_format($ytdSales, 2) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">{{ now()->year }}</p>
            </div>

            <!-- Average Transaction -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Avg Transaction (30d)</p>
                <p class="text-2xl font-bold text-gray-900 dark:text-gray-100 mt-2">
                    ₱{{ number_format($avgTransaction, 2) }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">Last 30 days</p>
            </div>
        </div>

 <!-- Top Branch (Admin Only) -->
        @if(auth()->user()->isAdmin() && $topBranch)
        <div class="bg-gradient-to-r from-purple-500 to-purple-600 dark:from-purple-600 dark:to-purple-700 rounded-lg p-6 mb-8">
            <div class="flex items-center justify-between text-white">
                <div>
                    <p class="text-sm font-medium opacity-90">Top Performing Branch This Month</p>
                    <p class="text-3xl font-bold mt-2">{{ $topBranch->name }}</p>
                    <p class="text-lg mt-1 opacity-90">₱{{ number_format($topBranch->total, 2) }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-lg flex items-center justify-center">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path>
                    </svg>
                </div>
            </div>
        </div>
        @endif

<!-- Charts Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Sales Trend Chart -->
    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Sales Trend (Last 30 Days)
        </h3>
        <div class="relative" style="height: 300px;">
            <canvas id="salesTrendChart"></canvas>
        </div>
    </div>

    <!-- Top Products Chart -->
    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Top 5 Products
        </h3>
        <div class="relative" style="height: 300px;">
            <canvas id="topProductsChart"></canvas>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
    <!-- Payment Methods Chart -->
    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Payment Methods (This Month)
        </h3>
        <div class="relative" style="height: 300px;">
            <canvas id="paymentMethodsChart"></canvas>
        </div>
    </div>

    <!-- Quick Stats Table -->
    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">
            Top Products by Sales
        </h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                <thead>
                    <tr>
                        <th class="px-3 py-2 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Product
                        </th>
                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Qty
                        </th>
                        <th class="px-3 py-2 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                            Sales
                        </th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($topProducts as $product)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100">
                            {{ Str::limit($product->product_name, 25) }}
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                            {{ number_format($product->total_quantity) }}
                        </td>
                        <td class="px-3 py-3 text-sm text-gray-900 dark:text-gray-100 font-medium text-right">
                            ₱{{ number_format($product->total_sales, 2) }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="px-3 py-8 text-center text-gray-500 dark:text-gray-400">
                            No sales data yet. Import transactions to see insights.
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
    // Detect dark mode
    const isDarkMode = document.documentElement.classList.contains('dark');

    // Color palette for dark/light mode
    const colors = {
        text: isDarkMode ? '#fafafa' : '#111827',
        textSecondary: isDarkMode ? '#a3a3a3' : '#6b7280',
        grid: isDarkMode ? '#262626' : '#e5e7eb',
        accent: isDarkMode ? '#a78bfa' : '#8b5cf6',
        success: isDarkMode ? '#34d399' : '#10b981',
        warning: isDarkMode ? '#fbbf24' : '#f59e0b',
        danger: isDarkMode ? '#f87171' : '#ef4444',
        info: isDarkMode ? '#60a5fa' : '#3b82f6',
    };

    // Chart defaults
    Chart.defaults.color = colors.text;
    Chart.defaults.borderColor = colors.grid;
    Chart.defaults.font.family = 'Inter, system-ui, sans-serif';

    // 1. Sales Trend Chart
    const salesTrendCtx = document.getElementById('salesTrendChart');
    if (salesTrendCtx) {
        const salesData = @json($salesTrend);

        new Chart(salesTrendCtx, {
            type: 'line',
            data: {
                labels: salesData.map(d => new Date(d.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric' })),
                datasets: [{
                    label: 'Daily Sales',
                    data: salesData.map(d => d.total),
                    borderColor: colors.accent,
                    backgroundColor: `${colors.accent}20`,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3,
                    pointHoverRadius: 5,
                    pointBackgroundColor: colors.accent,
                    pointBorderColor: isDarkMode ? '#171717' : '#ffffff',
                    pointBorderWidth: 2,
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
                        displayColors: false,
                        callbacks: {
                            label: (context) => `₱${context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: colors.grid, drawBorder: false },
                        ticks: {
                            callback: (value) => '₱' + value.toLocaleString('en-US', { maximumFractionDigits: 0 })
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                    }
                }
            }
        });
    }

    // 2. Top Products Chart
    const topProductsCtx = document.getElementById('topProductsChart');
    if (topProductsCtx) {
        const productsData = @json($topProducts);

        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: productsData.map(p => p.product_name.substring(0, 20)),
                datasets: [{
                    label: 'Sales',
                    data: productsData.map(p => p.total_sales),
                    backgroundColor: [
                        colors.accent,
                        colors.success,
                        colors.info,
                        colors.warning,
                        colors.danger,
                    ],
                    borderRadius: 6,
                    borderSkipped: false,
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
                        displayColors: false,
                        callbacks: {
                            label: (context) => `₱${context.parsed.y.toLocaleString('en-US', { minimumFractionDigits: 2 })}`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: colors.grid, drawBorder: false },
                        ticks: {
                            callback: (value) => '₱' + value.toLocaleString('en-US', { maximumFractionDigits: 0 })
                        }
                    },
                    x: {
                        grid: { display: false, drawBorder: false },
                    }
                }
            }
        });
    }

    // 3. Payment Methods Chart
    const paymentMethodsCtx = document.getElementById('paymentMethodsChart');
    if (paymentMethodsCtx) {
        const paymentData = @json($paymentMethods);

        new Chart(paymentMethodsCtx, {
            type: 'doughnut',
            data: {
                labels: paymentData.map(p => p.payment_method.replace('_', ' ').toUpperCase()),
                datasets: [{
                    data: paymentData.map(p => p.total),
                    backgroundColor: [
                        colors.accent,
                        colors.success,
                        colors.info,
                        colors.warning,
                    ],
                    borderWidth: 0,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 15,
                            usePointStyle: true,
                            pointStyle: 'circle',
                        }
                    },
                    tooltip: {
                        backgroundColor: isDarkMode ? '#262626' : '#ffffff',
                        titleColor: colors.text,
                        bodyColor: colors.text,
                        borderColor: colors.grid,
                        borderWidth: 1,
                        padding: 12,
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
</script>

        {{-- <!-- Phase Status -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Development Progress</h2>
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Phase 1: Foundation & Auth</span>
                    <span class="px-3 py-1 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 text-xs font-medium rounded-full">✓ Complete</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Phase 2: Branch & User Management</span>
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full">Pending</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Phase 3: Products & Inventory</span>
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full">Pending</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Phase 4: Transaction Import</span>
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full">Pending</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Phase 5: Analytics Dashboard</span>
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full">Pending</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-700 dark:text-gray-300">Phase 6: Forecasting & Polish</span>
                    <span class="px-3 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 text-xs font-medium rounded-full">Pending</span>
                </div>
            </div>
        </div> --}}



    </div>
</x-app-layout>
