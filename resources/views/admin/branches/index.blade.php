<x-app-layout>
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Branches</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your retail branches</p>
            </div>

            <a href="{{ route('admin.branches.create') }}"
            class="inline-flex items-center justify-center px-3 py-3 font-medium text-white transition-all duration-200 rounded-full bg-primary-600 hover:bg-primary-700 md:px-4 md:py-2 md:rounded-md">
                <svg class="flex-shrink-0 w-4 h-4 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden text-sm md:inline">Add Branch</span>
            </a>
        </div>

        <!-- Filters -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.branches.index') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
                <!-- Search -->
                <div>
                    <input
                        type="text"
                        name="search"
                        value="{{ request('search') }}"
                        placeholder="Search by name or code..."
                        class="w-full px-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                </div>

                <!-- Status Filter -->
                <div>
                    <select
                        name="status"
                        class="w-full px-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
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
                    <a href="{{ route('admin.branches.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 transition-colors bg-gray-100 rounded-md dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 dark:text-gray-300">
                        Clear
                    </a>
                </div>
            </form>
        </div>

        <!-- Branches Table -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
            <!-- Desktop Table View -->
            <table class="hidden w-full md:table">
                <thead class="border-b border-gray-200 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                    <tr>
                        <!-- Sortable: Branch Name -->
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            <a href="{{ route('admin.branches.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'name', 'direction' => request('sort') === 'name' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                Branch
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

                        <!-- Sortable: Code -->
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            <a href="{{ route('admin.branches.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'code', 'direction' => request('sort') === 'code' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                Code
                                @if(request('sort') === 'code')
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

                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Manager</th>
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Users</th>

                        <!-- Sortable: Status -->
                        <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">
                            <a href="{{ route('admin.branches.index', array_merge(request()->except(['sort', 'direction', 'page']), ['sort' => 'status', 'direction' => request('sort') === 'status' && request('direction') === 'asc' ? 'desc' : 'asc'])) }}"
                               class="flex items-center gap-1 hover:text-gray-700 dark:hover:text-gray-300">
                                Status
                                @if(request('sort') === 'status')
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
                    @forelse($branches as $branch)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $branch->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $branch->timezone }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 py-1 font-mono text-xs text-gray-700 bg-gray-100 rounded dark:bg-gray-800 dark:text-gray-300">
                                    {{ $branch->code }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($branch->manager)
                                    <div class="text-sm text-gray-900 dark:text-gray-100">{{ $branch->manager->name }}</div>
                                    <div class="text-xs text-gray-500 dark:text-gray-400">{{ $branch->manager->email }}</div>
                                @else
                                    <span class="text-sm text-gray-400 dark:text-gray-500">No manager</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap dark:text-gray-100">
                                {{ $branch->users_count }} user{{ $branch->users_count != 1 ? 's' : '' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($branch->status === 'active')
                                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-300">Active</span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-300">Inactive</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.branches.edit', $branch) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">
                                No branches found. Create your first branch!
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Mobile Card View (unchanged) -->
            <div class="divide-y divide-gray-200 md:hidden dark:divide-gray-800">
                @forelse($branches as $branch)
                    <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                        <!-- Branch Name & Status -->
                        <div class="flex items-start justify-between mb-3">
                            <div class="flex-1">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $branch->name }}</div>
                                <div class="text-sm text-gray-500 dark:text-gray-400">{{ $branch->timezone }}</div>
                            </div>
                            @if($branch->status === 'active')
                                <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-300">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-300">Inactive</span>
                            @endif
                        </div>

                        <!-- Code -->
                        <div class="mb-3">
                            <div class="mb-1 text-xs tracking-wider text-gray-500 uppercase dark:text-gray-400">Code</div>
                            <span class="px-2 py-1 font-mono text-xs text-gray-700 bg-gray-100 rounded dark:bg-gray-800 dark:text-gray-300">
                                {{ $branch->code }}
                            </span>
                        </div>

                        <!-- Manager -->
                        <div class="mb-3">
                            <div class="mb-1 text-xs tracking-wider text-gray-500 uppercase dark:text-gray-400">Manager</div>
                            @if($branch->manager)
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $branch->manager->name }}</div>
                                <div class="text-xs text-gray-500 dark:text-gray-400">{{ $branch->manager->email }}</div>
                            @else
                                <span class="text-sm text-gray-400 dark:text-gray-500">No manager</span>
                            @endif
                        </div>

                        <!-- Users -->
                        <div class="mb-3">
                            <div class="mb-1 text-xs tracking-wider text-gray-500 uppercase dark:text-gray-400">Users</div>
                            <div class="text-sm text-gray-900 dark:text-gray-100">
                                {{ $branch->users_count }} user{{ $branch->users_count != 1 ? 's' : '' }}
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center gap-3 pt-3 border-t border-gray-200 dark:border-gray-800">
                            <a href="{{ route('admin.branches.edit', $branch) }}" class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                Edit
                            </a>
                            <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">
                        No branches found. Create your first branch!
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        @if($branches->hasPages())
            <div class="mt-6">
                {{ $branches->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
