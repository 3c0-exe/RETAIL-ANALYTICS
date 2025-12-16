<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                {{ __('Import Details') }}
            </h2>
            <a href="{{ route('admin.imports.index') }}" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
                ← Back to Imports
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto space-y-6 max-w-7xl sm:px-6 lg:px-8">

            @if(session('success'))
                <div class="p-4 text-green-700 bg-green-100 border border-green-400 rounded-md dark:bg-green-900/30 dark:border-green-600 dark:text-green-400">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="p-4 text-red-700 bg-red-100 border border-red-400 rounded-md dark:bg-red-900/30 dark:border-red-600 dark:text-red-400">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Import Summary Card -->
            <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                <div class="p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">File Name</h4>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $import->file_name }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Branch</h4>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">{{ $import->branch->name ?? 'N/A' }}</p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                            <p class="mt-1">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400',
                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400',
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400',
                                    ];
                                @endphp
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full {{ $statusColors[$import->status] ?? '' }}">
                                    {{ ucfirst($import->status) }}
                                </span>
                            </p>
                        </div>
                        <div>
                            <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Progress</h4>
                            <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100">
                                @if($import->total_rows > 0)
                                    {{ $import->successful_rows }} / {{ $import->total_rows }}
                                @else
                                    Not processed yet
                                @endif
                            </p>
                            @if($import->total_rows > 0)
                                <div class="w-full h-2 mt-2 bg-gray-200 rounded-full dark:bg-gray-700">
                                    <div class="h-2 transition-all duration-300 bg-purple-600 rounded-full" style="width: {{ ($import->successful_rows / $import->total_rows) * 100 }}%"></div>
                                </div>
                            @endif
                        </div>
                    </div>

                    @if($import->status === 'pending')
                        <div class="mt-6">
                            <form action="{{ route('admin.imports.process', $import) }}" method="POST" class="inline" onsubmit="return confirm('Process this import? This action cannot be undone.');">
                                @csrf
                                <button type="submit" class="px-6 py-2 font-medium text-white transition bg-purple-600 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                    🚀 Process Import
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Error Log -->
            @if($import->failed_rows > 0 && $import->errors)
                <div class="border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:border-red-800">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold text-red-900 dark:text-red-400">
                            ⚠️ Errors ({{ $import->failed_rows }} rows failed)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-red-200 dark:divide-red-800">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-red-700 uppercase dark:text-red-300">Row</th>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-red-700 uppercase dark:text-red-300">Error</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-red-200 dark:divide-red-800">
                                    @foreach($import->errors as $error)
                                        <tr>
                                            <td class="px-4 py-2 text-sm text-red-900 dark:text-red-300">{{ $error['row'] ?? 'N/A' }}</td>
                                            <td class="px-4 py-2 text-sm text-red-800 dark:text-red-400">{{ $error['message'] ?? 'Unknown error' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Preview Data -->
            @if($import->status === 'pending' && !empty($previewData))
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            📊 Data Preview (First 50 rows)
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        @if(!empty($previewData[0]))
                                            @foreach($previewData[0] as $column)
                                                <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">
                                                    {{ $column }}
                                                </th>
                                            @endforeach
                                        @endif
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach(array_slice($previewData, 1) as $row)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            @foreach($row as $value)
                                                <td class="px-4 py-2 text-gray-900 dark:text-gray-100 whitespace-nowrap">
                                                    {{ $value }}
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Imported Transactions -->
            @if($import->status === 'completed' && !empty($transactions) && $transactions->count() > 0)
                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            ✅ Recent Transactions from this Import
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-900">
                                    <tr>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Transaction Code</th>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Date</th>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Customer</th>
                                        <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Items</th>
                                        <th class="px-4 py-2 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                    @foreach($transactions as $transaction)
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                                {{ $transaction->transaction_code }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transaction->transaction_date->format('M d, Y') }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transaction->customer->name ?? 'Walk-in' }}
                                            </td>
                                            <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">
                                                {{ $transaction->items->count() }}
                                            </td>
                                            <td class="px-4 py-2 text-sm font-medium text-right text-gray-900 dark:text-gray-100">
                                                ₱{{ number_format($transaction->total, 2) }}
                                            </td>
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
</x-app-layout>
