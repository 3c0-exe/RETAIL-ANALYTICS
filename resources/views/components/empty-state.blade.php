{{-- resources/views/components/empty-state.blade.php --}}
@props([
    'icon' => 'box', // box, chart, users, database, file, calendar, etc.
    'title' => 'No data available',
    'description' => null,
    'actionText' => null,
    'actionRoute' => null,
    'secondaryText' => null,
    'secondaryRoute' => null,
    'size' => 'default' // small, default, large
])

@php
    $sizeClasses = [
        'small' => 'py-8',
        'default' => 'py-12',
        'large' => 'py-16'
    ];

    $iconSizes = [
        'small' => 'w-12 h-12',
        'default' => 'w-16 h-16',
        'large' => 'w-20 h-20'
    ];

    $icons = [
        'box' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4',
        'chart' => 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z',
        'users' => 'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z',
        'database' => 'M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4',
        'file' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
        'calendar' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z',
        'folder' => 'M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z',
        'search' => 'M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z',
        'shopping-cart' => 'M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z',
        'document' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z',
        'upload' => 'M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12',
        'bell' => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
        'trending-up' => 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6',
        'filter' => 'M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z'
    ];
@endphp

<div {{ $attributes->merge(['class' => 'flex flex-col items-center justify-center text-center ' . $sizeClasses[$size]]) }}>
    {{-- Icon --}}
    <div class="flex items-center justify-center mb-4 rounded-full bg-gray-100 dark:bg-gray-800 {{ $iconSizes[$size] }} sm:mb-6">
        <svg class="text-gray-400 dark:text-gray-500 {{ $size === 'small' ? 'w-6 h-6' : ($size === 'large' ? 'w-10 h-10' : 'w-8 h-8') }}"
             fill="none"
             stroke="currentColor"
             viewBox="0 0 24 24">
            <path stroke-linecap="round"
                  stroke-linejoin="round"
                  stroke-width="2"
                  d="{{ $icons[$icon] ?? $icons['box'] }}">
            </path>
        </svg>
    </div>

    {{-- Title --}}
    <h3 class="mb-2 {{ $size === 'small' ? 'text-sm' : 'text-base sm:text-lg' }} font-semibold text-gray-900 dark:text-gray-100">
        {{ $title }}
    </h3>

    {{-- Description --}}
    @if($description)
    <p class="max-w-sm mb-6 text-xs {{ $size === 'small' ? 'sm:text-xs' : 'sm:text-sm' }} text-gray-500 dark:text-gray-400">
        {{ $description }}
    </p>
    @endif

    {{-- Action Buttons --}}
    @if($actionText && $actionRoute)
    <div class="flex flex-col gap-2 sm:flex-row sm:gap-3">
        <a href="{{ $actionRoute }}"
           class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-white transition-colors rounded-lg bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            {{ $actionText }}
        </a>

        @if($secondaryText && $secondaryRoute)
        <a href="{{ $secondaryRoute }}"
           class="inline-flex items-center justify-center px-4 py-2 text-sm font-medium text-gray-700 transition-colors bg-white border border-gray-300 rounded-lg hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
            {{ $secondaryText }}
        </a>
        @endif
    </div>
    @endif

    {{-- Custom Slot for Additional Content --}}
    @if($slot->isNotEmpty())
    <div class="mt-4">
        {{ $slot }}
    </div>
    @endif
</div>
