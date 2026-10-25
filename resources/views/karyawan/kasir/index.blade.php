<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-amber-600 rounded-full"></div>
                <div>
                    <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                        {{ __('Kasir & Pencatatan Pesanan') }}
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Catat transaksi penjualan langsung dan upload bukti bayar QRIS</p>
                </div>
            </div>
            <div class="hidden sm:flex items-center gap-2">
                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-300">
                    <span class="w-2 h-2 rounded-full bg-emerald-600 animate-pulse"></span>
                    Kasir Aktif: {{ Auth::user()->name }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="posKasir()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-900 text-sm flex items-center justify-between shadow-xs">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-medium">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-center gap-2 shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Ringkasan Hari Ini --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Transaksi Hari Ini</p>
                            <p class="text-2xl font-extrabold text-emerald-950 mt-1">{{ $jumlahTransaksiHariIni }}</p>
                            <p class="text-xs text-stone-500 mt-0.5">transaksi selesai Anda catat</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-emerald-50 border border-emerald-200/60 flex items-center justify-center text-emerald-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Omzet Kasir Hari Ini</p>
                            <p class="text-2xl font-extrabold text-amber-950 mt-1">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</p>
                            <p class="text-xs text-stone-500 mt-0.5">total penerimaan hari ini</p>
                        </div>
                        <div class="w-11 h-11 rounded-xl bg-amber-50 border border-amber-200/60 flex items-center justify-center text-amber-700">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Terminal Kasir POS (Pilih Menu di Kiri, Keranjang & Checkout di Kanan) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- Kolom Kiri: Katalog Produk (7 Kolom) --}}
                <div class="lg:col-span-7 bg-white/95 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden flex flex-col">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                    {{-- Search Header --}}
                    <div class="p-4 sm:p-5 border-b border-stone-200/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-stone-50/60">
                        <div>
                            <h3 class="font-bold text-base text-emerald-950 flex items-center gap-2">
                                <span>🍵 Pilih Menu Teh</span>
                            </h3>
                            <p class="text-xs text-stone-500">Klik varian untuk menambahkan ke keranjang pesanan</p>
                        </div>
                        <div class="w-full sm:w-60">
                            <div class="relative">
                                <input
                                    type="text"
                                    x-model="searchQuery"
                                    placeholder="Cari menu teh..."
                                    class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 shadow-2xs"
                                >
                                <svg class="w-4 h-4 text-stone-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- Product Grid --}}
                    <div class="p-4 sm:p-5 max-h-[560px] overflow-y-auto space-y-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3.5">
                            @forelse ($produks as $produk)
                                <div
                                    x-show="matchesSearch('{{ addslashes(strtolower($produk->nama)) }}')"
                                    class="p-3.5 rounded-2xl border border-stone-200/90 bg-white hover:border-emerald-300 hover:shadow-md transition-all flex flex-col justify-between"
                                >
                                    <div>
                                        <div class="flex items-center gap-2 mb-2">
                                            <div class="w-7 h-7 rounded-lg bg-emerald-50 border border-emerald-200 flex items-center justify-center text-sm">
                                                🍵
                                            </div>
                                            <h4 class="font-bold text-sm text-emerald-950 leading-tight">{{ $produk->nama }}</h4>
                                        </div>

                                        @if ($produk->varians->count() > 0)
                                            <div class="space-y-1.5 mt-2">
                                                @foreach ($produk->varians as $varian)
                                                    <button
                                                        type="button"
                                                        @click="addToCart({{ $varian->id }}, '{{ addslashes($produk->nama) }} ({{ addslashes($varian->nama_varian) }})', {{ $varian->harga_jual }})"
                                                        class="w-full flex items-center justify-between p-2 rounded-xl border border-stone-200/80 bg-stone-50/80 hover:bg-emerald-50 hover:border-emerald-300 text-left transition duration-150 group"
                                                    >
                                                        <div class="min-w-0 pr-2">
                                                            <div class="text-xs font-semibold text-stone-800 group-hover:text-emerald-900 truncate">
                                                                {{ $varian->nama_varian }}
                                                            </div>
                                                            <div class="text-[10px] text-stone-400">{{ $varian->kategori ?? 'Varian' }}</div>
                                                        </div>
                                                        <div class="flex items-center gap-1.5 shrink-0">
                                                            <span class="text-xs font-bold text-emerald-800">
                                                                Rp {{ number_format($varian->harga_jual, 0, ',', '.') }}
                                                            </span>
                                                            <span class="w-5 h-5 rounded-md bg-emerald-600 text-white flex items-center justify-center text-xs font-bold group-hover:scale-110 transition">
                                                                +
                                                            </span>
                                                        </div>
                                                    </button>
                                                @endforeach
                                            </div>
                                        @else
                                            <p class="text-xs text-stone-400 italic py-2">Belum ada varian aktif</p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-12 text-stone-400">
                                    Belum ada produk aktif yang tersedia.
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan: Keranjang & Checkout Form (5 Kolom) --}}
                <div class="lg:col-span-5 bg-white/95 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-emerald-600"></div>

                    <div class="p-4 sm:p-5 border-b border-stone-200/70 bg-stone-50/60 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-base text-emerald-950 flex items-center gap-1.5">
                                <span>🛒 Keranjang Pesanan</span>
                            </h3>
                            <p class="text-xs text-stone-500" x-text="cart.length + ' jenis minuman dipilih'"></p>
                        </div>
                        <button
                            type="button"
                            @click="clearCart()"
                            x-show="cart.length > 0"
                            class="text-xs font-semibold text-rose-600 hover:text-rose-800 hover:underline"
                        >
                            Kosongkan
                        </button>
                    </div>

                    {{-- Form Checkout --}}
                    <form
                        method="POST"
                        action="{{ route('karyawan.kasir.store') }}"
                        enctype="multipart/form-data"
                        class="p-4 sm:p-5 space-y-4"
                        @submit="return validateBeforeSubmit($event)"
                    >
                        @csrf
                        {{-- Hidden JSON Items --}}
                        <input type="hidden" name="items" :value="JSON.stringify(cart)">

                        {{-- Item List --}}
                        <div class="space-y-2 max-h-52 overflow-y-auto pr-1">
                            <template x-if="cart.length === 0">
                                <div class="py-8 text-center text-stone-400 text-xs">
                                    <svg class="w-8 h-8 mx-auto text-stone-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg>
                                    Keranjang masih kosong.<br>Klik varian menu teh di sebelah kiri untuk menambahkan.
                                </div>
                            </template>

                            <template x-for="(item, index) in cart" :key="item.varian_id">
                                <div class="flex items-center justify-between p-2.5 rounded-xl bg-stone-50/80 border border-stone-200/70">
                                    <div class="min-w-0 flex-1 pr-2">
                                        <div class="text-xs font-bold text-emerald-950 truncate" x-text="item.name"></div>
                                        <div class="text-[11px] text-stone-500" x-text="'Rp ' + item.price.toLocaleString('id-ID')"></div>
                                    </div>
                                    <div class="flex items-center gap-2">
                                        <div class="flex items-center border border-stone-200 rounded-lg overflow-hidden bg-white">
                                            <button type="button" @click="decreaseQty(index)" class="px-2 py-0.5 text-xs text-stone-600 hover:bg-stone-100">-</button>
                                            <span class="px-2.5 py-0.5 text-xs font-bold text-stone-800" x-text="item.qty"></span>
                                            <button type="button" @click="increaseQty(index)" class="px-2 py-0.5 text-xs text-stone-600 hover:bg-stone-100">+</button>
                                        </div>
                                        <div class="text-xs font-bold text-emerald-900 w-16 text-right" x-text="'Rp ' + (item.price * item.qty).toLocaleString('id-ID')"></div>
                                        <button type="button" @click="removeFromCart(index)" class="text-stone-400 hover:text-rose-600 p-1">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </template>
                        </div>

                        {{-- Total Pembayaran --}}
                        <div class="pt-3 border-t border-stone-200/80 flex items-center justify-between">
                            <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Total Tagihan:</span>
                            <span class="text-xl font-extrabold text-emerald-950" x-text="'Rp ' + getTotal().toLocaleString('id-ID')"></span>
                        </div>

                        {{-- Metode Pembayaran --}}
                        <div class="space-y-2 pt-2">
                            <label class="block text-xs font-bold text-emerald-950">
                                Metode Pembayaran <span class="text-rose-500">*</span>
                            </label>

                            <div class="grid grid-cols-3 gap-2">
                                {{-- Tunai --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_bayar" value="tunai" x-model="metodeBayar" class="sr-only">
                                    <div
                                        class="p-2.5 text-center rounded-xl border text-xs font-bold transition flex flex-col items-center gap-1"
                                        :class="metodeBayar === 'tunai' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                                        <span>Tunai</span>
                                    </div>
                                </label>

                                {{-- QRIS --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_bayar" value="qris" x-model="metodeBayar" class="sr-only">
                                    <div
                                        class="p-2.5 text-center rounded-xl border text-xs font-bold transition flex flex-col items-center gap-1"
                                        :class="metodeBayar === 'qris' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                                        <span>QRIS</span>
                                    </div>
                                </label>

                                {{-- Transfer --}}
                                <label class="cursor-pointer">
                                    <input type="radio" name="metode_bayar" value="transfer" x-model="metodeBayar" class="sr-only">
                                    <div
                                        class="p-2.5 text-center rounded-xl border text-xs font-bold transition flex flex-col items-center gap-1"
                                        :class="metodeBayar === 'transfer' ? 'bg-emerald-600 text-white border-emerald-600 shadow-xs' : 'bg-stone-50 text-stone-700 border-stone-200 hover:bg-stone-100'"
                                    >
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                        <span>Transfer</span>
                                    </div>
                                </label>
                            </div>
                        </div>

                        {{-- Panel Khusus Tunai: Uang Diterima & Kembalian --}}
                        <div x-show="metodeBayar === 'tunai'" class="p-3 bg-stone-50 rounded-xl border border-stone-200/80 space-y-2">
                            <div class="flex items-center justify-between gap-2">
                                <label class="text-xs font-semibold text-stone-700">Uang Diterima (Rp):</label>
                                <input
                                    type="number"
                                    min="0"
                                    step="500"
                                    x-model.number="uangDiterima"
                                    class="w-36 py-1 px-2.5 text-right font-bold text-xs rounded-lg border-stone-300 focus:border-emerald-600 focus:ring-emerald-600"
                                    placeholder="0"
                                >
                            </div>
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                <button type="button" @click="uangDiterima = getTotal()" class="px-2 py-0.5 rounded text-[10px] bg-stone-200 hover:bg-stone-300 font-semibold">Uang Pas</button>
                                <button type="button" @click="uangDiterima = 10000" class="px-2 py-0.5 rounded text-[10px] bg-stone-200 hover:bg-stone-300 font-semibold">10k</button>
                                <button type="button" @click="uangDiterima = 20000" class="px-2 py-0.5 rounded text-[10px] bg-stone-200 hover:bg-stone-300 font-semibold">20k</button>
                                <button type="button" @click="uangDiterima = 50000" class="px-2 py-0.5 rounded text-[10px] bg-stone-200 hover:bg-stone-300 font-semibold">50k</button>
                                <button type="button" @click="uangDiterima = 100000" class="px-2 py-0.5 rounded text-[10px] bg-stone-200 hover:bg-stone-300 font-semibold">100k</button>
                            </div>
                            <div class="pt-1.5 border-t border-stone-200 flex items-center justify-between text-xs">
                                <span class="font-semibold text-stone-600">Kembalian:</span>
                                <span
                                    class="font-bold"
                                    :class="(uangDiterima - getTotal()) >= 0 ? 'text-emerald-700 font-extrabold' : 'text-rose-600'"
                                    x-text="(uangDiterima - getTotal()) >= 0 ? 'Rp ' + (uangDiterima - getTotal()).toLocaleString('id-ID') : 'Kurang Rp ' + Math.abs(uangDiterima - getTotal()).toLocaleString('id-ID')"
                                ></span>
                            </div>
                        </div>

                        {{-- Panel Khusus QRIS: Upload Foto Bukti Pembayaran QRIS --}}
                        <div x-show="metodeBayar === 'qris'" class="p-3.5 bg-blue-50/70 rounded-xl border border-blue-200 space-y-2.5">
                            <div class="flex items-center gap-2">
                                <div class="w-6 h-6 rounded-md bg-blue-600 text-white flex items-center justify-center font-bold text-xs">
                                    Q
                                </div>
                                <div>
                                    <div class="text-xs font-bold text-blue-950">Pembayaran QRIS UMKM Teh Solo</div>
                                    <div class="text-[10px] text-blue-700">Minta pelanggan scan QRIS & unggah foto bukti transfer/sukses</div>
                                </div>
                            </div>

                            {{-- File Upload --}}
                            <div>
                                <label class="block text-xs font-bold text-blue-950 mb-1">
                                    Upload Foto Bukti Bayar QRIS <span class="text-rose-500">*</span>
                                </label>
                                <input
                                    type="file"
                                    name="bukti_bayar"
                                    id="bukti_bayar_qris"
                                    accept="image/*"
                                    @change="previewImage($event)"
                                    class="w-full text-xs text-stone-600 file:mr-2.5 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer"
                                >
                            </div>

                            {{-- Preview Image --}}
                            <div x-show="imagePreview" class="relative mt-2 p-1.5 bg-white rounded-xl border border-blue-200">
                                <img :src="imagePreview" class="h-28 w-full object-cover rounded-lg" alt="Bukti QRIS">
                                <button
                                    type="button"
                                    @click="clearImage()"
                                    class="absolute top-2 right-2 bg-rose-600 text-white rounded-full p-1 shadow-xs hover:bg-rose-700"
                                    title="Hapus gambar"
                                >
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                </button>
                            </div>
                        </div>

                        {{-- Panel Khusus Transfer: Upload Foto Bukti Transfer --}}
                        <div x-show="metodeBayar === 'transfer'" class="p-3.5 bg-amber-50/70 rounded-xl border border-amber-200 space-y-2.5">
                            <div class="text-xs font-bold text-amber-950">Transfer Bank UMKM Teh Solo</div>
                            <div class="text-[11px] text-stone-600">BCA: 123-456-7890 a.n UMKM Teh Solo</div>
                            <div>
                                <label class="block text-xs font-semibold text-stone-700 mb-1">Upload Bukti Transfer (Opsional)</label>
                                <input
                                    type="file"
                                    name="bukti_bayar"
                                    accept="image/*"
                                    class="w-full text-xs text-stone-600 file:mr-2.5 file:py-1 file:px-2.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-amber-600 file:text-white hover:file:bg-amber-700 cursor-pointer"
                                >
                            </div>
                        </div>

                        {{-- Catatan Pesanan --}}
                        <div>
                            <label class="block text-xs font-bold text-stone-700 mb-1">Catatan Pesanan (Opsional)</label>
                            <input
                                type="text"
                                name="catatan"
                                placeholder="Contoh: Es sedikit, tanpa sedotan, manis sedang"
                                class="w-full text-xs rounded-xl border-stone-300 focus:border-emerald-600 focus:ring-emerald-600"
                            >
                        </div>

                        {{-- Submit Button --}}
                        <button
                            type="submit"
                            :disabled="cart.length === 0"
                            class="w-full py-3 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-sm font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            <span>Selesaikan & Simpan Pesanan</span>
                        </button>
                    </form>
                </div>
            </div>

            {{-- Riwayat Penjualan Hari Ini --}}
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-emerald-600"></div>
                <div class="p-5 border-b border-stone-200/60 flex items-center justify-between">
                    <div>
                        <h3 class="text-base font-bold text-emerald-950">📋 Riwayat Transaksi Hari Ini</h3>
                        <p class="text-xs text-stone-500 mt-0.5">Daftar nota penjualan yang baru saja dicatat oleh Anda hari ini</p>
                    </div>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80 text-xs text-stone-600 uppercase tracking-wider font-bold">
                            <tr>
                                <th class="text-left px-6 py-3.5">No. Nota</th>
                                <th class="text-left px-6 py-3.5">Waktu</th>
                                <th class="text-left px-6 py-3.5">Rincian Item</th>
                                <th class="text-center px-6 py-3.5">Metode</th>
                                <th class="text-center px-6 py-3.5">Bukti Bayar</th>
                                <th class="text-right px-6 py-3.5">Total Tagihan</th>
                                <th class="text-center px-6 py-3.5">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs">
                            @forelse ($penjualanHariIni as $penjualan)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-emerald-950">{{ $penjualan->no_nota }}</td>
                                    <td class="px-6 py-4 text-stone-500">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('H:i') }} WIB</td>
                                    <td class="px-6 py-4 text-stone-700">
                                        <div class="space-y-0.5">
                                            @foreach ($penjualan->detailPenjualans as $detail)
                                                <div class="text-[11px]">
                                                    <span class="font-semibold">{{ $detail->qty }}x</span>
                                                    {{ $detail->varianProduk->produk->nama ?? 'Produk' }} ({{ $detail->varianProduk->nama_varian ?? '-' }})
                                                </div>
                                            @endforeach
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                            {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $penjualan->metode_bayar }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($penjualan->bukti_bayar)
                                            <a href="{{ asset('storage/' . $penjualan->bukti_bayar) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-semibold text-blue-700 hover:underline">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Lihat Foto
                                            </a>
                                        @else
                                            <span class="text-stone-300 text-[11px]">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-extrabold text-stone-900">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                            {{ $penjualan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ ucfirst($penjualan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-8 text-center text-stone-400">Belum ada transaksi yang dicatat hari ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($penjualanHariIni as $penjualan)
                        <div class="p-4 space-y-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="font-bold text-emerald-950 text-sm">{{ $penjualan->no_nota }}</span>
                                    <div class="text-[11px] text-stone-500">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('H:i') }} WIB</div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold
                                    {{ $penjualan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($penjualan->status) }}
                                </span>
                            </div>

                            <div class="text-xs text-stone-600 space-y-0.5">
                                @foreach ($penjualan->detailPenjualans as $detail)
                                    <div>{{ $detail->qty }}x {{ $detail->varianProduk->produk->nama ?? 'Produk' }} ({{ $detail->varianProduk->nama_varian ?? '-' }})</div>
                                @endforeach
                            </div>

                            <div class="flex justify-between items-center pt-1 border-t border-stone-100">
                                <div class="flex items-center gap-1.5">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $penjualan->metode_bayar }}
                                    </span>
                                    @if ($penjualan->bukti_bayar)
                                        <a href="{{ asset('storage/' . $penjualan->bukti_bayar) }}" target="_blank" class="text-[10px] font-bold text-blue-700 underline">
                                            Bukti Foto
                                        </a>
                                    @endif
                                </div>
                                <span class="font-extrabold text-sm text-stone-900">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-xs">Belum ada transaksi hari ini</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        function posKasir() {
            return {
                searchQuery: '',
                cart: [],
                metodeBayar: 'tunai',
                uangDiterima: 0,
                imagePreview: null,

                matchesSearch(text) {
                    if (!this.searchQuery) return true;
                    return text.includes(this.searchQuery.toLowerCase());
                },

                addToCart(varianId, name, price) {
                    const existing = this.cart.find(item => item.varian_id === varianId);
                    if (existing) {
                        existing.qty++;
                    } else {
                        this.cart.push({
                            varian_id: varianId,
                            name: name,
                            price: price,
                            qty: 1
                        });
                    }
                },

                increaseQty(index) {
                    this.cart[index].qty++;
                },

                decreaseQty(index) {
                    if (this.cart[index].qty > 1) {
                        this.cart[index].qty--;
                    } else {
                        this.removeFromCart(index);
                    }
                },

                removeFromCart(index) {
                    this.cart.splice(index, 1);
                },

                clearCart() {
                    this.cart = [];
                },

                getTotal() {
                    return this.cart.reduce((sum, item) => sum + (item.price * item.qty), 0);
                },

                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.imagePreview = URL.createObjectURL(file);
                    }
                },

                clearImage() {
                    this.imagePreview = null;
                    const input = document.getElementById('bukti_bayar_qris');
                    if (input) input.value = '';
                },

                validateBeforeSubmit(event) {
                    if (this.cart.length === 0) {
                        alert('Silakan pilih produk terlebih dahulu!');
                        event.preventDefault();
                        return false;
                    }
                    if (this.metodeBayar === 'tunai' && this.uangDiterima > 0 && this.uangDiterima < this.getTotal()) {
                        if (!confirm('Uang yang diterima kurang dari total tagihan! Tetap lanjutkan transaksi?')) {
                            event.preventDefault();
                            return false;
                        }
                    }
                    return true;
                }
            };
        }
    </script>
</x-app-layout>
