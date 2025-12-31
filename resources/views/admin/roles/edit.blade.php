<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
        {{-- ============================================================== --}}
        {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-4 sm:space-y-6">

            <div class="flex items-center gap-3 sm:gap-4">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-200 rounded-md dark:bg-gray-800"></div>
                <div class="flex-1">
                    <div class="h-6 sm:h-8 bg-gray-200 rounded dark:bg-gray-800 w-48 sm:w-64 mb-2"></div>
                    <div class="h-3 sm:h-4 bg-gray-200 rounded dark:bg-gray-800 w-40 sm:w-48"></div>
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white border border-gray-200 rounded-lg dark:bg-[#171717] dark:border-gray-800 space-y-4 sm:space-y-6">
                <div class="h-5 sm:h-6 bg-gray-200 rounded dark:bg-gray-800 w-32 sm:w-40 mb-4"></div>

                <div class="space-y-4">
                    <div>
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-28 sm:w-32 mb-2"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    </div>
                    <div>
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-28 sm:w-32 mb-2"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    </div>
                    <div>
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-20 sm:w-24 mb-2"></div>
                        <div class="h-20 sm:h-24 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                    </div>
                </div>
            </div>

            <div class="p-4 sm:p-6 bg-white border border-gray-200 rounded-lg dark:bg-[#171717] dark:border-gray-800">
                <div class="flex flex-col sm:flex-row justify-between gap-3 sm:gap-0 mb-4 sm:mb-6">
                    <div class="h-5 sm:h-6 bg-gray-200 rounded dark:bg-gray-800 w-32"></div>
                    <div class="flex gap-2">
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-16"></div>
                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-16"></div>
                    </div>
                </div>

                <div class="space-y-4">
                    @for($i=0; $i<5; $i++)
                        <div class="flex items-center justify-between py-2 border-b border-gray-100 dark:border-gray-800">
                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-1/3 sm:w-1/4"></div>
                            <div class="flex gap-4 sm:gap-8">
                                <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                                <div class="w-4 h-4 bg-gray-200 rounded dark:bg-gray-800"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>

            <div class="h-14 sm:h-16 bg-blue-50 dark:bg-blue-900/20 rounded-lg w-full"></div>
            <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 pt-2">
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-24"></div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-32"></div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500 ease-in-out space-y-4 sm:space-y-6">
            <!-- Header -->
            <div class="flex items-center gap-3 sm:gap-4">
                <a href="{{ route('admin.roles.index') }}" class="p-2 text-gray-500 rounded-md hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                </a>
                <div class="flex-1 min-w-0">
                    <h1 class="text-xl sm:text-2xl font-bold text-gray-900 dark:text-gray-100 truncate">Edit Role: {{ $role->display_name }}</h1>
                    <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">
                        @if($role->is_system)
                            System role - only permissions can be modified
                        @else
                            Modify role details and permissions
                        @endif
                    </p>
                </div>
            </div>

            @if($role->is_system)
                <div class="p-3 sm:p-4 border border-purple-200 rounded-lg bg-purple-50 dark:bg-purple-900/20 dark:border-purple-800">
                    <div class="flex gap-3">
                        <svg class="w-5 h-5 text-purple-600 dark:text-purple-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="text-sm font-medium text-purple-800 dark:text-purple-200">System Role</p>
                            <p class="text-xs sm:text-sm text-purple-700 dark:text-purple-300">This is a protected system role. You can only modify its permissions.</p>
                        </div>
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.roles.update', $role) }}" class="space-y-4 sm:space-y-6">
                @csrf
                @method('PUT')

                <!-- Basic Info Card (Only show if not system role) -->
                @if(!$role->is_system)
                    <div class="p-4 sm:p-6 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                        <h2 class="mb-4 text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Basic Information</h2>

                        <div class="space-y-4">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Role Name (Internal)</label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', $role->name) }}"
                                       class="block w-full mt-1 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 @error('name') border-red-500 @enderror">
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
                                       value="{{ old('display_name', $role->display_name) }}"
                                       class="block w-full mt-1 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 @error('display_name') border-red-500 @enderror">
                                @error('display_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Description</label>
                                <textarea name="description"
                                          id="description"
                                          rows="3"
                                          class="block w-full mt-1 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500 @error('description') border-red-500 @enderror">{{ old('description', $role->description) }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Permissions Matrix -->
                <div class="p-4 sm:p-6 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 mb-4">
                        <h2 class="text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Permissions</h2>
                        <div class="flex gap-2">
                            <button type="button" onclick="selectAll()" class="text-xs sm:text-sm text-blue-600 hover:underline dark:text-blue-400">
                                Select All
                            </button>
                            <span class="text-gray-400">|</span>
                            <button type="button" onclick="deselectAll()" class="text-xs sm:text-sm text-gray-600 hover:underline dark:text-gray-400">
                                Deselect All
                            </button>
                        </div>
                    </div>

                    {{-- Desktop Table View --}}
                    <div class="hidden md:block overflow-x-auto -mx-4 sm:-mx-6">
                        <div class="inline-block min-w-full align-middle px-4 sm:px-6">
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
                                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
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
                                                               {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
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
                    </div>

                    {{-- Mobile Card View --}}
                    <div class="md:hidden space-y-4">
                        @foreach($modules as $moduleKey => $moduleName)
                            <div class="p-3 border border-gray-200 rounded-lg dark:border-gray-700">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100 mb-3">{{ $moduleName }}</h3>
                                <div class="space-y-2">
                                    @foreach($actions as $actionKey => $actionName)
                                        @php
                                            $permission = $permissions->firstWhere('name', "{$moduleKey}.{$actionKey}");
                                        @endphp
                                        @if($permission)
                                            <label class="flex items-center justify-between p-2 rounded hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors cursor-pointer">
                                                <span class="text-sm text-gray-700 dark:text-gray-300">{{ $actionName }}</span>
                                                <input type="checkbox"
                                                       name="permissions[]"
                                                       value="{{ $permission->id }}"
                                                       {{ in_array($permission->id, $rolePermissions) ? 'checked' : '' }}
                                                       class="w-4 h-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500 dark:border-gray-600 dark:bg-gray-700 permission-checkbox">
                                            </label>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @error('permissions')
                        <p class="mt-2 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Role Stats -->
                <div class="p-3 sm:p-4 border border-blue-200 rounded-lg bg-blue-50 dark:bg-blue-900/20 dark:border-blue-800">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 sm:gap-4 text-sm">
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <span class="text-blue-800 dark:text-blue-200">
                                <strong>{{ $role->users()->count() }}</strong> {{ Str::plural('user', $role->users()->count()) }} assigned
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-4 h-4 text-blue-600 dark:text-blue-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-blue-800 dark:text-blue-200">
                                <strong>{{ count($rolePermissions) }}</strong> {{ Str::plural('permission', count($rolePermissions)) }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex flex-col-reverse sm:flex-row items-center justify-end gap-3">
                    <a href="{{ route('admin.roles.index') }}" class="w-full sm:w-auto text-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-600 dark:hover:bg-gray-700 transition-colors">
                        Cancel
                    </a>
                    <button type="submit" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700 transition-colors">
                        Update Role
                    </button>
                </div>
            </form>
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
    </div>
</x-app-layout>
