<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-emerald-900 tracking-tight">Konfirmasi Password</h2>
        <p class="text-sm text-stone-500 mt-1">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </p>
    </div>

    <form method="POST" action="{{ route('password.confirm') }}">
        @csrf

        <!-- Password -->
        <div>
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="current-password"
                            placeholder="••••••••" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <div class="flex justify-end mt-6">
            <x-primary-button class="w-full sm:w-auto justify-center">
                {{ __('Confirm') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
