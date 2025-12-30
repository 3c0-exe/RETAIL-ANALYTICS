<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-6">

        {{-- ============================================================== --}}
        {{-- 1. FULL PAGE SKELETON (Visible on Load)                        --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-6">

            <div class="flex items-center space-x-2 mb-4">
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-4"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-20"></div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <div class="h-8 bg-gray-200 rounded dark:bg-gray-700 w-40 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-64"></div>
                </div>
                <div class="h-10 bg-gray-200 rounded-lg dark:bg-gray-700 w-full sm:w-32"></div>
            </div>

            <div class="bg-white border border-gray-200 shadow-sm dark:bg-[#171717] rounded-lg dark:border-gray-800 p-4 sm:p-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                    </div>
                    <div class="flex items-end">
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-full"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white border border-gray-200 shadow-sm dark:bg-[#171717] rounded-lg dark:border-gray-800 overflow-hidden">
                <div class="lg:hidden">
                    <x-card-skeleton count="3" />
                </div>
                <div class="hidden lg:block">
                    <x-table-skeleton
                        rows="5"
                        :headers="true"
                        :colSizes="['w-1/6', 'w-1/6', 'w-1/6', 'w-1/6', 'w-1/6', 'w-1/6']"
                    />
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL PAGE CONTENT (Hidden Initially)                        --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500">

            <nav class="mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-purple-600 dark:text-gray-400">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Activity Logs</span>
                        </div>
                    </li>
                </ol>
            </nav>

            {{-- Page Header --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white">Activity Logs</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Monitor all user actions across the system</p>
                </div>
                {{-- Export Button (Spinner Added) --}}
                <button type="button"
                        onclick="handleExport(this)"
                        class="inline-flex items-center justify-center w-full sm:w-auto px-4 py-2 text-sm font-medium text-white transition-colors bg-green-600 rounded-lg hover:bg-green-700">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    Export CSV
                </button>
            </div>

            {{-- Filters --}}
            <div class="mb-6 bg-white border border-gray-200 shadow-sm dark:bg-[#171717] rounded-lg dark:border-gray-800">
                <div class="p-4 sm:p-6">
                    <form method="GET" action="{{ route('admin.activity-logs.index') }}" onsubmit="handleFilter(this)" class="space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
                            {{-- Search --}}
                            <div>
                                <label for="search" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Search</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                       placeholder="Description..."
                                       class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-[#0a0a0a] dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>

                            {{-- Action Filter --}}
                            <div>
                                <label for="action" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Action</label>
                                <select name="action" id="action" class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-[#0a0a0a] dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">All Actions</option>
                                    @foreach($actions as $action)
                                        <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>
                                            {{ ucfirst(str_replace('_', ' ', $action)) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- Model Filter --}}
                            <div>
                                <label for="model_type" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">Model</label>
                                <select name="model_type" id="model_type" class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-[#0a0a0a] dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                                    <option value="">All Models</option>
                                    @foreach($modelTypes as $modelType)
                                        <option value="{{ $modelType }}" {{ request('model_type') == $modelType ? 'selected' : '' }}>
                                            {{ class_basename($modelType) }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- IP Address Filter --}}
                            <div>
                                <label for="ip_address" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">IP Address</label>
                                <input type="text" name="ip_address" id="ip_address" value="{{ request('ip_address') }}"
                                       placeholder="127.0.0.1"
                                       class="w-full px-3 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-lg dark:bg-[#0a0a0a] dark:border-gray-700 dark:text-white focus:ring-2 focus:ring-primary-500 focus:border-primary-500">
                            </div>

                            {{-- Filter Button --}}
                            <div class="flex items-end">
                                <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white transition-colors bg-primary-600 rounded-lg hover:bg-primary-700">
                                    Apply Filters
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Results Count --}}
            <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
                Showing <span class="font-medium text-gray-900 dark:text-gray-100">{{ $logs->firstItem() ?? 0 }}</span>
                to <span class="font-medium text-gray-900 dark:text-gray-100">{{ $logs->lastItem() ?? 0 }}</span>
                of <span class="font-medium text-gray-900 dark:text-gray-100">{{ $logs->total() }}</span> entries
            </div>

            {{-- Activity Logs Table --}}
            <div class="bg-white border border-gray-200 shadow-sm dark:bg-[#171717] rounded-lg dark:border-gray-800">
                <div class="hidden lg:block overflow-x-auto">
                    <table class="w-full">
                        <thead class="border-b border-gray-200 bg-gray-50 dark:bg-[#0a0a0a] dark:border-gray-800">
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Time</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">User</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Action</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Model</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">IP Address</th>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Browser/Device</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                            @forelse($logs as $log)
                                <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition {{ $log->isSuspicious() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                    <td class="px-4 py-3 text-sm text-gray-900 whitespace-nowrap dark:text-gray-100">
                                        <div>{{ $log->created_at->format('M d, H:i') }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $log->created_at->diffForHumans() }}</div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="flex items-center justify-center w-8 h-8 mr-3 text-xs font-medium text-white rounded-full bg-primary-600">
                                                {{ substr($log->user->name ?? 'U', 0, 1) }}
                                            </div>
                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $log->user->name ?? 'Unknown' }}</div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[150px]">{{ $log->user->email ?? 'N/A' }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex items-center gap-2">
                                            @if($log->isSuspicious())
                                                <svg class="w-4 h-4 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20" title="Suspicious Activity">
                                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                                </svg>
                                            @endif
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                @if(str_contains($log->action, 'created')) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                                @elseif(str_contains($log->action, 'updated')) bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                                @elseif(str_contains($log->action, 'deleted')) bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                                @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                                @endif">
                                                {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                        <div>{{ $log->model_type ? class_basename($log->model_type) : '-' }}</div>
                                        @if($log->model_id)
                                            <div class="text-xs text-gray-500 dark:text-gray-400">#{{ $log->model_id }}</div>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="font-mono text-xs text-gray-900 dark:text-gray-100">{{ $log->ip_address ?? '-' }}</span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <div class="flex flex-col gap-1">
                                            @if($log->browser)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 w-fit">
                                                    {{ $log->browser }}
                                                </span>
                                            @endif
                                            @if($log->device)
                                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 w-fit">
                                                    {{ $log->device }}
                                                </span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p class="text-lg font-medium text-gray-900 dark:text-gray-100">No activity logs found</p>
                                        <p class="mt-1 text-sm">Try adjusting your filters</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile/Tablet Compact Card View --}}
                <div class="lg:hidden">
                    <div class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($logs as $log)
                            <div class="p-4 {{ $log->isSuspicious() ? 'bg-red-50 dark:bg-red-900/10' : '' }}">
                                {{-- Header: User & Time --}}
                                <div class="flex items-center justify-between mb-3">
                                    <div class="flex items-center min-w-0 flex-1 mr-2">
                                        <div class="flex items-center justify-center w-9 h-9 mr-2.5 text-xs font-medium text-white rounded-full bg-primary-600 flex-shrink-0">
                                            {{ substr($log->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
                                                {{ $log->user->name ?? 'Unknown' }}
                                            </div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $log->created_at->format('M d, H:i') }}
                                            </div>
                                        </div>
                                    </div>
                                    @if($log->isSuspicious())
                                        <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                    @endif
                                </div>

                                {{-- Action & Model --}}
                                <div class="flex items-center gap-2 mb-2 flex-wrap">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium
                                        @if(str_contains($log->action, 'created')) bg-green-100 dark:bg-green-900/30 text-green-800 dark:text-green-300
                                        @elseif(str_contains($log->action, 'updated')) bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300
                                        @elseif(str_contains($log->action, 'deleted')) bg-red-100 dark:bg-red-900/30 text-red-800 dark:text-red-300
                                        @else bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-300
                                        @endif">
                                        {{ ucfirst(str_replace('_', ' ', $log->action)) }}
                                    </span>
                                    @if($log->model_type)
                                        <span class="text-xs text-gray-600 dark:text-gray-400">
                                            {{ class_basename($log->model_type) }}
                                            @if($log->model_id)<span class="text-gray-500">#{{ $log->model_id }}</span>@endif
                                        </span>
                                    @endif
                                </div>

                                {{-- Tech Details: IP, Browser, Device --}}
                                <div class="flex items-center gap-2 text-xs flex-wrap">
                                    @if($log->ip_address)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 font-mono">
                                            {{ $log->ip_address }}
                                        </span>
                                    @endif
                                    @if($log->browser)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300 font-medium">
                                            {{ $log->browser }}
                                        </span>
                                    @endif
                                    @if($log->device)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300 font-medium">
                                            {{ $log->device }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-gray-500 dark:text-gray-400">
                                <svg class="w-12 h-12 mx-auto mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <p class="text-lg font-medium text-gray-900 dark:text-gray-100">No activity logs found</p>
                                <p class="mt-1 text-sm">Try adjusting your filters</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Pagination --}}
            @if($logs->hasPages())
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- 3. JAVASCRIPT LOGIC                                            --}}
    {{-- ============================================================== --}}
    <script>
        // 1. Initial Page Skeleton Logic
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const pageSkeleton = document.getElementById('PageSkeleton');
                const realContent = document.getElementById('RealPageContent');
                if (pageSkeleton) pageSkeleton.remove();
                if (realContent) {
                    realContent.classList.remove('hidden');
                    setTimeout(() => realContent.classList.remove('opacity-0'), 10);
                }
            }, 500);
        });

        // 2. Handle Export Button
        window.handleExport = (btn) => {
            // Show spinner
            showButtonLoading(btn, 'Exporting...');

            // Execute Export
            exportCSV();

            // Reset button after 3 seconds (since page doesn't reload on download)
            setTimeout(() => {
                hideButtonLoading(btn);
            }, 3000);
        };

        // 3. Handle Filter Submission
        window.handleFilter = (form) => {
            const btn = form.querySelector('button[type="submit"]');
            showButtonLoading(btn, 'Filtering...');
        };

        // 4. Existing Export Logic
        function exportCSV() {
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("admin.activity-logs.export") }}';
            form.style.display = 'none';

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            const params = new URLSearchParams(window.location.search);
            for (const [key, value] of params) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = key;
                input.value = value;
                form.appendChild(input);
            }

            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        }
    </script>
</x-app-layout>
