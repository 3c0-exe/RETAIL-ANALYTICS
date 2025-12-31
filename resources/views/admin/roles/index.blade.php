<x-app-layout>
    <div class="space-y-4 sm:space-y-6">
        {{-- ============================================================== --}}
        {{-- 1. PAGE SKELETON (Visible on Load)                             --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-4 sm:space-y-6">

            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex-1">
                    <div class="h-7 sm:h-8 bg-gray-200 rounded dark:bg-gray-800 w-48 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-64"></div>
                </div>
                <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-32"></div>
            </div>

            <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <div class="flex flex-col gap-3 sm:flex-row sm:gap-4">
                    <div class="flex-1 h-10 bg-gray-200 rounded dark:bg-gray-800"></div>
                    <div class="h-10 bg-gray-200 rounded dark:bg-gray-800 w-full sm:w-24"></div>
                </div>
            </div>

            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                {{-- Desktop skeleton --}}
                <div class="hidden md:block">
                    <div class="bg-gray-50 dark:bg-gray-900 px-6 py-3 border-b border-gray-200 dark:border-gray-700 grid grid-cols-6 gap-4">
                        @for($i=0; $i<6; $i++)
                            <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                        @endfor
                    </div>
                    <div class="divide-y divide-gray-200 dark:divide-gray-700">
                        @for($i=0; $i<5; $i++)
                            <div class="px-6 py-4 grid grid-cols-6 gap-4 items-center">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-200 rounded-full dark:bg-gray-800"></div>
                                    <div class="space-y-2">
                                        <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-24"></div>
                                        <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-16"></div>
                                    </div>
                                </div>
                                <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-full"></div>
                                <div class="flex justify-center"><div class="h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-16"></div></div>
                                <div class="flex justify-center"><div class="h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-24"></div></div>
                                <div class="flex justify-center"><div class="h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-20"></div></div>
                                <div class="flex justify-end gap-2">
                                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-12"></div>
                                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-12"></div>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>
                {{-- Mobile skeleton --}}
                <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                    @for($i=0; $i<5; $i++)
                        <div class="p-4 space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-gray-200 rounded-full dark:bg-gray-800"></div>
                                <div class="flex-1 space-y-2">
                                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-800 w-3/4"></div>
                                    <div class="h-3 bg-gray-200 rounded dark:bg-gray-800 w-1/2"></div>
                                </div>
                            </div>
                            <div class="flex gap-2">
                                <div class="h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-20"></div>
                                <div class="h-6 bg-gray-200 rounded-full dark:bg-gray-800 w-24"></div>
                            </div>
                        </div>
                    @endfor
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL CONTENT (Hidden Initially)                             --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500 ease-in-out space-y-4 sm:space-y-6">
            <!-- Header -->
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Roles & Permissions</h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage user roles and their permissions</p>
                </div>
                <a href="{{ route('admin.roles.create') }}" class="inline-flex items-center justify-center gap-2 px-4 py-2 text-sm font-medium text-white transition rounded-md bg-primary-600 hover:bg-primary-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Create Role
                </a>
            </div>

            <!-- Filters -->
            <div class="p-4 bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                <form method="GET" class="flex flex-col gap-3 sm:flex-row sm:gap-4">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search roles..."
                           class="flex-1 border-gray-300 rounded-md dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100 focus:ring-primary-500 focus:border-primary-500">

                    <div class="flex gap-2 sm:gap-3">
                        <button type="submit" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700 transition-colors">
                            Search
                        </button>

                        @if(request('search'))
                            <a href="{{ route('admin.roles.index') }}" class="flex-1 sm:flex-none px-4 py-2 text-sm font-medium text-center text-gray-700 bg-gray-100 rounded-md hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                                Clear
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Roles Table/Cards -->
            <div class="overflow-hidden bg-white border border-gray-200 rounded-lg dark:bg-gray-800 dark:border-gray-700">
                {{-- Desktop Table View --}}
                <div class="hidden md:block overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900">
                            <tr>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Role</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Description</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400">Users</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400">Permissions</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-center text-gray-500 uppercase dark:text-gray-400">Type</th>
                                <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($roles as $role)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center gap-3">
                                            <div class="flex items-center justify-center w-10 h-10 text-sm font-medium text-white rounded-full bg-primary-600">
                                                {{ substr($role->display_name, 0, 1) }}
                                            </div>
                                            <div>
                                                <p class="font-medium text-gray-900 dark:text-gray-100">{{ $role->display_name }}</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $role->name }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 max-w-xs">
                                        <p class="text-sm text-gray-600 dark:text-gray-400 truncate">
                                            {{ $role->description ?? '—' }}
                                        </p>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                            {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            {{ $role->permissions_count }} {{ Str::plural('permission', $role->permissions_count) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center whitespace-nowrap">
                                        @if($role->is_system)
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200">
                                                System
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                                Custom
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right whitespace-nowrap">
                                        <div class="flex items-center justify-end gap-3">
                                            <a href="{{ route('admin.roles.edit', $role) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 transition-colors">
                                                Edit
                                            </a>

                                            @if(!$role->is_system)
                                                <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Are you sure you want to delete this role?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 transition-colors">
                                                        Delete
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                                        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No roles found</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Card View --}}
                <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($roles as $role)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            <div class="flex items-start gap-3 mb-3">
                                <div class="flex items-center justify-center flex-shrink-0 w-10 h-10 text-sm font-medium text-white rounded-full bg-primary-600">
                                    {{ substr($role->display_name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 dark:text-gray-100 truncate">{{ $role->display_name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $role->name }}</p>
                                </div>
                                @if($role->is_system)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200 flex-shrink-0">
                                        System
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 flex-shrink-0">
                                        Custom
                                    </span>
                                @endif
                            </div>

                            @if($role->description)
                                <p class="mb-3 text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $role->description }}
                                </p>
                            @endif

                            <div class="flex flex-wrap gap-2 mb-3">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                    {{ $role->users_count }} {{ Str::plural('user', $role->users_count) }}
                                </span>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                    {{ $role->permissions_count }} {{ Str::plural('permission', $role->permissions_count) }}
                                </span>
                            </div>

                            <div class="flex gap-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="flex-1 text-sm text-center py-2 text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 font-medium transition-colors">
                                    Edit
                                </a>

                                @if(!$role->is_system)
                                    <form method="POST" action="{{ route('admin.roles.destroy', $role) }}" onsubmit="return confirm('Are you sure you want to delete this role?');" class="flex-1">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="w-full text-sm py-2 text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium transition-colors">
                                            Delete
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="px-6 py-12 text-center">
                            <svg class="w-12 h-12 mx-auto text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">No roles found</p>
                        </div>
                    @endforelse
                </div>

                @if($roles->hasPages())
                    <div class="px-4 sm:px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                        {{ $roles->links() }}
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
    </div>
</x-app-layout>
