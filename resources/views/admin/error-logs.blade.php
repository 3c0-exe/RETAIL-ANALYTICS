<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100">
                    Error Logs
                </h1>
                <p class="mt-1 text-sm text-gray-600 sm:mt-2 dark:text-gray-400">
                    View and manage Laravel error logs
                </p>
            </div>

            <div class="flex gap-2">
                <a href="{{ route('admin.error-logs.download') }}"
                   class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    📥 Download
                </a>
                <form method="POST" action="{{ route('admin.error-logs.clear') }}" class="inline">
                    @csrf
                    <button type="submit" onclick="return confirm('Clear all logs?')"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                        🗑️ Clear Logs
                    </button>
                </form>
            </div>
        </div>

        @if(session('success'))
        <div class="p-4 mb-6 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800">
            <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Filters -->
        <div class="mb-6 bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4">
            <form method="GET" class="flex flex-wrap gap-4">
                <div class="flex-1 min-w-[200px]">
                    <input type="text" name="search" value="{{ $search }}" placeholder="Search logs..."
                           class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                </div>

                <select name="level" class="px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
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

                <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                    Filter
                </button>

                @if($filter !== 'all' || $search)
                <a href="{{ route('admin.error-logs.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    Clear
                </a>
                @endif
            </form>
        </div>

        <!-- Logs List -->
        @if(empty($logs))
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-8 text-center">
            <div class="mb-4 text-6xl text-gray-400 dark:text-gray-600">📝</div>
            <p class="text-gray-500 dark:text-gray-400">No error logs found.</p>
        </div>
        @else
        <div class="space-y-2">
            @foreach($logs as $index => $log)
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden" x-data="{ expanded: false }">
                <!-- Log Summary -->
                <div class="flex items-start gap-4 p-4 cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-900/50" @click="expanded = !expanded">
                    <!-- Level Badge -->
                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full shrink-0
                        {{ $log['level'] === 'ERROR' || $log['level'] === 'CRITICAL' || $log['level'] === 'EMERGENCY' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}
                        {{ $log['level'] === 'WARNING' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                        {{ $log['level'] === 'INFO' || $log['level'] === 'NOTICE' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                        {{ $log['level'] === 'DEBUG' ? 'bg-gray-100 text-gray-800 dark:bg-gray-900/20 dark:text-gray-400' : '' }}">
                        {{ $log['level'] }}
                    </span>

                    <!-- Message -->
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-medium text-gray-900 dark:text-gray-100 line-clamp-2">
                            {{ $log['message'] }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($log['timestamp'])->diffForHumans() }}
                            <span class="text-gray-400 dark:text-gray-600">•</span>
                            {{ $log['timestamp'] }}
                        </p>
                    </div>

                    <!-- Expand Icon -->
                    @if($log['context'])
                    <div class="text-gray-400">
                        <svg x-show="!expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                        <svg x-show="expanded" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                        </svg>
                    </div>
                    @endif
                </div>

                <!-- Expandable Details -->
                @if($log['context'])
                <div x-show="expanded" x-collapse class="p-4 border-t border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                    <pre class="overflow-x-auto font-mono text-xs text-gray-700 whitespace-pre-wrap dark:text-gray-300">{{ trim($log['context']) }}</pre>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        <div class="p-4 mt-6 rounded-lg bg-blue-50 dark:bg-blue-900/20">
            <p class="text-xs text-blue-800 dark:text-blue-300">
                💡 <strong>Tip:</strong> Showing last 100 log entries. Click any log to expand details.
            </p>
        </div>
        @endif
    </div>
</x-app-layout>
