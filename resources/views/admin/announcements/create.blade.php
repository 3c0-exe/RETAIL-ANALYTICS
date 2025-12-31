<x-app-layout>
    <div class="max-w-3xl px-4 py-4 mx-auto sm:px-6 lg:px-8 sm:py-6">
        {{-- ============================================================== --}}
        {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-4 sm:space-y-6">

            <div class="mb-4 sm:mb-6">
                <div class="h-7 sm:h-8 bg-gray-200 rounded dark:bg-gray-800 w-48 sm:w-64 mb-2"></div>
                <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-64 sm:w-96"></div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6 space-y-4 sm:space-y-6">

                <div>
                    <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-14 sm:w-16 mb-2"></div>
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                </div>

                <div>
                    <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-20 sm:w-24 mb-2"></div>
                    <div class="h-24 sm:h-32 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                </div>

                <div>
                    <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-14 sm:w-16 mb-2"></div>
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                </div>

                <div>
                    <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-28 sm:w-32 mb-2"></div>
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-40 sm:w-48 mt-1"></div>
                </div>

                <div class="space-y-3 sm:space-y-4">
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                        <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-20 sm:w-24"></div>
                    </div>
                    <div class="flex items-center gap-3">
                        <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                        <div class="h-3.5 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-48 sm:w-64"></div>
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-2 sm:gap-3 pt-2">
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-32"></div>
                    <div class="h-9 sm:h-10 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-24"></div>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500 ease-in-out">
            <div class="mb-4 sm:mb-6">
                <h1 class="text-xl sm:text-2xl lg:text-3xl font-bold text-gray-900 dark:text-gray-100">
                    {{ isset($announcement) ? 'Edit' : 'Create' }} Announcement
                </h1>
                <p class="mt-1 sm:mt-2 text-xs sm:text-sm text-gray-600 dark:text-gray-400">
                    {{ isset($announcement) ? 'Update announcement details' : 'Create a new system-wide announcement' }}
                </p>
            </div>

            <form method="POST" action="{{ isset($announcement) ? route('admin.announcements.update', $announcement) : route('admin.announcements.store') }}" class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                @csrf
                @if(isset($announcement))
                    @method('PUT')
                @endif

                <div class="mb-4 sm:mb-6">
                    <label class="block mb-2 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Title *</label>
                    <input type="text" name="title" value="{{ old('title', $announcement->title ?? '') }}" required
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow @error('title') border-red-500 @enderror">
                    @error('title')
                    <p class="mt-1 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 sm:mb-6">
                    <label class="block mb-2 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Message *</label>
                    <textarea name="message" rows="4" required
                              class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow @error('message') border-red-500 @enderror">{{ old('message', $announcement->message ?? '') }}</textarea>
                    @error('message')
                    <p class="mt-1 text-xs sm:text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4 sm:mb-6">
                    <label class="block mb-2 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Type *</label>
                    <select name="type" required
                            class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow">
                        <option value="info" {{ old('type', $announcement->type ?? 'info') === 'info' ? 'selected' : '' }}>Info (Blue)</option>
                        <option value="success" {{ old('type', $announcement->type ?? '') === 'success' ? 'selected' : '' }}>Success (Green)</option>
                        <option value="warning" {{ old('type', $announcement->type ?? '') === 'warning' ? 'selected' : '' }}>Warning (Yellow)</option>
                        <option value="error" {{ old('type', $announcement->type ?? '') === 'error' ? 'selected' : '' }}>Error (Red)</option>
                    </select>
                </div>

                <div class="mb-4 sm:mb-6">
                    <label class="block mb-2 text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Expires At (Optional)</label>
                    <input type="datetime-local" name="expires_at"
                           value="{{ old('expires_at', isset($announcement) && $announcement->expires_at ? $announcement->expires_at->format('Y-m-d\TH:i') : '') }}"
                           class="w-full px-3 py-2 text-sm border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent transition-shadow">
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Leave empty for no expiration</p>
                </div>

                <div class="mb-4 sm:mb-6 space-y-3 sm:space-y-4">
                    @if(isset($announcement))
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_active" value="1" {{ old('is_active', $announcement->is_active ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500 focus:ring-2 transition-all">
                        <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                    @endif

                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="checkbox" name="is_dismissible" value="1" {{ old('is_dismissible', $announcement->is_dismissible ?? true) ? 'checked' : '' }}
                               class="w-4 h-4 border-gray-300 rounded text-primary-600 focus:ring-primary-500 focus:ring-2 transition-all">
                        <span class="text-xs sm:text-sm font-medium text-gray-700 dark:text-gray-300">Users can dismiss this announcement</span>
                    </label>
                </div>

                <div class="flex flex-col sm:flex-row items-center gap-2 sm:gap-3">
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 text-xs sm:text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700 transition-colors">
                        {{ isset($announcement) ? 'Update' : 'Create' }} Announcement
                    </button>
                    <a href="{{ route('admin.announcements.index') }}" class="w-full sm:w-auto px-4 py-2 text-xs sm:text-sm font-medium text-center text-gray-700 bg-white border border-gray-300 rounded-md dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
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
