<x-app-layout>
    <div class="py-4 sm:py-6 lg:py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Header -->
            <div class="mb-6 sm:mb-8">
                <a href="{{ route('notifications.index') }}"
                   class="inline-flex items-center gap-2 text-sm font-medium text-blue-600 hover:text-blue-700 dark:text-blue-400 dark:hover:text-blue-300 mb-4 transition-colors group">
                    <svg class="w-4 h-4 transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Notifications
                </a>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-white tracking-tight">
                    Notification Preferences
                </h1>
                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 mt-2">
                    Customize which email notifications you receive
                </p>
            </div>

            <!-- Preferences Form -->
            <form method="POST" action="{{ route('notifications.preferences.update') }}"
                  class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
                @csrf
                @method('PUT')

                <div class="p-5 sm:p-6 lg:p-8">
                    <div class="space-y-6 sm:space-y-8">

                        <!-- Low Stock Alerts -->
                        <div class="flex gap-4 p-4 sm:p-5 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 transition-colors">
                            <div class="flex items-center h-5 mt-1">
                                <input type="checkbox"
                                       name="email_low_stock"
                                       id="email_low_stock"
                                       value="1"
                                       {{ (auth()->user()->notification_preferences['email_low_stock'] ?? true) ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-offset-gray-800 cursor-pointer transition-all">
                            </div>
                            <div class="flex-1 min-w-0">
                                <label for="email_low_stock" class="block font-semibold text-base sm:text-lg text-gray-900 dark:text-white cursor-pointer mb-2">
                                    📦 Low Stock Alerts
                                </label>
                                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                                    Get notified via email when products fall below their minimum stock threshold, helping you maintain inventory levels
                                </p>
                            </div>
                        </div>

                        <!-- Forecast Deviation Alerts -->
                        <div class="flex gap-4 p-4 sm:p-5 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 transition-colors">
                            <div class="flex items-center h-5 mt-1">
                                <input type="checkbox"
                                       name="email_forecast_deviation"
                                       id="email_forecast_deviation"
                                       value="1"
                                       {{ (auth()->user()->notification_preferences['email_forecast_deviation'] ?? true) ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-offset-gray-800 cursor-pointer transition-all">
                            </div>
                            <div class="flex-1 min-w-0">
                                <label for="email_forecast_deviation" class="block font-semibold text-base sm:text-lg text-gray-900 dark:text-white cursor-pointer mb-2">
                                    📊 Forecast Deviation Alerts
                                </label>
                                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                                    Receive alerts when actual sales significantly differ from forecasted amounts, allowing you to adjust your strategy
                                </p>
                            </div>
                        </div>

                        <!-- High Value Transaction Alerts -->
                        <div class="flex gap-4 p-4 sm:p-5 bg-gray-50 dark:bg-gray-900/50 rounded-lg border border-gray-100 dark:border-gray-700 hover:border-gray-200 dark:hover:border-gray-600 transition-colors">
                            <div class="flex items-center h-5 mt-1">
                                <input type="checkbox"
                                       name="email_high_value_transaction"
                                       id="email_high_value_transaction"
                                       value="1"
                                       {{ (auth()->user()->notification_preferences['email_high_value_transaction'] ?? true) ? 'checked' : '' }}
                                       class="w-5 h-5 text-blue-600 border-gray-300 rounded focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:border-gray-600 dark:bg-gray-700 dark:focus:ring-offset-gray-800 cursor-pointer transition-all">
                            </div>
                            <div class="flex-1 min-w-0">
                                <label for="email_high_value_transaction" class="block font-semibold text-base sm:text-lg text-gray-900 dark:text-white cursor-pointer mb-2">
                                    💰 High Value Transactions
                                </label>
                                <p class="text-sm sm:text-base text-gray-600 dark:text-gray-400 leading-relaxed">
                                    Stay informed about large transactions over ₱10,000 to monitor significant business activity
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="px-5 py-4 sm:px-6 sm:py-5 lg:px-8 bg-gray-50 dark:bg-gray-900/50 border-t border-gray-200 dark:border-gray-700">
                    <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3">
                        <a href="{{ route('notifications.index') }}"
                           class="w-full sm:w-auto px-6 py-2.5 text-center text-sm font-medium bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-600 hover:border-gray-400 dark:hover:border-gray-500 transition-all">
                            Cancel
                        </a>
                        <button type="submit"
                                class="w-full sm:w-auto px-6 py-2.5 text-sm font-medium bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all shadow-sm hover:shadow inline-flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Save Preferences
                        </button>
                    </div>
                </div>
            </form>

            <!-- Info Box -->
            <div class="mt-6 bg-gradient-to-br from-blue-50 to-blue-100 dark:from-blue-900/20 dark:to-blue-900/10 border border-blue-200 dark:border-blue-800 rounded-xl shadow-sm overflow-hidden">
                <div class="p-5 sm:p-6">
                    <div class="flex gap-4">
                        <div class="flex-shrink-0">
                            <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full bg-blue-100 dark:bg-blue-900/50 flex items-center justify-center">
                                <svg class="w-5 h-5 sm:w-6 sm:h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="flex-1 min-w-0">
                            <h3 class="font-semibold text-base sm:text-lg text-blue-900 dark:text-blue-200 mb-2">
                                About Email Notifications
                            </h3>
                            <p class="text-sm sm:text-base text-blue-800 dark:text-blue-300 leading-relaxed">
                                You'll always see notifications in your bell icon (🔔) at the top of the screen. These email preferences give you an extra layer of awareness by sending notifications to your inbox for important events.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Additional Tips -->
            <div class="mt-6 grid sm:grid-cols-2 gap-4">
                <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 dark:bg-purple-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-sm text-gray-900 dark:text-white mb-1">Instant Updates</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Changes take effect immediately</p>
                        </div>
                    </div>
                </div>

                <div class="p-4 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                    <div class="flex items-start gap-3">
                        <div class="w-8 h-8 rounded-lg bg-green-100 dark:bg-green-900/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-medium text-sm text-gray-900 dark:text-white mb-1">Spam-Free</h4>
                            <p class="text-xs text-gray-600 dark:text-gray-400">Only important alerts are sent</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
