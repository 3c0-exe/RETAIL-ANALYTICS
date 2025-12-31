<x-app-layout>
    <div class="max-w-4xl mx-auto space-y-6">
        {{-- ============================================================== --}}
        {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-6">

            <div class="flex items-center gap-4">
                <div class="w-10 h-10 bg-gray-200 rounded-md dark:bg-gray-800"></div>
                <div>
                    <div class="h-8 bg-gray-200 rounded dark:bg-gray-800 w-64 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-48"></div>
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-200 rounded-lg dark:bg-[#171717] dark:border-gray-800 space-y-6">
                <div class="h-6 bg-gray-200 rounded dark:bg-gray-800 w-40 mb-4"></div>

                <div class="space-y-4">
                    <div>
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-32 mb-2"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    </div>
                    <div>
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-32 mb-2"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    </div>
                    <div>
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-24 mb-2"></div>
                        <div class="h-24 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    </div>
                </div>
            </div>

            <div class="p-6 bg-white border border-gray-200 rounded-lg dark:bg-[#171717] dark:border-gray-800">
                <div class="flex justify-between mb-6">
                    <div class="h-6 bg-gray-200 rounded dark:bg-gray-800 w-32"></div>
                    <div class="flex gap-2">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-16"></div>
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-16"></div>
                    </div>
                </div>

                <div class="space-y-4">
                    @for($i=0; $i<5; $i++)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-1/4"></div>
                            <div class="flex gap-8">
                                <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-24"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-32"></div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500 ease-in-out space-y-6">
            <!-- Header -->
        <div class="flex items-center gap-4">
            <a href="{{ route('admin.roles.index') }}" class="p-2 text-gray-500 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Create New Role</h1>
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Define a custom role with specific permissions</p>
            </div>
        </div>

        <form method="POST" action="{{ route('admin.roles.store') }}" class="space-y-6">
            @csrf

            <!-- Basic Info Card -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Basic Information</h2>

                <div class="space-y-4">
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name (Internal)</label>
                        <input type="text"
                               name="name"
                               id="name"
                               value="{{ old('name') }}"
                               placeholder="store_manager"
                               class="block w-full mt-1 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 @error('name') border-red-500 @enderror">
                        <p class="mt-1 text-xs text-gray-500">Lowercase, no spaces (use underscores)</p>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="display_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Display Name</label>
                        <input type="text"
                               name="display_name"
                               id="display_name"
                               value="{{ old('display_name') }}"
                               placeholder="Store Manager"
                               class="block w-full mt-1 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 @error('display_name') border-red-500 @enderror">
                        @error('display_name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                        <textarea name="description"
                                  id="description"
                                  rows="3"
                                  placeholder="Brief description of this role's responsibilities..."
                                  class="block w-full mt-1 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Permissions Matrix -->
            <div class="p-6 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Permissions</h2>
                    <div class="flex gap-2">
                        <button type="button" onclick="selectAll()" class="text-xs text-blue-600 hover:underline dark:text-blue-400">
                            Select All
                        </button>
                        <button type="button" onclick="deselectAll()" class="text-xs text-gray-600 hover:underline dark:text-gray-400">
                            Deselect All
                        </button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-4 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Module</th>
                                @foreach($actions as $actionKey => $actionName)
                                    <th class="px-4 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400">{{ $actionName }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($modules as $moduleKey => $moduleName)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                    <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">{{ $moduleName }}</td>
                                    @foreach($actions as $actionKey => $actionName)
                                        @php
                                            $permission = $permissions->firstWhere('name', "{$moduleKey}.{$actionKey}");
                                        @endphp
                                        <td class="px-4 py-3 text-center">
                                            @if($permission)
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 permission-checkbox">
                                            @else
                                                <span class="text-gray-400">—</span>
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                @error('permissions')
                    <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <!-- Actions -->
            <div class="flex items-center justify-end gap-3">
                <a href="{{ route('admin.roles.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                    Create Role
                </button>
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
        
        function selectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = true;
            });
        }

        function deselectAll() {
            document.querySelectorAll('.permission-checkbox').forEach(checkbox => {
                checkbox.checked = false;
            });
        }
    </script>
</x-app-layout>
