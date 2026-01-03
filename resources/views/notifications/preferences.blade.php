{{-- resources/views/notifications/preferences.blade.php --}}

<x-app-layout>
    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notification Preferences</h1>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">
                        Choose which notifications you want to receive via email. In-app notifications are always enabled.
                    </p>
                </div>
                <a href="{{ route('notifications.index') }}"
                   class="px-4 py-2 text-sm bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                    ← Back
                </a>
            </div>

            <!-- Form -->
            <form method="POST" action="{{ route('notifications.preferences.update') }}">
                @csrf
                @method('PUT')

                <div class="bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-700">
                    <!-- Table Header -->
                    <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-200 dark:border-gray-700 font-semibold text-sm text-gray-700 dark:text-gray-300">
                        <div>Notification Type</div>
                        <div class="text-center">In-App</div>
                        <div class="text-center">Email</div>
                    </div>

                    <!-- Notification Types -->
                    @foreach($types as $type => $label)
                        @php
                            $emailEnabled = $preferences[$type] ?? $defaults[$type]['email'] ?? true;
                            $inAppEnabled = true; // Always enabled
                        @endphp

                        <div class="grid grid-cols-3 gap-4 p-4 border-b border-gray-200 dark:border-gray-700 last:border-0 items-center hover:bg-gray-50 dark:hover:bg-gray-700/50 transition">
                            <!-- Label -->
                            <div>
                                <div class="font-medium text-gray-900 dark:text-white">{{ $label }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-500 mt-1">
                                    {{ $this->getNotificationDescription($type) }}
                                </div>
                            </div>

                            <!-- In-App Toggle (Always On) -->
                            <div class="flex justify-center">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                    ✓ Always On
                                </span>
                            </div>

                            <!-- Email Toggle -->
                            <div class="flex justify-center">
                                <label class="relative inline-flex items-center cursor-pointer">
                                    <input
                                        type="checkbox"
                                        name="email_{{ $type }}"
                                        class="sr-only peer"
                                        {{ $emailEnabled ? 'checked' : '' }}
                                    >
                                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-purple-300 dark:peer-focus:ring-purple-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-purple-600"></div>
                                </label>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- Submit Button -->
                <div class="mt-6 flex justify-end">
                    <button
                        type="submit"
                        class="px-6 py-2.5 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition font-medium"
                    >
                        Save Preferences
                    </button>
                </div>
            </form>

            <!-- Info Box -->
            <div class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 border border-blue-200 dark:border-blue-800 rounded-lg">
                <div class="flex gap-3">
                    <svg class="w-5 h-5 text-blue-600 dark:text-blue-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                    </svg>
                    <div class="text-sm text-blue-800 dark:text-blue-200">
                        <strong>Note:</strong> Critical alerts (like out of stock) will always be sent via email regardless of your preferences.
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

@php
function getNotificationDescription($type) {
    return match($type) {
        'low_stock' => 'When product inventory falls below threshold',
        'out_of_stock' => 'When a product runs out completely',
        'overstock' => 'When inventory exceeds recommended levels',
        'sales_drop' => 'When sales drop significantly below average',
        'high_value_transaction' => 'When a transaction exceeds ₱10,000',
        'daily_summary' => 'End-of-day sales report',
        'import_completion' => 'When data imports finish',
        'forecast_deviation' => 'When actual sales differ from predictions',
        'customer_segment_change' => 'When customers move between segments',
        'failed_login' => 'When suspicious login activity is detected',
        'new_user' => 'When new users are added to the system',
        default => '',
    };
}
@endphp
