<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Sales Analytics
                </h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">
                    Comprehensive sales insights and performance metrics
                </p>
            </div>
            <button onclick="window.print()" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md font-medium text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                Print Report
            </button>
        </div>

        <!-- Filters Section -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <form method="GET" action="{{ route('analytics.sales') }}" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
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

                    <!-- Branch Filter (Admin Only) -->
                    @if(auth()->user()->isAdmin())
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Branch
                        </label>
                        <select name="branch_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
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
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Category
                        </label>
                        <select name="category_id" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100">
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
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md font-medium text-sm">
                        Apply Filters
                    </button>
                    <a href="{{ route('analytics.sales') }}" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 dark:bg-gray-700 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 rounded-md font-medium text-sm">
                        Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Export Dropdown -->
        <div class="flex justify-end mb-4">
            <div x-data="{ open: false }" class="relative">
                <button @click="open = !open" type="button" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-md font-medium text-sm flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Export as CSV
                        </button>
                    </form>

                    <form method="POST" action="{{ route('export.sales.excel') }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Export as Excel
                        </button>
                    </form>

                    <form method="POST" action="{{ route('export.sales.pdf') }}">
                        @csrf
                        <input type="hidden" name="start_date" value="{{ request('start_date') }}">
                        <input type="hidden" name="end_date" value="{{ request('end_date') }}">
                        <input type="hidden" name="branch_id" value="{{ request('branch_id') }}">
                        <button type="submit" class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                            Export as PDF
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- 1. SALES BY BRANCH -->
        @if(auth()->user()->isAdmin())
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Sales by Branch
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Branch
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Transactions
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Sales
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Avg Transaction
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Growth
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($salesByBranch as $branch)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $branch->name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                {{ number_format($branch->transaction_count) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                ₱{{ number_format($branch->total_sales, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                ₱{{ number_format($branch->avg_transaction, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium
                                    {{ $branch->growth >= 0 ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300' : 'bg-red-100 dark:bg-red-900/20 text-red-700 dark:text-red-300' }}">
                                    @if($branch->growth >= 0)
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                        </svg>
                                    @else
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                        </svg>
                                    @endif
                                    {{ number_format(abs($branch->growth), 1) }}%
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No sales data for selected period
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Branch Chart -->
            <div class="mt-6" style="height: 300px;">
                <canvas id="branchChart"></canvas>
            </div>
        </div>
        @endif

        <!-- 2. SALES BY CATEGORY -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Sales by Category
            </h2>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- Category Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead>
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                    Category
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                    Units
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                    Sales
                                </th>
                                <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">
                                    %
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($salesByCategory->take(10) as $category)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                    {{ $category->category_name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                    {{ number_format($category->total_quantity) }}
                                </td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                    ₱{{ number_format($category->total_sales, 2) }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                    {{ number_format($category->percentage, 1) }}%
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                    No category data available
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Category Chart -->
                <div style="height: 300px;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
        </div>

        <!-- 3. TOP 20 PRODUCTS -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Top 20 Products
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                SKU
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Product
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Category
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Units Sold
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Revenue
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Avg Margin
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($topProducts as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm font-mono text-gray-600 dark:text-gray-400">
                                {{ $product->sku }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ Str::limit($product->product_name, 40) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                {{ $product->category_name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                {{ number_format($product->units_sold) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                ₱{{ number_format($product->revenue, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-right">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    {{ $product->avg_margin > 0 ? 'bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-800 text-gray-600 dark:text-gray-400' }}">
                                    ₱{{ number_format($product->avg_margin, 2) }}
                                </span>
                            </td>
                        </tr>
                        @empty

<tr>
                            <td colspan="6" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No product data available
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- 4. SALES HEATMAP -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Sales Heatmap (Hour × Day of Week)
            </h2>
            <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
                Identify peak sales hours and days. Darker colors indicate higher sales.
            </p>

            <div class="overflow-x-auto">
                <div class="inline-block min-w-full">
                    <div class="grid grid-cols-25 gap-1">
                        <!-- Header Row (Hours) -->
                        <div class="col-span-1"></div>
                        @for($hour = 0; $hour < 24; $hour++)
                            <div class="text-center text-xs text-gray-600 dark:text-gray-400 pb-2">
                                {{ str_pad($hour, 2, '0', STR_PAD_LEFT) }}
                            </div>
                        @endfor

                        <!-- Data Rows (Days) -->
                        @php
                            $days = ['', 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
                        @endphp
                        @for($day = 1; $day <= 7; $day++)
                            <div class="text-right text-xs text-gray-600 dark:text-gray-400 pr-2 flex items-center justify-end">
                                {{ $days[$day] }}
                            </div>
                            @for($hour = 0; $hour < 24; $hour++)
                                @php
                                    $cell = $heatmap[$day][$hour] ?? ['sales' => 0, 'count' => 0];
                                    $intensity = $maxSales > 0 ? ($cell['sales'] / $maxSales) : 0;
                                    $opacity = max(0.1, $intensity);
                                @endphp
                                <div
                                    class="aspect-square rounded cursor-pointer transition-all hover:ring-2 hover:ring-primary-500"
                                    style="background-color: rgba(139, 92, 246, {{ $opacity }})"
                                    title="₱{{ number_format($cell['sales'], 2) }} ({{ $cell['count'] }} transactions)"
                                    onclick="showHeatmapDetail('{{ $days[$day] }}', {{ $hour }}, {{ $cell['sales'] }}, {{ $cell['count'] }})">
                                </div>
                            @endfor
                        @endfor
                    </div>

                    <!-- Legend -->
                    <div class="flex items-center justify-center gap-2 mt-6">
                        <span class="text-xs text-gray-600 dark:text-gray-400">Low</span>
                        <div class="flex gap-1">
                            @for($i = 1; $i <= 5; $i++)
                                <div class="w-6 h-6 rounded" style="background-color: rgba(139, 92, 246, {{ $i * 0.2 }})"></div>
                            @endfor
                        </div>
                        <span class="text-xs text-gray-600 dark:text-gray-400">High</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- 5. SALES BY CASHIER -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 mb-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">
                Top Performers (Cashiers)
            </h2>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Rank
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Cashier
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Transactions
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Total Sales
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                Avg Transaction
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($salesByCashier as $index => $cashier)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-4 py-3 text-sm font-bold text-gray-900 dark:text-gray-100">
                                @if($index === 0)
                                    🥇
                                @elseif($index === 1)
                                    🥈
                                @elseif($index === 2)
                                    🥉
                                @else
                                    {{ $index + 1 }}
                                @endif
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $cashier->cashier_name }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                {{ number_format($cashier->transaction_count) }}
                            </td>
                            <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100 text-right">
                                ₱{{ number_format($cashier->total_sales, 2) }}
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 text-right">
                                ₱{{ number_format($cashier->avg_transaction, 2) }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                                No cashier data available
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
            accent: isDarkMode ? '#a78bfa' : '#8b5cf6',
            success: isDarkMode ? '#34d399' : '#10b981',
            warning: isDarkMode ? '#fbbf24' : '#f59e0b',
            danger: isDarkMode ? '#f87171' : '#ef4444',
            info: isDarkMode ? '#60a5fa' : '#3b82f6',
        };

        Chart.defaults.color = colors.text;
        Chart.defaults.borderColor = colors.grid;
        Chart.defaults.font.family = 'Inter, system-ui, sans-serif';

        // Branch Chart (Admin only)
        @if(auth()->user()->isAdmin())
        const branchCtx = document.getElementById('branchChart');
        if (branchCtx) {
            const branchData = @json($salesByBranch);
            new Chart(branchCtx, {
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
        @endif

        // Category Chart
        const categoryCtx = document.getElementById('categoryChart');
        if (categoryCtx) {
            const categoryData = @json($salesByCategory->take(5));
            new Chart(categoryCtx, {
                type: 'doughnut',
                data: {
                    labels: categoryData.map(c => c.category_name),
                    datasets: [{
                        data: categoryData.map(c => c.total_sales),
                        backgroundColor: [
                            colors.accent,
                            colors.success,
                            colors.info,
                            colors.warning,
                            colors.danger,
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
                                    const percentage = categoryData[context.dataIndex].percentage;
                                    return `${context.label}: ₱${context.parsed.toLocaleString('en-US', { minimumFractionDigits: 2 })} (${percentage.toFixed(1)}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        // Heatmap detail popup
        function showHeatmapDetail(day, hour, sales, count) {
            if (count === 0) return;
            const time = `${String(hour).padStart(2, '0')}:00`;
            alert(`${day} at ${time}\nSales: ₱${sales.toLocaleString('en-US', { minimumFractionDigits: 2 })}\nTransactions: ${count}`);
        }
    </script>
</x-app-layout>
