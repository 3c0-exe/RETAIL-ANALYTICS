<x-app-layout>
    <div class="max-w-2xl mx-auto">

        {{-- ============================================================== --}}
        {{-- 1. FORM SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="FormSkeleton" class="space-y-6 animate-pulse">

            <div class="mb-6">
                <div class="w-48 h-8 mb-2 bg-gray-200 rounded dark:bg-gray-700"></div>
                <div class="w-32 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 space-y-6">

                <div class="space-y-2">
                    <div class="w-32 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                    <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                </div>

                <div class="space-y-2">
                    <div class="w-24 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                    <div class="w-full h-24 bg-gray-200 rounded dark:bg-gray-700"></div>
                </div>

                <div class="space-y-2">
                    <div class="w-16 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                    <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                </div>
            </div>

            <div class="flex justify-end gap-3">
                <div class="w-24 h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                <div class="w-40 h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL FORM CONTENT (Hidden Initially)                        --}}
        {{-- ============================================================== --}}
        <div id="RealFormContent" class="hidden transition-opacity duration-500 opacity-0">
            <div class="mb-6">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Add New Category</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Create a new product category</p>
            </div>

            <form action="{{ route('admin.categories.store') }}" method="POST" class="space-y-6">
                  <method="POST"
                  class="space-y-6"
                  onsubmit="showFormLoading(this)">
                @csrf

                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category Name *
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name') }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <textarea name="description"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>
                        <select name="is_active"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="1" {{ old('is_active', 1) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active') == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-3">
                    <a href="{{ route('admin.categories.index') }}"
                       class="px-6 py-2 text-gray-700 transition bg-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-400 dark:hover:bg-gray-600">
                        Cancel
                    </a>
                    <button type="submit"
                            class="px-6 py-2 text-white transition rounded-lg bg-primary-600 hover:bg-primary-700">
                        Create Category
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- 3. JAVASCRIPT LOGIC                                            --}}
    {{-- ============================================================== --}}
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                // 1. Find the two main sections
                const formSkeleton = document.getElementById('FormSkeleton');
                const realForm = document.getElementById('RealFormContent');

                // 2. Remove the skeleton
                if (formSkeleton) formSkeleton.remove();

                // 3. Reveal the real form
                if (realForm) {
                    realForm.classList.remove('hidden');
                    // Small delay to ensure the element is rendered before fading in
                    setTimeout(() => {
                        realForm.classList.remove('opacity-0');
                    }, 10);
                }
            }, 500);
        });
    </script>
</x-app-layout>
