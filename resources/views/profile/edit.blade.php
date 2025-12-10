<x-app-layout>
    <div class="py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            {{-- Page Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 dark:text-white">Profile Settings</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage your account settings and preferences</p>
            </div>

            {{-- Success Message --}}
            @if (session('success'))
                <div class="mb-6 p-4 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg">
                    <p class="text-sm text-green-800 dark:text-green-200">{{ session('success') }}</p>
                </div>
            @endif

            {{-- Profile Information --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Information</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Update your account's profile information and email address.</p>
                </div>

                <form method="POST" action="{{ route('profile.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Name --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Name</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                            class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            required>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Role Badge --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Role</label>
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 dark:bg-blue-900/30 text-blue-800 dark:text-blue-300">
                            {{ ucfirst($user->role) }}
                        </span>
                    </div>

                    @if($user->role !== 'admin')
                        {{-- Branch Assignment --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Branch</label>
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 dark:bg-purple-900/30 text-purple-800 dark:text-purple-300">
                                {{ $user->branch->name ?? 'No Branch Assigned' }}
                            </span>
                        </div>
                    @endif

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-500/20">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>

            {{-- Avatar Upload --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Profile Picture</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Upload a profile picture (JPEG, PNG, GIF - max 2MB)</p>
                </div>

                <div class="p-6">
                    <div class="flex items-center space-x-6">
                        {{-- Current Avatar --}}
                        <div class="flex-shrink-0">
                            @if($user->avatar)
                                <img src="{{ Storage::url($user->avatar) }}" alt="Avatar" class="w-20 h-20 rounded-full object-cover border-4 border-gray-200 dark:border-gray-700">
                            @else
                                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-blue-500 to-purple-600 flex items-center justify-center border-4 border-gray-200 dark:border-gray-700">
                                    <span class="text-2xl font-bold text-white">{{ substr($user->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Upload Form --}}
                        <div class="flex-1">
                            <form method="POST" action="{{ route('profile.avatar.update') }}" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <input type="file" name="avatar" id="avatar" accept="image/*"
                                        class="block w-full text-sm text-gray-900 dark:text-gray-300 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 dark:file:bg-blue-900/30 file:text-blue-700 dark:file:text-blue-300 hover:file:bg-blue-100 dark:hover:file:bg-blue-900/50 cursor-pointer">
                                    @error('avatar')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center space-x-3">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-lg transition-colors">
                                        Upload New
                                    </button>

                                    @if($user->avatar)
                                        <button type="button" onclick="document.getElementById('deleteAvatarForm').submit()" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg transition-colors">
                                            Remove
                                        </button>
                                    @endif
                                </div>
                            </form>

                            @if($user->avatar)
                                <form id="deleteAvatarForm" method="POST" action="{{ route('profile.avatar.delete') }}" class="hidden">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Change Password --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 mb-6">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Change Password</h2>
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Ensure your account is using a strong password to stay secure.</p>
                </div>

                <form method="POST" action="{{ route('profile.password.update') }}" class="p-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Current Password --}}
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                            class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            required>
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">New Password</label>
                        <input type="password" name="password" id="password"
                            class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            required>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Confirm New Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="w-full px-4 py-2.5 bg-white dark:bg-gray-900 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-900 dark:text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-colors"
                            required>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg transition-colors focus:ring-4 focus:ring-blue-500/20">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>

{{-- Theme Preference --}}
<div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700">
    <div class="p-6 border-b border-gray-200 dark:border-gray-700">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Theme Preference</h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Choose your preferred color theme.</p>
    </div>

    <div class="p-6" x-data="{ selectedTheme: '{{ $user->theme }}' }">
        <div class="flex items-center space-x-4">
            {{-- Light Theme --}}
            <label class="flex items-center cursor-pointer">
                <input type="radio"
                       x-model="selectedTheme"
                       value="light"
                       @change="
                           const rootEl = document.documentElement;
                           const alpineData = Alpine.$data(rootEl);
                           alpineData.darkMode = false;
                           fetch('{{ route('profile.theme.update') }}', {
                               method: 'PUT',
                               headers: {
                                   'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                   'Content-Type': 'application/json'
                               },
                               body: JSON.stringify({ theme: 'light' })
                           })
                       "
                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-2 focus:ring-blue-500">
                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Light Mode</span>
            </label>

            {{-- Dark Theme --}}
            <label class="flex items-center cursor-pointer">
                <input type="radio"
                       x-model="selectedTheme"
                       value="dark"
                       @change="
                           const rootEl = document.documentElement;
                           const alpineData = Alpine.$data(rootEl);
                           alpineData.darkMode = true;
                           fetch('{{ route('profile.theme.update') }}', {
                               method: 'PUT',
                               headers: {
                                   'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                   'Content-Type': 'application/json'
                               },
                               body: JSON.stringify({ theme: 'dark' })
                           })
                       "
                       class="w-4 h-4 text-blue-600 border-gray-300 focus:ring-2 focus:ring-blue-500">
                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300">Dark Mode</span>
            </label>
        </div>

        <p class="mt-4 text-xs text-gray-500 dark:text-gray-400">
            Theme changes apply immediately and are saved automatically.
        </p>
    </div>
</div>
        </div>
    </div>
</x-app-layout>
