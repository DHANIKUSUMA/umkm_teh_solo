<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.penjualan.index') }}" class="p-2 rounded-xl text-stone-500 hover:text-emerald-800 hover:bg-emerald-50 transition" title="Kembali ke Penjualan">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
                <div>
                    <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                        {{ __('Tambah Pesanan Baru (Admin)') }}
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Catat transaksi penjualan langsung dari panel administrasi</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.penjualan.index') }}" class="px-3.5 py-2 rounded-xl border border-stone-200 text-stone-600 hover:bg-stone-100 text-xs font-semibold transition">
                    &larr; Riwayat Penjualan
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8" x-data="adminPos()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-900 text-sm flex items-center gap-2 shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span class="font-medium">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Terminal Kasir POS Admin --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">

                {{-- Kolom Kiri: Katalog Produk (7 Kolom) --}}
                <div class="lg:col-span-7 bg-white/95 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden flex flex-col">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                    {{-- Search Header --}}
                    <div class="p-4 sm:p-5 border-b border-stone-200/70 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-stone-50/60">
                        <div>
                            <h3 class="font-bold text-base text-emerald-950 flex items-center gap-2">
                                <span>🍵 Pilih Menu Produk Teh</span>
                            </h3>
                            <p class="text-xs text-stone-500">Pilih varian cup untuk ditambahkan ke pesanan baru</p>
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
                    <div class="p-4 sm:p-5 max-h-[580px] overflow-y-auto space-y-4">
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
                                                        @click="addToCart({{ $varian->id }}, '{{ addslashes($produk->nama) }} ({{ addslashes($varian->nama_varian) }})', {{ $varian->harga_jual }}, {{ $varian->harga_modal }})"
                                                        class="w-full flex items-center justify-between p-2 rounded-xl border border-stone-200/80 bg-stone-50/80 hover:bg-emerald-50 hover:border-emerald-300 text-left transition duration-150 group"
                                                    >
                                                        <div class="min-w-0 pr-2">
                                                            <div class="text-xs font-semibold text-stone-800 group-hover:text-emerald-900 truncate">
                                                                {{ $varian->nama_varian }}
                                                            </div>
                                                            <div class="text-[10px] text-stone-400">Modal: Rp {{ number_format($varian->harga_modal, 0, ',', '.') }}</div>
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

                {{-- Kolom Kanan: Keranjang & Checkout (5 Kolom) --}}
                <div class="lg:col-span-5 bg-white/95 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-emerald-600"></div>

                    <div class="p-4 sm:p-5 border-b border-stone-200/70 bg-stone-50/60 flex items-center justify-between">
                        <div>
                            <h3 class="font-bold text-base text-emerald-950 flex items-center gap-1.5">
                                <span>🛒 Rincian Pesanan Baru</span>
                            </h3>
                            <p class="text-xs text-stone-500" x-text="cart.length + ' jenis menu dipilih'"></p>
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

                    {{-- Form Checkout Admin --}}
                    <form
                        method="POST"
                        action="{{ route('admin.penjualan.store') }}"
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
                                    Keranjang masih kosong.<br>Pilih varian teh di sebelah kiri untuk mencatat transaksi.
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

                        {{-- Total Pembayaran & Estimasi Laba --}}
                        <div class="pt-3 border-t border-stone-200/80 space-y-1">
                            <div class="flex items-center justify-between">
                                <span class="text-xs font-bold uppercase tracking-wider text-stone-500">Total Tagihan:</span>
                                <span class="text-xl font-extrabold text-emerald-950" x-text="'Rp ' + getTotal().toLocaleString('id-ID')"></span>
                            </div>
                            <div class="flex items-center justify-between text-[11px] text-stone-500">
                                <span>Estimasi Margin Laba:</span>
                                <span class="font-bold text-emerald-700" x-text="'Rp ' + getEstimatedProfit().toLocaleString('id-ID')"></span>
                            </div>
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

                        {{-- Panel Khusus Tunai --}}
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
                                    <div class="text-[10px] text-blue-700">Unggah foto screenshot atau struk QRIS pelanggan</div>
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
                                    id="admin_bukti_qris"
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

                        {{-- Panel Khusus Transfer: Upload Bukti Transfer --}}
                        <div x-show="metodeBayar === 'transfer'" class="p-3.5 bg-amber-50/70 rounded-xl border border-amber-200 space-y-2.5">
                            <div class="text-xs font-bold text-amber-950">Transfer Rekening Bank UMKM Teh Solo</div>
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
                                placeholder="Contoh: Pesanan untuk rapat, less ice"
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
                            <span>Simpan Transaksi Penjualan</span>
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
        function adminPos() {
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

                addToCart(varianId, name, price, modal) {
                    const existing = this.cart.find(item => item.varian_id === varianId);
                    if (existing) {
                        existing.qty++;
                    } else {
                        this.cart.push({
                            varian_id: varianId,
                            name: name,
                            price: price,
                            modal: modal,
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

                getEstimatedProfit() {
                    return this.cart.reduce((sum, item) => sum + ((item.price - (item.modal || 0)) * item.qty), 0);
                },

                previewImage(event) {
                    const file = event.target.files[0];
                    if (file) {
                        this.imagePreview = URL.createObjectURL(file);
                    }
                },

                clearImage() {
                    this.imagePreview = null;
                    const input = document.getElementById('admin_bukti_qris');
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
