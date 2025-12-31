<x-app-layout>
    <div class="max-w-3xl mx-auto">

        {{-- ============================================================== --}}
        {{-- 1. FORM SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="FormSkeleton" class="space-y-8 animate-pulse">

            <div class="space-y-4">
                <div class="w-32 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                <div class="w-48 h-8 bg-gray-200 rounded dark:bg-gray-700"></div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6 space-y-6">

                <div class="space-y-6">
                    <div class="space-y-2">
                        <div class="w-24 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                        <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="w-32 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                        <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                    </div>
                </div>

                <div class="pt-4 space-y-6 border-t border-gray-100 dark:border-gray-800">
                    <div class="space-y-2">
                        <div class="w-24 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                        <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                    </div>
                    <div class="space-y-2">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-36"></div>
                        <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                    </div>
                </div>

                <div class="pt-4 space-y-6 border-t border-gray-100 dark:border-gray-800">
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div class="space-y-2">
                            <div class="w-16 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                            <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                        </div>
                        <div class="space-y-2">
                            <div class="w-20 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                            <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <div class="w-32 h-4 bg-gray-200 rounded dark:bg-gray-700"></div>
                        <div class="w-full h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                    </div>
                </div>

                <div class="flex gap-3 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <div class="w-32 h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                    <div class="w-24 h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL FORM CONTENT (Hidden Initially)                        --}}
        {{-- ============================================================== --}}
        <div id="RealFormContent" class="hidden transition-opacity duration-500 opacity-0">

            <div class="mb-8">
                <div class="flex items-center gap-2 mb-2 text-sm text-gray-600 dark:text-gray-400">
                    <a href="{{ route('admin.users.index') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Users</a>
                    <span>/</span>
                    <span>Create</span>
                </div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Create New User</h1>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <form action="{{ route('admin.users.store') }}" method="POST" onsubmit="showFormLoading(this)">
                    @csrf

                    <div class="mb-6">
                        <label for="name" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Full Name <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="email" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email Address <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="password_confirmation" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Confirm Password <span class="text-red-500">*</span>
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                    </div>

                    {{-- ✅ UPDATED: New role_id dropdown --}}
                    <div class="mb-6">
                        <label for="role_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Role <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="role_id"
                            name="role_id"
                            class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                            <option value="">Select Role</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}"
                                        {{ old('role_id') == $role->id ? 'selected' : '' }}>
                                    {{ $role->display_name }}
                                    @if($role->is_system)
                                        (System)
                                    @endif
                                </option>
                            @endforeach
                        </select>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Branch Manager and Viewer require branch assignment
                        </p>
                        @error('role_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="branch_id" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Branch
                        </label>
                        <select
                            id="branch_id"
                            name="branch_id"
                            class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        >
                            <option value="">No branch (Admin/Analyst only)</option>
                            @foreach($branches as $branch)
                                <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                                    {{ $branch->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('branch_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="theme" class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Default Theme <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="theme"
                            name="theme"
                            class="w-full px-4 py-2 text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                            <option value="light" {{ old('theme', 'light') === 'light' ? 'selected' : '' }}>Light</option>
                            <option value="dark" {{ old('theme') === 'dark' ? 'selected' : '' }}>Dark</option>
                        </select>
                        @error('theme')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-800">
                        <button type="submit" class="px-6 py-2 font-medium text-white transition-colors rounded-md bg-primary-600 hover:bg-primary-700">
                            Create User
                        </button>
                        <a href="{{ route('admin.users.index') }}" class="px-6 py-2 font-medium text-gray-700 transition-colors bg-gray-100 rounded-md dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-gray-300">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
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
            }, 500); // 500ms delay
        });
    </script>
</x-app-layout>
