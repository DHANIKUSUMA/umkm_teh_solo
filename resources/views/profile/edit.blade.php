<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-emerald-700 rounded-full"></div>
            <div>
                <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                    {{ __('Profil Pengguna') }}
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Kelola identitas akun, informasi login, dan keamanan sandi</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">

            {{-- User Banner / Ringkasan Akun --}}
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-emerald-700 to-emerald-500 flex items-center justify-center text-white text-xl font-extrabold shadow-md shadow-emerald-700/20 shrink-0">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <div class="flex items-center gap-2 flex-wrap">
                                <h3 class="font-bold text-base sm:text-lg text-emerald-950">{{ $user->name }}</h3>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-extrabold uppercase tracking-wide
                                    {{ $user->hasRole('admin') || $user->role === 'admin' ? 'bg-amber-100 text-amber-800 border border-amber-300/60' : 'bg-emerald-100 text-emerald-800 border border-emerald-300/60' }}">
                                    {{ $user->hasRole('admin') || $user->role === 'admin' ? 'Admin / Owner' : 'Karyawan Kasir' }}
                                </span>
                            </div>
                            <p class="text-xs text-stone-500 mt-0.5">{{ $user->email }}</p>
                            @if($user->username)
                                <p class="text-[11px] text-stone-400 mt-0.5">Username: <span class="font-medium text-stone-600">@ {{ $user->username }}</span></p>
                            @endif
                        </div>
                    </div>
                    <div class="text-left sm:text-right pt-3 sm:pt-0 border-t sm:border-t-0 border-stone-100">
                        <span class="text-[11px] text-stone-400 block">Bergabung sejak</span>
                        <span class="text-xs font-semibold text-stone-700">{{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</span>
                    </div>
                </div>
            </div>

            {{-- Form Informasi Profil --}}
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-7 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                @include('profile.partials.update-profile-information-form')
            </div>

            {{-- Form Ubah Password --}}
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-7 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                @include('profile.partials.update-password-form')
            </div>

            {{-- Form Hapus Akun --}}
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-7 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-rose-500 to-rose-600"></div>
                @include('profile.partials.delete-user-form')
            </div>

        </div>
    </div>
</x-app-layout>
