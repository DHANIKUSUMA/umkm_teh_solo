<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-emerald-700 rounded-full"></div>
            <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                {{ __('Laporan Penjualan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">

            {{-- Filter Periode --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-50 rounded-xl flex items-center justify-center border border-emerald-200/60 text-emerald-600 shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-sm text-stone-900">Periode Laporan</h3>
                            <p class="text-xs text-stone-500">Pilih bulan & tahun penjualan</p>
                        </div>
                    </div>

                    <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-2 sm:flex sm:items-end gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Bulan</label>
                            <select name="bulan" class="w-full sm:w-40 border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs">
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}" {{ (int) $bulan === $i ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::create()->month($i)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-stone-700 mb-1">Tahun</label>
                            <select name="tahun" class="w-full sm:w-28 border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs">
                                @for ($y = now()->year; $y >= now()->year - 3; $y--)
                                    <option value="{{ $y }}" {{ (int) $tahun === $y ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-span-2 sm:col-span-1">
                            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-xs font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                <span>Tampilkan</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Statistik Cards --}}
            <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <p class="text-sm font-medium text-stone-500">Total Penjualan</p>
                    <p class="text-2xl sm:text-3xl font-bold text-emerald-900 mt-1">{{ number_format($totalPenjualan) }}</p>
                    <p class="text-xs text-stone-400 mt-1">transaksi selesai</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <p class="text-sm font-medium text-stone-500">Total Pendapatan</p>
                    <p class="text-2xl sm:text-3xl font-bold text-amber-900 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                    <p class="text-xs text-stone-400 mt-1">omzet kotor</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 to-amber-500"></div>
                    <p class="text-sm font-medium text-stone-500">Estimasi Laba</p>
                    <p class="text-2xl sm:text-3xl font-bold text-emerald-800 mt-1">Rp {{ number_format($totalLaba, 0, ',', '.') }}</p>
                    <p class="text-xs text-stone-400 mt-1">pendapatan - modal</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-red-400 to-red-500"></div>
                    <p class="text-sm font-medium text-stone-500">Transaksi Batal</p>
                    <p class="text-2xl sm:text-3xl font-bold text-red-700 mt-1">{{ number_format($totalBatal) }}</p>
                    <p class="text-xs text-stone-400 mt-1">dibatalkan</p>
                </div>
            </div>

            {{-- Produk Terlaris --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>
                <div class="p-5 sm:p-6 border-b border-stone-200/60">
                    <h3 class="text-lg font-bold text-emerald-900">🏆 Produk Terlaris</h3>
                    <p class="text-sm text-stone-500 mt-0.5">Top 5 varian paling banyak terjual</p>
                </div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">#</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Produk</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Varian</th>
                                <th class="text-right px-6 py-3 font-semibold text-stone-600">Qty Terjual</th>
                                <th class="text-right px-6 py-3 font-semibold text-stone-600">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($produkTerlaris as $item)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 text-stone-400">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4 font-medium text-emerald-800">{{ $item->varianProduk?->produk?->nama ?? '-' }}</td>
                                    <td class="px-6 py-4 text-stone-600">{{ $item->varianProduk?->nama_varian ?? '-' }}</td>
                                    <td class="px-6 py-4 text-right font-semibold text-stone-800">{{ number_format($item->total_qty) }}</td>
                                    <td class="px-6 py-4 text-right font-semibold text-emerald-700">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-8 text-center text-stone-400">Belum ada data penjualan di periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($produkTerlaris as $item)
                        <div class="p-4">
                            <div class="flex justify-between items-start mb-1">
                                <div>
                                    <span class="font-medium text-emerald-800">{{ $item->varianProduk?->produk?->nama ?? '-' }}</span>
                                    <span class="text-stone-400 text-xs ml-1">· {{ $item->varianProduk?->nama_varian ?? '-' }}</span>
                                </div>
                                <span class="text-xs font-bold text-stone-700">{{ number_format($item->total_qty) }} pcs</span>
                            </div>
                            <p class="text-sm font-semibold text-emerald-700">Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</p>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-sm">Belum ada data penjualan di periode ini</div>
                    @endforelse
                </div>
            </div>

            {{-- Penjualan Per Hari --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-emerald-600"></div>
                <div class="p-5 sm:p-6 border-b border-stone-200/60">
                    <h3 class="text-lg font-bold text-emerald-900">📊 Akumulasi Penjualan Harian</h3>
                    <p class="text-sm text-stone-500 mt-0.5">Rincian akumulasi penjualan per hari</p>
                </div>

                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Tanggal</th>
                                <th class="text-right px-6 py-3 font-semibold text-stone-600">Jumlah Transaksi</th>
                                <th class="text-right px-6 py-3 font-semibold text-stone-600">Total Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($penjualanPerHari as $hari)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-stone-700">{{ \Carbon\Carbon::parse($hari->tanggal)->format('d M Y') }}</td>
                                    <td class="px-6 py-4 text-right">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                            {{ number_format($hari->jumlah) }} transaksi
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-bold text-emerald-700">Rp {{ number_format($hari->pendapatan, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-stone-400">Belum ada penjualan di periode ini</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($penjualanPerHari as $hari)
                        <div class="p-4 flex justify-between items-center">
                            <div>
                                <p class="text-sm font-semibold text-stone-800">{{ \Carbon\Carbon::parse($hari->tanggal)->format('d M Y') }}</p>
                                <span class="inline-flex items-center px-2 py-0.5 mt-1 rounded-full text-[11px] font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200/60">
                                    {{ number_format($hari->jumlah) }} transaksi
                                </span>
                            </div>
                            <span class="font-bold text-emerald-700">Rp {{ number_format($hari->pendapatan, 0, ',', '.') }}</span>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-sm">Belum ada penjualan di periode ini</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
