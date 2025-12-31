<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        {{-- ============================================================== --}}
        {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-6">

            <div class="flex items-center gap-2 mb-4">
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-24"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-4"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-28"></div>
            </div>

            <div class="flex items-center justify-between mb-6">
                <div>
                    <div class="h-8 bg-gray-200 rounded dark:bg-gray-800 w-64 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-96"></div>
                </div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-48"></div>
            </div>

            <div class="space-y-4">
                @for($i=0; $i<3; $i++)
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                        <div class="flex items-start justify-between">
                            <div class="flex-1 space-y-4">
                                <div class="flex gap-2">
                                    <div class="h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-16"></div>
                                    <div class="h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-16"></div>
                                    <div class="h-6 bg-gray-200 rounded dark:bg-gray-800 w-24"></div>
                                </div>

                                <div class="space-y-2">
                                    <div class="h-6 bg-gray-200 rounded dark:bg-gray-800 w-1/3"></div>
                                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-3/4"></div>
                                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-1/2"></div>
                                </div>

                                <div class="flex gap-4">
                                    <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-32"></div>
                                    <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-24"></div>
                                </div>
                            </div>

                            <div class="flex gap-2 ml-4">
                                <div class="h-8 w-8 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="h-8 w-8 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="h-8 w-8 bg-gray-200 rounded dark:bg-gray-800"></div>
                            </div>
                        </div>
                    </div>
                @endfor
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500 ease-in-out">
            <nav class="mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-sm text-gray-700 hover:text-purple-600 dark:text-gray-400">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Announcements</span>
                        </div>
                    </li>
                </ol>
            </nav>
        <!-- Page Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100">
                    System Announcements
                </h1>
                <p class="mt-1 text-sm text-gray-600 sm:mt-2 dark:text-gray-400">
                    Create and manage system-wide announcements
                </p>
            </div>

            <a href="{{ route('admin.announcements.create') }}"
               class="px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                + Create Announcement
            </a>
        </div>

        @if(session('success'))
        <div class="p-4 mb-6 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800">
            <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
        @endif

        <!-- Announcements List -->
        @if($announcements->isEmpty())
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-8 text-center">
            <div class="mb-4 text-6xl text-gray-400 dark:text-gray-600">📢</div>
            <p class="mb-4 text-gray-500 dark:text-gray-400">No announcements yet.</p>
            <a href="{{ route('admin.announcements.create') }}"
               class="inline-block px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                Create First Announcement
            </a>
        </div>
        @else
        <div class="space-y-4">
            @foreach($announcements as $announcement)
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <!-- Header -->
                        <div class="flex items-center gap-3 mb-2">
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full
                                {{ $announcement->type === 'info' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                                {{ $announcement->type === 'warning' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                {{ $announcement->type === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                {{ $announcement->type === 'error' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                {{ ucfirst($announcement->type) }}
                            </span>

                            @if($announcement->is_active)
                            <span class="px-2 py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                Active
                            </span>
                            @else
                            <span class="px-2 py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-900/20 dark:text-gray-400">
                                Inactive
                            </span>
                            @endif

                            @if($announcement->expires_at)
                            <span class="text-xs text-gray-500 dark:text-gray-400">
                                Expires: {{ $announcement->expires_at->format('M d, Y') }}
                            </span>
                            @endif
                        </div>

                        <!-- Content -->
                        <h3 class="mb-2 text-lg font-semibold text-gray-900 dark:text-gray-100">
                            {{ $announcement->title }}
                        </h3>
                        <p class="mb-3 text-sm text-gray-600 dark:text-gray-400">
                            {{ $announcement->message }}
                        </p>

                        <!-- Meta -->
                        <div class="flex items-center gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <span>Created by {{ $announcement->creator->name }}</span>
                            <span>•</span>
                            <span>{{ $announcement->created_at->diffForHumans() }}</span>
                            @if($announcement->is_dismissible)
                            <span>•</span>
                            <span>Dismissible</span>
                            @endif
                        </div>
                    </div>

                    <!-- Actions -->
                    <div class="flex items-center gap-2 ml-4">
                        <form method="POST" action="{{ route('admin.announcements.toggle', $announcement) }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-500 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800" title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                @if($announcement->is_active)
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </button>
                        </form>

                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="p-2 text-gray-500 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800" title="Edit">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>

                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="inline" onsubmit="return confirm('Delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $announcements->links() }}
        </div>
        @endif
    </div>
        </div>

        <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const skeleton = document.getElementById('PageSkeleton');
                const content = document.getElementById('RealPageContent');

                if (skeleton) skeleton.style.display = 'none';

                if (content) {
                    content.classList.remove('hidden');
                    setTimeout(() => {
                        content.classList.remove('opacity-0');
                    }, 50);
                }
            }, 500); // 500ms delay to prevent flicker
        });
    </script>

</x-app-layout>
