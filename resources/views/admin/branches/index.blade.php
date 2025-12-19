<x-app-layout>
    <div class="max-w-7xl mx-auto">
        <!-- Header -->
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Branches</h1>
                <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your retail branches</p>
            </div>

            <a href="{{ route('admin.branches.create') }}"
            class="inline-flex items-center justify-center text-white font-medium transition-all duration-200
                    bg-primary-600 hover:bg-primary-700
                    md:px-4 md:py-2 md:rounded-md
                    px-3 py-3 rounded-full">
                <svg class="w-4 h-4 md:mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span class="hidden md:inline text-sm">Add Branch</span>
            </a>
        </div>

        <!-- Branches Table -->
       <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
    <!-- Desktop Table View -->
    <table class="hidden md:table w-full">
        <thead class="bg-gray-50 dark:bg-gray-800/50 border-b border-gray-200 dark:border-gray-800">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Branch</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Code</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Manager</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Users</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
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
                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded text-xs font-mono">
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
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                        {{ $branch->users_count }} user{{ $branch->users_count != 1 ? 's' : '' }}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($branch->status === 'active')
                            <span class="px-2 py-1 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-full text-xs font-medium">Active</span>
                        @else
                            <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-xs font-medium">Inactive</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
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

    <!-- Mobile Card View -->
    <div class="md:hidden divide-y divide-gray-200 dark:divide-gray-800">
        @forelse($branches as $branch)
            <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50">
                <!-- Branch Name & Status -->
                <div class="flex items-start justify-between mb-3">
                    <div class="flex-1">
                        <div class="font-medium text-gray-900 dark:text-gray-100">{{ $branch->name }}</div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ $branch->timezone }}</div>
                    </div>
                    @if($branch->status === 'active')
                        <span class="px-2 py-1 bg-green-100 dark:bg-green-900/20 text-green-700 dark:text-green-300 rounded-full text-xs font-medium">Active</span>
                    @else
                        <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded-full text-xs font-medium">Inactive</span>
                    @endif
                </div>

                <!-- Code -->
                <div class="mb-3">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Code</div>
                    <span class="px-2 py-1 bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300 rounded text-xs font-mono">
                        {{ $branch->code }}
                    </span>
                </div>

                <!-- Manager -->
                <div class="mb-3">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Manager</div>
                    @if($branch->manager)
                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $branch->manager->name }}</div>
                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $branch->manager->email }}</div>
                    @else
                        <span class="text-sm text-gray-400 dark:text-gray-500">No manager</span>
                    @endif
                </div>

                <!-- Users -->
                <div class="mb-3">
                    <div class="text-xs text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-1">Users</div>
                    <div class="text-sm text-gray-900 dark:text-gray-100">
                        {{ $branch->users_count }} user{{ $branch->users_count != 1 ? 's' : '' }}
                    </div>
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-3 border-t border-gray-200 dark:border-gray-800">
                    <a href="{{ route('admin.branches.edit', $branch) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 text-sm font-medium">
                        Edit
                    </a>
                    <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this branch?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 text-sm font-medium">
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
