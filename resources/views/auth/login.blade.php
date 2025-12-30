<x-guest-layout>


    <div class="flex flex-col sm:justify-center sm:items-center pt-6 sm:pt-0">

        <div class="w-full sm:max-w-md px-6 py-8 bg-white overflow-hidden dark:bg-gray-800 ">

            <div class="flex justify-center mb-6">
                <img src="{{ asset('img/Prisma Logo (2).png') }}"
                     alt="Logo"
                     class="h-20 w-auto object-contain">
            </div>

            <div class="mb-6 text-center">
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Welcome Back</h1>
                <p class="text-sm text-gray-500 mt-2 dark:text-gray-400">Please sign in to your account</p>
            </div>

            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <x-input-label for="email" :value="__('Email')" class="text-gray-700 font-medium ml-1 dark:text-gray-300" />
                    <x-text-input id="email" class="block mt-2 w-full py-3 px-4 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                                    placeholder="you@example.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                </div>

                <div class="mt-5">
                    <x-input-label for="password" :value="__('Password')" class="text-gray-700 font-medium ml-1 dark:text-gray-300" />

                    <x-text-input id="password" class="block mt-2 w-full py-3 px-4 rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    type="password"
                                    name="password"
                                    required autocomplete="current-password"
                                    placeholder="••••••••" />

                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                </div>

                <div class="flex items-center justify-between mt-6">
                    <label for="remember_me" class="inline-flex items-center">
                        <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500 h-5 w-5 dark:bg-gray-700 dark:border-gray-600" name="remember">
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
                    </label>

                    @if (Route::has('password.request'))
                        <a class="text-sm text-indigo-600 hover:text-indigo-500 font-medium dark:text-indigo-400" href="{{ route('password.request') }}">
                            {{ __('Forgot password?') }}
                        </a>
                    @endif
                </div>

                <div class="mt-8">
                    <x-primary-button class="w-full justify-center py-3.5 text-base font-semibold rounded-xl shadow-lg transition duration-150 ease-in-out">
                        {{ __('Log in') }}
                    </x-primary-button>
                </div>

            </form>
        </div>
    </div>
</x-guest-layout>
