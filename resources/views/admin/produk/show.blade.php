<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.produk.index') }}" class="p-2 rounded-xl text-stone-500 hover:text-emerald-800 hover:bg-emerald-50 transition" title="Kembali ke Daftar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
                <div>
                    <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                        {{ $produk->nama }}
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Detail menu, varian cup, harga jual, dan performa penjualan</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('admin.produk.edit', $produk) }}" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-bold transition">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    <span>Edit Nama Menu</span>
                </a>
                <form method="POST" action="{{ route('admin.produk.toggle-active', $produk) }}" class="inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="px-3.5 py-2 rounded-xl text-xs font-bold transition {{ $produk->is_active ? 'bg-amber-50 text-amber-800 border border-amber-200 hover:bg-amber-100' : 'bg-emerald-50 text-emerald-800 border border-emerald-200 hover:bg-emerald-100' }}">
                        {{ $produk->is_active ? 'Non-aktifkan Menu' : 'Aktifkan Menu' }}
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="{
        editModalOpen: false,
        editingVarian: { id: null, nama_varian: '', kategori: '', harga_modal: 0, harga_jual: 0 },
        openEditModal(varian) {
            this.editingVarian = { ...varian };
            this.editModalOpen = true;
        }
    }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Ringkasan Menu --}}
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Total Varian</p>
                    <p class="text-2xl font-extrabold text-emerald-950 mt-1">{{ $produk->varians->count() }} <span class="text-sm font-normal text-stone-500">varian cup</span></p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Total Cup Terjual</p>
                    <p class="text-2xl font-extrabold text-amber-900 mt-1">{{ $produk->varians->sum('total_terjual') }} <span class="text-sm font-normal text-stone-500">cup</span></p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5">
                    <p class="text-xs font-semibold uppercase tracking-wider text-stone-400">Status Ketersediaan</p>
                    <div class="mt-1.5">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold {{ $produk->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ $produk->is_active ? '✓ Tersedia untuk Dijual' : '✕ Dinonaktifkan' }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Kelola Varian & Form Tambah Varian Baru --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                {{-- Tabel Daftar Varian (2 Kolom) --}}
                <div class="lg:col-span-2 bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 overflow-hidden relative">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 to-amber-500"></div>

                    <div class="p-5 border-b border-stone-200/60 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-base text-emerald-950">Daftar Varian Produk</h3>
                            <p class="text-xs text-stone-500 mt-0.5">Varian ukuran/pilihan rasa untuk menu ini</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-stone-50/80 text-xs font-bold text-stone-600 uppercase tracking-wider">
                                <tr>
                                    <th class="text-left px-5 py-3">Nama Varian</th>
                                    <th class="text-right px-4 py-3">Harga Modal</th>
                                    <th class="text-right px-4 py-3">Harga Jual</th>
                                    <th class="text-right px-4 py-3">Laba / Cup</th>
                                    <th class="text-center px-4 py-3">Status</th>
                                    <th class="text-right px-5 py-3">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-xs">
                                @forelse ($produk->varians as $varian)
                                    <tr class="hover:bg-emerald-50/30 transition">
                                        <td class="px-5 py-3.5">
                                            <div class="font-bold text-stone-800">{{ $varian->nama_varian }}</div>
                                            <div class="text-[11px] text-stone-400">{{ $varian->kategori ?? 'Umum' }} • Terjual: {{ $varian->total_terjual ?? 0 }}</div>
                                        </td>
                                        <td class="px-4 py-3.5 text-right text-stone-600 font-medium">
                                            Rp {{ number_format($varian->harga_modal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-bold text-emerald-950">
                                            Rp {{ number_format($varian->harga_jual, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-bold text-emerald-700">
                                            +Rp {{ number_format($varian->harga_jual - $varian->harga_modal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center">
                                            <form method="POST" action="{{ route('admin.produk.varian.toggle-active', [$produk, $varian]) }}">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="px-2 py-0.5 rounded-full text-[10px] font-bold cursor-pointer transition {{ $varian->is_active ? 'bg-emerald-100 text-emerald-800' : 'bg-stone-200 text-stone-600' }}">
                                                    {{ $varian->is_active ? 'Aktif' : 'Non-Aktif' }}
                                                </button>
                                            </form>
                                        </td>
                                        <td class="px-5 py-3.5 text-right">
                                            <div class="flex items-center justify-end gap-1">
                                                <button
                                                    type="button"
                                                    @click="openEditModal({{ Js::from($varian) }})"
                                                    class="p-1 rounded text-amber-700 hover:bg-amber-50"
                                                    title="Edit Varian"
                                                >
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                                </button>
                                                <form method="POST" action="{{ route('admin.produk.varian.destroy', [$produk, $varian]) }}" onsubmit="return confirm('Hapus varian ini?');" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1 rounded text-rose-600 hover:bg-rose-50" title="Hapus Varian">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-8 text-center text-stone-400">Belum ada varian untuk produk ini.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- Form Tambah Varian Baru (1 Kolom) --}}
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 to-emerald-700"></div>

                    <h3 class="font-bold text-base text-emerald-950 mb-1">+ Tambah Varian Baru</h3>
                    <p class="text-xs text-stone-500 mb-4">Tambahkan varian ukuran baru untuk {{ $produk->nama }}</p>

                    <form method="POST" action="{{ route('admin.produk.varian.store', $produk) }}" class="space-y-4">
                        @csrf

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Nama Varian <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_varian" placeholder="Contoh: Cup Jumbo / Extra Manis" required class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl text-xs">
                        </div>

                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Kategori</label>
                            <input type="text" name="kategori" value="{{ $produk->nama }}" placeholder="Contoh: Teh Dingin" class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl text-xs">
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">Harga Modal <span class="text-rose-500">*</span></label>
                                <input type="number" min="0" step="500" name="harga_modal" placeholder="0" required class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl text-xs">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-stone-700 mb-1">Harga Jual <span class="text-rose-500">*</span></label>
                                <input type="number" min="0" step="500" name="harga_jual" placeholder="0" required class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl text-xs">
                            </div>
                        </div>

                        <button type="submit" class="w-full py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-xs font-bold hover:from-emerald-600 hover:to-emerald-500 transition shadow-xs">
                            + Simpan Varian Baru
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Modal Edit Varian --}}
        <div
            x-show="editModalOpen"
            x-cloak
            class="fixed inset-0 z-50 overflow-y-auto flex items-center justify-center p-4 bg-stone-900/60 backdrop-blur-xs"
        >
            <div
                @click.away="editModalOpen = false"
                class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6 relative border border-stone-200"
            >
                <h3 class="text-base font-bold text-emerald-950 mb-1">Edit Varian Produk</h3>
                <p class="text-xs text-stone-500 mb-4">Ubah nama varian, kategori, atau harga</p>

                <form
                    method="POST"
                    :action="'{{ url('/admin/produk/' . $produk->id . '/varian') }}/' + editingVarian.id"
                    class="space-y-4"
                >
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Nama Varian <span class="text-rose-500">*</span></label>
                        <input type="text" name="nama_varian" x-model="editingVarian.nama_varian" required class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl text-xs">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-stone-700 mb-1">Kategori</label>
                        <input type="text" name="kategori" x-model="editingVarian.kategori" class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl text-xs">
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Harga Modal <span class="text-rose-500">*</span></label>
                            <input type="number" min="0" step="500" name="harga_modal" x-model="editingVarian.harga_modal" required class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl text-xs">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Harga Jual <span class="text-rose-500">*</span></label>
                            <input type="number" min="0" step="500" name="harga_jual" x-model="editingVarian.harga_jual" required class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl text-xs">
                        </div>
                    </div>

                    <div class="pt-4 border-t border-stone-100 flex items-center justify-end gap-2">
                        <button type="button" @click="editModalOpen = false" class="px-4 py-2 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-50 text-xs font-semibold">
                            Batal
                        </button>
                        <button type="submit" class="px-4 py-2 bg-emerald-700 hover:bg-emerald-600 text-white rounded-xl text-xs font-bold">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
