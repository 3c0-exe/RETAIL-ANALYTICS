<div x-data="{ open: false, unreadCount: 0, alerts: [] }"
     x-init="fetchUnreadAlerts(); setInterval(fetchUnreadAlerts, 60000)"
     @click.away="open = false"
     class="relative">

    {{-- Bell Icon --}}
    <button @click="open = !open" class="relative p-2 text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 rounded-lg">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
        </svg>

        {{-- Badge --}}
        <span x-show="unreadCount > 0"
              x-text="unreadCount > 9 ? '9+' : unreadCount"
              class="absolute -top-1 -right-1 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
        </span>
    </button>

    {{-- Dropdown --}}
    <div x-show="open"
         x-transition
         class="absolute right-0 mt-2 w-80 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50">

        {{-- Header --}}
        <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
            <form method="POST" action="{{ route('alerts.readAll') }}">
                @csrf
                <button type="submit" class="text-xs text-purple-600 hover:text-purple-700">
                    Mark all read
                </button>
            </form>
        </div>

        {{-- Alerts List --}}
        <div class="max-h-96 overflow-y-auto">
            <template x-if="alerts.length === 0">
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400 text-sm">
                    No new notifications
                </div>
            </template>

            <template x-for="alert in alerts" :key="alert.id">
                <div class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                    <div class="flex items-start gap-3">
                        <span x-text="alert.icon" class="text-2xl"></span>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 dark:text-white" x-text="alert.title"></p>
                            <p class="text-xs text-gray-600 dark:text-gray-400 mt-1" x-text="alert.message"></p>
                            <p class="text-xs text-gray-400 dark:text-gray-500 mt-1" x-text="formatDate(alert.created_at)"></p>
                        </div>
                        <button @click="markAsRead(alert.id)" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </template>
        </div>

        {{-- Footer --}}
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            <a href="{{ route('alerts.index') }}" class="text-sm text-purple-600 hover:text-purple-700">
                View all notifications →
            </a>
        </div>
    </div>
</div>

<script>
    function fetchUnreadAlerts() {
        fetch('{{ route("alerts.unread") }}')
            .then(res => res.json())
            .then(data => {
                this.alerts = data;
                this.unreadCount = data.length;
            });
    }

    function markAsRead(id) {
        fetch(`/alerts/${id}/read`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            }
        }).then(() => {
            this.alerts = this.alerts.filter(a => a.id !== id);
            this.unreadCount = this.alerts.length;
        });
    }

    function formatDate(date) {
        const d = new Date(date);
        const now = new Date();
        const diff = Math.floor((now - d) / 1000);

        if (diff < 60) return 'Just now';
        if (diff < 3600) return Math.floor(diff / 60) + 'm ago';
        if (diff < 86400) return Math.floor(diff / 3600) + 'h ago';
        return Math.floor(diff / 86400) + 'd ago';
    }
</script>
