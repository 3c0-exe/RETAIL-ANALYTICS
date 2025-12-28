<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex flex-col gap-4 mb-6 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100">
                    Custom Report Builder
                </h1>
                <p class="mt-1 text-sm text-gray-600 sm:mt-2 dark:text-gray-400">
                    Build and save custom reports with your preferred metrics and dimensions
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Left Panel: Report Configuration -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Configure Report
                    </h2>

                    <form id="reportConfigForm" class="space-y-6">
                        <!-- Metrics Selection -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Select Metrics (What to Measure)
                            </label>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="metrics[]" value="total_sales" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Total Sales (₱)</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="metrics[]" value="transaction_count" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Transaction Count</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="metrics[]" value="avg_transaction" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Avg Transaction Value</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="metrics[]" value="customer_count" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Customer Count</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="metrics[]" value="product_count" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Products Sold</span>
                                </label>
                            </div>
                        </div>

                        <!-- Dimensions Selection -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Group By (Dimensions)
                            </label>
                            <div class="grid grid-cols-1 gap-3 sm:grid-cols-2">
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="dimensions[]" value="branch" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">By Branch</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="dimensions[]" value="category" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">By Category</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="dimensions[]" value="product" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">By Product</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="dimensions[]" value="date" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">By Date</span>
                                </label>
                                <label class="flex items-center p-3 border border-gray-300 rounded-lg cursor-pointer hover:bg-gray-50 dark:border-gray-700 dark:hover:bg-gray-800">
                                    <input type="checkbox" name="dimensions[]" value="month" class="w-4 h-4 rounded text-primary-600 focus:ring-primary-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">By Month</span>
                                </label>
                            </div>
                        </div>

                        <!-- Date Range -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Date Range
                            </label>
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                <div>
                                    <label class="block mb-1 text-xs text-gray-600 dark:text-gray-400">Start Date</label>
                                    <input type="date" name="start_date" id="startDate" required
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                </div>
                                <div>
                                    <label class="block mb-1 text-xs text-gray-600 dark:text-gray-400">End Date</label>
                                    <input type="date" name="end_date" id="endDate" required
                                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100">
                                </div>
                            </div>
                        </div>

                        <!-- Chart Type -->
                        <div>
                            <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Visualization Type
                            </label>
                            <div class="grid grid-cols-2 gap-3 sm:grid-cols-4">
                                <label class="flex flex-col items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary-500 dark:border-gray-700">
                                    <input type="radio" name="chart_type" value="bar" checked class="sr-only peer">
                                    <svg class="w-8 h-8 mb-1 text-gray-600 peer-checked:text-primary-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                    </svg>
                                    <span class="text-xs font-medium peer-checked:text-primary-600">Bar Chart</span>
                                </label>
                                <label class="flex flex-col items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary-500 dark:border-gray-700">
                                    <input type="radio" name="chart_type" value="line" class="sr-only peer">
                                    <svg class="w-8 h-8 mb-1 text-gray-600 peer-checked:text-primary-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"></path>
                                    </svg>
                                    <span class="text-xs font-medium peer-checked:text-primary-600">Line Chart</span>
                                </label>
                                <label class="flex flex-col items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary-500 dark:border-gray-700">
                                    <input type="radio" name="chart_type" value="pie" class="sr-only peer">
                                    <svg class="w-8 h-8 mb-1 text-gray-600 peer-checked:text-primary-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path>
                                    </svg>
                                    <span class="text-xs font-medium peer-checked:text-primary-600">Pie Chart</span>
                                </label>
                                <label class="flex flex-col items-center p-3 border-2 border-gray-300 rounded-lg cursor-pointer hover:border-primary-500 dark:border-gray-700">
                                    <input type="radio" name="chart_type" value="table" class="sr-only peer">
                                    <svg class="w-8 h-8 mb-1 text-gray-600 peer-checked:text-primary-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18m-9-4v8m-7 0h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                                    </svg>
                                    <span class="text-xs font-medium peer-checked:text-primary-600">Table</span>
                                </label>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col gap-3 sm:flex-row">
                            <button type="button" onclick="generateReport()"
                                    class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                                Generate Report
                            </button>
                            <button type="button" onclick="openSaveModal()"
                                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700">
                                Save Report
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Right Panel: Saved Reports -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                    <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Saved Reports
                    </h2>

                    <div id="savedReportsList" class="space-y-2">
                        <!-- Populated by JavaScript -->
                        <p class="text-sm text-gray-500 dark:text-gray-400">No saved reports yet</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Report Results Section -->
        <div id="reportResults" style="display: none;" class="mt-6 bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Report Results
                </h2>
                <button onclick="exportReport()" class="px-4 py-2 text-sm font-medium text-white bg-green-600 rounded-md hover:bg-green-700">
                    Export CSV
                </button>
            </div>

            <!-- Summary Cards -->
            <div id="summaryCards" class="grid grid-cols-2 gap-4 mb-6 md:grid-cols-4">
                <!-- Populated by JavaScript -->
            </div>

            <!-- Chart Container -->
            <div id="chartContainer" class="mb-6">
                <canvas id="reportChart"></canvas>
            </div>

            <!-- Data Table -->
            <div id="tableContainer" class="overflow-x-auto">
                <table id="reportTable" class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                    <!-- Populated by JavaScript -->
                </table>
            </div>
        </div>
    </div>

    <!-- Save Report Modal -->
    <div id="saveReportModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75" onclick="closeSaveModal()"></div>

            <div class="relative z-50 w-full max-w-md p-6 bg-white rounded-lg dark:bg-gray-800">
                <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Save Report
                </h3>

                <form id="saveReportForm" onsubmit="saveReport(event)">
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Report Name
                        </label>
                        <input type="text" id="reportName" required
                               class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100"
                               placeholder="My Custom Report">
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Report Type
                        </label>
                        <select id="reportType" class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            <option value="sales">Sales Report</option>
                            <option value="customers">Customer Report</option>
                            <option value="products">Product Report</option>
                        </select>
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                            Save
                        </button>
                        <button type="button" onclick="closeSaveModal()" class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600">
                            Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0"></script>

    <script>
        let currentReportData = null;
        let reportChart = null;

        // Set default dates
        document.getElementById('endDate').valueAsDate = new Date();
        const startDate = new Date();
        startDate.setMonth(startDate.getMonth() - 3);
        document.getElementById('startDate').valueAsDate = startDate;

        // Generate Report
        async function generateReport() {
            const form = document.getElementById('reportConfigForm');
            const formData = new FormData(form);

            const metrics = formData.getAll('metrics[]');
            const dimensions = formData.getAll('dimensions[]');

            if (metrics.length === 0) {
                alert('Please select at least one metric');
                return;
            }

            if (dimensions.length === 0) {
                alert('Please select at least one dimension');
                return;
            }

            const config = {
                metrics: metrics,
                dimensions: dimensions,
                date_range: {
                    start: formData.get('start_date'),
                    end: formData.get('end_date')
                },
                chart_type: formData.get('chart_type')
            };

            try {
                const response = await fetch('/reports/generate', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify(config)
                });

                const data = await response.json();
                currentReportData = data;
                displayReport(data);
            } catch (error) {
                console.error('Error generating report:', error);
                alert('Failed to generate report. Please try again.');
            }
        }

        // Display Report
        function displayReport(data) {
            document.getElementById('reportResults').style.display = 'block';

            // Display summary
            displaySummary(data.data.summary);

            // Display chart or table
            if (data.config.chart_type === 'table') {
                document.getElementById('chartContainer').style.display = 'none';
                displayTable(data.data.results, data.config);
            } else {
                document.getElementById('chartContainer').style.display = 'block';
                displayChart(data.data.results, data.config);
                displayTable(data.data.results, data.config);
            }
        }

        // Display Summary Cards
        function displaySummary(summary) {
            const container = document.getElementById('summaryCards');
            const metricLabels = {
                total_sales: 'Total Sales',
                transaction_count: 'Transactions',
                avg_transaction: 'Avg Transaction',
                customer_count: 'Customers',
                product_count: 'Products Sold'
            };

            container.innerHTML = Object.entries(summary).map(([key, value]) => `
                <div class="p-4 border border-gray-200 rounded-lg dark:border-gray-700">
                    <p class="text-xs text-gray-600 dark:text-gray-400">${metricLabels[key]}</p>
                    <p class="mt-1 text-xl font-bold text-gray-900 dark:text-gray-100">
                        ${key === 'total_sales' || key === 'avg_transaction' ? '₱' : ''}${value.toLocaleString()}
                    </p>
                </div>
            `).join('');
        }

        // Display Chart
