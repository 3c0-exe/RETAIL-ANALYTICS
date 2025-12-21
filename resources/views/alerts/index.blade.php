<x-app-layout>
    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                    Manage your alerts and notifications
                </p>
            </div>

            @if($alerts->where('is_read', false)->count() > 0)
                <form action="{{ route('alerts.readAll') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                        Mark All as Read
                    </button>
                </form>
            @endif
        </div>

        <!-- Alerts List -->
        <div class="bg-white dark:bg-[#171717] rounded-lg border border-gray-200 dark:border-gray-800 overflow-hidden">
            @forelse($alerts as $alert)
                <div class="border-b border-gray-200 dark:border-gray-800 last:border-0 {{ $alert->is_read ? 'opacity-60' : 'bg-primary-50/30 dark:bg-primary-900/10' }}">
                    <div class="p-4 flex items-start gap-4">
                        <!-- Severity Indicator -->
                        <div class="flex-shrink-0 mt-1">
                            @if($alert->severity === 'critical')
                                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                            @elseif($alert->severity === 'warning')
                                <div class="w-3 h-3 bg-yellow-500 rounded-full"></div>
                            @else
                                <div class="w-3 h-3 bg-blue-500 rounded-full"></div>
                            @endif
                        </div>

                        <!-- Content -->
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-4">
                                <div class="flex-1">
                                    <div class="flex items-center gap-2">
                                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $alert->title }}
                                        </h3>
                                        @if(!$alert->is_read)
                                            <span class="px-2 py-0.5 text-xs font-medium text-primary-700 bg-primary-100 dark:bg-primary-900/30 dark:text-primary-300 rounded-full">
                                                New
                                            </span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                                        {{ $alert->message }}
                                    </p>
                                    <div class="mt-2 flex items-center gap-4 text-xs text-gray-500 dark:text-gray-500">
                                        <span>{{ $alert->created_at->format('M d, Y • g:i A') }}</span>
                                        <span>•</span>
                                        <span>{{ $alert->created_at->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <!-- Actions -->
                                <div class="flex items-center gap-2">
                                    @if(!$alert->is_read)
                                        <form action="{{ route('alerts.read', $alert) }}" method="POST" class="inline">
                                            @csrf
                                            <button type="submit" class="p-1 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400" title="Mark as read">
                                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    @endif

                                    @if($alert->related_type && $alert->related_id)
                                        <a href="{{ route('alerts.show', $alert) }}" class="p-1 text-gray-400 hover:text-primary-600 dark:hover:text-primary-400" title="View details">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                            </svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-12 text-center">
                    <svg class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <h3 class="mt-4 text-lg font-medium text-gray-900 dark:text-white">No notifications</h3>
                    <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">You're all caught up!</p>
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($alerts->hasPages())
            <div class="mt-6">
                {{ $alerts->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
