<x-app-layout>
    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8 sm:py-6">

        {{-- ============================================================== --}}
        {{-- 1. SKELETON LOADER (Visible Initially)                         --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="space-y-6 animate-pulse">

            <div class="w-32 h-4 mb-6 bg-gray-200 rounded dark:bg-gray-800"></div>

            <div class="flex flex-col gap-4 mb-8 sm:flex-row sm:items-center sm:justify-between">
                <div class="space-y-2">
                    <div class="w-48 h-8 bg-gray-200 rounded dark:bg-gray-800"></div>
                    <div class="w-64 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                </div>
                <div class="w-32 h-10 bg-gray-200 rounded rounded-lg dark:bg-gray-800"></div>
            </div>

            <div class="hidden overflow-hidden border border-gray-200 rounded-lg lg:block dark:border-gray-800">
                <div class="bg-gray-50 dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-gray-800 p-4 grid grid-cols-6 gap-4">
                    @for($i=0; $i<6; $i++)
                        <div class="w-full h-3 bg-gray-200 rounded dark:bg-gray-800"></div>
                    @endfor
                </div>
                <div class="bg-white dark:bg-[#171717] divide-y divide-gray-200 dark:divide-gray-800">
                    @for($i=0; $i<5; $i++)
                        <div class="grid items-center grid-cols-6 gap-4 p-4">
                            <div class="space-y-2">
                                <div class="w-3/4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-1/2 h-3 bg-gray-200 rounded dark:bg-gray-800"></div>
                            </div>
                            <div class="w-1/2 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                            <div class="w-20 h-6 bg-gray-200 rounded-full dark:bg-gray-800"></div>
                            <div class="space-y-2">
                                <div class="w-full h-2 bg-gray-200 rounded dark:bg-gray-800"></div>
                            </div>
                            <div class="w-1/2 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                            <div class="w-24 h-8 ml-auto bg-gray-200 rounded dark:bg-gray-800"></div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="space-y-4 lg:hidden">
                @for($i=0; $i<3; $i++)
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-5 space-y-4">
                        <div class="flex items-start justify-between">
                            <div class="w-1/2 space-y-2">
                                <div class="w-full h-5 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-2/3 h-3 bg-gray-200 rounded dark:bg-gray-800"></div>
                            </div>
                            <div class="w-16 h-6 bg-gray-200 rounded-full dark:bg-gray-800"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <div class="w-16 h-3 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-12 h-3 bg-gray-200 rounded dark:bg-gray-800"></div>
                            </div>
                            <div class="w-full h-2 bg-gray-200 rounded dark:bg-gray-800"></div>
                        </div>
                        <div class="flex justify-end pt-2">
                            <div class="w-full h-8 bg-gray-200 rounded dark:bg-gray-800"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden transition-opacity duration-500 ease-in-out opacity-0">
            <!-- Breadcrumb -->
        <nav class="mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xs text-gray-700 transition-colors sm:text-sm hover:text-purple-600 dark:text-gray-400">
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="flex-shrink-0 w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                        </svg>
                        <span class="text-xs font-medium text-gray-500 sm:text-sm dark:text-gray-400">Imports</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between sm:mb-8 sm:gap-4">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl font-bold text-gray-900 truncate sm:text-2xl lg:text-3xl dark:text-gray-100">Imports</h1>
                <p class="mt-1 text-xs text-gray-600 sm:text-sm dark:text-gray-400">Upload and manage your sales data files</p>
            </div>

            <a href="{{ route('admin.imports.create') }}"
               class="inline-flex items-center justify-center gap-2 text-white font-semibold transition-all duration-200
                      bg-primary-600 hover:bg-primary-700 active:bg-primary-800 hover:scale-[1.02] active:scale-[0.98]
                      px-5 py-2.5 rounded-lg text-sm shadow-sm hover:shadow w-full sm:w-auto flex-shrink-0">
                <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>New Import</span>
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="p-3 mb-4 text-xs text-green-700 bg-green-100 border border-green-500 rounded-lg sm:p-4 sm:mb-6 sm:text-sm dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 mb-4 text-xs text-red-700 bg-red-100 border border-red-500 rounded-lg sm:p-4 sm:mb-6 sm:text-sm dark:bg-red-900/20 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Desktop Table View (hidden on mobile/tablet) -->
        <div class="hidden lg:block bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase xl:px-6 dark:text-gray-400">File Name</th>
                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase xl:px-6 dark:text-gray-400">Branch</th>
                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase xl:px-6 dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase xl:px-6 dark:text-gray-400">Progress</th>
                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase xl:px-6 dark:text-gray-400">Uploaded By</th>
                            <th class="hidden px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase xl:px-6 dark:text-gray-400 xl:table-cell">Date</th>
                            <th class="px-4 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase xl:px-6 dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($imports as $import)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition-colors">
                                <td class="px-4 py-4 xl:px-6">
                                    <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $import->file_name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 xl:hidden mt-0.5">
                                        {{ $import->created_at?->format('M d, Y H:i') ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 xl:px-6 dark:text-gray-400">
                                    {{ $import->branch->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-4 xl:px-6">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                            'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                            'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                        ];
                                    @endphp
                                    <span class="inline-flex px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$import->status] ?? '' }}">
                                        {{ ucfirst($import->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 xl:px-6 dark:text-gray-400">
                                    @if($import->total_rows > 0)
                                        <div class="font-medium">{{ $import->successful_rows }} / {{ $import->total_rows }}</div>
                                        @if($import->failed_rows > 0)
                                            <div class="text-xs text-red-600 dark:text-red-400 mt-0.5">({{ $import->failed_rows }} failed)</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-4 text-sm text-gray-600 xl:px-6 dark:text-gray-400">
                                    {{ $import->user->name ?? 'Unknown User' }}
                                </td>
                                <td class="hidden px-4 py-4 text-sm text-gray-600 xl:px-6 dark:text-gray-400 xl:table-cell whitespace-nowrap">
                                    {{ $import->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-4 py-4 xl:px-6">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.imports.show', $import) }}"
                                           class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-primary-600 bg-white hover:bg-gray-50 active:bg-gray-100 dark:text-primary-400 dark:bg-[#0a0a0a] dark:hover:bg-[#1a1a1a] dark:border dark:border-gray-800 rounded-md transition-all duration-150 hover:scale-105 active:scale-95">
                                            View
                                        </a>
                                        @if($import->status === 'failed' || $import->status === 'completed')
                                            <button type="button"
                                                    onclick="openDeleteModal({{ $import->id }}, '{{ addslashes($import->file_name) }}')"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 active:bg-red-800 dark:bg-red-600 dark:hover:bg-red-700 rounded-md transition-all duration-150 hover:scale-105 active:scale-95">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center sm:py-16">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 mb-4 text-gray-300 sm:w-16 sm:h-16 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="mb-2 text-base font-medium text-gray-900 sm:text-lg dark:text-gray-100">No imports yet</p>
                                        <p class="max-w-sm mb-4 text-xs text-gray-500 sm:text-sm dark:text-gray-400">Get started by uploading your first sales data file</p>
                                        <a href="{{ route('admin.imports.create') }}"
                                           class="inline-flex items-center justify-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 active:bg-primary-800 rounded-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98] shadow-sm hover:shadow">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                            </svg>
                                            Upload Your First Import
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Tablet/Mobile Card View -->
        <div class="space-y-3 lg:hidden sm:space-y-4">
            @forelse($imports as $import)
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4 sm:p-5">
                        <!-- Header: File Name & Status -->
                        <div class="flex items-start justify-between gap-3 mb-3 sm:mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="text-sm font-semibold text-gray-900 truncate dark:text-gray-100 sm:text-base">
                                    {{ $import->file_name }}
                                </h3>
                                <p class="mt-1 text-xs text-gray-500 sm:text-sm dark:text-gray-400">
                                    {{ $import->branch->name ?? 'N/A' }}
                                </p>
                            </div>
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                    'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                    'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                    'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                ];
                            @endphp
                            <span class="inline-flex px-2 sm:px-2.5 py-1 text-xs font-medium rounded-full flex-shrink-0 {{ $statusColors[$import->status] ?? '' }}">
                                {{ ucfirst($import->status) }}
                            </span>
                        </div>

                        <!-- Progress Section -->
                        @if($import->total_rows > 0)
                            <div class="pb-3 mb-3 border-b border-gray-200 sm:mb-4 sm:pb-4 dark:border-gray-800">
                                <div class="flex items-center justify-between mb-2 text-xs text-gray-600 sm:text-sm dark:text-gray-400">
                                    <span class="font-medium">Progress</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $import->successful_rows }} / {{ $import->total_rows }}</span>
                                </div>
                                <div class="w-full h-2 overflow-hidden bg-gray-200 rounded-full dark:bg-gray-700">
                                    <div class="h-2 transition-all duration-300 rounded-full bg-primary-600" style="width: {{ $import->total_rows > 0 ? ($import->successful_rows / $import->total_rows * 100) : 0 }}%"></div>
                                </div>
                                @if($import->failed_rows > 0)
                                    <p class="mt-2 text-xs font-medium text-red-600 sm:text-sm dark:text-red-400">{{ $import->failed_rows }} row(s) failed</p>
                                @endif
                            </div>
                        @endif

                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center mb-4 text-xs text-gray-500 gap-x-4 gap-y-2 sm:text-sm dark:text-gray-400">
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span class="truncate">{{ $import->user->name }}</span>
                            </div>
                            <div class="flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="whitespace-nowrap">{{ $import->created_at->format('M d, Y H:i') }}</span>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex gap-2 sm:gap-3">
                            <a href="{{ route('admin.imports.show', $import) }}"
                               class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-semibold text-primary-600 bg-white hover:bg-gray-50 active:bg-gray-100 dark:text-primary-400 dark:bg-[#0a0a0a] dark:hover:bg-[#1a1a1a] dark:border dark:border-gray-800 rounded-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]">
                                <span>View Details</span>
                            </a>
                            @if($import->status === 'failed' || $import->status === 'completed')
                                <button type="button"
                                        onclick="openDeleteModal({{ $import->id }}, '{{ addslashes($import->file_name) }}')"
                                        class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 active:bg-red-800 dark:bg-red-600 dark:hover:bg-red-700 rounded-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]">
                                    <svg class="flex-shrink-0 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm">
                    <div class="p-8 text-center sm:p-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 sm:w-20 sm:h-20 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="mb-2 text-base font-semibold text-gray-900 sm:text-lg dark:text-gray-100">No imports yet</p>
                        <p class="max-w-sm mx-auto mb-6 text-sm text-gray-500 sm:text-base dark:text-gray-400">Get started by uploading your first sales data file</p>
                        <a href="{{ route('admin.imports.create') }}"
                           class="inline-flex items-center justify-center gap-2 px-5 py-3 text-sm font-semibold text-white bg-primary-600 hover:bg-primary-700 active:bg-primary-800 rounded-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98] shadow-sm hover:shadow">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Upload Your First Import
                        </a>
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($imports->hasPages())
            <div class="mt-6 sm:mt-8">
                {{ $imports->links() }}
            </div>
        @endif
    </div>

    <!-- Delete Confirmation Modal -->
<!-- Enhanced Delete Confirmation Modal -->
<!-- Replace the existing deleteModal div in your index.blade.php with this -->

<div id="deleteModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <!-- Backdrop -->
    <div class="fixed inset-0 transition-opacity duration-300 bg-gray-900/75 dark:bg-black/80 backdrop-blur-sm" onclick="closeDeleteModal()"></div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-full p-4 text-center sm:p-0">
        <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 text-left shadow-xl transition-all duration-300 scale-95 opacity-0 sm:my-8 w-full max-w-lg mx-4 sm:mx-auto" id="modalContent">

            <!-- Loading State -->
            <div id="modalLoading" class="p-8 text-center">
                <div class="w-12 h-12 mx-auto border-b-2 rounded-full animate-spin border-primary-600"></div>
                <p class="mt-4 text-sm text-gray-600 dark:text-gray-400">Loading import details...</p>
            </div>

            <!-- Modal Content (Hidden Initially) -->
            <div id="modalBody" class="hidden">
                <!-- Modal Header -->
                <div class="bg-white dark:bg-[#171717] px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <!-- Icon -->
                        <div class="flex items-center justify-center flex-shrink-0 w-12 h-12 mx-auto bg-red-100 rounded-full dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="w-6 h-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="flex-1 mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left">
                            <h3 class="text-base font-semibold leading-6 text-gray-900 sm:text-lg dark:text-gray-100" id="modal-title">
                                ⚠️ Remove Import?
                            </h3>
                            <div class="mt-3">
                                <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                                    <span class="font-semibold text-gray-900 dark:text-gray-100" id="deleteFileName"></span>
                                </p>

                                <!-- Import Stats -->
                                <div id="importStats" class="bg-gray-50 dark:bg-[#0a0a0a] rounded-lg p-4 space-y-2">
                                    <p class="text-sm text-gray-700 dark:text-gray-300">
                                        This import contains:
                                    </p>
                                    <ul class="text-sm text-gray-600 dark:text-gray-400 space-y-1.5 ml-4">
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-600 rounded-full"></span>
                                            <span><strong id="statsTransactions">0</strong> transactions</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-600 rounded-full"></span>
                                            <span><strong id="statsSales">₱0.00</strong> in sales</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-600 rounded-full"></span>
                                            <span><strong id="statsNewCustomers">0</strong> new customer(s)</span>
                                        </li>
                                        <li class="flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-600 rounded-full"></span>
                                            <span><strong id="statsRepeatCustomers">0</strong> repeat customer purchase(s)</span>
                                        </li>
                                    </ul>
                                </div>

                                <p class="mt-4 mb-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                    What would you like to do?
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 dark:bg-[#0a0a0a] px-4 py-3 sm:px-6 space-y-3">
                    <!-- Delete Record + Data Button -->
                    <form id="deleteFormWithData" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <input type="hidden" name="delete_data" value="1">
                        <button type="submit"
                                class="inline-flex w-full justify-center items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-700 active:bg-red-800 transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete Record + Data
                        </button>
                    </form>

                    <!-- Delete Record Only Button -->
                    <form id="deleteFormRecordOnly" method="POST" class="w-full">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex w-full justify-center items-center gap-2 rounded-lg bg-orange-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-orange-700 active:bg-orange-800 transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]">
                            <svg class="flex-shrink-0 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Delete Record Only
                        </button>
                    </form>

                    <!-- Cancel Button -->
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="inline-flex w-full justify-center items-center rounded-lg bg-white dark:bg-[#0a0a0a] px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition-all duration-150">
                            Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>

// Enhanced Modal Logic with Stats Loading
async function openDeleteModal(importId, fileName) {
    console.log('Opening modal for import ID:', importId); // Debug log

    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');
    const modalLoading = document.getElementById('modalLoading');
    const modalBody = document.getElementById('modalBody');
    const formWithData = document.getElementById('deleteFormWithData');
    const formRecordOnly = document.getElementById('deleteFormRecordOnly');
    const fileNameSpan = document.getElementById('deleteFileName');

    // Set the form action URLs
    formWithData.action = `/admin/imports/${importId}`;
    formRecordOnly.action = `/admin/imports/${importId}`;

    // Set the file name in the modal
    fileNameSpan.textContent = fileName;

    // Show modal with loading state
    modal.classList.remove('hidden');
    modalLoading.classList.remove('hidden');
    modalBody.classList.add('hidden');

    // Trigger animation
    setTimeout(() => {
        modalContent.classList.remove('scale-95', 'opacity-0');
        modalContent.classList.add('scale-100', 'opacity-100');
    }, 10);

    // Prevent body scroll
    document.body.style.overflow = 'hidden';

    // Fetch import stats
    try {
        console.log('Fetching stats from:', `/admin/imports/${importId}/stats`); // Debug log

        const response = await fetch(`/admin/imports/${importId}/stats`);

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const stats = await response.json();
        console.log('Stats received:', stats); // Debug log

        // Update stats in modal
        document.getElementById('statsTransactions').textContent = stats.transactions || 0;
        document.getElementById('statsSales').textContent = '₱' + (stats.sales || '0.00');
        document.getElementById('statsNewCustomers').textContent = stats.new_customers || 0;
        document.getElementById('statsRepeatCustomers').textContent = stats.repeat_customers || 0;

        // Show the modal body
        modalLoading.classList.add('hidden');
        modalBody.classList.remove('hidden');
    } catch (error) {
        console.error('Failed to load import stats:', error);

        // Set default values on error
        document.getElementById('statsTransactions').textContent = '0';
        document.getElementById('statsSales').textContent = '₱0.00';
        document.getElementById('statsNewCustomers').textContent = '0';
        document.getElementById('statsRepeatCustomers').textContent = '0';

        // Show modal anyway with default values
        modalLoading.classList.add('hidden');
        modalBody.classList.remove('hidden');
    }
}

function closeDeleteModal() {
    const modal = document.getElementById('deleteModal');
    const modalContent = document.getElementById('modalContent');

    // Animate out
    modalContent.classList.remove('scale-100', 'opacity-100');
    modalContent.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        modal.classList.add('hidden');
        document.body.style.overflow = '';
    }, 300);
}

// Close modal on Escape key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        const modal = document.getElementById('deleteModal');
        if (!modal.classList.contains('hidden')) {
            closeDeleteModal();
        }
    }
});
</script>
        </div>


    <script>
        // Transition from Skeleton to Real Content
        document.addEventListener('DOMContentLoaded', () => {
            const skeleton = document.getElementById('PageSkeleton');
            const realContent = document.getElementById('RealPageContent');

            // Simulate loading delay (optional, remove setTimeout to load instantly)
            setTimeout(() => {
                if(skeleton) {
                    skeleton.style.display = 'none'; // Instant switch or use fading logic
                }
                if(realContent) {
                    realContent.classList.remove('hidden');
                    // Small delay to allow 'hidden' class removal to paint before opacity transition
                    setTimeout(() => {
                        realContent.classList.remove('opacity-0');
                    }, 10);
                }
            }, 600); // 600ms delay for smoothness
        });
    </script>
</x-app-layout>
