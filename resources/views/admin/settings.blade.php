<x-app-layout>
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <!-- Page Header -->
        <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 sm:text-3xl dark:text-gray-100">
                System Settings
            </h1>
            <p class="mt-1 text-sm text-gray-600 sm:mt-2 dark:text-gray-400">
                Configure company information, email settings, and system preferences
            </p>
        </div>

        @if(session('success'))
        <div class="p-4 mb-6 border border-green-200 rounded-lg bg-green-50 dark:bg-green-900/20 dark:border-green-800">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-sm font-medium text-green-800 dark:text-green-300">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Company Settings -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Company Information
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @if($settings->has('company'))
                        @foreach($settings['company'] as $setting)
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ ucwords(str_replace('_', ' ', str_replace('company_', '', $setting->key))) }}
                                </label>
                                @if($setting->key === 'company_timezone')
                                    <select name="settings[{{ $setting->key }}]" class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                        <option value="Asia/Manila" {{ $setting->value === 'Asia/Manila' ? 'selected' : '' }}>Asia/Manila (PHT)</option>
                                        <option value="UTC" {{ $setting->value === 'UTC' ? 'selected' : '' }}>UTC</option>
                                        <option value="America/New_York" {{ $setting->value === 'America/New_York' ? 'selected' : '' }}>America/New_York (EST)</option>
                                        <option value="Europe/London" {{ $setting->value === 'Europe/London' ? 'selected' : '' }}>Europe/London (GMT)</option>
                                    </select>
                                @elseif($setting->key === 'company_currency')
                                    <select name="settings[{{ $setting->key }}]" class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                        <option value="PHP" {{ $setting->value === 'PHP' ? 'selected' : '' }}>PHP (₱)</option>
                                        <option value="USD" {{ $setting->value === 'USD' ? 'selected' : '' }}>USD ($)</option>
                                        <option value="EUR" {{ $setting->value === 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                                        <option value="GBP" {{ $setting->value === 'GBP' ? 'selected' : '' }}>GBP (£)</option>
                                    </select>
                                @elseif($setting->type === 'number')
                                    <input type="number" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}" step="0.01"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @else
                                    <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                                @endif
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Email Settings -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                    Email Configuration
                </h2>

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    @if($settings->has('email'))
                        @foreach($settings['email'] as $setting)
                            <div>
                                <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    {{ ucwords(str_replace('_', ' ', str_replace('mail_', '', $setting->key))) }}
                                </label>
                                <input type="text" name="settings[{{ $setting->key }}]" value="{{ $setting->value }}"
                                       class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">
                            </div>
                        @endforeach
                    @endif
                </div>

                <div class="p-3 mt-4 rounded-md bg-blue-50 dark:bg-blue-900/20">
                    <p class="text-xs text-blue-800 dark:text-blue-300">
                        💡 <strong>Note:</strong> SMTP settings are configured in your <code>.env</code> file. These settings control the sender information.
                    </p>
                </div>
            </div>

            <!-- System Settings -->
            <div class="bg-white dark:bg-[#171717] border border-gray-200 dark:border-gray-800 rounded-lg p-6">
                <h2 class="mb-4 text-lg font-semibold text-gray-900 dark:text-gray-100">
                    System Configuration
                </h2>

                <div class="space-y-4">
                    @if($settings->has('system'))
                        @foreach($settings['system'] as $setting)
                            @if($setting->type === 'boolean' && $setting->key !== 'maintenance_mode')
                                <div class="flex items-center justify-between">
                                    <div>
                                        <label class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                        </label>
                                    </div>
                                    <label class="relative inline-flex items-center cursor-pointer">
                                        <input type="checkbox" name="settings[{{ $setting->key }}]" value="1"
                                               {{ $setting->value ? 'checked' : '' }}
                                               class="sr-only peer">
                                        <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all dark:border-gray-600 peer-checked:bg-primary-600"></div>
                                    </label>
                                </div>
                            @elseif($setting->key !== 'maintenance_mode')
                                <div>
                                    <label class="block mb-2 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        {{ ucwords(str_replace('_', ' ', $setting->key)) }}
                                    </label>
                                    <textarea name="settings[{{ $setting->key }}]" rows="2"
                                              class="w-full px-3 py-2 border border-gray-300 rounded-md dark:border-gray-700 dark:bg-gray-900 dark:text-gray-100">{{ $setting->value }}</textarea>
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col gap-3 sm:flex-row sm:justify-between">
                <div class="flex gap-3">
                    <button type="submit" class="px-4 py-2 text-sm font-medium text-white rounded-md bg-primary-600 hover:bg-primary-700">
                        Save Settings
                    </button>
                    <button type="button" onclick="clearCache()" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 dark:bg-gray-800 dark:text-gray-300 dark:border-gray-700 dark:hover:bg-gray-700">
                        Clear Cache
                    </button>
                </div>

                <form method="POST" action="{{ route('admin.settings.maintenance') }}">
                    @csrf
                    <button type="submit" class="w-full px-4 py-2 text-sm font-medium text-white bg-yellow-600 rounded-md sm:w-auto hover:bg-yellow-700">
                        {{ \App\Models\AdminSetting::get('maintenance_mode', false) ? 'Disable' : 'Enable' }} Maintenance Mode
                    </button>
                </form>
            </div>
        </form>
    </div>

    <script>
        async function clearCache() {
            if (!confirm('Clear all application caches?')) return;

            try {
                const response = await fetch('{{ route("admin.settings.clear-cache") }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });

                if (response.ok) {
                    alert('All caches cleared successfully!');
                    location.reload();
                } else {
                    alert('Failed to clear caches');
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to clear caches');
            }
        }
    </script>
</x-app-layout>
