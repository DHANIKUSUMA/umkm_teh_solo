<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.produk.index') }}" class="p-2 rounded-xl text-stone-500 hover:text-emerald-800 hover:bg-emerald-50 transition" title="Kembali">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
            <div>
                <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                    {{ __('Tambah Menu / Produk Baru') }}
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Daftarkan minuman teh atau produk baru beserta varian harganya</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm space-y-1">
                        <div class="font-semibold flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-rose-600" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                            <span>Terjadi kesalahan saat pengisian formulir:</span>
                        </div>
                        <ul class="list-disc list-inside text-xs text-rose-700 space-y-0.5 ml-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.produk.store') }}" x-data="{
                    namaProduk: '{{ old('nama', '') }}',
                    varians: {{ Js::from(old('varians', [
                        ['nama_varian' => 'Cup Regular', 'kategori' => 'Teh Original', 'harga_modal' => 2000, 'harga_jual' => 4000],
                        ['nama_varian' => 'Cup Jumbo', 'kategori' => 'Teh Original', 'harga_modal' => 3000, 'harga_jual' => 6000]
                    ])) }},
                    addVarian() {
                        this.varians.push({
                            nama_varian: '',
                            kategori: this.namaProduk || 'Teh',
                            harga_modal: 0,
                            harga_jual: 0
                        });
                    },
                    removeVarian(index) {
                        if (this.varians.length > 1) {
                            this.varians.splice(index, 1);
                        }
                    }
                }" class="space-y-6">
                    @csrf

                    {{-- Nama Produk --}}
                    <div>
                        <label for="nama" class="block text-sm font-bold text-emerald-950 mb-1.5">
                            Nama Menu / Produk <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            x-model="namaProduk"
                            placeholder="Contoh: Es Teh Manis Solo, Teh Kampul, Teh Melati"
                            required
                            class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-sm"
                        >
                        <p class="text-xs text-stone-500 mt-1">Nama induk produk (dapat memiliki beberapa varian ukuran/rasa).</p>
                    </div>

                    {{-- Varian Produk Dinamis --}}
                    <div class="pt-4 border-t border-stone-200/80">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h3 class="text-base font-bold text-emerald-950">Varian & Harga Produk</h3>
                                <p class="text-xs text-stone-500">Tentukan harga modal dan harga jual untuk setiap varian.</p>
                            </div>
                            <button
                                type="button"
                                @click="addVarian()"
                                class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 rounded-xl text-xs font-bold border border-emerald-200 transition"
                            >
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <span>+ Tambah Varian</span>
                            </button>
                        </div>

                        <div class="space-y-3">
                            <template x-for="(varian, index) in varians" :key="index">
                                <div class="p-4 rounded-xl border border-stone-200/90 bg-stone-50/70 hover:border-emerald-300 transition-all space-y-3">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-bold uppercase tracking-wider text-emerald-800" x-text="'Varian #' + (index + 1)"></span>
                                        <button
                                            type="button"
                                            @click="removeVarian(index)"
                                            x-show="varians.length > 1"
                                            class="text-xs font-medium text-rose-600 hover:text-rose-800 hover:underline flex items-center gap-1"
                                        >
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            Hapus Baris
                                        </button>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                                        {{-- Nama Varian --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-stone-700 mb-1">
                                                Nama Varian <span class="text-rose-500">*</span>
                                            </label>
                                            <input
                                                type="text"
                                                :name="'varians[' + index + '][nama_varian]'"
                                                x-model="varian.nama_varian"
                                                placeholder="Contoh: Cup Regular / Panas"
                                                required
                                                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs"
                                            >
                                        </div>

                                        {{-- Kategori --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-stone-700 mb-1">
                                                Kategori
                                            </label>
                                            <input
                                                type="text"
                                                :name="'varians[' + index + '][kategori]'"
                                                x-model="varian.kategori"
                                                placeholder="Contoh: Teh Dingin"
                                                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs"
                                            >
                                        </div>

                                        {{-- Harga Modal --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-stone-700 mb-1">
                                                Harga Modal (Rp) <span class="text-rose-500">*</span>
                                            </label>
                                            <input
                                                type="number"
                                                min="0"
                                                step="500"
                                                :name="'varians[' + index + '][harga_modal]'"
                                                x-model.number="varian.harga_modal"
                                                placeholder="0"
                                                required
                                                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs"
                                            >
                                        </div>

                                        {{-- Harga Jual --}}
                                        <div>
                                            <label class="block text-xs font-semibold text-stone-700 mb-1">
                                                Harga Jual (Rp) <span class="text-rose-500">*</span>
                                            </label>
                                            <input
                                                type="number"
                                                min="0"
                                                step="500"
                                                :name="'varians[' + index + '][harga_jual]'"
                                                x-model.number="varian.harga_jual"
                                                placeholder="0"
                                                required
                                                class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs"
                                            >
                                        </div>
                                    </div>

                                    {{-- Profit Indicator --}}
                                    <div class="flex items-center justify-between text-[11px] pt-1 text-stone-500">
                                        <span>Estimasi Margin / Laba Bersih per Cup:</span>
                                        <span
                                            class="font-bold"
                                            :class="(varian.harga_jual - varian.harga_modal) >= 0 ? 'text-emerald-700' : 'text-rose-600'"
                                            x-text="'Rp ' + ((varian.harga_jual || 0) - (varian.harga_modal || 0)).toLocaleString('id-ID')"
                                        ></span>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="pt-6 border-t border-stone-200/80 flex items-center justify-end gap-3">
                        <a href="{{ route('admin.produk.index') }}" class="px-5 py-2.5 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 text-sm font-semibold transition">
                            Batal
                        </a>
                        <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-sm font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Simpan Menu Baru</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