// Display Chart
function displayChart(results, config) {
    if (reportChart) {
        reportChart.destroy();
    }

    const ctx = document.getElementById('reportChart');
    const isDark = document.documentElement.classList.contains('dark');

    const labels = results.map(r => {
        if (r.branch_name) return r.branch_name;
        if (r.category_name) return r.category_name;
        if (r.product_name) return r.product_name;
        if (r.date) return r.date;
        if (r.month) return r.month;
        return 'Unknown';
    });

    // Extended color palette with 30+ distinct colors
    const colors = [
        '#3b82f6', '#10b981', '#f59e0b', '#8b5cf6', '#ef4444',
        '#06b6d4', '#ec4899', '#14b8a6', '#f97316', '#6366f1',
        '#84cc16', '#f43f5e', '#0ea5e9', '#a855f7', '#22c55e',
        '#eab308', '#d946ef', '#06b6d4', '#fb923c', '#8b5cf6',
        '#4ade80', '#fbbf24', '#c026d3', '#38bdf8', '#a3e635',
        '#fb7185', '#2dd4bf', '#facc15', '#c084fc', '#34d399',
        '#fca5a5', '#67e8f9', '#bef264', '#f9a8d4', '#5eead4'
    ];

    const datasets = config.metrics.map((metric, index) => {
        // For pie charts, use different colors for each data point
        if (config.chart_type === 'pie') {
            return {
                label: metric.replace('_', ' ').toUpperCase(),
                data: results.map(r => r[metric] || 0),
                backgroundColor: results.map((_, i) => colors[i % colors.length]),
                borderColor: '#ffffff',
                borderWidth: 2
            };
        } else {
            return {
                label: metric.replace('_', ' ').toUpperCase(),
                data: results.map(r => r[metric] || 0),
                backgroundColor: colors[index % colors.length],
                borderColor: colors[index % colors.length],
                borderWidth: 2
            };
        }
    });

    reportChart = new Chart(ctx, {
        type: config.chart_type,
        data: { labels, datasets },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    labels: {
                        color: isDark ? '#a3a3a3' : '#6b7280'
                    }
                }
            },
            scales: config.chart_type !== 'pie' ? {
                y: {
                    beginAtZero: true,
                    ticks: { color: isDark ? '#a3a3a3' : '#6b7280' },
                    grid: { color: isDark ? '#262626' : '#e5e7eb' }
                },
                x: {
                    ticks: { color: isDark ? '#a3a3a3' : '#6b7280' },
                    grid: { display: false }
                }
            } : {}
        }
    });
}

        // Display Table
        function displayTable(results, config) {
            const container = document.getElementById('reportTable');
            const headers = [];

            if (results[0]) {
                Object.keys(results[0]).forEach(key => {
                    headers.push(key.replace('_', ' ').toUpperCase());
                });
            }

            container.innerHTML = `
                <thead class="bg-gray-50 dark:bg-gray-900">
                    <tr>
                        ${headers.map(h => `<th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">${h}</th>`).join('')}
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200 dark:bg-[#171717] dark:divide-gray-800">
                    ${results.map(row => `
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                            ${Object.values(row).map(val => `<td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">${val !== null ? val : 'N/A'}</td>`).join('')}
                        </tr>
                    `).join('')}
                </tbody>
            `;
        }

        // Save Report Modal
        function openSaveModal() {
            if (!currentReportData) {
                alert('Please generate a report first');
                return;
            }
            document.getElementById('saveReportModal').classList.remove('hidden');
        }

        function closeSaveModal() {
            document.getElementById('saveReportModal').classList.add('hidden');
        }

// Save Report
        async function saveReport(event) {
            event.preventDefault();

            console.log('Save button clicked');
            console.log('Current report data:', currentReportData);

            if (!currentReportData) {
                alert('Please generate a report first');
                return;
            }

            const name = document.getElementById('reportName').value;
            const type = document.getElementById('reportType').value;

            console.log('Saving report:', { name, type, config: currentReportData.config });

            try {
                const response = await fetch('/reports/save', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        name: name,
                        type: type,
                        config: currentReportData.config,
                        is_favorite: false
                    })
                });

                console.log('Response status:', response.status);
                const data = await response.json();
                console.log('Response data:', data);

                if (!response.ok) {
                    throw new Error(data.error || 'Failed to save report');
                }

                alert('Report saved successfully!');
                closeSaveModal();
                await loadSavedReports();
            } catch (error) {
                console.error('Error saving report:', error);
                alert('Failed to save report: ' + error.message);
            }
        }

        // Load Saved Reports
