<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <!-- Section Title -->
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-emerald-900 tracking-tight">Masuk ke Akun</h2>
        <p class="text-sm text-stone-500 mt-1">Silakan masukkan kredensial Anda untuk melanjutkan</p>
    </div>

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-stone-300 text-emerald-600 shadow-sm focus:ring-emerald-500 transition duration-200" name="remember">
                <span class="ms-2 text-sm text-stone-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 mt-6">
            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-700 hover:text-emerald-900 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-200" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Log in') }}
            </x-primary-button>
        </div>

        <!-- Register Link -->
        <div class="mt-6 pt-5 border-t border-stone-200 text-center">
            <p class="text-sm text-stone-500">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-emerald-700 hover:text-emerald-900 transition duration-200">
                    Daftar Sekarang
                </a>
            </p>
        </div>
    </form>
</x-guest-layout>
