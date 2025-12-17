@php
use Illuminate\Support\Facades\Storage;
@endphp


<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ darkMode: localStorage.getItem('theme') === 'dark' || (localStorage.getItem('theme') === null && '{{ auth()->user()->theme ?? 'light' }}' === 'dark') }" x-init="$watch('darkMode', val => { localStorage.setItem('theme', val ? 'dark' : 'light'); document.documentElement.classList.toggle('dark', val); })" :class="{ 'dark': darkMode }">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100">
        <div class="flex min-h-screen">
            <!-- Sidebar -->
            <aside class="hidden lg:flex lg:flex-col w-64 border-r border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-[#171717]">
                <!-- Logo -->
                <div class="flex items-center h-16 px-6 border-b border-gray-200 dark:border-gray-800">
                    <h1 class="text-xl font-bold text-primary-600 dark:text-primary-400">RetailAnalytics</h1>
                </div>

                <!-- Navigation -->
                <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                    <!-- Dashboard -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('dashboard') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                        </svg>
                        Dashboard
                    </a>

@if(auth()->user()->isAdmin())
    <!-- Admin Only -->
    <div class="pt-4">
        <p class="px-3 text-xs font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-500">Admin</p>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.branches.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.branches.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                Branches
            </a>
            <a href="{{ route('admin.users.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.users.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                </svg>
                Users
            </a>
        </div>
    </div>

    <!-- Inventory Section -->
    <div class="pt-4">
        <p class="px-3 text-xs font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-500">Inventory</p>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.categories.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.categories.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                </svg>
                Categories
            </a>
            <a href="{{ route('admin.products.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.products.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                </svg>
                Products
            </a>
        </div>
    </div>

    <!-- Data Management Section -->
    <div class="pt-4">
        <p class="px-3 text-xs font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-500">Data</p>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.imports.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.imports.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                Imports
            </a>
        </div>
    </div>

    <!-- System Section -->
    <div class="pt-4">
        <p class="px-3 text-xs font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-500">System</p>
        <div class="mt-2 space-y-1">
            <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.activity-logs.*') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Activity Logs
            </a>
        </div>
    </div>
@endif

                    <!-- Analytics Section -->
                 
                    <div class="pt-4">
                        <p class="px-3 text-xs font-semibold tracking-wider text-gray-400 uppercase dark:text-gray-500">Analytics</p>
                        <div class="mt-2 space-y-1">
                            <!-- REPLACE THIS LINK -->
                            <a href="{{ route('analytics.sales') }}"
                            class="flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('analytics.sales') ? 'bg-primary-50 dark:bg-primary-900/20 text-primary-600 dark:text-primary-400' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                </svg>
                                Sales Analytics
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-md dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                                Customers
                            </a>
                            <a href="#" class="flex items-center gap-3 px-3 py-2 text-sm font-medium text-gray-700 rounded-md dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                                Forecasting
                            </a>
                        </div>
                    </div>
                </nav>

<!-- User Section -->
<div class="p-4 border-t border-gray-200 dark:border-gray-800">
    <div class="flex items-center gap-3">
        @if(auth()->user()->avatar)
            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                 alt="Avatar"
                 class="object-cover w-8 h-8 border-2 rounded-full border-primary-600 dark:border-primary-500">
        @else
            <div class="flex items-center justify-center w-8 h-8 text-sm font-medium text-white rounded-full bg-primary-600 dark:bg-primary-500">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        @endif
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-100">
                                {{ auth()->user()->name }}
                            </p>
                            <p class="text-xs text-gray-500 truncate dark:text-gray-400">
                                {{ ucfirst(str_replace('_', ' ', auth()->user()->role)) }}
                            </p>
                        </div>
                    </div>
                </div>
            </aside>

            <!-- Main Content -->
            <div class="flex flex-col flex-1 overflow-hidden">
                <!-- Top Bar -->
                <header class="h-16 border-b border-gray-200 dark:border-gray-800 bg-white dark:bg-[#171717] flex items-center justify-between px-6">
                    <div class="flex items-center gap-4">
                        <!-- Mobile menu button -->
                        <button class="p-2 text-gray-500 rounded-md lg:hidden hover:bg-gray-100 dark:hover:bg-gray-800">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                            </svg>
                        </button>

                        @if(auth()->user()->branch)
                            <div class="items-center hidden gap-2 px-3 py-1 bg-gray-100 rounded-full sm:flex dark:bg-gray-800">
                                <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                                <span class="text-sm font-medium text-gray-700 dark:text-gray-300">{{ auth()->user()->branch->name }}</span>
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-3">
                        <!-- Dark Mode Toggle -->
                        <button @click="darkMode = !darkMode" class="p-2 text-gray-500 transition-colors rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            <svg x-show="!darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                            </svg>
                            <svg x-show="darkMode" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </button>

<!-- Profile Dropdown -->
<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" class="flex items-center gap-2 p-2 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
        @if(auth()->user()->avatar)
            <img src="{{ Storage::url(auth()->user()->avatar) }}"
                 alt="Avatar"
                 class="object-cover w-8 h-8 border-2 border-gray-200 rounded-full dark:border-gray-700">
        @else
            <div class="flex items-center justify-center w-8 h-8 text-sm font-medium text-white rounded-full bg-primary-600 dark:bg-primary-500">
                {{ substr(auth()->user()->name, 0, 1) }}
            </div>
        @endif
    </button>

                            <div x-show="open" @click.away="open = false" x-transition class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-md shadow-lg py-1 z-10">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">Profile Settings</a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="w-full px-4 py-2 text-sm text-left text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800">
                                        Log Out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto bg-gray-50 dark:bg-[#0a0a0a] p-6">
                    @if (session('success'))
                        <div class="p-4 mb-6 border border-green-200 rounded-md bg-green-50 dark:bg-green-900/20 dark:border-green-800">
                            <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="p-4 mb-6 border border-red-200 rounded-md bg-red-50 dark:bg-red-900/20 dark:border-red-800">
                            <p class="text-sm text-red-800 dark:text-red-200">{{ session('error') }}</p>
                        </div>
                    @endif

                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