// Load Saved Reports
async function loadSavedReports() {
    console.log('Loading saved reports...');
    try {
        const response = await fetch('/reports/list', {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        console.log('Load reports response status:', response.status);

        if (!response.ok) {
            throw new Error('Failed to load saved reports');
        }

        const savedReports = await response.json();
        console.log('Loaded reports:', savedReports);

        const container = document.getElementById('savedReportsList');

        if (savedReports.length === 0) {
            container.innerHTML = '<p class="text-sm text-gray-500 dark:text-gray-400">No saved reports yet</p>';
            return;
        }

        container.innerHTML = savedReports.map(report => `
            <div class="p-3 border border-gray-200 rounded-lg dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-800">
                <div class="flex items-start justify-between">
                    <div class="flex-1 cursor-pointer" onclick="loadSavedReport(${report.id})">
                        <h3 class="text-sm font-medium text-gray-900 dark:text-gray-100">${report.name}</h3>
                        <p class="text-xs text-gray-500 dark:text-gray-400">${report.type}</p>
                        <p class="mt-1 text-xs text-gray-400 dark:text-gray-500">${new Date(report.created_at).toLocaleDateString()}</p>
                    </div>
                    <button onclick="deleteReport(${report.id}, event)" class="p-1 text-red-500 hover:text-red-700">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        `).join('');
    } catch (error) {
        console.error('Error loading saved reports:', error);
    }
}

// Load a saved report
async function loadSavedReport(reportId) {
    try {
        const response = await fetch(`/reports/saved/${reportId}`, {
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load report');
        }

        const data = await response.json();

        // Apply the saved configuration to the form
        const config = data.report.config;

        // Set metrics
        document.querySelectorAll('input[name="metrics[]"]').forEach(checkbox => {
            checkbox.checked = config.metrics.includes(checkbox.value);
        });

        // Set dimensions
        document.querySelectorAll('input[name="dimensions[]"]').forEach(checkbox => {
            checkbox.checked = config.dimensions.includes(checkbox.value);
        });

        // Set date range
        document.getElementById('startDate').value = config.date_range.start;
        document.getElementById('endDate').value = config.date_range.end;

        // Set chart type
        document.querySelector(`input[name="chart_type"][value="${config.chart_type}"]`).checked = true;

        // Display the report data
        currentReportData = {
            data: data.data,
            config: config
        };
        displayReport(currentReportData);

        alert('Report loaded successfully!');
    } catch (error) {
        console.error('Error loading report:', error);
        alert('Failed to load report: ' + error.message);
    }
}

// Delete a saved report
async function deleteReport(reportId, event) {
    event.stopPropagation();

    if (!confirm('Are you sure you want to delete this report?')) {
        return;
    }

    try {
        const response = await fetch(`/reports/saved/${reportId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            throw new Error('Failed to delete report');
        }

        alert('Report deleted successfully!');
        loadSavedReports();
    } catch (error) {
        console.error('Error deleting report:', error);
        alert('Failed to delete report: ' + error.message);
    }
}

// Load saved reports on page load
document.addEventListener('DOMContentLoaded', function() {
    loadSavedReports();
});

        // Export Report
        function exportReport() {
            if (!currentReportData) return;

            const csv = convertToCSV(currentReportData.data.results);
            const blob = new Blob([csv], { type: 'text/csv' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = `custom_report_${new Date().toISOString()}.csv`;
            a.click();
        }

        function convertToCSV(data) {
            if (!data || data.length === 0) return '';

            const headers = Object.keys(data[0]).join(',');
            const rows = data.map(row => Object.values(row).join(','));

            return [headers, ...rows].join('\n');
        }
    </script>
</x-app-layout>
