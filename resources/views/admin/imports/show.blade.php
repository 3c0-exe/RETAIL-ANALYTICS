<x-app-layout>
    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8 sm:py-6">
        {{-- ============================================================== --}}
        {{-- 1. FULL PAGE SKELETON (Visible on Load)                        --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="space-y-6 animate-pulse">

            <div class="flex items-center justify-between mb-6">
                <div class="w-48 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                <div class="w-20 h-8 bg-gray-200 rounded dark:bg-gray-800"></div>
            </div>

            <div class="mb-8">
                <div class="w-64 h-8 mb-2 bg-gray-200 rounded dark:bg-gray-800"></div>
                <div class="w-48 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    @for($i=0; $i<4; $i++)
                        <div class="space-y-3">
                            <div class="flex items-center gap-2">
                                <div class="w-4 h-4 bg-gray-200 rounded-full dark:bg-gray-800"></div>
                                <div class="w-20 h-3 bg-gray-200 rounded dark:bg-gray-800"></div>
                            </div>
                            <div class="w-32 h-5 bg-gray-200 rounded dark:bg-gray-800"></div>
                        </div>
                    @endfor
                </div>
                <div class="pt-6 mt-8 border-t border-gray-200 dark:border-gray-800">
                    <div class="w-40 h-10 bg-gray-200 rounded dark:bg-gray-800"></div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 space-y-4">
                <div class="w-40 h-6 mb-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                @for($j=0; $j<5; $j++)
                    <div class="w-full h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                @endfor
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden transition-opacity duration-500 ease-in-out opacity-0">
            <nav class="flex items-center justify-between mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-xs text-gray-700 transition-colors sm:text-sm hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">
                        <svg class="w-3 h-3 sm:w-3.5 sm:h-3.5 mr-1.5 sm:mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        <span class="hidden sm:inline">Dashboard</span>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-3 h-3 mx-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 6 10">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('admin.imports.index') }}" class="text-xs text-gray-700 transition-colors sm:text-sm hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400">
                            Imports
                        </a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-3 h-3 mx-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 6 10">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="text-xs font-medium text-gray-500 sm:text-sm dark:text-gray-400">Details</span>
                    </div>
                </li>
            </ol>

            <a href="{{ route('admin.imports.index') }}"
               class="inline-flex items-center justify-center gap-2 px-3 sm:px-4 py-2 text-xs sm:text-sm font-semibold text-gray-700 bg-white hover:bg-gray-50 active:bg-gray-100 dark:text-gray-300 dark:bg-[#0a0a0a] dark:hover:bg-[#1a1a1a] dark:border dark:border-gray-800 rounded-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]">
                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 14 10">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
                </svg>
                <span class="hidden sm:inline">Back</span>
            </a>
        </nav>

        <div class="mb-6 sm:mb-8">
            <h1 class="text-xl font-bold text-gray-900 sm:text-2xl lg:text-3xl dark:text-gray-100">Import Details</h1>
            <p class="mt-1 text-xs text-gray-600 sm:text-sm dark:text-gray-400">View and manage import information</p>
        </div>

        <div class="space-y-4 sm:space-y-6">
            <div id="DetailSkeleton" class="space-y-4 animate-pulse sm:space-y-6">
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 sm:gap-6">
                        @for($i=0; $i<4; $i++)
                        <div class="space-y-2">
                            <div class="w-20 h-3 bg-gray-200 rounded sm:h-4 dark:bg-gray-700 sm:w-24"></div>
                            <div class="h-5 bg-gray-200 rounded sm:h-6 dark:bg-gray-700 w-28 sm:w-32"></div>
                        </div>
                        @endfor
                    </div>
                    <div class="mt-6 bg-gray-200 rounded h-9 sm:h-10 dark:bg-gray-700 w-36 sm:w-40"></div>
                </div>
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 space-y-3 sm:space-y-4">
                    <div class="w-40 h-5 mb-4 bg-gray-200 rounded sm:h-6 dark:bg-gray-700 sm:w-48"></div>
                    <div class="space-y-2 sm:space-y-3">
                        @for($j=0; $j<5; $j++)
                        <div class="w-full bg-gray-200 rounded h-7 sm:h-8 dark:bg-gray-700"></div>
                        @endfor
                    </div>
                </div>
            </div>

            <div id="RealDetailContent" class="hidden space-y-4 transition-opacity duration-500 opacity-0 sm:space-y-6">

                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg shadow-sm overflow-hidden">
                    <div class="p-4 sm:p-6">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4 sm:gap-6">
                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="flex-shrink-0 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                                    </svg>
                                    <h4 class="text-xs font-medium text-gray-500 sm:text-sm dark:text-gray-400">File Name</h4>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 truncate sm:text-base dark:text-gray-100" title="{{ $import->file_name }}">
                                    {{ $import->file_name }}
                                </p>
                            </div>

                            <div class="min-w-0">
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="flex-shrink-0 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <h4 class="text-xs font-medium text-gray-500 sm:text-sm dark:text-gray-400">Branch</h4>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 truncate sm:text-base dark:text-gray-100" title="{{ $import->branch->name ?? 'N/A' }}">
                                    {{ $import->branch->name ?? 'N/A' }}
                                </p>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="flex-shrink-0 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    <h4 class="text-xs font-medium text-gray-500 sm:text-sm dark:text-gray-400">Status</h4>
                                </div>
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                        'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                        'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                        'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                    ];
                                @endphp
                                <span class="inline-flex px-2.5 py-1 text-xs sm:text-sm font-semibold rounded-full {{ $statusColors[$import->status] ?? '' }}">
                                    {{ ucfirst($import->status) }}
                                </span>
                            </div>

                            <div>
                                <div class="flex items-center gap-2 mb-2">
                                    <svg class="flex-shrink-0 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                    </svg>
                                    <h4 class="text-xs font-medium text-gray-500 sm:text-sm dark:text-gray-400">Progress</h4>
                                </div>
                                <p class="text-sm font-semibold text-gray-900 sm:text-base dark:text-gray-100">
                                    @if($import->total_rows > 0)
                                        {{ $import->successful_rows }} / {{ $import->total_rows }}
                                    @else
                                        Not processed yet
                                    @endif
                                </p>
                                @if($import->total_rows > 0)
                                    <div class="w-full h-2 mt-2 overflow-hidden bg-gray-200 rounded-full dark:bg-gray-700">
                                        <div class="h-2 transition-all duration-300 rounded-full bg-primary-600" style="width: {{ ($import->successful_rows / $import->total_rows) * 100 }}%"></div>
                                    </div>
                                    @if($import->failed_rows > 0)
                                        <p class="text-xs text-red-600 dark:text-red-400 mt-1.5 font-medium">{{ $import->failed_rows }} failed</p>
                                    @endif
                                @endif
                            </div>
                        </div>

                        @if($import->status === 'pending')
                            <div class="pt-6 mt-6 border-t border-gray-200 dark:border-gray-800">
                                <button type="button"
                                        onclick="openProcessModal()"
                                        class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 active:bg-primary-800 rounded-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98] shadow-sm hover:shadow">
                                    <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                    </svg>
                                    <span>Process Import</span>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                @if($import->failed_rows > 0 && $import->errors)
                    <div class="overflow-hidden border border-red-200 rounded-lg shadow-sm bg-red-50 dark:bg-red-900/10 dark:border-red-800">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="flex-shrink-0 w-5 h-5 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                </svg>
                                <h3 class="text-base font-semibold text-red-900 sm:text-lg dark:text-red-400">
                                    Errors ({{ $import->failed_rows }} rows failed)
                                </h3>
                            </div>

                            <div class="space-y-3 md:hidden">
                                @foreach($import->errors as $error)
                                    <div class="bg-white dark:bg-[#0a0a0a] border border-red-200 dark:border-red-800 rounded-lg p-3">
                                        <div class="flex items-start justify-between gap-2 mb-2">
                                            <span class="text-xs font-medium text-red-700 dark:text-red-300">Row {{ $error['row'] ?? 'N/A' }}</span>
                                        </div>
                                        <p class="text-sm text-red-800 dark:text-red-400">{{ $error['message'] ?? 'Unknown error' }}</p>
                                    </div>
                                @endforeach
                            </div>

                            <div class="hidden overflow-x-auto md:block">
                                <table class="min-w-full divide-y divide-red-200 dark:divide-red-800">
                                    <thead class="bg-red-100 dark:bg-red-900/20">
                                        <tr>
                                            <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-red-700 uppercase dark:text-red-300">Row</th>
                                            <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-red-700 uppercase dark:text-red-300">Error Message</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white dark:bg-[#0a0a0a] divide-y divide-red-200 dark:divide-red-800">
                                        @foreach($import->errors as $error)
                                            <tr class="transition-colors hover:bg-red-50 dark:hover:bg-red-900/10">
                                                <td class="px-4 py-3 text-sm font-medium text-red-900 dark:text-red-300">{{ $error['row'] ?? 'N/A' }}</td>
                                                <td class="px-4 py-3 text-sm text-red-800 dark:text-red-400">{{ $error['message'] ?? 'Unknown error' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if($import->status === 'pending' && !empty($previewData))
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="flex-shrink-0 w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <h3 class="text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">Data Preview</h3>
                            </div>
                            <div class="-mx-4 overflow-x-auto sm:-mx-6">
                                <div class="inline-block min-w-full px-4 align-middle sm:px-6">
                                    <table class="min-w-full text-xs divide-y divide-gray-200 sm:text-sm dark:divide-gray-800">
                                        <thead class="bg-gray-50 dark:bg-[#0a0a0a]">
                                            <tr>
                                                @if(!empty($previewData[0]))
                                                    @foreach($previewData[0] as $column)
                                                        <th class="px-3 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase sm:px-4 dark:text-gray-400 whitespace-nowrap">{{ $column }}</th>
                                                    @endforeach
                                                @endif
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-[#171717] dark:divide-gray-800">
                                            @forelse(array_slice($previewData ?? [], 1) as $row)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition-colors">
                                                    @foreach($row as $value)
                                                        <td class="px-3 py-3 text-gray-900 sm:px-4 dark:text-gray-100 whitespace-nowrap">{{ $value }}</td>
                                                    @endforeach
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="100" class="px-4 py-8 text-sm text-center text-gray-500 dark:text-gray-400">
                                                        No preview data available
                                                    </td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

                @if($import->status === 'completed' && !empty($transactions))
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="p-4 sm:p-6">
                            <div class="flex items-center gap-2 mb-4">
                                <svg class="flex-shrink-0 w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <h3 class="text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">Recent Transactions</h3>
                            </div>

                            <div class="space-y-3 md:hidden">
                                @forelse($transactions ?? [] as $transaction)
                                    <div class="bg-gray-50 dark:bg-[#0a0a0a] border border-gray-200 dark:border-gray-800 rounded-lg p-3">
                                        <div class="flex items-start justify-between mb-2">
                                            <div class="flex-1 min-w-0">
                                                <p class="text-sm font-semibold text-gray-900 truncate dark:text-gray-100">{{ $transaction->transaction_code }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $transaction->timestamp?->format('M d, Y') ?? 'N/A' }}</p>
                                            </div>
                                            <span class="flex-shrink-0 text-sm font-semibold text-gray-900 dark:text-gray-100">₱{{ number_format($transaction->total, 2) }}</span>
                                        </div>
                                        <div class="flex items-center justify-between pt-2 text-xs text-gray-600 border-t border-gray-200 dark:text-gray-400 dark:border-gray-700">
                                            <span>{{ $transaction->customer->name ?? 'Walk-in' }}</span>
                                            <span>{{ $transaction->items->count() }} item(s)</span>
                                        </div>
                                    </div>
                                @empty
                                    <div class="p-6 text-sm text-center text-gray-500 dark:text-gray-400">
                                        No transactions found
                                    </div>
                                @endforelse
                            </div>

                            <div class="hidden -mx-4 overflow-x-auto md:block sm:-mx-6">
                                <div class="inline-block min-w-full px-4 align-middle sm:px-6">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                                        <thead class="bg-gray-50 dark:bg-[#0a0a0a]">
                                            <tr>
                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase dark:text-gray-400">Code</th>
                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase dark:text-gray-400">Date</th>
                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase dark:text-gray-400">Customer</th>
                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-left text-gray-600 uppercase dark:text-gray-400">Items</th>
                                                <th class="px-4 py-3 text-xs font-semibold tracking-wider text-right text-gray-600 uppercase dark:text-gray-400">Total</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200 dark:bg-[#171717] dark:divide-gray-800">
                                            @foreach($transactions as $transaction)
                                                <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition-colors">
                                                    <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->transaction_code }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 whitespace-nowrap">{{ $transaction->timestamp?->format('M d, Y') ?? 'N/A' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $transaction->customer->name ?? 'Walk-in' }}</td>
                                                    <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $transaction->items->count() }}</td>
                                                    <td class="px-4 py-3 text-sm font-semibold text-right text-gray-900 dark:text-gray-100">₱{{ number_format($transaction->total, 2) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif

            </div>
        </div>
    </div>

        </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const skeleton = document.getElementById('PageSkeleton');
                const content = document.getElementById('RealPageContent');

                if (skeleton) skeleton.style.display = 'none';

                if (content) {
                    content.classList.remove('hidden');
                    // Small delay to allow display change to register before starting opacity transition
                    setTimeout(() => {
                        content.classList.remove('opacity-0');
                    }, 50);
                }
            }, 600); // 600ms simulated delay
        });

        // ... Existing modal functions ...
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const skeleton = document.getElementById('DetailSkeleton');
                const content = document.getElementById('RealDetailContent');
                if (skeleton) skeleton.remove();
                if (content) {
                    content.classList.remove('hidden');
                    setTimeout(() => content.classList.remove('opacity-0'), 10);
                }
            }, 500);
        });

function openProcessModal() {
    if (confirm('Process this import? This action cannot be undone.')) {
        // Create and submit form
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route("admin.imports.process", $import) }}';

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        if (csrfToken) {
            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = csrfToken;
            form.appendChild(csrfInput);
        }

        document.body.appendChild(form);
        form.submit();
    }
}
    </script>
</x-app-layout>
