<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-amber-600 rounded-full"></div>
            <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                {{ __('Kasir') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan Hari Ini --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <p class="text-sm font-medium text-stone-500">Transaksi Hari Ini</p>
                    <p class="text-2xl font-bold text-emerald-900 mt-1">{{ $jumlahTransaksiHariIni }}</p>
                    <p class="text-xs text-stone-400 mt-1">transaksi selesai</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <p class="text-sm font-medium text-stone-500">Pendapatan Hari Ini</p>
                    <p class="text-2xl font-bold text-amber-900 mt-1">Rp {{ number_format($totalHariIni, 0, ',', '.') }}</p>
                    <p class="text-xs text-stone-400 mt-1">total penjualan Anda</p>
                </div>
            </div>

            {{-- Daftar Produk Tersedia --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>
                <div class="p-5 sm:p-6 border-b border-stone-200/60">
                    <h3 class="text-lg font-bold text-emerald-900">🍵 Produk Tersedia</h3>
                    <p class="text-sm text-stone-500 mt-0.5">Pilih produk untuk transaksi baru</p>
                </div>

                <div class="p-5 sm:p-6">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @forelse ($produks as $produk)
                            <div class="border border-stone-200/80 rounded-xl p-4 hover:border-emerald-300 hover:shadow-md transition-all duration-200 bg-white/60">
                                <h4 class="font-bold text-emerald-800 mb-2">{{ $produk->nama }}</h4>
                                @if ($produk->varians->count() > 0)
                                    <div class="space-y-1.5">
                                        @foreach ($produk->varians as $varian)
                                            <div class="flex justify-between items-center text-sm py-1 px-2 rounded-lg bg-stone-50/80">
                                                <span class="text-stone-600">
                                                    {{ $varian->nama_varian }}
                                                    @if($varian->kategori)
                                                        <span class="text-xs text-stone-400">({{ $varian->kategori }})</span>
                                                    @endif
                                                </span>
                                                <span class="font-semibold text-emerald-700">Rp {{ number_format($varian->harga_jual, 0, ',', '.') }}</span>
                                            </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-xs text-stone-400">Belum ada varian</p>
                                @endif
                            </div>
                        @empty
                            <div class="col-span-full text-center py-8 text-stone-400">Belum ada produk tersedia</div>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Riwayat Penjualan Hari Ini --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-emerald-600"></div>
                <div class="p-5 sm:p-6 border-b border-stone-200/60">
                    <h3 class="text-lg font-bold text-emerald-900">📋 Riwayat Hari Ini</h3>
                    <p class="text-sm text-stone-500 mt-0.5">Transaksi yang Anda catat hari ini</p>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">No. Nota</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Waktu</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Metode</th>
                                <th class="text-right px-6 py-3 font-semibold text-stone-600">Total</th>
                                <th class="text-center px-6 py-3 font-semibold text-stone-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($penjualanHariIni as $penjualan)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-emerald-800">{{ $penjualan->no_nota }}</td>
                                    <td class="px-6 py-4 text-stone-500">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ ucfirst($penjualan->metode_bayar) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-stone-800">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $penjualan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($penjualan->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-stone-400">Belum ada transaksi hari ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($penjualanHariIni as $penjualan)
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-1">
                                <span class="font-medium text-emerald-800 text-sm">{{ $penjualan->no_nota }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $penjualan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($penjualan->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-stone-500">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('H:i') }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($penjualan->metode_bayar) }}
                                </span>
                                <span class="font-bold text-stone-800">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-sm">Belum ada transaksi hari ini</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
