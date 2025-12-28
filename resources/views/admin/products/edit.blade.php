<x-app-layout>
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6">
            <div class="flex items-center gap-3 mb-3">
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex items-center justify-center w-10 h-10 text-gray-600 transition bg-gray-100 rounded-lg dark:bg-gray-800 dark:text-gray-400 hover:bg-gray-200 dark:hover:bg-gray-700">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <div>
                    <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Product</h1>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update product details and inventory</p>
                </div>
            </div>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-4 sm:space-y-6">
            @csrf
            @method('PUT')

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <h2 class="mb-4 text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Basic Information</h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Product Name *
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $product->name) }}"
                               required
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            SKU
                        </label>
                        <input type="text"
                               value="{{ $product->sku }}"
                               disabled
                               class="w-full px-3 py-2.5 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Barcode
                        </label>
                        <input type="text"
                               value="{{ $product->barcode }}"
                               disabled
                               class="w-full px-3 py-2.5 text-sm text-gray-600 bg-gray-100 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category
                        </label>
                        <select name="category_id"
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="">Uncategorized</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>
                        <select name="is_active"
                                class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                            <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <textarea name="description"
                                  rows="3"
                                  class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <h2 class="mb-4 text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Pricing</h2>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Cost (₱) *
                        </label>
                        <input type="number"
                               name="cost"
                               value="{{ old('cost', $product->cost) }}"
                               step="0.01"
                               min="0"
                               required
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Selling Price (₱) *
                        </label>
                        <input type="number"
                               name="price"
                               value="{{ old('price', $product->price) }}"
                               step="0.01"
                               min="0"
                               required
                               class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <h2 class="mb-4 text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Product Image</h2>

                @if($product->image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}"
                             class="object-cover w-24 h-24 sm:w-32 sm:h-32 border border-gray-300 rounded-lg dark:border-gray-700">
                    </div>
                @endif

                <input type="file"
                       name="image"
                       accept="image/*"
                       class="w-full px-3 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-primary-50 file:text-primary-700 hover:file:bg-primary-100 dark:file:bg-primary-900/20 dark:file:text-primary-400">
                <p class="mt-1 text-xs sm:text-sm text-gray-500 dark:text-gray-400">Maximum file size: 2MB. Leave empty to keep current image.</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-4 sm:p-6">
                <h2 class="mb-4 text-base sm:text-lg font-semibold text-gray-900 dark:text-gray-100">Branch Inventory</h2>
                <p class="mb-4 text-xs sm:text-sm text-gray-600 dark:text-gray-400">Update stock levels per branch</p>

                <div class="space-y-3">
                    @foreach($branches as $branch)
                        @php
                            $branchProduct = $product->branchProducts->where('branch_id', $branch->id)->first();
                        @endphp
                        <div class="flex flex-col sm:flex-row sm:items-center gap-3 p-3 sm:p-4 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg">
                            <div class="flex-1 min-w-0">
                                <span class="block font-medium text-sm text-gray-900 dark:text-gray-100 truncate">{{ $branch->name }}</span>
                                @if($branchProduct)
                                    <span class="block text-xs text-gray-600 dark:text-gray-400 mt-0.5">
                                        Current: {{ $branchProduct->quantity }} units
                                    </span>
                                @endif
                            </div>
                            <div class="flex gap-2 sm:gap-3">
                                <div class="flex-1 sm:flex-none">
                                    <input type="number"
                                           name="branches[{{ $branch->id }}][quantity]"
                                           placeholder="Quantity"
                                           min="0"
                                           value="{{ old('branches.'.$branch->id.'.quantity', $branchProduct->quantity ?? 0) }}"
                                           class="w-full sm:w-28 px-3 py-2 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                </div>
                                <div class="flex-1 sm:flex-none">
                                    <input type="number"
                                           name="branches[{{ $branch->id }}][low_stock_threshold]"
                                           placeholder="Alert Level"
                                           min="0"
                                           value="{{ old('branches.'.$branch->id.'.low_stock_threshold', $branchProduct->low_stock_threshold ?? 10) }}"
                                           class="w-full sm:w-28 px-3 py-2 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 sticky bottom-0 bg-white dark:bg-[#0a0a0a] py-4 -mx-4 px-4 sm:mx-0 sm:px-0 sm:static sm:justify-end border-t sm:border-t-0 border-gray-200 dark:border-gray-800">
                <a href="{{ route('admin.products.index') }}"
                   class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-gray-700 transition bg-gray-200 rounded-lg dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-300 dark:hover:bg-gray-600 order-2 sm:order-1">
                    Cancel
                </a>
                <button type="submit"
                        class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-white transition rounded-lg bg-primary-600 hover:bg-primary-700 order-1 sm:order-2">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Update Product
                </button>
            </div>
        </form>

        <div class="mt-6 p-4 sm:p-6 bg-red-50 dark:bg-red-900/10 border border-red-200 dark:border-red-900/50 rounded-lg">
            <h3 class="text-base sm:text-lg font-semibold text-red-900 dark:text-red-400 mb-2">Danger Zone</h3>
            <p class="text-sm text-red-700 dark:text-red-300 mb-4">Once you delete this product, there is no going back. Please be certain.</p>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this product? This action cannot be undone.');">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center justify-center px-6 py-2.5 text-sm font-medium text-red-700 transition bg-red-100 rounded-lg dark:bg-red-900/30 dark:text-red-400 hover:bg-red-200 dark:hover:bg-red-900/50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Delete Product
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
