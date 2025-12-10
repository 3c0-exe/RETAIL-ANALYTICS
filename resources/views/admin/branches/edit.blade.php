<x-app-layout>
    <div class="max-w-3xl mx-auto">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center gap-2 text-sm text-gray-600 dark:text-gray-400 mb-2">
                <a href="{{ route('admin.branches.index') }}" class="hover:text-gray-900 dark:hover:text-gray-100">Branches</a>
                <span>/</span>
                <span>Edit</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-gray-100">Edit Branch</h1>
        </div>

        <!-- Form -->
        <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
            <form action="{{ route('admin.branches.update', $branch) }}" method="POST">
                @csrf
                @method('PUT')

                <!-- Branch Name -->
                <div class="mb-6">
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Branch Name <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="name"
                        name="name"
                        value="{{ old('name', $branch->name) }}"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required
                    >
                    @error('name')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Branch Code -->
                <div class="mb-6">
                    <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Branch Code <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="text"
                        id="code"
                        name="code"
                        value="{{ old('code', $branch->code) }}"
                        maxlength="10"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required
                    >
                    @error('code')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Grid: Timezone and Currency -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                    <!-- Timezone -->
                    <div>
                        <label for="timezone" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Timezone <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="timezone"
                            name="timezone"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                            <option value="Asia/Manila" {{ old('timezone', $branch->timezone) === 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila</option>
                            <option value="Asia/Singapore" {{ old('timezone', $branch->timezone) === 'Asia/Singapore' ? 'selected' : '' }}>Asia/Singapore</option>
                            <option value="Asia/Tokyo" {{ old('timezone', $branch->timezone) === 'Asia/Tokyo' ? 'selected' : '' }}>Asia/Tokyo</option>
                            <option value="UTC" {{ old('timezone', $branch->timezone) === 'UTC' ? 'selected' : '' }}>UTC</option>
                        </select>
                        @error('timezone')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Currency -->
                    <div>
                        <label for="currency" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Currency <span class="text-red-500">*</span>
                        </label>
                        <select
                            id="currency"
                            name="currency"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                            required
                        >
                            <option value="PHP" {{ old('currency', $branch->currency) === 'PHP' ? 'selected' : '' }}>PHP - Philippine Peso</option>
                            <option value="USD" {{ old('currency', $branch->currency) === 'USD' ? 'selected' : '' }}>USD - US Dollar</option>
                            <option value="EUR" {{ old('currency', $branch->currency) === 'EUR' ? 'selected' : '' }}>EUR - Euro</option>
                        </select>
                        @error('currency')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <!-- Tax Rate -->
                <div class="mb-6">
                    <label for="tax_rate" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Tax Rate (%) <span class="text-red-500">*</span>
                    </label>
                    <input
                        type="number"
                        id="tax_rate"
                        name="tax_rate"
                        value="{{ old('tax_rate', $branch->tax_rate) }}"
                        step="0.01"
                        min="0"
                        max="100"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required
                    >
                    @error('tax_rate')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Manager -->
                <div class="mb-6">
                    <label for="manager_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Branch Manager
                    </label>
                    <select
                        id="manager_id"
                        name="manager_id"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                    >
                        <option value="">No manager assigned</option>
                        @foreach($managers as $manager)
                            <option value="{{ $manager->id }}" {{ old('manager_id', $branch->manager_id) == $manager->id ? 'selected' : '' }}>
                                {{ $manager->name }} ({{ $manager->email }})
                            </option>
                        @endforeach
                    </select>
                    @error('manager_id')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Status -->
                <div class="mb-6">
                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select
                        id="status"
                        name="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-gray-700 rounded-md bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-primary-500 focus:border-transparent"
                        required
                    >
                        <option value="active" {{ old('status', $branch->status) === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status', $branch->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Actions -->
                <div class="flex items-center gap-3 pt-6 border-t border-gray-200 dark:border-gray-800">
                    <button type="submit" class="px-6 py-2 bg-primary-600 hover:bg-primary-700 text-white rounded-md font-medium transition-colors">
                        Update Branch
                    </button>
                    <a href="{{ route('admin.branches.index') }}" class="px-6 py-2 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 rounded-md font-medium transition-colors">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
