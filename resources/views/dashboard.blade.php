<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
            <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                {{ __('Dashboard') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Welcome Card -->
            <div class="bg-white/90 backdrop-blur-md overflow-hidden shadow-md shadow-stone-200/50 rounded-xl sm:rounded-2xl border border-stone-200/80 relative">
                <!-- Top Accent Line -->
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                <div class="p-5 sm:p-8">
                    <div class="flex items-center gap-3">
                        <div class="flex-shrink-0 w-10 h-10 sm:w-12 sm:h-12 bg-emerald-50 rounded-xl flex items-center justify-center border border-emerald-200/60">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                        <div>
                            <p class="text-base sm:text-lg font-semibold text-emerald-900">
                                {{ __("You're logged in!") }}
                            </p>
                            <p class="text-sm text-stone-500 mt-0.5">Selamat datang di Panel Administrasi UMKM Teh Solo</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
