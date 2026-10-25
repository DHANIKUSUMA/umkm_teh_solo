<x-guest-layout>
    <div class="mb-6">
        <h2 class="text-xl sm:text-2xl font-bold text-emerald-900 tracking-tight">Verifikasi Email</h2>
        <p class="text-sm text-stone-500 mt-1">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </p>
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-4 py-3">
            {{ __('A new verification link has been sent to the email address you provided during registration.') }}
        </div>
    @endif

    <div class="mt-4 flex flex-col sm:flex-row items-center justify-between gap-3">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button class="w-full sm:w-auto justify-center">
                    {{ __('Resend Verification Email') }}
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="text-sm text-emerald-700 hover:text-emerald-900 font-medium rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 transition duration-200">
                {{ __('Log Out') }}
            </button>
        </form>
    </div>
</x-guest-layout>
