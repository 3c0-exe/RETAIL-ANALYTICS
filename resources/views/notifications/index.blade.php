<x-app-layout>
    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                            Notifications
                        </h1>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                            Stay updated with your inventory alerts
                        </p>
                    </div>

                    <!-- Actions - Stacked on Mobile -->
                    <div class="flex flex-col sm:flex-row gap-2 sm:gap-3">
                        <a href="{{ route('notifications.preferences') }}"
                           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Preferences
                        </a>

                        @if($alerts->where('is_read', true)->count() > 0)
                            <form method="POST" action="{{ route('notifications.clear-read') }}" class="w-full sm:w-auto">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 text-sm font-medium bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all shadow-sm"
                                        onclick="return confirm('Clear all read notifications?')">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                    Clear Read
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="mb-4">
                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700 p-3">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3">
                        <!-- Status Filters -->
                        <div class="flex gap-2 flex-1">
                            <a href="{{ route('notifications.index') }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-md transition-all {{ !request('filter') ? 'bg-purple-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                All
                            </a>
                            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-md transition-all inline-flex items-center gap-1.5 {{ request('filter') === 'unread' ? 'bg-purple-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                Unread
                                @if(auth()->user()->unreadAlertsCount() > 0)
                                    <span class="px-1.5 py-0.5 text-xs font-semibold rounded-full {{ request('filter') === 'unread' ? 'bg-white/20' : 'bg-purple-100 dark:bg-purple-900/40 text-purple-700 dark:text-purple-300' }}">
                                        {{ auth()->user()->unreadAlertsCount() }}
                                    </span>
                                @endif
                            </a>
                            <a href="{{ route('notifications.index', ['filter' => 'read']) }}"
                               class="px-3 py-1.5 text-sm font-medium rounded-md transition-all {{ request('filter') === 'read' ? 'bg-purple-600 text-white' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                                Read
                            </a>
                        </div>

                        <!-- Type Filter -->
                        <select onchange="window.location.href='{{ route('notifications.index') }}?type=' + this.value"
                                class="px-3 py-1.5 text-sm rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 focus:outline-none focus:ring-2 focus:ring-purple-500">
                            <option value="">All Types</option>
                            @foreach($types as $key => $label)
                                <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>

            <!-- Notifications List - Improved Mobile Layout -->
            <div class="space-y-2 sm:space-y-0 sm:bg-white sm:dark:bg-gray-800 sm:rounded-lg sm:border sm:border-gray-200 sm:dark:border-gray-700 sm:overflow-hidden">
                @forelse($alerts as $alert)
                    <details class="group bg-white dark:bg-gray-800 rounded-lg sm:rounded-none border border-gray-200 dark:border-gray-700 sm:border-0 sm:border-b sm:last:border-0 overflow-hidden {{ !$alert->is_read ? 'ring-2 ring-blue-200 dark:ring-blue-800 sm:ring-0 sm:bg-blue-50/30 sm:dark:bg-blue-900/5' : '' }}">
                        <summary class="cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors list-none">
                            <div class="p-4">
                                <div class="flex gap-3">
                                    <!-- Icon -->
                                    <div class="flex-shrink-0">
                                        <div class="w-10 h-10 rounded-lg flex items-center justify-center {{
                                            $alert->severity === 'critical' ? 'bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400' :
                                            ($alert->severity === 'warning' ? 'bg-yellow-100 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400' : 'bg-blue-100 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400')
                                        }}">
                                            @if($alert->severity === 'critical')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                </svg>
                                            @elseif($alert->severity === 'warning')
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1 min-w-0">
                                        <!-- Title Row -->
                                        <div class="flex items-start justify-between gap-2 mb-1.5">
                                            <h3 class="font-semibold text-sm text-gray-900 dark:text-white leading-tight pr-2">
                                                {{ $alert->title }}
                                            </h3>
                                            <div class="flex items-center gap-2 flex-shrink-0">
                                                @if(!$alert->is_read)
                                                    <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                                @endif
                                                <svg class="w-5 h-5 text-gray-400 transition-transform group-open:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </div>
                                        </div>

                                        <!-- Preview Message -->
                                        <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2 leading-relaxed mb-2">
                                            {{ $alert->message }}
                                        </p>

                                        <!-- Meta Info -->
                                        <div class="flex items-center gap-2 flex-wrap">
                                            <span class="px-2 py-0.5 rounded text-xs font-medium {{
                                                $alert->severity === 'critical' ? 'bg-red-100 text-red-700 dark:bg-red-900/30 dark:text-red-300' :
                                                ($alert->severity === 'warning' ? 'bg-yellow-100 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-blue-100 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300')
                                            }}">
                                                {{ ucfirst($alert->severity) }}
                                            </span>
                                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                                {{ $alert->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </summary>

                        <!-- Expanded Content -->
                        <div class="px-4 pb-4 border-t border-gray-100 dark:border-gray-700">
                            <div class="mt-4 space-y-4">
                                <!-- Full Message -->
                                <div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed">
                                        {{ $alert->message }}
                                    </p>
                                </div>

                                <!-- Metadata -->
                                @if($alert->metadata)
                                    <div class="grid grid-cols-2 gap-3 p-3 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700">
                                        @foreach($alert->metadata as $key => $value)
                                            <div>
                                                <div class="text-xs text-gray-500 dark:text-gray-500 mb-1">
                                                    {{ ucwords(str_replace('_', ' ', $key)) }}
                                                </div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white break-words">
                                                    {{ is_array($value) ? json_encode($value) : $value }}
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Footer -->
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex flex-wrap items-center gap-2 text-xs">
                                        <span class="inline-flex items-center gap-1.5 text-gray-500 dark:text-gray-400">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            {{ $alert->created_at->format('M d, Y g:i A') }}
                                        </span>

                                        <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 dark:bg-gray-700 text-gray-700 dark:text-gray-300">
                                            {{ ucwords(str_replace('_', ' ', $alert->type)) }}
                                        </span>
                                    </div>

                                    @if(!$alert->is_read)
                                        <form method="POST" action="{{ route('notifications.mark-read', $alert->id) }}">
                                            @csrf
                                            <button type="submit"
                                                    class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 text-xs font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-all">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                </svg>
                                                Mark as read
                                            </button>
                                        </form>
                                    @else
                                        <span class="inline-flex items-center gap-1.5 text-xs text-gray-400 dark:text-gray-500">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Read
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </details>
                @empty
                    <div class="text-center py-12 px-4 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                        <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-700 mb-3">
                            <svg class="w-8 h-8 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                        </div>
                        <h3 class="text-base font-medium text-gray-900 dark:text-white mb-1">No notifications</h3>
                        <p class="text-sm text-gray-500 dark:text-gray-400">You're all caught up!</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            @if($alerts->hasPages())
                <div class="mt-4">
                    {{ $alerts->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
