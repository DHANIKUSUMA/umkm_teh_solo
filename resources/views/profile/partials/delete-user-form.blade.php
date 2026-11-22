<section class="space-y-4">
    <header class="flex items-center gap-3 mb-4">
        <div class="w-10 h-10 rounded-xl bg-rose-50 border border-rose-200/60 flex items-center justify-center text-rose-700 shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        </div>
        <div>
            <h2 class="text-base font-bold text-stone-900">
                Hapus Akun
            </h2>
            <p class="text-xs text-stone-500">
                Tindakan permanen. Seluruh data akun akan dihapus dan tidak dapat dikembalikan.
            </p>
        </div>
    </header>

    <div class="max-w-xl">
        <button
            type="button"
            x-data=""
            x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-rose-50 hover:bg-rose-100 text-rose-700 hover:text-rose-800 rounded-xl text-xs font-bold border border-rose-200 transition shadow-xs"
        >
            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            <span>Hapus Akun Saya</span>
        </button>
    </div>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-rose-100 text-rose-700 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
                <div>
                    <h2 class="text-base font-bold text-stone-900">
                        Konfirmasi Hapus Akun
                    </h2>
                    <p class="text-xs text-stone-500">
                        Apakah Anda yakin ingin menghapus akun ini secara permanen?
                    </p>
                </div>
            </div>

            <p class="text-xs text-stone-600 mb-4 bg-stone-50 p-3 rounded-xl border border-stone-200/70">
                Setelah akun Anda dihapus, semua data dan informasi yang tersimpan akan terhapus secara permanen. Masukkan kata sandi Anda untuk mengonfirmasi.
            </p>

            <div class="mb-4">
                <label for="password" class="block text-xs font-bold text-stone-700 mb-1.5">Kata Sandi Anda</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full border-stone-300 focus:border-rose-600 focus:ring-rose-600 rounded-xl shadow-2xs text-xs sm:text-sm text-stone-900"
                    placeholder="Masukkan kata sandi untuk konfirmasi"
                />
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-1.5" />
            </div>

            <div class="flex justify-end gap-2 pt-2 border-t border-stone-100">
                <button type="button" x-on:click="$dispatch('close')" class="px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-semibold transition">
                    Batal
                </button>

                <button type="submit" class="px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-xl text-xs font-bold transition shadow-md shadow-rose-600/20">
                    Ya, Hapus Akun
                </button>
            </div>
        </form>
    </x-modal>
</section>
