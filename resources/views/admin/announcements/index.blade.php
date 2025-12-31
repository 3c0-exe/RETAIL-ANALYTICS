<x-app-layout>
    <div class="px-4 py-4 mx-auto max-w-7xl sm:px-6 lg:px-8 sm:py-6">
        {{-- ============================================================== --}}
        {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-4 sm:space-y-6">

            <div class="flex items-center gap-2 mb-3 sm:mb-4">
                <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-20 sm:w-24"></div>
                <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-3 sm:w-4"></div>
                <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-24 sm:w-28"></div>
            </div>

            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-4 sm:mb-6">
                <div class="flex-1">
                    <div class="h-7 sm:h-8 bg-gray-200 rounded dark:bg-gray-800 w-48 sm:w-64 mb-2"></div>
                    <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-64 sm:w-96"></div>
                </div>
                <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-48"></div>
            </div>

            <div class="space-y-3 sm:space-y-4">
                @for($i=0; $i<3; $i++)
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                            <div class="flex-1 space-y-3 sm:space-y-4 min-w-0">
                                <div class="flex flex-wrap gap-2">
                                    <div class="h-5 sm:h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-14 sm:w-16"></div>
                                    <div class="h-5 sm:h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-14 sm:w-16"></div>
                                    <div class="h-5 sm:h-6 bg-gray-200 rounded dark:bg-gray-800 w-20 sm:w-24"></div>
                                </div>

                                <div class="space-y-2">
                                    <div class="h-5 sm:h-6 bg-gray-200 rounded dark:bg-gray-800 w-2/3 sm:w-1/3"></div>
                                    <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-3/4"></div>
                                    <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-3/4 sm:w-1/2"></div>
                                </div>

                                <div class="flex flex-wrap gap-2 sm:gap-4">
                                    <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-28 sm:w-32"></div>
                                    <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-20 sm:w-24"></div>
                                </div>
                            </div>

                            <div class="flex gap-2 sm:ml-4 justify-end sm:justify-start">
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
            <nav class="mb-3 sm:mb-4 flex" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xs sm:text-sm text-gray-700 hover:text-purple-600 dark:text-gray-400 transition-colors">Dashboard</a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Announcements</span>
                        </div>
                    </li>
                </ol>
            </nav>

        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-4 sm:mb-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100">
                    System Announcements
                </h1>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    Create and manage system-wide announcements
                </p>
            </div>

            <a href="{{ route('admin.announcements.create') }}"
               class="flex items-center justify-center px-4 py-2 text-xs sm:text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700 transition-colors whitespace-nowrap">
                <span class="hidden sm:inline">+ Create Announcement</span>
                <span class="sm:hidden">+ New</span>
            </a>
        </div>

        @if($announcements->isEmpty())
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 sm:p-8 text-center">
            <div class="mb-3 sm:mb-4 text-5xl sm:text-6xl text-gray-400 dark:text-gray-600">📢</div>
            <p class="mb-3 sm:mb-4 text-sm sm:text-base text-gray-500 dark:text-gray-400">No announcements yet.</p>
            <a href="{{ route('admin.announcements.create') }}"
               class="inline-block px-4 py-2 text-xs sm:text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700 transition-colors">
                Create First Announcement
            </a>
        </div>
        @else
        <div class="space-y-3 sm:space-y-4">
            @foreach($announcements as $announcement)
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 hover:shadow-sm transition-shadow">
                <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 sm:gap-3 mb-2">
                            <span class="px-2 sm:px-2.5 py-0.5 sm:py-1 text-xs font-semibold rounded-full
                                {{ $announcement->type === 'info' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-400' : '' }}
                                {{ $announcement->type === 'warning' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-400' : '' }}
                                {{ $announcement->type === 'success' ? 'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-400' : '' }}
                                {{ $announcement->type === 'error' ? 'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-400' : '' }}">
                                {{ ucfirst($announcement->type) }}
                            </span>

                            @if($announcement->is_active)
                            <span class="px-2 py-0.5 sm:py-1 text-xs font-medium text-green-800 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                Active
                            </span>
                            @else
                            <span class="px-2 py-0.5 sm:py-1 text-xs font-medium text-gray-800 bg-gray-100 rounded-full dark:bg-gray-900/20 dark:text-gray-400">
                                Inactive
                            </span>
                            @endif

                            @if($announcement->expires_at)
                            <span class="text-xs text-gray-500 dark:text-gray-400 break-words">
                                Expires: {{ $announcement->expires_at->format('M d, Y') }}
                            </span>
                            @endif
                        </div>

                        <h3 class="mb-2 text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 break-words">
                            {{ $announcement->title }}
                        </h3>
                        <p class="mb-3 text-xs sm:text-sm text-gray-600 dark:text-gray-400 break-words">
                            {{ $announcement->message }}
                        </p>

                        <div class="flex flex-wrap items-center gap-2 sm:gap-4 text-xs text-gray-500 dark:text-gray-400">
                            <span class="break-words">Created by {{ $announcement->creator->name }}</span>
                            <span class="hidden sm:inline">•</span>
                            <span>{{ $announcement->created_at->diffForHumans() }}</span>
                            @if($announcement->is_dismissible)
                            <span class="hidden sm:inline">•</span>
                            <span>Dismissible</span>
                            @endif
                        </div>
                    </div>

                    <div class="flex items-center gap-2 sm:ml-4 justify-end sm:justify-start flex-shrink-0">
                        <form method="POST" action="{{ route('admin.announcements.toggle', $announcement) }}" class="inline">
                            @csrf
                            <button type="submit" class="p-2 text-gray-500 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="{{ $announcement->is_active ? 'Deactivate' : 'Activate' }}">
                                @if($announcement->is_active)
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @else
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </button>
                        </form>

                        <a href="{{ route('admin.announcements.edit', $announcement) }}" class="p-2 text-gray-500 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors" title="Edit">
                            <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>

                        <form method="POST" action="{{ route('admin.announcements.destroy', $announcement) }}" class="inline" onsubmit="return confirm('Delete this announcement?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-red-500 rounded-md hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors" title="Delete">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 sm:mt-6">
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
            }, 500);
        });
    </script>

</x-app-layout>
