{{-- resources/views/components/notification-bell.blade.php --}}

<div x-data="notificationBell" class="relative">
    <!-- Bell Button -->
    <button
        @click="toggleDropdown"
        class="relative p-2 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 transition"
    >
        <!-- Bell Icon -->
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9">
            </path>
        </svg>

        <!-- Badge -->
        <span
            x-show="unreadCount > 0"
            x-text="unreadCount"
            class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-red-500 rounded-full"
        ></span>
    </button>

    <!-- Dropdown -->
    <div
        x-show="isOpen"
        @click.away="isOpen = false"
        x-transition
        class="absolute right-0 mt-2 w-96 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
    >
        <!-- Header -->
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Notifications</h3>
            <button
                @click="markAllAsRead"
                class="text-xs text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                x-show="unreadCount > 0"
            >
                Mark all as read
            </button>
        </div>

        <!-- Notifications List -->
        <div class="max-h-96 overflow-y-auto">
            <template x-if="notifications.length === 0">
                <div class="px-4 py-8 text-center text-gray-500 dark:text-gray-400">
                    <svg class="w-12 h-12 mx-auto mb-2 text-gray-300 dark:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-sm">No notifications</p>
                </div>
            </template>

            <template x-for="notification in notifications" :key="notification.id">
                <div
                    @click="markAsRead(notification.id)"
                    class="px-4 py-3 border-b border-gray-100 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer transition"
                    :class="{ 'bg-blue-50 dark:bg-blue-900/20': !notification.is_read }"
                >
                    <div class="flex items-start gap-3">
                        <!-- Severity Indicator -->
                        <div
                            class="flex-shrink-0 w-2 h-2 mt-2 rounded-full"
                            :class="{
                                'bg-red-500': notification.severity === 'critical',
                                'bg-yellow-500': notification.severity === 'warning',
                                'bg-blue-500': notification.severity === 'info'
                            }"
                        ></div>

                        <div class="flex-1 min-w-0">
                            <p
                                class="text-sm font-medium text-gray-900 dark:text-white"
                                :class="{ 'font-bold': !notification.is_read }"
                                x-text="notification.title"
                            ></p>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1" x-text="notification.message"></p>
                            <p class="text-xs text-gray-500 dark:text-gray-500 mt-1" x-text="notification.time_ago"></p>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <!-- Footer -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
            <a
                href="/notifications"
                class="block text-sm text-center text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
            >
                View all notifications
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
            console.log('Notification Bell initialized'); // Debug log
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
                console.log('Fetching notifications...'); // Debug log
                const response = await fetch('/notifications/api/recent');

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Notifications received:', data); // Debug log

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
