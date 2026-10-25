<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.penjualan.index') }}" class="p-2 rounded-xl text-stone-500 hover:text-emerald-800 hover:bg-emerald-50 transition" title="Kembali ke Daftar Penjualan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
                <div>
                    <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                        {{ __('Nota Transaksi: ') . $penjualan->no_nota }}
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Rincian produk, bukti pembayaran, dan kalkulasi keuntungan</p>
                </div>
            </div>

            <div class="flex items-center gap-2">
                <button
                    type="button"
                    onclick="window.print()"
                    class="inline-flex items-center gap-1.5 px-4 py-2 bg-stone-100 hover:bg-stone-200 text-stone-700 rounded-xl text-xs font-bold transition"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                    <span>Cetak Nota</span>
                </button>

                <a href="{{ route('admin.penjualan.create') }}" class="inline-flex items-center gap-1.5 px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-xs font-bold hover:from-emerald-600 hover:to-emerald-500 transition shadow-xs">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Transaksi Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="{ lightboxOpen: false }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            {{-- Ringkasan Transaksi --}}
            <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Penjualan</p>
                    <p class="text-2xl font-extrabold text-emerald-950 mt-1">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</p>
                    <p class="text-xs text-stone-500 mt-0.5">{{ $penjualan->detailPenjualans->sum('qty') }} cup minuman</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Modal Pokok</p>
                    <p class="text-2xl font-extrabold text-stone-700 mt-1">Rp {{ number_format($totalModal, 0, ',', '.') }}</p>
                    <p class="text-xs text-stone-500 mt-0.5">HPP bahan baku</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Laba Bersih</p>
                    <p class="text-2xl font-extrabold text-emerald-700 mt-1">+Rp {{ number_format($totalLaba, 0, ',', '.') }}</p>
                    <p class="text-xs text-stone-500 mt-0.5">{{ $penjualan->total > 0 ? round(($totalLaba / $penjualan->total) * 100, 1) : 0 }}% margin keuntungan</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5">
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Metode & Status</p>
                    <div class="mt-2 flex items-center gap-1.5">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold uppercase
                            {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                            {{ $penjualan->metode_bayar }}
                        </span>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold
                            {{ $penjualan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                            {{ ucfirst($penjualan->status) }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- 2 Kolom: Detail Rincian Item (Kiri) & Bukti Bayar / Kasir Info (Kanan) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- Tabel Item Produk (8 Kolom) --}}
                <div class="lg:col-span-8 bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 overflow-hidden relative">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                    <div class="p-5 border-b border-stone-200/60 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-base text-emerald-950">Rincian Item Produk Pesanan</h3>
                            <p class="text-xs text-stone-500 mt-0.5">Waktu transaksi: {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y, H:i:s') }} WIB</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-stone-50/80 text-xs text-stone-600 uppercase tracking-wider font-bold">
                                <tr>
                                    <th class="text-left px-5 py-3.5">Produk & Varian</th>
                                    <th class="text-right px-4 py-3.5">Harga Satuan</th>
                                    <th class="text-center px-4 py-3.5">Qty</th>
                                    <th class="text-right px-4 py-3.5">Subtotal</th>
                                    <th class="text-right px-5 py-3.5">Margin Laba</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-stone-100 text-xs">
                                @foreach ($penjualan->detailPenjualans as $detail)
                                    <tr class="hover:bg-emerald-50/30 transition">
                                        <td class="px-5 py-3.5">
                                            <div class="font-bold text-stone-900">
                                                {{ $detail->varianProduk->produk->nama ?? 'Menu Produk' }}
                                            </div>
                                            <div class="text-[11px] text-stone-500">
                                                Varian: {{ $detail->varianProduk->nama_varian ?? '-' }}
                                                @if(optional($detail->varianProduk)->kategori)
                                                    <span class="text-stone-400">({{ $detail->varianProduk->kategori }})</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-medium text-stone-700">
                                            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-3.5 text-center font-bold text-stone-900">
                                            {{ $detail->qty }}
                                        </td>
                                        <td class="px-4 py-3.5 text-right font-extrabold text-stone-900">
                                            Rp {{ number_format($detail->subtotal, 0, ',', '.') }}
                                        </td>
                                        <td class="px-5 py-3.5 text-right font-bold text-emerald-700">
                                            +Rp {{ number_format($detail->subtotal - ($detail->harga_modal * $detail->qty), 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-stone-50/90 font-bold border-t border-stone-200/80">
                                <tr>
                                    <td colspan="3" class="px-5 py-3.5 text-right text-stone-600">Total Tagihan:</td>
                                    <td class="px-4 py-3.5 text-right text-base text-emerald-950 font-extrabold">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                    <td class="px-5 py-3.5 text-right text-emerald-700 font-extrabold">+Rp {{ number_format($totalLaba, 0, ',', '.') }}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    @if ($penjualan->catatan)
                        <div class="p-4 bg-amber-50/60 border-t border-amber-200/60 text-xs text-amber-950 flex items-start gap-2">
                            <span class="font-bold">Catatan Pesanan:</span>
                            <span>{{ $penjualan->catatan }}</span>
                        </div>
                    @endif
                </div>

                {{-- Informasi Kasir & Bukti Pembayaran (4 Kolom) --}}
                <div class="lg:col-span-4 space-y-4">

                    {{-- Bukti Pembayaran (QRIS / Transfer) --}}
                    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                        <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                        <div class="flex items-center justify-between mb-3">
                            <h4 class="font-bold text-sm text-stone-900 flex items-center gap-1.5">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span>Bukti Pembayaran</span>
                            </h4>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase {{ $penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : ($penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800') }}">
                                {{ $penjualan->metode_bayar }}
                            </span>
                        </div>

                        @if ($penjualan->bukti_bayar)
                            <div class="space-y-3">
                                <div class="rounded-xl overflow-hidden border border-stone-200 relative group cursor-pointer" @click="lightboxOpen = true">
                                    <img
                                        src="{{ asset('storage/' . $penjualan->bukti_bayar) }}"
                                        alt="Bukti Bayar {{ $penjualan->no_nota }}"
                                        class="w-full h-48 object-cover group-hover:scale-105 transition duration-300"
                                    >
                                    <div class="absolute inset-0 bg-stone-900/40 opacity-0 group-hover:opacity-100 transition flex items-center justify-center text-white text-xs font-semibold gap-1.5">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                                        Klik untuk Perbesar Foto
                                    </div>
                                </div>

                                <div class="flex items-center justify-between text-xs">
                                    <a
                                        href="{{ asset('storage/' . $penjualan->bukti_bayar) }}"
                                        target="_blank"
                                        class="text-blue-700 hover:underline font-semibold flex items-center gap-1"
                                    >
                                        <span>Buka di tab baru &rarr;</span>
                                    </a>
                                    <button type="button" @click="lightboxOpen = true" class="text-stone-500 hover:text-stone-700">
                                        Perbesar
                                    </button>
                                </div>
                            </div>
                        @else
                            <div class="p-6 text-center rounded-xl bg-stone-50 border border-stone-200/70 text-stone-400 text-xs">
                                @if ($penjualan->metode_bayar === 'tunai')
                                    <span>Pembayaran Tunai (Cash) - Tidak memerlukan bukti transfer online.</span>
                                @else
                                    <span>Tidak ada file foto bukti bayar yang diunggah untuk transaksi ini.</span>
                                @endif
                            </div>
                        @endif
                    </div>

                    {{-- Informasi Petugas Kasir & Tanggal --}}
                    <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 space-y-3">
                        <h4 class="font-bold text-xs uppercase tracking-wider text-stone-400">Informasi Pelayanan</h4>

                        <div class="flex items-center gap-3 p-2.5 rounded-xl bg-stone-50 border border-stone-200/70">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-600 to-emerald-800 text-white font-bold flex items-center justify-center text-xs shrink-0">
                                {{ strtoupper(substr($penjualan->user->name ?? 'A', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <div class="text-xs font-bold text-emerald-950 truncate">{{ $penjualan->user->name ?? 'Kasir' }}</div>
                                <div class="text-[10px] text-stone-500 capitalize">{{ $penjualan->user->role ?? 'Petugas' }} • {{ $penjualan->user->email ?? '-' }}</div>
                            </div>
                        </div>

                        <div class="text-xs text-stone-500 space-y-1 pt-1">
                            <div class="flex justify-between">
                                <span>No. Nota:</span>
                                <span class="font-bold text-stone-800">{{ $penjualan->no_nota }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Tanggal:</span>
                                <span>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d F Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span>Waktu:</span>
                                <span>{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('H:i:s') }} WIB</span>
                            </div>
                        </div>

                        {{-- Ubah Status Transaksi --}}
                        <div class="pt-3 border-t border-stone-200/80">
                            <form method="POST" action="{{ route('admin.penjualan.update-status', $penjualan) }}">
                                @csrf
                                @method('PATCH')
                                <label class="block text-xs font-semibold text-stone-700 mb-1.5">Ubah Status Transaksi:</label>
                                <div class="flex items-center gap-2">
                                    <select name="status" class="w-full text-xs rounded-xl border-stone-300 focus:border-emerald-600 focus:ring-emerald-600">
                                        <option value="selesai" {{ $penjualan->status === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                        <option value="batal" {{ $penjualan->status === 'batal' ? 'selected' : '' }}>Batal</option>
                                    </select>
                                    <button type="submit" class="px-3 py-2 bg-stone-800 hover:bg-stone-900 text-white rounded-xl text-xs font-bold shrink-0 transition">
                                        Update
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </div>

        {{-- Lightbox Modal untuk Bukti Bayar --}}
        @if ($penjualan->bukti_bayar)
            <div
                x-show="lightboxOpen"
                x-cloak
                class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-stone-900/80 backdrop-blur-sm"
                @click="lightboxOpen = false"
                @keydown.escape.window="lightboxOpen = false"
            >
                <div class="relative max-w-3xl max-h-[90vh] bg-white rounded-2xl overflow-hidden shadow-2xl p-2" @click.stop>
                    <button
                        type="button"
                        @click="lightboxOpen = false"
                        class="absolute top-4 right-4 z-10 bg-stone-900/70 text-white rounded-full p-2 hover:bg-stone-900 transition"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <img
                        src="{{ asset('storage/' . $penjualan->bukti_bayar) }}"
                        alt="Bukti Bayar Full"
                        class="max-h-[85vh] w-auto mx-auto object-contain rounded-xl"
                    >
                    <div class="p-3 text-center text-xs font-bold text-stone-700">
                        Foto Bukti Pembayaran QRIS / Transfer - No. Nota: {{ $penjualan->no_nota }}
                    </div>
                </div>
            </div>
        @endif
    </div>
</x-app-layout>
