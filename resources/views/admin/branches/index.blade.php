<x-app-layout>
    <div class="mx-auto max-w-7xl">

        {{-- ============================================================== --}}
        {{-- 1. FULL PAGE SKELETON (Visible on Load)                        --}}
        {{-- ============================================================== --}}
        <div id="PageSkeleton" class="animate-pulse space-y-6">
            <div class="flex items-center space-x-2 mb-4">
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-16"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-4"></div>
                <div class="h-3 bg-gray-200 rounded dark:bg-gray-700 w-24"></div>
            </div>

            <div class="flex items-center justify-between">
                <div>
                    <div class="h-8 bg-gray-200 rounded dark:bg-gray-700 w-48 mb-2"></div>
                    <div class="h-4 bg-gray-200 rounded dark:bg-gray-700 w-64"></div>
                </div>
                <div class="h-10 bg-gray-200 rounded-md dark:bg-gray-700 w-10 md:w-32"></div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div class="h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                    <div class="h-10 bg-gray-200 rounded dark:bg-gray-700"></div>
                    <div class="flex gap-2">
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 flex-1"></div>
                        <div class="h-10 bg-gray-200 rounded dark:bg-gray-700 w-20"></div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
                <div class="block md:hidden">
                    <x-card-skeleton count="3" />
                </div>
                <div class="hidden md:block">
                    <x-table-skeleton rows="5" :headers="true" :colSizes="['w-1/4', 'w-1/6', 'w-1/4', 'w-1/6', 'w-1/6', 'w-1/6']" />
                </div>
            </div>
        </div>

        {{-- ============================================================== --}}
        {{-- 2. REAL PAGE CONTENT (Hidden Initially)                        --}}
        {{-- ============================================================== --}}
        <div id="RealPageContent" class="hidden opacity-0 transition-opacity duration-500">

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
                            <span class="text-sm font-medium text-gray-500 dark:text-gray-400">Branches</span>
                        </div>
                    </li>
                </ol>
            </nav>

            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Branches</h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Manage your retail branches</p>
                </div>

                <a href="{{ route('admin.branches.create') }}"
                   onclick="showButtonLoading(this, 'Loading...')"
                   class="inline-flex items-center justify-center px-3 py-3 font-medium text-white transition-all duration-200 rounded-full bg-primary-600 hover:bg-primary-700 md:px-4 md:py-2 md:rounded-md">
                    <svg class="flex-shrink-0 w-4 h-4 md:mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                    <span class="hidden text-sm md:inline">Add Branch</span>
                </a>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 mb-6">
                <form method="GET" action="{{ route('admin.branches.index') }}" onsubmit="handleFilter(this)" class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Search by name or code..." class="w-full px-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                    </div>
                    <div>
                        <select name="status" class="w-full px-4 py-2 text-sm text-gray-900 bg-white border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-800 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                    <input type="hidden" name="sort" value="{{ request('sort') }}">
                    <input type="hidden" name="direction" value="{{ request('direction') }}">

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

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
                <table class="hidden w-full md:table">
                    <thead class="border-b border-gray-200 bg-gray-50 dark:bg-gray-800/50 dark:border-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Branch</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Code</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Manager</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Users</th>
                            <th class="px-6 py-3 text-xs font-medium tracking-wider text-left text-gray-500 uppercase dark:text-gray-400">Status</th>
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
                                <td class="px-6 py-4 whitespace-nowrap"><span class="px-2 py-1 font-mono text-xs text-gray-700 bg-gray-100 rounded dark:bg-gray-800 dark:text-gray-300">{{ $branch->code }}</span></td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($branch->manager)
                                        <div class="text-sm text-gray-900 dark:text-gray-100">{{ $branch->manager->name }}</div>
                                    @else
                                        <span class="text-sm text-gray-400 dark:text-gray-500">No manager</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 whitespace-nowrap dark:text-gray-100">{{ $branch->users_count }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($branch->status === 'active')
                                        <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-300">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-300">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm font-medium text-right whitespace-nowrap">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.branches.edit', $branch) }}"
                                           onclick="showButtonLoading(this, '')"
                                           class="text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">
                                            Edit
                                        </a>

                                        <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" onsubmit="return handleDelete(this);">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500 dark:text-gray-400">No branches found.</td></tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="divide-y divide-gray-200 md:hidden dark:divide-gray-800">
                    @forelse($branches as $branch)
                        <div class="p-4 hover:bg-gray-50 dark:hover:bg-gray-800/50">
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

                             <div class="mb-3">
                                <div class="mb-1 text-xs tracking-wider text-gray-500 uppercase dark:text-gray-400">Code</div>
                                <span class="px-2 py-1 font-mono text-xs text-gray-700 bg-gray-100 rounded dark:bg-gray-800 dark:text-gray-300">{{ $branch->code }}</span>
                            </div>

                             <div class="flex items-center gap-3 pt-3 border-t border-gray-200 dark:border-gray-800">
                                <a href="{{ route('admin.branches.edit', $branch) }}"
                                   onclick="showButtonLoading(this, 'Editing...')"
                                   class="text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300">Edit</a>

                                <form action="{{ route('admin.branches.destroy', $branch) }}" method="POST" onsubmit="return handleDelete(this);">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">Delete</button>
                                </form>
                            </div>
                        </div>
                    @empty
                        <div class="px-4 py-12 text-center text-gray-500 dark:text-gray-400">No branches found.</div>
                    @endforelse
                </div>
            </div>

            @if($branches->hasPages())
                <div class="mt-6">{{ $branches->links() }}</div>
            @endif
        </div>
    </div>

    {{-- ============================================================== --}}
    {{-- 3. JAVASCRIPT LOGIC                                            --}}
    {{-- ============================================================== --}}
    <script>
        // 1. Initial Page Skeleton
        document.addEventListener('DOMContentLoaded', () => {
            setTimeout(() => {
                const pageSkeleton = document.getElementById('PageSkeleton');
                const realContent = document.getElementById('RealPageContent');
                if (pageSkeleton) pageSkeleton.remove();
                if (realContent) {
                    realContent.classList.remove('hidden');
                    setTimeout(() => realContent.classList.remove('opacity-0'), 10);
                }
            }, 500);
        });

        // 2. Handle Delete Submission
        window.handleDelete = (form) => {
            if (confirm('Are you sure you want to delete this branch?')) {
                const btn = form.querySelector('button[type="submit"]');
                showButtonLoading(btn, 'Deleting...');
                return true;
            }
            return false;
        };

        // 3. Handle Filter Submission
        window.handleFilter = (form) => {
            const btn = form.querySelector('button[type="submit"]');
            showButtonLoading(btn, 'Filtering...');
        };
    </script>
</x-app-layout>
