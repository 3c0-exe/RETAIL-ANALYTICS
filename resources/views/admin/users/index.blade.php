<x-app-layout>
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Users</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage system users and their roles</p>
            </div>

            <a href="{{ route('admin.users.create') }}"
            class="inline-flex items-center justify-center px-3 py-3 font-medium text-white transition-all duration-200 rounded-full bg-primary-600 hover:bg-primary-700 md:px-4 md:py-2 md:rounded-md">
                <svg class="flex-shrink-0 w-4 h-4 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden text-sm md:inline">Add User</span>
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-4">
                <!-- Search -->
                <div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name or email..."
                        class="w-full px-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                </div>

                <!-- Role Filter -->
                <div>
                    <select
                        name="role"
                        class="w-full px-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="branch_manager" {{ request('role') === 'branch_manager' ? 'selected' : '' }}>Branch Manager</option>
                        <option value="analyst" {{ request('role') === 'analyst' ? 'selected' : '' }}>Analyst</option>
                        <option value="viewer" {{ request('role') === 'viewer' ? 'selected' : '' }}>Viewer</option>
                    </select>
                </div>

                <!-- Branch Filter -->
                <div>
                    <select
                        name="branch_id"
                        class="w-full px-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                        <option value="">All Branches</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ request('branch_id') == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Hidden inputs to preserve sorting -->
                <input type="hidden" name="sort" value="{{ request('sort') }}">
                <input type="hidden" name="direction" value="{{ request('direction') }}">

                <!-- Actions -->
                <div class="flex gap-2">
                    <button type="submit" class="flex-1 px-4 py-2 text-sm font-medium text-white transition-colors rounded-md bg-primary-600 hover:bg-primary-700">
                        Filter
                    </button>
                    <a href="{{ route('admin.users.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-md dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-gray-300">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
            <!-- Desktop Table View -->
            <table class="hidden w-full md:table">
                <thead class="border-b border-gray-200 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                    <tr>
                        <!-- Sortable: User Name -->
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            <a href="{{ route('admin.users.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                User
                                @if(request('sort') === 'name')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(request('direction') === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>

                        <!-- Sortable: Role -->
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            <a href="{{ route('admin.users.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'role', 'direction' => request('sort') === 'role' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                Role
                                @if(request('sort') === 'role')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(request('direction') === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>

                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Branch</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Theme</th>

                        <!-- Sortable: Created Date -->
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            <a href="{{ route('admin.users.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'created_at', 'direction' => request('sort') === 'created_at' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                Created
                                @if(request('sort') === 'created_at')
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        @if(request('direction') === 'asc')
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                                        @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                        @endif
                                    </svg>
                                @endif
                            </a>
                        </th>

                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-right text-gray-500 uppercase dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex items-center justify-center w-10 h-10 font-medium text-white rounded-full bg-primary-600 dark:bg-primary-500">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 rounded-full text-xs font-medium
                                    @if($user->role === 'admin') bg-purple-100 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300
                                    @elseif($user->role === 'branch_manager') bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300
                                    @elseif($user->role === 'analyst') bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300
                                    @else bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                                    @endif">
                                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap dark:text-gray-100">
                                @if($user->branch)
                                    <div class="flex items-center gap-2">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        {{ $user->branch->name }}
                                    </div>
                                @else
                                    <span class="text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->theme === 'dark')
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap dark:text-gray-400">
                                {{ $user->created_at->format('M d, Y') }}
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.users.edit', $user) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                        Edit
                                    </a>
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                                Delete
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No users found. Try adjusting your filters.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile Card View (unchanged) -->
            <div class="divide-y divide-gray-200 md:hidden dark:divide-gray-800">
                @forelse($users as $user)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <!-- User Info -->
                        <div class="flex items-center mb-3">
                            <div class="flex items-center justify-center w-12 h-12 text-lg font-medium text-white rounded-full bg-primary-600 dark:bg-primary-500">
                                {{ substr($user->name, 0, 1) }}
                            </div>
                            <div class="flex-1 ml-3">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                            </div>
                        </div>

                        <!-- Role -->
                        <div class="mb-3">
                            <div class="mb-1 text-xs tracking-wider text-gray-500 uppercase dark:text-gray-400">Role</div>
                            <span class="px-2 py-1 rounded-full text-xs font-medium
                                @if($user->role === 'admin') bg-purple-100 dark:bg-purple-900/20 text-purple-700 dark:text-purple-300
                                @elseif($user->role === 'branch_manager') bg-blue-100 dark:bg-blue-900/20 text-blue-700 dark:text-blue-300
                                @elseif($user->role === 'analyst') bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300
                                @else bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300
                                @endif">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>

                        <!-- Branch & Theme -->
                        <div class="grid grid-cols-2 gap-3 mb-3">
                            <div>
                                <div class="mb-1 text-xs tracking-wider text-gray-500 uppercase dark:text-gray-400">Branch</div>
                                @if($user->branch)
                                    <div class="flex items-center gap-1 text-sm text-gray-900 dark:text-gray-100">
                                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        </svg>
                                        {{ $user->branch->name }}
                                    </div>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">—</span>
                                @endif
                            </div>
                            <div>
                                <div class="mb-1 text-xs tracking-wider text-gray-500 uppercase dark:text-gray-400">Theme</div>
                                @if($user->theme === 'dark')
                                    <svg class="w-5 h-5 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path>
                                    </svg>
                                @else
                                    <svg class="w-5 h-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                @endif
                            </div>
                        </div>

                        <!-- Created Date -->
                        <div class="mb-3">
                            <div class="mb-1 text-xs tracking-wider text-gray-500 uppercase dark:text-gray-400">Created</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">{{ $user->created_at->format('M d, Y') }}</div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-3 border-t border-gray-200 dark:border-gray-800">
                            <a href="{{ route('admin.users.edit', $user) }}" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                Edit
                            </a>
                            @if($user->id !== auth()->id())
                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                        Delete
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                        No users found. Try adjusting your filters.
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
