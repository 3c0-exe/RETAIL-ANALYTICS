<x-app-layout>
    <div class="max-w-4xl mx-auto">
        <!-- Header -->
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Product</h1>
            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update product details and inventory</p>
        </div>

        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Basic Info Card -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Basic Information</h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Product Name -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Product Name *
                        </label>
                        <input type="text"
                               name="name"
                               value="{{ old('name', $product->name) }}"
                               required
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100">
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- SKU (Read-only) -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            SKU
                        </label>
                        <input type="text"
                               value="{{ $product->sku }}"
                               disabled
                               class="w-full px-3 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <!-- Barcode (Read-only) -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Barcode
                        </label>
                        <input type="text"
                               value="{{ $product->barcode }}"
                               disabled
                               class="w-full px-3 py-2 text-gray-600 bg-gray-100 border border-gray-300 rounded-lg dark:border-gray-700 dark:bg-gray-800 dark:text-gray-400">
                    </div>

                    <!-- Category -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Category
                        </label>
                        <select name="category_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100">
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

                    <!-- Status -->
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Status
                        </label>
                        <select name="is_active"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100">
                            <option value="1" {{ old('is_active', $product->is_active) == 1 ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ old('is_active', $product->is_active) == 0 ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                            Description
                        </label>
                        <textarea name="description"
                                  rows="3"
                                  class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Pricing Card -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Pricing</h2>

                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    <!-- Cost -->
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
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100">
                        @error('cost')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Price -->
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
                               class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100">
                        @error('price')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Image Upload Card -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Product Image</h2>

                @if($product->image)
                    <div class="mb-4">
                        <img src="{{ Storage::url($product->image) }}"
                             alt="{{ $product->name }}"
                             class="object-cover w-32 h-32 border border-gray-300 rounded-lg dark:border-gray-700">
                    </div>
                @endif

                <input type="file"
                       name="image"
                       accept="image/*"
                       class="w-full px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100">
                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Maximum file size: 2MB. Leave empty to keep current image.</p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Inventory Card -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">Branch Inventory</h2>
                <p class="mb-4 text-sm text-gray-600 dark:text-gray-400">Update stock levels per branch</p>

                <div class="space-y-4">
                    @foreach($branches as $branch)
                        @php
                            $branchProduct = $product->branchProducts->where('branch_id', $branch->id)->first();
                        @endphp
                        <div class="flex items-center gap-4 p-4 bg-gray-50 dark:bg-[#0a0a0a] rounded-lg">
                            <div class="flex-1">
                                <span class="font-medium text-gray-900 dark:text-gray-100">{{ $branch->name }}</span>
                                @if($branchProduct)
                                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">
                                        Current: {{ $branchProduct->quantity }} units
                                    </span>
                                @endif
                            </div>
                            <div>
                                <input type="number"
                                       name="branches[{{ $branch->id }}][quantity]"
                                       placeholder="Quantity"
                                       min="0"
                                       value="{{ old('branches.'.$branch->id.'.quantity', $branchProduct->quantity ?? 0) }}"
                                       class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 text-sm">
                            </div>
                            <div>
                                <input type="number"
                                       name="branches[{{ $branch->id }}][low_stock_threshold]"
                                       placeholder="Low Stock Alert"
                                       min="0"
                                       value="{{ old('branches.'.$branch->id.'.low_stock_threshold', $branchProduct->low_stock_threshold ?? 10) }}"
                                       class="w-32 px-3 py-2 border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#0a0a0a] text-gray-900 dark:text-gray-100 text-sm">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end gap-3">
                <a href="{{ route('admin.products.index') }}"
                   class="px-6 py-2 text-gray-700 transition bg-gray-300 rounded-lg dark:bg-gray-700 dark:text-gray-300 hover:bg-gray-400 dark:hover:bg-gray-600">
                    Cancel
                </a>
                <button type="submit"
                        class="px-6 py-2 text-white transition rounded-lg bg-primary-600 hover:bg-primary-700">
                    Update Product
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
