{{-- resources/views/notifications/index.blade.php --}}

<x-app-layout>
    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>

                <div class="flex gap-3">
                    <a href="{{ route('notifications.preferences') }}"
                       class="px-4 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                        ⚙️ Preferences
                    </a>

                    @if($alerts->where('is_read', true)->count() > 0)
                        <form method="POST" action="{{ route('notifications.clear-read') }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 text-sm bg-red-600 text-white rounded-lg hover:bg-red-700 transition"
                                    onclick="return confirm('Clear all read notifications?')">
                                🗑️ Clear Read
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Filters -->
            <div class="flex flex-wrap gap-3 mb-6">
                <a href="{{ route('notifications.index') }}"
                   class="px-4 py-2 text-sm rounded-lg transition {{ !request('filter') ? 'bg-purple-600 text-white' : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600' }}">
                    All
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'unread']) }}"
                   class="px-4 py-2 text-sm rounded-lg transition {{ request('filter') === 'unread' ? 'bg-purple-600 text-white' : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600' }}">
                    Unread ({{ auth()->user()->unreadAlertsCount() }})
                </a>
                <a href="{{ route('notifications.index', ['filter' => 'read']) }}"
                   class="px-4 py-2 text-sm rounded-lg transition {{ request('filter') === 'read' ? 'bg-purple-600 text-white' : 'bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600' }}">
                    Read
                </a>

                <!-- Type Filter Dropdown -->
                <select onchange="window.location.href='{{ route('notifications.index') }}?type=' + this.value"
                        class="px-4 py-2 text-sm rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800">
                    <option value="">All Types</option>
                    @foreach($types as $key => $label)
                        <option value="{{ $key }}" {{ request('type') === $key ? 'selected' : '' }}>
                            {{ $label }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Notifications List -->
            <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                @forelse($alerts as $alert)
                    <div class="border-b border-gray-200 dark:border-gray-700 last:border-0 p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition {{ !$alert->is_read ? 'bg-blue-50 dark:bg-blue-900/20' : '' }}">
                        <div class="flex items-start gap-4">
                            <!-- Severity Indicator -->
                            <div class="flex-shrink-0">
                                <div class="w-3 h-3 rounded-full mt-1 {{
                                    $alert->severity === 'critical' ? 'bg-red-500' :
                                    ($alert->severity === 'warning' ? 'bg-yellow-500' : 'bg-blue-500')
                                }}"></div>
                            </div>

                            <!-- Content -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white {{ !$alert->is_read ? 'font-bold' : '' }}">
                                            {{ $alert->title }}
                                        </h3>
                                        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                                            {{ $alert->message }}
                                        </p>

                                        <!-- Metadata -->
                                        @if($alert->metadata)
                                            <div class="mt-2 text-xs text-gray-500 dark:text-gray-500">
                                                @foreach($alert->metadata as $key => $value)
                                                    <span class="inline-block mr-3">
                                                        <strong>{{ ucwords(str_replace('_', ' ', $key)) }}:</strong> {{ $value }}
                                                    </span>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Mark as Read Button -->
                                    @if(!$alert->is_read)
                                        <form method="POST" action="{{ route('notifications.mark-read', $alert->id) }}">
                                            @csrf
                                            <button type="submit" class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                                                Mark as read
                                            </button>
                                        </form>
                                    @endif
                                </div>

                                <div class="flex items-center gap-4 mt-2 text-xs text-gray-500 dark:text-gray-500">
                                    <span>{{ $alert->created_at->diffForHumans() }}</span>
                                    <span class="px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-700">
                                        {{ ucwords(str_replace('_', ' ', $alert->type)) }}
                                    </span>
                                    <span class="px-2 py-0.5 rounded-full {{
                                        $alert->severity === 'critical' ? 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300' :
                                        ($alert->severity === 'warning' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300' : 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300')
                                    }}">
                                        {{ ucfirst($alert->severity) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500 dark:text-gray-400">No notifications found</p>
                    </div>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $alerts->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
