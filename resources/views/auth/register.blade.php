<x-guest-layout>
    <!-- Section Title -->
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-emerald-900 tracking-tight">Buat Akun Baru</h2>
        <p class="text-sm text-stone-500 mt-1">Isi formulir di bawah untuk mendaftar</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Nama lengkap" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Username -->
        <div class="mt-4">
            <x-input-label for="username" :value="__('Username')" />
            <x-text-input id="username" class="block mt-1 w-full" type="text" name="username" :value="old('username')" required autocomplete="username" placeholder="username_anda" />
            <x-input-error :messages="$errors->get('username')" class="mt-2" />
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="email" placeholder="nama@email.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-3 mt-6">
            <a class="text-sm text-emerald-700 hover:text-emerald-900 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-200" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
