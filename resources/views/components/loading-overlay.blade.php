{{-- resources/views/components/loading-overlay.blade.php --}}
@props([
    'show' => false,
    'message' => 'Loading...',
    'transparent' => false
])

<div x-data="{ show: {{ $show ? 'true' : 'false' }} }"
     x-show="show"
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     {{ $attributes->merge(['class' => 'fixed inset-0 z-50 flex items-center justify-center']) }}
     style="display: none;">

    <!-- Backdrop -->
    <div class="absolute inset-0 {{ $transparent ? 'bg-white/50 dark:bg-black/50' : 'bg-white/90 dark:bg-black/90' }}"></div>

    <!-- Loading Content -->
    <div class="relative z-10 flex flex-col items-center gap-4 p-8 bg-white border border-gray-200 rounded-lg shadow-xl dark:bg-gray-900 dark:border-gray-800">
        <!-- Spinner -->
        <svg class="w-12 h-12 text-purple-600 animate-spin dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>

        <!-- Message -->
        <p class="text-base font-medium text-gray-900 dark:text-gray-100">
            {{ $message }}
        </p>

        <!-- Additional Content Slot -->
        @if($slot->isNotEmpty())
            <div class="text-sm text-gray-600 dark:text-gray-400">
                {{ $slot }}
            </div>
        @endif
    </div>
</div>
