<x-app-layout>

    {{-- ============================================================== --}}
        {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-6">

            <div class="flex items-center gap-2 mb-4">
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-24"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-4"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-16"></div>
            </div>

            <div class="mb-6">
                <div class="h-8 bg-gray-200 rounded dark:bg-gray-800 w-48 mb-2"></div>
                <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-96"></div>
            </div>

            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <div class="lg:col-span-1 space-y-6">
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 space-y-4">
                        <div class="h-6 bg-gray-200 rounded dark:bg-gray-800 w-32 mb-4"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>

                        <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                            <div class="h-5 bg-gray-200 rounded dark:bg-gray-800 w-32 mb-3"></div>
                            <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full mb-3"></div>
                            <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                        </div>
                    </div>

                    <div class="h-32 bg-blue-50 dark:bg-blue-900/20 rounded-lg w-full"></div>
                </div>

                <div class="lg:col-span-2">
                    <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg">
                        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                            <div class="h-6 bg-gray-200 rounded dark:bg-gray-800 w-48"></div>
                        </div>
                        <div class="divide-y divide-gray-200 dark:divide-gray-800">
                            @for($i=0; $i<5; $i++)
                                <div class="px-6 py-4">
                                    <div class="flex justify-between items-center">
                                        <div class="space-y-2 w-2/3">
                                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-1/2"></div>
                                            <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-3/4"></div>
                                        </div>
                                        <div class="flex gap-2">
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
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Backups</span>
                        </div>
                    </li>
                </ol>
            </nav>
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100">
                Backup & Restore
            </h1>
            <p class="mt-1 text-sm text-gray-600 sm:mt-2 dark:text-gray-400">
                Create, download, and restore database backups
            </p>
        </div>

        @if(session('success'))
        <div class="p-4 mb-6 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800">
            <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
        </div>
        @endif

        @if(session('error'))
        <div class="p-4 mb-6 border border-red-200 rounded-lg bg-red-50 dark:bg-red-900/20 dark:border-red-800">
            <p class="text-sm font-medium text-red-800 dark:text-red-300">{{ session('error') }}</p>
        </div>
        @endif

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <!-- Actions Column -->
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 space-y-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                        Quick Actions
                    </h2>

                    <!-- Create Database Backup -->
                    <form method="POST" action="{{ route('admin.backups.create') }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Create database backup? This may take a few moments.')"
                                class="w-full px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                            💾 Create Database Backup
                        </button>
                    </form>

                    <!-- Clean Old Backups -->
                    <form method="POST" action="{{ route('admin.backups.clean') }}">
                        @csrf
                        <button type="submit" onclick="return confirm('Clean old backups? This will remove backups based on retention policy.')"
                                class="w-full px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700">
                            🧹 Clean Old Backups
                        </button>
                    </form>

                    <div class="pt-4 border-t border-gray-200 dark:border-gray-800">
                        <h3 class="mb-3 text-sm font-semibold text-gray-900 dark:text-gray-100">
                            Restore from File
                        </h3>

                        <form method="POST" action="{{ route('admin.backups.restore') }}" enctype="multipart/form-data" x-data="{ file: null }">
                            @csrf
                            <div class="mb-3">
                                <input type="file" name="backup_file" accept=".sql,.zip" @change="file = $event.target.files[0]" required
                                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-400">
                            </div>
                            <button type="submit" onclick="return confirm('⚠️ WARNING: This will replace ALL data! Make sure you have a current backup. Continue?')"
                                    class="w-full px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                ⚠️ Restore Database
                            </button>
                        </form>

                        <div class="p-3 mt-3 rounded-md bg-yellow-50 dark:bg-yellow-900/20">
                            <p class="text-xs text-yellow-800 dark:text-yellow-300">
                                <strong>⚠️ Warning:</strong> Restoring will replace all current data. Only upload trusted backup files (.sql or .zip).
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Backup Info -->
                <div class="p-4 mt-6 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800">
                    <h3 class="mb-2 text-sm font-semibold text-blue-900 dark:text-blue-200">
                        ℹ️ Backup Information
                    </h3>
                    <ul class="space-y-1 text-xs text-blue-800 dark:text-blue-300">
                        <li>• Backups stored in: storage/app/backups</li>
                        <li>• Retention: 7 days (all), 16 days (daily)</li>
                        <li>• Database only: ~5-50 MB</li>
                        <li>• Full backup: includes files</li>
                    </ul>
                </div>
            </div>

            <!-- Backups List Column -->
            <div class="lg:col-span-2">
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg">
                    <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-800">
                        <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">
                            Available Backups ({{ count($backups) }})
                        </h2>
                    </div>

                    @if(empty($backups))
                    <div class="p-12 text-center">
                        <div class="mb-4 text-6xl text-gray-400 dark:text-gray-600">💾</div>
                        <p class="mb-4 text-gray-500 dark:text-gray-400">No backups found.</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500">Create your first backup using the buttons on the left.</p>
                    </div>
                    @else
                    <div class="divide-y divide-gray-200 dark:divide-gray-800">
                        @foreach($backups as $backup)
                        <div class="px-6 py-4 hover:bg-gray-50 dark:hover:bg-gray-900/50">
                            <div class="flex items-center justify-between">
                                <div class="flex-1 min-w-0">
                                    <h3 class="text-sm font-medium text-gray-900 truncate dark:text-gray-100">
                                        {{ $backup['name'] }}
                                    </h3>
                                    <div class="flex items-center gap-4 mt-1 text-xs text-gray-500 dark:text-gray-400">
                                        <span>📅 {{ $backup['date_human'] }}</span>
                                        <span>•</span>
                                        <span>📊 {{ $backup['size_human'] }}</span>
                                        <span>•</span>
                                        <span>{{ \Carbon\Carbon::createFromTimestamp($backup['date'])->diffForHumans() }}</span>
                                    </div>
                                </div>

                                <div class="flex items-center gap-2 ml-4">
                                    <a href="{{ route('admin.backups.download', basename($backup['path'])) }}"
                                       class="p-2 text-blue-600 rounded-md hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/20"
                                       title="Download">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    </a>

                                    <form method="POST" action="{{ route('admin.backups.destroy', basename($backup['path'])) }}" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Delete this backup?')"
                                                class="p-2 text-red-600 rounded-md hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/20"
                                                title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
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
