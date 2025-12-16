<x-app-layout>
    <div class="mx-auto max-w-7xl">
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Categories</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage product categories</p>
            </div>
            <a href="{{ route('admin.categories.create') }}"
               class="px-4 py-2 text-white transition rounded-lg bg-primary-600 hover:bg-primary-700">
                + Add Category
            </a>
        </div>

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="p-4 mb-6 text-green-700 bg-green-100 border border-green-500 rounded-lg dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="p-4 mb-6 text-red-700 bg-red-100 border border-red-500 rounded-lg dark:bg-red-900/20 dark:text-red-400">
                {{ session('error') }}
            </div>
        @endif

        <!-- Categories Table -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-gray-800">
                    <tr>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Name</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Slug</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Products</th>
                        <th class="px-6 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Status</th>
                        <th class="px-6 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                    @forelse($categories as $category)
                        <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition">
                            <td class="px-6 py-4">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</div>
                                @if($category->description)
                                    <div class="text-sm text-gray-500 dark:text-gray-400">{{ Str::limit($category->description, 50) }}</div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $category->slug }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                {{ $category->products_count }} products
                            </td>
                            <td class="px-6 py-4">
                                @if($category->is_active)
                                    <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">
                                        Active
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400">
                                        Inactive
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 space-x-2 text-right">
                                <a href="{{ route('admin.categories.edit', $category) }}"
                                   class="text-sm font-medium text-primary-600 hover:text-primary-700">
                                    Edit
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}"
                                      method="POST"
                                      class="inline"
                                      onsubmit="return confirm('Delete this category?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm font-medium text-red-600 hover:text-red-700">
                                        Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-8 text-center text-gray-500 dark:text-gray-400">
                                No categories found. <a href="{{ route('admin.categories.create') }}" class="text-primary-600 hover:underline">Create one</a>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $categories->links() }}
        </div>
    </div>
</x-app-layout>
