<section>
    <header class="flex items-center gap-3 mb-6">
        <div class="w-10 h-10 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-700 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
        </div>
        <div>
            <h2 class="text-base font-bold text-stone-900">
                Perbarui Kata Sandi
            </h2>
            <p class="text-xs text-stone-500">
                Pastikan akun Anda menggunakan kata sandi yang aman dan tidak mudah ditebak.
            </p>
        </div>
    </header>

    <form method="post" action="{{ route('password.update') }}" class="space-y-4 max-w-xl">
        @csrf
        @method('put')

        <div>
            <label for="update_password_current_password" class="block text-xs font-bold text-stone-700 mb-1.5">Kata Sandi Saat Ini</label>
            <input id="update_password_current_password" name="current_password" type="password"
                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs sm:text-sm text-stone-900"
                autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password" class="block text-xs font-bold text-stone-700 mb-1.5">Kata Sandi Baru</label>
            <input id="update_password_password" name="password" type="password"
                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs sm:text-sm text-stone-900"
                autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-1.5" />
        </div>

        <div>
            <label for="update_password_password_confirmation" class="block text-xs font-bold text-stone-700 mb-1.5">Konfirmasi Kata Sandi Baru</label>
            <input id="update_password_password_confirmation" name="password_confirmation" type="password"
                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs sm:text-sm text-stone-900"
                autocomplete="new-password" placeholder="Ulangi kata sandi baru" />
            <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-1.5" />
        </div>

        <div class="flex items-center gap-3 pt-2">
            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-xs font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                <span>Simpan Kata Sandi</span>
            </button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="inline-flex items-center gap-1.5 text-xs font-bold text-emerald-700 bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200/60"
                >
                    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>Kata sandi berhasil diperbarui!</span>
                </p>
            @endif
        </div>
    </form>
</section>
