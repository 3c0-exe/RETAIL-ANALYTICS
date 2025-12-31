<x-app-layout>

    {{-- ============================================================== --}}
    {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
    {{-- ============================================================== --}}
    <div id="PageSkeleton" class="animate-pulse space-y-4 sm:space-y-6">

        <div class="flex items-center gap-2 mb-3 sm:mb-4">
            <div class="h-3 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-20 sm:w-24"></div>
            <div class="h-3 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-3 sm:w-4"></div>
            <div class="h-3 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-12 sm:w-16"></div>
        </div>

        <div class="mb-4 sm:mb-6">
            <div class="h-6 sm:h-8 bg-gray-200 rounded dark:bg-gray-800 w-40 sm:w-48 mb-2"></div>
            <div class="h-3 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-full max-w-sm sm:max-w-md"></div>
        </div>

        <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-3">

            <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 space-y-3 sm:space-y-4">
                    <div class="h-5 sm:h-6 bg-gray-200 rounded dark:bg-gray-800 w-28 sm:w-32 mb-3 sm:mb-4"></div>
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>

                    <div class="pt-3 sm:pt-4 border-t border-gray-200 dark:border-gray-800">
                        <div class="h-4 sm:h-5 bg-gray-200 rounded dark:bg-gray-800 w-28 sm:w-32 mb-3"></div>
                        <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full mb-3"></div>
                        <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    </div>
                </div>

                <div class="h-28 sm:h-32 bg-blue-50 dark:bg-blue-900/20 rounded-lg w-full"></div>
            </div>

            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg">
                    <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-800">
                        <div class="h-5 sm:h-6 bg-gray-200 rounded dark:bg-gray-800 w-36 sm:w-48"></div>
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-800">
                        @for($i=0; $i<5; $i++)
                            <div class="px-4 sm:px-6 py-3 sm:py-4">
                                <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                    <div class="space-y-2 flex-1">
                                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-3/4 sm:w-1/2"></div>
                                        <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-3/4"></div>
                                    </div>
                                    <div class="flex gap-2 self-end sm:self-auto">
                                        <div class="h-8 w-8 bg-gray-200 rounded dark:bg-gray-800"></div>
                                        <div class="h-8 w-8 bg-gray-200 rounded dark:bg-gray-800"></div>
                                    </div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
    {{-- ============================================================== --}}
    <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500 ease-in-out">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <!-- Breadcrumb -->
            <nav class="mb-3 sm:mb-4 flex overflow-x-auto scrollbar-hide" aria-label="Breadcrumb">
                <ol class="inline-flex items-center space-x-1 md:space-x-3 whitespace-nowrap">
                    <li class="inline-flex items-center">
                        <a href="{{ route('dashboard') }}" class="text-xs sm:text-sm text-gray-700 hover:text-purple-600 dark:text-gray-400 transition-colors">
                            Dashboard
                        </a>
                    </li>
                    <li>
                        <div class="flex items-center">
                            <svg class="w-3 h-3 mx-1 text-gray-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"></path>
                            </svg>
                            <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400">Backups</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <!-- Page Header -->
            <div class="mb-4 sm:mb-6">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100">
                    Backup & Restore
                </h1>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    Create, download, and restore database backups
                </p>
            </div>

            <!-- Success Message -->
            @if(session('success'))
            <div class="p-3 sm:p-4 mb-4 sm:mb-6 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800 animate-fadeIn">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-600 dark:text-green-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-xs sm:text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <!-- Error Message -->
            @if(session('error'))
            <div class="p-3 sm:p-4 mb-4 sm:mb-6 border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:border-red-800 animate-fadeIn">
                <div class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-400 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                    </svg>
                    <p class="text-xs sm:text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            <div class="grid grid-cols-1 gap-4 sm:gap-6 lg:grid-cols-3">

                <!-- Actions Column -->
                <div class="lg:col-span-1 space-y-4 sm:space-y-6">
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 space-y-3 sm:space-y-4 shadow-sm">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                            <span class="text-lg sm:text-xl">⚡</span>
                            Quick Actions
                        </h2>

                        <!-- Create Database Backup -->
                        <form method="POST" action="{{ route('admin.backups.create') }}">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Create database backup? This may take a few moments.')"
                                    class="w-full px-4 py-2.5 sm:py-2 text-xs sm:text-sm font-medium text-white rounded-lg bg-primary-600 hover:bg-primary-700 active:bg-primary-800 transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2 touch-manipulation">
                                <span class="text-base sm:text-lg">💾</span>
                                <span>Create Database Backup</span>
                            </button>
                        </form>

                        <!-- Clean Old Backups -->
                        <form method="POST" action="{{ route('admin.backups.clean') }}">
                            @csrf
                            <button type="submit"
                                    onclick="return confirm('Clean old backups? This will remove backups based on retention policy.')"
                                    class="w-full px-4 py-2.5 sm:py-2 text-xs sm:text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 active:bg-gray-100 dark:active:bg-gray-600 transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2 touch-manipulation">
                                <span class="text-base sm:text-lg">🧹</span>
                                <span>Clean Old Backups</span>
                            </button>
                        </form>

                        <!-- Restore Section -->
                        <div class="pt-3 sm:pt-4 border-t border-gray-200 dark:border-gray-800">
                            <h3 class="mb-3 text-xs sm:text-sm font-semibold text-gray-900 dark:text-gray-100 flex items-center gap-2">
                                <span class="text-base sm:text-lg">🔄</span>
                                Restore from File
                            </h3>

                            <form method="POST" action="{{ route('admin.backups.restore') }}" enctype="multipart/form-data" x-data="{ file: null }">
                                @csrf
                                <div class="mb-3">
                                    <label class="block w-full">
                                        <input type="file"
                                               name="backup_file"
                                               accept=".sql,.zip"
                                               @change="file = $event.target.files[0]"
                                               required
                                               class="block w-full text-xs sm:text-sm text-gray-500 file:mr-3 sm:file:mr-4 file:py-2 sm:file:py-2 file:px-3 sm:file:px-4 file:rounded-lg file:border-0 file:text-xs sm:file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-400 dark:hover:file:bg-primary-900/30 transition-colors cursor-pointer touch-manipulation">
                                    </label>
                                </div>
                                <button type="submit"
                                        onclick="return confirm('⚠️ WARNING: This will replace ALL data! Make sure you have a current backup. Continue?')"
                                        class="w-full px-4 py-2.5 sm:py-2 text-xs sm:text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 active:bg-red-800 transition-all duration-200 shadow-sm hover:shadow-md flex items-center justify-center gap-2 touch-manipulation">
                                    <span class="text-base sm:text-lg">⚠️</span>
                                    <span>Restore Database</span>
                                </button>
                            </form>

                            <div class="p-3 mt-3 rounded-lg bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800">
                                <div class="flex items-start gap-2">
                                    <span class="text-base flex-shrink-0">⚠️</span>
                                    <p class="text-xs text-yellow-800 dark:text-yellow-300">
                                        <strong class="font-semibold">Warning:</strong> Restoring will replace all current data. Only upload trusted backup files (.sql or .zip).
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Backup Info -->
                    <div class="p-3 sm:p-4 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800 shadow-sm">
                        <h3 class="mb-2 text-xs sm:text-sm font-semibold text-blue-900 dark:text-blue-200 flex items-center gap-2">
                            <span class="text-base">ℹ️</span>
                            Backup Information
                        </h3>
                        <ul class="space-y-1 text-xs text-blue-800 dark:text-blue-300">
                            <li class="flex items-start gap-1.5">
                                <span class="flex-shrink-0 mt-0.5">•</span>
                                <span>Backups stored in: storage/app/backups</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="flex-shrink-0 mt-0.5">•</span>
                                <span>Retention: 7 days (all), 16 days (daily)</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="flex-shrink-0 mt-0.5">•</span>
                                <span>Database only: ~5-50 MB</span>
                            </li>
                            <li class="flex items-start gap-1.5">
                                <span class="flex-shrink-0 mt-0.5">•</span>
                                <span>Full backup: includes files</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Backups List Column -->
                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg shadow-sm overflow-hidden">
                        <div class="px-4 sm:px-6 py-3 sm:py-4 border-b border-gray-200 dark:border-gray-800 bg-gray-50 dark:bg-gray-900/50">
                            <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100 flex items-center justify-between">
                                <span class="flex items-center gap-2">
                                    <span class="text-lg sm:text-xl">📦</span>
                                    <span>Available Backups</span>
                                </span>
                                <span class="text-xs sm:text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 px-2 sm:px-3 py-1 rounded-full">
                                    {{ count($backups) }}
                                </span>
                            </h2>
                        </div>

                        @if(empty($backups))
                        <div class="p-8 sm:p-12 text-center">
                            <div class="mb-4 text-5xl sm:text-6xl text-gray-400 dark:text-gray-600">💾</div>
                            <p class="mb-2 sm:mb-4 text-sm sm:text-base text-gray-500 dark:text-gray-400 font-medium">No backups found.</p>
                            <p class="text-xs sm:text-sm text-gray-400 dark:text-gray-500">Create your first backup using the buttons on the left.</p>
                        </div>
                        @else
                        <div class="divide-y divide-gray-200 dark:divide-gray-800">
                            @foreach($backups as $backup)
                            <div class="px-4 sm:px-6 py-3 sm:py-4 hover:bg-gray-50 dark:hover:bg-gray-900/50 transition-colors">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="text-xs sm:text-sm font-medium text-gray-900 dark:text-gray-100 truncate flex items-start gap-2">
                                            <span class="text-base flex-shrink-0 mt-0.5">📄</span>
                                            <span class="break-all sm:truncate">{{ $backup['name'] }}</span>
                                        </h3>
                                        <div class="flex flex-wrap items-center gap-2 mt-1.5 text-xs text-gray-500 dark:text-gray-400">
                                            <span class="flex items-center gap-1 whitespace-nowrap">
                                                <span>📅</span>
                                                <span>{{ $backup['date_human'] }}</span>
                                            </span>
                                            <span class="hidden sm:inline">•</span>
                                            <span class="flex items-center gap-1 whitespace-nowrap">
                                                <span>📊</span>
                                                <span>{{ $backup['size_human'] }}</span>
                                            </span>
                                            <span class="hidden sm:inline">•</span>
                                            <span class="flex items-center gap-1 whitespace-nowrap">
                                                <span>🕐</span>
                                                <span>{{ \Carbon\Carbon::createFromTimestamp($backup['date'])->diffForHumans() }}</span>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="flex items-center gap-2 self-end sm:self-auto ml-6 sm:ml-4">
                                        <a href="{{ route('admin.backups.download', basename($backup['path'])) }}"
                                           class="p-2 text-blue-600 rounded-lg hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20 transition-all duration-200 shadow-sm hover:shadow active:scale-95 touch-manipulation"
                                           title="Download">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                            </svg>
                                        </a>

                                        <form method="POST" action="{{ route('admin.backups.destroy', basename($backup['path'])) }}" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    onclick="return confirm('Delete this backup?')"
                                                    class="p-2 text-red-600 rounded-lg hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20 transition-all duration-200 shadow-sm hover:shadow active:scale-95 touch-manipulation"
                                                    title="Delete">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                </svg>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fadeIn {
            animation: fadeIn 0.3s ease-out;
        }

        .scrollbar-hide {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        @media (max-width: 640px) {
            .touch-manipulation {
                -webkit-tap-highlight-color: transparent;
            }
        }
    </style>

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
