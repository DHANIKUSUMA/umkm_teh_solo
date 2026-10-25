<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-emerald-900 tracking-tight">Lupa Password</h2>
        <p class="text-sm text-stone-500 mt-1">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 mt-6">
            <a class="text-sm text-emerald-700 hover:text-emerald-900 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-200" href="{{ route('login') }}">
                Kembali ke Login
            </a>

            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
