<section>
    <header class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-emerald-50 border border-emerald-200/60 flex items-center justify-center text-emerald-700 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
        </div>
        <div>
            <h2 class="text-base font-bold text-stone-900">
                Informasi Profil
            </h2>
            <p class="text-xs text-stone-500">
                Perbarui nama lengkap dan alamat email akun Anda.
            </p>
        </div>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="space-y-4 max-w-xl">
        @csrf
        @method('patch')

        <div>
            <label for="name" class="block text-xs font-bold text-stone-700 mb-1.5">Nama Lengkap</label>
            <input id="name" name="name" type="text"
                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs sm:text-sm text-stone-900 placeholder:text-stone-400"
                value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap" />
            <x-input-error class="mt-1.5" :messages="$errors->get('name')" />
        </div>

        <div>
            <label for="email" class="block text-xs font-bold text-stone-700 mb-1.5">Alamat Email</label>
            <input id="email" name="email" type="email"
                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs sm:text-sm text-stone-900 placeholder:text-stone-400"
                value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="contoh@tehsolo.test" />
            <x-input-error class="mt-1.5" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-2.5 p-3 rounded-xl bg-amber-50 border border-amber-200/60 text-xs text-amber-900 flex items-center justify-between gap-2">
                    <p>Alamat email Anda belum terverifikasi.</p>
                    <button form="send-verification" class="text-emerald-700 hover:text-emerald-800 font-bold underline">
                        Kirim Ulang Verifikasi
                    </button>
                </div>

                @if (session('status') === 'verification-link-sent')
                    <p class="mt-2 text-xs font-semibold text-emerald-700">
                        Tautan verifikasi baru telah dikirimkan ke alamat email Anda.
                    </p>
                @endif
            @endif
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-xs font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                <span>Simpan Perubahan</span>
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200/60"
                >
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Perubahan berhasil disimpan!</span>
                </p>
            @endif
        </div>
    </form>
</section>
