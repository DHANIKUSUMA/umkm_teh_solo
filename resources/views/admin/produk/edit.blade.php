<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <a href="{{ route('admin.produk.index') }}" class="p-2 rounded-xl text-stone-500 hover:text-emerald-800 hover:bg-emerald-50 transition" title="Kembali">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            </a>
            <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-amber-700 rounded-full"></div>
            <div>
                <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                    {{ __('Edit Menu / Produk: ') . $produk->nama }}
                </h2>
                <p class="text-xs text-stone-500 mt-0.5">Perbarui informasi induk menu produk teh</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white/95 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-6 sm:p-8 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-amber-500 to-emerald-600"></div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.produk.update', $produk) }}" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label for="nama" class="block text-sm font-bold text-emerald-950 mb-1.5">
                            Nama Menu / Produk <span class="text-rose-500">*</span>
                        </label>
                        <input
                            type="text"
                            name="nama"
                            id="nama"
                            value="{{ old('nama', $produk->nama) }}"
                            required
                            class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-xs text-sm"
                        >
                    </div>

                    <div class="p-4 rounded-xl bg-stone-50 border border-stone-200/80 flex items-center justify-between">
                        <div>
                            <div class="text-sm font-bold text-stone-800">Status Produk</div>
                            <div class="text-xs text-stone-500">Produk saat ini berstatus: {{ $produk->is_active ? 'Aktif' : 'Non-Aktif' }}</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-xs font-bold {{ $produk->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-200 text-stone-600' }}">
                            {{ $produk->is_active ? 'Aktif' : 'Non-Aktif' }}
                        </span>
                    </div>

                    <div class="pt-4 border-t border-stone-200/80 flex items-center justify-between">
                        <a href="{{ route('admin.produk.show', $produk) }}" class="text-xs font-semibold text-emerald-700 hover:underline flex items-center gap-1">
                            <span>Kelola varian & harga produk &rarr;</span>
                        </a>

                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.produk.index') }}" class="px-4 py-2 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 text-sm font-semibold transition">
                                Batal
                            </a>
                            <button type="submit" class="px-5 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-sm font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
