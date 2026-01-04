{{-- resources/views/components/notification-bell.blade.php --}}

<div x-data="notificationBell" class="relative">
    <!-- Bell Button -->
    <button
        @click="toggleDropdown"
        class="relative p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition rounded-md hover:bg-gray-100 dark:hover:bg-gray-800"
    >
        <!-- Bell Icon -->
        <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>

        <!-- Badge -->
        <span
            x-show="unreadCount > 0"
            x-text="unreadCount > 9 ? '9+' : unreadCount"
            class="absolute -top-1 -right-1 inline-flex items-center justify-center min-w-[18px] h-[18px] px-1 text-xs font-bold leading-none text-white bg-red-500 rounded-full"
        ></span>
    </button>

    <!-- Dropdown -->
    <div
        x-show="isOpen"
        @click.away="isOpen = false"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed sm:absolute inset-x-4 top-16 sm:inset-x-auto sm:right-0 sm:top-auto sm:mt-2 w-auto sm:w-96 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 max-h-[80vh] sm:max-h-[500px] flex flex-col"
        style="display: none;"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700 flex-shrink-0">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
            <button
                @click="markAllAsRead"
                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
                x-show="unreadCount > 0"
            >
                Mark all read
            </button>
        </div>

        <!-- Notifications List (Scrollable) -->
        <div class="overflow-y-auto flex-1">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    <svg class="w-10 h-10 sm:w-12 sm:h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-sm">No notifications</p>
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div
                    @click="markAsRead(notification.id)"
                    class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 last:border-0 hover:bg-gray-50 dark:hover:bg-gray-700/50 cursor-pointer transition"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.is_read }"
                >
                    <div class="flex items-start gap-3">
                        <!-- Severity Indicator -->
                        <div
                            class="flex-shrink-0 w-2 h-2 mt-1.5 rounded-full"
                            :class="{
                                'bg-red-500': notification.severity === 'critical',
                                'bg-yellow-500': notification.severity === 'warning',
                                'bg-blue-500': notification.severity === 'info'
                            }"
                        ></div>

                        <div class="flex-1 min-w-0">
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-white break-words"
                                :class="{ 'font-bold': !notification.is_read }"
                                x-text="notification.title"
                            ></p>
                            <p class="text-xs sm:text-sm text-gray-600 dark:text-gray-400 mt-1 break-words line-clamp-2" x-text="notification.message"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" x-text="notification.time_ago"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700 flex-shrink-0">
            <a
                href="{{ route('notifications.index') }}"
                class="block text-sm text-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium"
            >
                View all notifications →
            </a>
        </div>
    </div>
</div>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('notificationBell', () => ({
        isOpen: false,
        notifications: [],
        unreadCount: 0,

        init() {
            this.fetchNotifications();
            // Poll every 30 seconds
            setInterval(() => this.fetchNotifications(), 30000);
        },

        toggleDropdown() {
            this.isOpen = !this.isOpen;
            if (this.isOpen) {
                this.fetchNotifications();
            }
        },

        async fetchNotifications() {
            try {
                const response = await fetch('/notifications/api/recent');

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();

                this.notifications = data.notifications || [];
                this.unreadCount = data.unread_count || 0;
            } catch (error) {
                console.error('Failed to fetch notifications:', error);
            }
        },

        async markAsRead(notificationId) {
            try {
                const response = await fetch(`/notifications/api/${notificationId}/read`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    this.fetchNotifications();
                    // Close dropdown after marking as read
                    this.isOpen = false;
                }
            } catch (error) {
                console.error('Failed to mark as read:', error);
            }
        },

        async markAllAsRead() {
            try {
                const response = await fetch('/notifications/api/mark-all-read', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Content-Type': 'application/json'
                    }
                });

                if (response.ok) {
                    this.fetchNotifications();
                }
            } catch (error) {
                console.error('Failed to mark all as read:', error);
            }
        }
    }));
});
</script>

<style>
/* Line clamp for notification message */
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>
