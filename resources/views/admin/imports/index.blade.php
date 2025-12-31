<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-4 sm:py-6">
        <!-- Breadcrumb -->
        <nav class="mb-4 sm:mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-2">
                <li class="inline-flex items-center">
                    <a href="{{ route('dashboard') }}" class="text-xs sm:text-sm text-gray-700 hover:text-purple-600 dark:text-gray-400 transition-colors">
                        Dashboard
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <svg class="w-3 h-3 mx-1 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                        </svg>
                        <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Imports</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 sm:mb-8 gap-3 sm:gap-4">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100 truncate">Imports</h1>
                <p class="mt-1 text-xs sm:text-sm text-gray-600 dark:text-gray-400">Upload and manage your sales data files</p>
            </div>

            <a href="{{ route('admin.imports.create') }}"
               class="inline-flex items-center justify-center gap-2 text-white font-semibold transition-all duration-200
                      bg-primary-600 hover:bg-primary-700 active:bg-primary-800 hover:scale-[1.02] active:scale-[0.98]
                      px-5 py-2.5 rounded-lg text-sm shadow-sm hover:shadow w-full sm:w-auto flex-shrink-0">
                <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>New Import</span>
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="p-3 sm:p-4 mb-4 sm:mb-6 text-xs sm:text-sm text-green-700 bg-green-100 border border-green-500 rounded-lg dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 sm:p-4 mb-4 sm:mb-6 text-xs sm:text-sm text-red-700 bg-red-100 border border-red-500 rounded-lg dark:bg-red-900/20 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Desktop Table View (hidden on mobile/tablet) -->
        <div class="hidden lg:block bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-4 xl:px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase tracking-wider dark:text-gray-400">File Name</th>
                            <th class="px-4 xl:px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase tracking-wider dark:text-gray-400">Branch</th>
                            <th class="px-4 xl:px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase tracking-wider dark:text-gray-400">Status</th>
                            <th class="px-4 xl:px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase tracking-wider dark:text-gray-400">Progress</th>
                            <th class="px-4 xl:px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase tracking-wider dark:text-gray-400">Uploaded By</th>
                            <th class="px-4 xl:px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase tracking-wider dark:text-gray-400 hidden xl:table-cell">Date</th>
                            <th class="px-4 xl:px-6 py-3 text-xs font-medium text-right text-gray-500 uppercase tracking-wider dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($imports as $import)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition-colors">
                                <td class="px-4 xl:px-6 py-4">
                                    <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $import->file_name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 xl:hidden mt-0.5">
                                        {{ $import->created_at?->format('M d, Y H:i') ?? '-' }}
                                    </div>
                                </td>
                                <td class="px-4 xl:px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $import->branch->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 xl:px-6 py-4">
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
                                <td class="px-4 xl:px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    @if($import->total_rows > 0)
                                        <div class="font-medium">{{ $import->successful_rows }} / {{ $import->total_rows }}</div>
                                        @if($import->failed_rows > 0)
                                            <div class="text-xs text-red-600 dark:text-red-400 mt-0.5">({{ $import->failed_rows }} failed)</div>
                                        @endif
                                    @else
                                        <span class="text-gray-400">-</span>
                                    @endif
                                </td>
                                <td class="px-4 xl:px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $import->user->name ?? 'Unknown User' }}
                                </td>
                                <td class="px-4 xl:px-6 py-4 text-sm text-gray-600 dark:text-gray-400 hidden xl:table-cell whitespace-nowrap">
                                    {{ $import->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-4 xl:px-6 py-4">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.imports.show', $import) }}"
                                           class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-primary-600 bg-white hover:bg-gray-50 active:bg-gray-100 dark:text-primary-400 dark:bg-[#0a0a0a] dark:hover:bg-[#1a1a1a] dark:border dark:border-gray-800 rounded-md transition-all duration-150 hover:scale-105 active:scale-95">
                                            View
                                        </a>
                                        @if($import->status === 'failed' || $import->status === 'completed')
                                            <button type="button"
                                                    onclick="openDeleteModal('{{ $import->id }}', '{{ $import->file_name }}')"
                                                    class="inline-flex items-center justify-center px-3 py-1.5 text-xs font-semibold text-white bg-red-600 hover:bg-red-700 active:bg-red-800 dark:bg-red-600 dark:hover:bg-red-700 rounded-md transition-all duration-150 hover:scale-105 active:scale-95">
                                                Delete
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 sm:py-16 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-12 h-12 sm:w-16 sm:h-16 mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-base sm:text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No imports yet</p>
                                        <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4 max-w-sm">Get started by uploading your first sales data file</p>
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
        <div class="lg:hidden space-y-3 sm:space-y-4">
            @forelse($imports as $import)
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm hover:shadow-md transition-shadow">
                    <div class="p-4 sm:p-5">
                        <!-- Header: File Name & Status -->
                        <div class="flex items-start justify-between gap-3 mb-3 sm:mb-4">
                            <div class="flex-1 min-w-0">
                                <h3 class="font-semibold text-gray-900 dark:text-gray-100 text-sm sm:text-base truncate">
                                    {{ $import->file_name }}
                                </h3>
                                <p class="text-xs sm:text-sm text-gray-500 dark:text-gray-400 mt-1">
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
                            <div class="mb-3 sm:mb-4 pb-3 sm:pb-4 border-b border-gray-200 dark:border-gray-800">
                                <div class="flex items-center justify-between text-xs sm:text-sm text-gray-600 dark:text-gray-400 mb-2">
                                    <span class="font-medium">Progress</span>
                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $import->successful_rows }} / {{ $import->total_rows }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
                                    <div class="bg-primary-600 h-2 rounded-full transition-all duration-300" style="width: {{ $import->total_rows > 0 ? ($import->successful_rows / $import->total_rows * 100) : 0 }}%"></div>
                                </div>
                                @if($import->failed_rows > 0)
                                    <p class="text-xs sm:text-sm text-red-600 dark:text-red-400 mt-2 font-medium">{{ $import->failed_rows }} row(s) failed</p>
                                @endif
                            </div>
                        @endif

                        <!-- Meta Information -->
                        <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-xs sm:text-sm text-gray-500 dark:text-gray-400 mb-4">
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
                                        onclick="openDeleteModal('{{ $import->id }}', '{{ $import->file_name }}')"
                                        class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-red-600 hover:bg-red-700 active:bg-red-800 dark:bg-red-600 dark:hover:bg-red-700 rounded-lg transition-all duration-150 hover:scale-[1.02] active:scale-[0.98]">
                                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden shadow-sm">
                    <div class="p-8 sm:p-12 text-center">
                        <svg class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 mb-2">No imports yet</p>
                        <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400 mb-6 max-w-sm mx-auto">Get started by uploading your first sales data file</p>
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
    <div id="deleteModal" class="hidden fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <!-- Backdrop -->
        <div class="fixed inset-0 bg-gray-900/75 dark:bg-black/80 backdrop-blur-sm transition-opacity duration-300" onclick="closeDeleteModal()"></div>

        <!-- Modal Container -->
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 text-left shadow-xl transition-all duration-300 scale-95 opacity-0 sm:my-8 w-full max-w-lg mx-4 sm:mx-auto" id="modalContent">
                <!-- Modal Header -->
                <div class="bg-white dark:bg-[#171717] px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <!-- Icon -->
                        <div class="mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 dark:bg-red-900/20 sm:mx-0 sm:h-10 sm:w-10">
                            <svg class="h-6 w-6 text-red-600 dark:text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                        </div>

                        <!-- Content -->
                        <div class="mt-3 text-center sm:ml-4 sm:mt-0 sm:text-left flex-1">
                            <h3 class="text-base sm:text-lg font-semibold leading-6 text-gray-900 dark:text-gray-100" id="modal-title">
                                Delete Import
                            </h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                    Are you sure you want to delete <span id="deleteFileName" class="font-semibold text-gray-900 dark:text-gray-100"></span>? This action cannot be undone.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="bg-gray-50 dark:bg-[#0a0a0a] px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-3">
                    <form id="deleteForm" method="POST" class="w-full sm:w-auto">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="inline-flex w-full justify-center items-center gap-2 rounded-lg bg-red-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm hover:bg-red-700 active:bg-red-800 transition-all duration-150 hover:scale-[1.02] active:scale-[0.98] sm:w-auto">
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Delete
                        </button>
                    </form>
                    <button type="button"
                            onclick="closeDeleteModal()"
                            class="mt-3 inline-flex w-full justify-center items-center rounded-lg bg-white dark:bg-[#0a0a0a] px-4 py-2.5 text-sm font-semibold text-gray-900 dark:text-gray-300 shadow-sm ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-50 dark:hover:bg-[#1a1a1a] transition-all duration-150 sm:mt-0 sm:w-auto">
                            Cancel
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openDeleteModal(importId, fileName) {
            const modal = document.getElementById('deleteModal');
            const modalContent = document.getElementById('modalContent');
            const form = document.getElementById('deleteForm');
            const fileNameSpan = document.getElementById('deleteFileName');

            // Set the form action URL
            form.action = `/admin/imports/${importId}`;

            // Set the file name in the modal
            fileNameSpan.textContent = fileName;

            // Show modal
            modal.classList.remove('hidden');

            // Trigger animation
            setTimeout(() => {
                modalContent.classList.remove('scale-95', 'opacity-0');
                modalContent.classList.add('scale-100', 'opacity-100');
            }, 10);

            // Prevent body scroll
            document.body.style.overflow = 'hidden';
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
</x-app-layout>
