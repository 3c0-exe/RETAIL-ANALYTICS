<div x-data="{ open: false, unreadCount: {{ $unreadCount }} }"
     @click.away="open = false"
     class="relative">

    {{-- Bell Icon --}}
    <button @click="open = !open"
            class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 rounded-lg transition-colors">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>

        {{-- Badge --}}
        @if($unreadCount > 0)
            <span x-text="unreadCount > 9 ? '9+' : unreadCount"
                  class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-[#171717] rounded-lg shadow-lg border border-gray-200 dark:border-gray-800 z-50"
         style="display: none;">

        {{-- Header --}}
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-800 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
            @if($unreadCount > 0)
                <form method="POST" action="{{ route('alerts.readAll') }}">
                    @csrf
                    <button type="submit"
                            class="text-xs text-primary-600 dark:text-primary-400 hover:underline">
                        Mark all read
                    </button>
                </form>
            @endif
        </div>

        {{-- Alerts List --}}
        <div class="max-h-96 overflow-y-auto">
            @forelse($recentAlerts as $alert)
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors">
                    <div class="flex items-start gap-3">
                        <span class="text-2xl flex-shrink-0">{{ $alert->icon }}</span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white">{{ $alert->title }}</p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 line-clamp-2">{{ $alert->message }}</p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1">{{ $alert->created_at->diffForHumans() }}</p>
                        </div>
                        <form method="POST" action="{{ route('alerts.read', $alert) }}" class="flex-shrink-0">
                            @csrf
                            <button type="submit"
                                    @click="unreadCount = Math.max(0, unreadCount - 1); $el.closest('.px-4').remove()"
                                    class="text-gray-400 hover:text-primary-600 dark:hover:text-primary-400"
                                    title="Mark as read">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    No new notifications
                </div>
            @endforelse
        </div>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-800">
            <a href="{{ route('alerts.index') }}" class="text-sm text-primary-600 dark:text-primary-400 hover:underline block text-center">
                View all notifications →
            </a>
        </div>
    </div>
</div>
