<x-guest-layout>

    {{-- Hiển thị thông báo success nếu có --}}
    @if (session('success'))
        <div
            x-data="{ show: true }"
            x-show="show"
            x-init="setTimeout(() => { show = false; window.location.href = '{{ route('login') }}'; }, 3000)"
            class="w-full max-w-md mx-auto my-4 p-4 rounded-lg bg-green-100 text-green-800 text-sm text-center border border-green-300 shadow-sm"
            role="alert"
        >
            <span class="font-semibold">✅ Thành công:</span> {{ session('success') }}
        </div>
    @endif

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Mật khẩu')" />

            <x-text-input id="password" class="block mt-1 w-full"
                          type="password"
                          name="password"
                          required autocomplete="current-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="flex items-center justify-between mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input
                    id="remember_me"
                    type="checkbox"
                    name="remember"
                    class="rounded dark:bg-gray-900 border-gray-300 dark:border-gray-700 text-indigo-600 shadow-sm focus:ring-indigo-500 dark:focus:ring-indigo-600 dark:focus:ring-offset-gray-800"
                />
                <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">{{ __('Nhớ mật khẩu') }}</span>
            </label>

            <a href="{{ route('register') }}" class="text-sm text-gray-600 dark:text-gray-400 font-normal hover:underline">
                Đăng ký
            </a>
        </div>

        {{--        password reset--}}
        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" href="{{ route('password.request') }}">
                    {{ __('Quên mật khẩu ?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Đăng nhập') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
