<x-app-layout>
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6 gap-4">
            <div>
                <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">Products</h1>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Manage your product catalog</p>
            </div>

            <a href="{{ route('admin.products.create') }}"
            class="inline-flex items-center justify-center text-white font-medium transition-all duration-200
                   bg-primary-600 hover:bg-primary-700 px-4 py-2 rounded-md text-sm w-full sm:w-auto">
                <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                <span>Add Product</span>
            </a>
        </div>

        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 mb-6">
            <form method="GET" action="{{ route('admin.products.index') }}" class="space-y-3">
                <div class="w-full">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Search by name, SKU, or barcode..."
                           class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                    <div>
                        <select name="category_id"
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <select name="is_active"
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">All Status</option>
                            <option value="1" {{ request('is_active') == '1' ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ request('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="flex gap-2 sm:col-span-2 lg:col-span-1">
                        <button type="submit"
                                class="flex-1 px-4 py-2.5 text-sm font-medium text-white transition bg-primary-600 rounded-lg hover:bg-primary-700">
                            Filter
                        </button>
                        <a href="{{ route('admin.products.index') }}"
                           class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-gray-700 transition bg-gray-200 rounded-lg dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600">
                            Clear
                        </a>
                    </div>
                </div>
            </form>
        </div>

        @if(session('success'))
            <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 border border-green-500 rounded-lg dark:bg-green-900/20 dark:text-green-400">
                {{ session('success') }}
            </div>
        @endif

        <div class="hidden md:block bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-[#0a0a0a] border-b border-gray-200 dark:border-gray-800">
                        <tr>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Image</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Product</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">SKU</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Category</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Price</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Stock</th>
                            <th class="px-4 py-3 text-xs font-medium text-left text-gray-500 uppercase dark:text-gray-400">Status</th>
                            <th class="px-4 py-3 text-xs font-medium text-right text-gray-500 uppercase dark:text-gray-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-800">
                        @forelse($products as $product)
                            <tr class="hover:bg-gray-50 dark:hover:bg-[#0a0a0a] transition">
                                <td class="px-4 py-3">
                                    @if($product->image)
                                        <img src="{{ Storage::url($product->image) }}"
                                             alt="{{ $product->name }}"
                                             class="object-cover w-12 h-12 rounded">
                                    @else
                                        <div class="flex items-center justify-center w-12 h-12 bg-gray-200 rounded dark:bg-gray-700">
                                            <span class="text-xs text-gray-400">No img</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</div>
                                    @if($product->barcode)
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ $product->barcode }}</div>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $product->sku }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $product->category->name ?? 'Uncategorized' }}</td>
                                <td class="px-4 py-3 text-sm font-medium text-gray-900 dark:text-gray-100">₱{{ number_format($product->price, 2) }}</td>
                                <td class="px-4 py-3 text-sm text-gray-600 dark:text-gray-400">{{ $product->total_stock ?? 0 }} units</td>
                                <td class="px-4 py-3">
                                    @if($product->is_active)
                                        <span class="px-2 py-1 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400">Active</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400">Inactive</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3">
                                    <div class="flex items-center justify-end gap-3">
                                        <a href="{{ route('admin.products.edit', $product) }}"
                                           class="text-sm font-medium text-primary-600 hover:text-primary-700 dark:text-primary-400 dark:hover:text-primary-300">
                                            Edit
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}"
                                              method="POST"
                                              class="inline"
                                              onsubmit="return confirm('Delete this product?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-medium text-red-700 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-4 py-8 text-sm text-center text-gray-500 dark:text-gray-400">
                                    No products found. <a href="{{ route('admin.products.create') }}" class="text-primary-600 hover:underline">Create one</a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="md:hidden space-y-3">
            @forelse($products as $product)
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-3">
                    <div class="flex items-start gap-3 mb-3">
                        @if($product->image)
                            <img src="{{ Storage::url($product->image) }}"
                                 alt="{{ $product->name }}"
                                 class="object-cover w-14 h-14 rounded flex-shrink-0">
                        @else
                            <div class="flex items-center justify-center w-14 h-14 bg-gray-200 rounded dark:bg-gray-700 flex-shrink-0">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                        @endif

                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-2">
                                <h3 class="font-medium text-gray-900 dark:text-gray-100 text-sm leading-tight">{{ $product->name }}</h3>
                                @if($product->is_active)
                                    <span class="px-2 py-0.5 text-xs font-medium text-green-700 bg-green-100 rounded-full dark:bg-green-900/20 dark:text-green-400 flex-shrink-0">Active</span>
                                @else
                                    <span class="px-2 py-0.5 text-xs font-medium text-gray-700 bg-gray-100 rounded-full dark:bg-gray-800 dark:text-gray-400 flex-shrink-0">Inactive</span>
                                @endif
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">SKU: {{ $product->sku }}</p>
                            <p class="text-lg font-semibold text-gray-900 dark:text-gray-100 mt-1">₱{{ number_format($product->price, 2) }}</p>
                        </div>
                    </div>

                    <div class="flex items-center justify-between text-xs text-gray-600 dark:text-gray-400 mb-3 pb-3 border-b border-gray-200 dark:border-gray-800">
                        <span>{{ $product->category->name ?? 'Uncategorized' }}</span>
                        <span>Stock: {{ $product->total_stock ?? 0 }}</span>
                    </div>

                    <div class="flex gap-2">
                        <a href="{{ route('admin.products.edit', $product) }}"
                           class="flex-1 inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700 transition">
                            <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                            Edit
                        </a>
                        <form action="{{ route('admin.products.destroy', $product) }}"
                              method="POST"
                              class="flex-1"
                              onsubmit="return confirm('Delete this product?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="w-full inline-flex items-center justify-center px-4 py-2.5 text-sm font-medium text-red-700 transition bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-8 text-center">
                    <svg class="w-16 h-16 mx-auto mb-4 text-gray-300 dark:text-gray-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400 mb-4">No products found.</p>
                    <a href="{{ route('admin.products.create') }}" class="inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-primary-600 rounded-lg hover:bg-primary-700">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Create Product
                    </a>
                </div>
            @endforelse
        </div>

        @if($products->hasPages())
            <div class="mt-6">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
