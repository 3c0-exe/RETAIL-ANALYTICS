<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">Imports</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload and manage your sales data files</p>
            </div>

            <a href="{{ route('admin.imports.create') }}"
               class="inline-flex items-center justify-center text-white font-medium transition-all duration-200
                      bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-md text-sm w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>New Import</span>
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 border border-green-500 rounded-lg dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 border border-red-500 rounded-lg dark:bg-red-900/20 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">File Name</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Branch</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Progress</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400 hidden lg:table-cell">Uploaded By</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400 hidden xl:table-cell">Date</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($imports as $import)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition">
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-gray-100 text-sm">{{ $import->file_name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400 lg:hidden">
                                        {{ $import->user->name }} • {{ $import->created_at->format('M d, Y') }}
                                    </div>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                    {{ $import->branch->name ?? 'N/A' }}
                                </td>
                                <td class="px-4 py-3">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400',
                                            'processing' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400',
                                            'completed' => 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400',
                                            'failed' => 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400',
                                        ];
                                    @endphp
                                    <span class="px-2 py-1 text-xs font-medium rounded-full {{ $statusColors[$import->status] ?? '' }}">
                                        {{ ucfirst($import->status) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">
                                    @if($import->total_rows > 0)
                                        <div>{{ $import->successful_rows }} / {{ $import->total_rows }}</div>
                                        @if($import->failed_rows > 0)
                                            <div class="text-xs text-red-600 dark:text-red-400">({{ $import->failed_rows }} failed)</div>
                                        @endif
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 hidden lg:table-cell">
                                    {{ $import->user->name }}
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400 hidden xl:table-cell">
                                    {{ $import->created_at->format('M d, Y H:i') }}
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.imports.show', $import) }}"
                                           class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                            View
                                        </a>
                                        @if($import->status === 'failed' || $import->status === 'completed')
                                            <form action="{{ route('admin.imports.destroy', $import) }}"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Delete this import?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg class="w-16 h-16 mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No imports yet</p>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Get started by uploading your first sales data file</p>
                                        <a href="{{ route('admin.imports.create') }}"
                                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
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

        <!-- Mobile Card View -->
        <div class="md:hidden">
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden divide-y divide-gray-200 dark:divide-gray-800">
                @forelse($imports as $import)
                    <div class="p-4">
                        <!-- File Name & Status -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1 min-w-0 mr-3">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100 text-sm truncate">
                                    {{ $import->file_name }}
                                </h3>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">
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
                            <span class="px-2 py-1 text-xs font-medium rounded-full flex-shrink-0 {{ $statusColors[$import->status] ?? '' }}">
                                {{ ucfirst($import->status) }}
                            </span>
                        </div>

                        <!-- Progress Info -->
                        @if($import->total_rows > 0)
                            <div class="mb-3 pb-3 border-b border-gray-200 dark:border-gray-800">
                                <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-1">
                                    <span>Progress</span>
                                    <span class="font-medium">{{ $import->successful_rows }} / {{ $import->total_rows }}</span>
                                </div>
                                <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-1.5">
                                    <div class="bg-primary-600 h-1.5 rounded-full" style="width: {{ $import->total_rows > 0 ? ($import->successful_rows / $import->total_rows * 100) : 0 }}%"></div>
                                </div>
                                @if($import->failed_rows > 0)
                                    <p class="text-xs text-red-600 dark:text-red-400 mt-1">{{ $import->failed_rows }} failed</p>
                                @endif
                            </div>
                        @endif

                        <!-- Meta Info -->
                        <div class="flex items-center justify-between text-xs text-gray-500 dark:text-gray-400 mb-3">
                            <span>{{ $import->user->name }}</span>
                            <span>{{ $import->created_at->format('M d, Y H:i') }}</span>
                        </div>

                        <!-- Actions -->
                        <div class="flex gap-2">
                            <a href="{{ route('admin.imports.show', $import) }}"
                               class="flex-1 text-center px-3 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition">
                                View Details
                            </a>
                            @if($import->status === 'failed' || $import->status === 'completed')
                                <form action="{{ route('admin.imports.destroy', $import) }}"
                                      method="POST"
                                      class="flex-shrink-0"
                                      onsubmit="return confirm('Delete this import?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-3 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="p-8 text-center">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                        </svg>
                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-2">No imports yet</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Get started by uploading your first sales data file</p>
                        <a href="{{ route('admin.imports.create') }}"
                           class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Upload Your First Import
                        </a>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($imports->hasPages())
            <div class="mt-6">
                {{ $imports->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
