<x-app-layout>
    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8 sm:py-6">

        {{-- ============================================================== --}}
        {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-4 sm:space-y-6">

            <div class="flex items-center gap-2 mb-3 sm:mb-4">
                <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-20 sm:w-24"></div>
                <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-3 sm:w-4"></div>
                <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-16 sm:w-20"></div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-4 sm:mb-6">
                <div class="flex-1">
                    <div class="h-7 sm:h-8 bg-gray-200 rounded dark:bg-gray-800 w-40 sm:w-48 mb-2"></div>
                    <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-56 sm:w-64"></div>
                </div>
                <div class="flex gap-2">
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-24 sm:w-28"></div>
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-24 sm:w-28"></div>
                </div>
            </div>

            <div class="mb-4 sm:mb-6 bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-3 sm:p-4">
                <div class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:gap-4">
                    <div class="flex-1 h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 min-w-full sm:min-w-[200px]"></div>
                    <div class="w-full sm:w-32 h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800"></div>
                    <div class="w-20 sm:w-24 h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800"></div>
                    <div class="w-16 sm:w-20 h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800"></div>
                </div>
            </div>

            <div class="space-y-2 sm:space-y-2">
                @for($i=0; $i<6; $i++)
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-3 sm:p-4">
                        <div class="flex items-start gap-3 sm:gap-4">
                            <div class="w-14 sm:w-16 h-5 sm:h-6 bg-gray-200 rounded-full dark:bg-gray-800 shrink-0"></div>

                            <div class="flex-1 space-y-2 min-w-0">
                                <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-3/4"></div>
                                <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-2/3 sm:w-1/3"></div>
                            </div>

                            <div class="w-4 sm:w-5 h-4 sm:h-5 bg-gray-200 rounded dark:bg-gray-800 shrink-0"></div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500 ease-in-out">
            <nav class="mb-3 sm:mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xs sm:text-sm text-gray-700 hover:text-purple-600 dark:text-gray-400 transition-colors">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Error Logs</span>
                        </div>
                    </li>
                </ol>
            </nav>

        <!-- Page Header -->
        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-4 sm:mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Error Logs
                </h1>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    View and manage Laravel error logs
                </p>
            </div>

            <div class="flex gap-2 flex-shrink-0">
                <a href="{{ route('admin.error-logs.download') }}"
                   class="flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors whitespace-nowrap">
                    <span class="hidden sm:inline">📥 Download</span>
                    <span class="sm:hidden">📥</span>
                </a>
                <form method="POST" action="{{ route('admin.error-logs.clear') }}" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Clear all logs?')"
                            class="flex items-center justify-center px-3 sm:px-4 py-2 text-xs sm:text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700 transition-colors whitespace-nowrap">
                        <span class="hidden sm:inline">🗑️ Clear Logs</span>
                        <span class="sm:hidden">🗑️</span>
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="p-3 sm:p-4 mb-4 sm:mb-6 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800">
            <p class="text-xs sm:text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Filters -->
        <div class="mb-4 sm:mb-6 bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-3 sm:p-4">
            <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:flex-wrap sm:gap-4">
                <div class="flex-1 min-w-full sm:min-w-[200px]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search logs..."
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow">
                </div>

                <select name="level" class="w-full sm:w-auto px-3 py-2 text-sm border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow">
                    <option value="all" {{ $filter === 'all' ? 'selected' : '' }}>All Levels</option>
                    <option value="emergency" {{ $filter === 'emergency' ? 'selected' : '' }}>Emergency</option>
                    <option value="alert" {{ $filter === 'alert' ? 'selected' : '' }}>Alert</option>
                    <option value="critical" {{ $filter === 'critical' ? 'selected' : '' }}>Critical</option>
                    <option value="error" {{ $filter === 'error' ? 'selected' : '' }}>Error</option>
                    <option value="warning" {{ $filter === 'warning' ? 'selected' : '' }}>Warning</option>
                    <option value="notice" {{ $filter === 'notice' ? 'selected' : '' }}>Notice</option>
                    <option value="info" {{ $filter === 'info' ? 'selected' : '' }}>Info</option>
                    <option value="debug" {{ $filter === 'debug' ? 'selected' : '' }}>Debug</option>
                </select>

                <div class="flex gap-2 sm:gap-3">
                    <button type="submit" class="flex-1 sm:flex-none px-4 py-2 text-xs sm:text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700 transition-colors">
                        Filter
                    </button>

                    @if($filter !== 'all' || $search)
                    <a href="{{ route('admin.error-logs.index') }}" class="flex-1 sm:flex-none px-4 py-2 text-xs sm:text-sm font-medium text-center text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Clear
                    </a>
                    @endif
                </div>
            </form>
        </div>

        <!-- Logs List -->
        @if(empty($logs))
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 sm:p-8 text-center">
            <div class="mb-3 sm:mb-4 text-5xl sm:text-6xl text-gray-400 dark:text-gray-600">📋</div>
            <p class="text-sm sm:text-base text-gray-500 dark:text-gray-400">No error logs found.</p>
        </div>
        @else
        <div class="space-y-2">
            @foreach($logs as $index => $log)
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden hover:shadow-sm transition-shadow" x-data="{ expanded: false }">
                <!-- Log Summary -->
                <div class="flex items-start gap-3 sm:gap-4 p-3 sm:p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors" @click="expanded = !expanded">
                    <!-- Level Badge -->
                    <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 text-xs font-semibold rounded-full shrink-0
                        {{ $log['level'] === 'ERROR' || $log['level'] === 'CRITICAL' || $log['level'] === 'EMERGENCY' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}
                        {{ $log['level'] === 'WARNING' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                        {{ $log['level'] === 'INFO' || $log['level'] === 'NOTICE' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                        {{ $log['level'] === 'DEBUG' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400' : '' }}">
                        {{ $log['level'] }}
                    </span>

                    <!-- Message -->
                    <div class="flex-1 min-w-0">
                        <p class="text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100 line-clamp-2 break-words">
                            {{ $log['message'] }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 flex flex-wrap items-center gap-1">
                            <span>{{ \Carbon\Carbon::parse($log['timestamp'])->diffForHumans() }}</span>
                            <span class="text-gray-400 dark:text-gray-600">•</span>
                            <span class="break-all">{{ $log['timestamp'] }}</span>
                        </p>
                    </div>

                    <!-- Expand Icon -->
                    @if($log['context'])
                    <div class="text-gray-400 shrink-0 mt-0.5">
                        <svg x-show="!expanded" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <svg x-show="expanded" class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Expandable Details -->
                @if($log['context'])
                <div x-show="expanded" x-collapse class="p-3 sm:p-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                    <pre class="overflow-x-auto font-mono text-xs text-gray-700 whitespace-pre-wrap break-words dark:text-gray-300">{{ trim($log['context']) }}</pre>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="p-3 sm:p-4 mt-4 sm:mt-6 rounded-lg bg-blue-50 dark:bg-blue-900/20 border border-blue-100 dark:border-blue-900">
            <p class="text-xs text-blue-800 dark:text-blue-300">
                💡 <strong>Tip:</strong> Showing last 100 log entries. Click any log to expand details.
            </p>
        </div>
        @endif
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
                    setTimeout(() => {
                        content.classList.remove('opacity-0');
                    }, 50);
                }
            }, 500);
        });
    </script>
</x-app-layout>
