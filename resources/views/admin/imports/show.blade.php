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

            {{-- ADD THIS NEW NAVIGATION BLOCK HERE --}}
        <nav class="flex items-center justify-between px-1">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center text-sm font-medium text-gray-700 hover:text-purple-600 dark:text-gray-400 dark:hover:text-white">
                        <svg class="w-3 h-3 mr-2.5" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                            <path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/>
                        </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <a href="{{ route('admin.imports.index') }}" class="ml-1 text-sm font-medium text-gray-700 hover:text-purple-600 md:ml-2 dark:text-gray-400 dark:hover:text-white">Imports</a>
                    </div>
                </li>
                <li aria-current="page">
                    <div class="flex items-center">
                        <svg class="w-3 h-3 text-gray-400 mx-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 6 10">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 9 4-4-4-4"/>
                        </svg>
                        <span class="ml-1 text-sm font-medium text-gray-500 md:ml-2 dark:text-gray-400">Details</span>
                    </div>
                </li>
            </ol>

            <a href="{{ route('admin.imports.index') }}" class="flex items-center px-3 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 hover:text-purple-600 dark:bg-gray-800 dark:text-gray-400 dark:border-gray-600 dark:hover:bg-gray-700 dark:hover:text-white transition-colors">
                <svg class="w-3.5 h-3.5 mr-2" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 10">
                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5H1m0 0 4 4M1 5l4-4"/>
                </svg>
                Back
            </a>
        </nav>

            {{-- ============================================================== --}}
            {{-- 1. DETAIL SKELETON                                             --}}
            {{-- ============================================================== --}}
            <div id="DetailSkeleton" class="animate-pulse space-y-6">
                <div class="bg-white dark:bg-[#171717] rounded-lg p-6">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                        @for($i=0; $i<4; $i++)
                        <div class="space-y-2">
                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                            <div class="h-6 bg-gray-200 rounded dark:bg-gray-700 w-32"></div>
                        </div>
                        @endfor
                    </div>
                    <div class="mt-6 h-10 bg-gray-200 rounded dark:bg-gray-700 w-40"></div>
                </div>
                <div class="bg-white dark:bg-[#171717] rounded-lg p-6 space-y-4">
                    <div class="h-6 bg-gray-200 rounded dark:bg-gray-700 w-48 mb-4"></div>
                    <div class="space-y-3">
                        @for($j=0; $j<5; $j++)
                        <div class="h-8 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                        @endfor
                    </div>
                </div>
            </div>

            {{-- ============================================================== --}}
            {{-- 2. REAL CONTENT                                                --}}
            {{-- ============================================================== --}}
            <div id="RealDetailContent" class="hidden opacity-0 transition-opacity duration-500 space-y-6">

                <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                    <div class="p-6">
                        <div class="grid grid-cols-1 gap-6 md:grid-cols-4">
                            {{-- FIX: Added min-w-0 to container and truncate/title to text --}}
                            <div class="min-w-0">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">File Name</h4>
                                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $import->file_name }}">
                                    {{ $import->file_name }}
                                </p>
                            </div>

                            {{-- Recommended: Apply similar protection to Branch name --}}
                            <div class="min-w-0">
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Branch</h4>
                                <p class="mt-1 text-lg font-semibold text-gray-900 dark:text-gray-100 truncate" title="{{ $import->branch->name ?? 'N/A' }}">
                                    {{ $import->branch->name ?? 'N/A' }}
                                </p>
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
                                        <div class="h-2 bg-purple-600 rounded-full transition-all duration-300" style="width: {{ ($import->successful_rows / $import->total_rows) * 100 }}%"></div>
                                    </div>
                                @endif
                            </div>
                        </div>

                        @if($import->status === 'pending')
                            <div class="mt-6">
                                <form action="{{ route('admin.imports.process', $import) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="showProcessLoading(this)">
                                    @csrf
                                    <button type="submit" class="px-6 py-2 font-medium text-white transition bg-purple-600 rounded-md hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500">
                                        🚀 Process Import
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                @if($import->failed_rows > 0 && $import->errors)
                    <div class="border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:border-red-800">
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-red-900 dark:text-red-400 mb-4">
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

                @if($import->status === 'pending' && !empty($previewData))
                    <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">📊 Data Preview</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full text-sm divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900">
                                        <tr>
                                            @if(!empty($previewData[0]))
                                                @foreach($previewData[0] as $column)
                                                    <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">{{ $column }}</th>
                                                @endforeach
                                            @endif
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        @foreach(array_slice($previewData, 1) as $row)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                @foreach($row as $value)
                                                    <td class="px-4 py-2 text-gray-900 dark:text-gray-100 whitespace-nowrap">{{ $value }}</td>
                                                @endforeach
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif

                @if($import->status === 'completed' && !empty($transactions))
                     <div class="overflow-hidden bg-white shadow-sm dark:bg-gray-800 sm:rounded-lg">
                        <div class="p-6">
                            <h3 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">✅ Recent Transactions</h3>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                    <thead class="bg-gray-50 dark:bg-gray-900">
                                        <tr>
                                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Code</th>
                                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Date</th>
                                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Customer</th>
                                            <th class="px-4 py-2 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Items</th>
                                            <th class="px-4 py-2 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200 dark:bg-gray-800 dark:divide-gray-700">
                                        @foreach($transactions as $transaction)
                                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50">
                                                <td class="px-4 py-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ $transaction->transaction_code }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $transaction->timestamp?->format('M d, Y') ?? 'N/A' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $transaction->customer->name ?? 'Walk-in' }}</td>
                                                <td class="px-4 py-2 text-sm text-gray-500 dark:text-gray-400">{{ $transaction->items->count() }}</td>
                                                <td class="px-4 py-2 text-sm font-medium text-right text-gray-900 dark:text-gray-100">₱{{ number_format($transaction->total, 2) }}</td>
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
    </div>

    <script>
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

        function showProcessLoading(form) {
            if (confirm('Process this import? This action cannot be undone.')) {
                const btn = form.querySelector('button[type="submit"]');
                // Store original HTML
                btn.dataset.original = btn.innerHTML;

                // Show spinner
                btn.disabled = true;
                btn.innerHTML = `
                    <svg class="w-5 h-5 mr-2 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Processing...
                `;
                return true;
            }
            return false;
        }
    </script>
</x-app-layout>
