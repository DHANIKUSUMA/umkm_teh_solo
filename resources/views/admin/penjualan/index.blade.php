<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-amber-700 rounded-full"></div>
                <div>
                    <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                        {{ __('Data Transaksi Penjualan') }}
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Pemantauan riwayat nota transaksi, karyawan, dan pembayaran QRIS/Tunai</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.penjualan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-sm font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>Tambah Pesanan Baru</span>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Flash Messages --}}
            @if (session('success'))
                <div class="p-4 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm flex items-center gap-2 shadow-xs">
                    <svg class="w-5 h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="p-4 rounded-xl bg-rose-50 border border-rose-200 text-rose-800 text-sm flex items-center gap-2 shadow-xs">
                    <svg class="w-5 h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            {{-- Ringkasan --}}
            @php
                $labelPeriode = match($periode ?? 'hari_ini') {
                    'hari_ini' => 'Hari Ini',
                    'bulan_ini' => 'Bulan Ini',
                    'semua' => 'Semua Riwayat',
                    default => 'Filter',
                };
            @endphp
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Transaksi ({{ $labelPeriode }})</p>
                    <p class="text-2xl font-extrabold text-amber-950 mt-1">{{ number_format($totalTransaksi) }} <span class="text-xs font-normal text-stone-500">transaksi</span></p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Pendapatan ({{ $labelPeriode }})</p>
                    <p class="text-2xl font-extrabold text-emerald-950 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6 space-y-4">
                {{-- Header Filter & Preset Periode --}}
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 pb-3 border-b border-stone-200/60">
                    <div class="flex items-center gap-2.5">
                        <div class="w-8 h-8 rounded-xl bg-emerald-50 border border-emerald-200/60 flex items-center justify-center text-emerald-700 shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xs font-bold text-stone-900">Filter Transaksi</h3>
                            <p class="text-2xs text-stone-500">
                                @if(($periode ?? 'hari_ini') === 'hari_ini')
                                    Penjualan hari ini ({{ \Carbon\Carbon::today()->translatedFormat('d M Y') }})
                                @elseif(($periode ?? '') === 'bulan_ini')
                                    Penjualan bulan ini ({{ \Carbon\Carbon::now()->translatedFormat('F Y') }})
                                @elseif(($periode ?? '') === 'semua')
                                    Menampilkan semua riwayat transaksi
                                @else
                                    Rentang tanggal khusus
                                @endif
                            </p>
                        </div>
                    </div>

                    {{-- Quick Filter Pills --}}
                    <div class="flex items-center gap-1.5 overflow-x-auto pb-1 sm:pb-0">
                        <a href="{{ route('admin.penjualan.index', array_merge(request()->except(['dari_tanggal', 'sampai_tanggal', 'page']), ['periode' => 'hari_ini'])) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ ($periode ?? 'hari_ini') === 'hari_ini' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                            Hari Ini
                        </a>
                        <a href="{{ route('admin.penjualan.index', array_merge(request()->except(['dari_tanggal', 'sampai_tanggal', 'page']), ['periode' => 'bulan_ini'])) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ ($periode ?? '') === 'bulan_ini' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                            Bulan Ini
                        </a>
                        <a href="{{ route('admin.penjualan.index', array_merge(request()->except(['dari_tanggal', 'sampai_tanggal', 'page']), ['periode' => 'semua'])) }}"
                           class="px-3 py-1.5 rounded-xl text-xs font-bold transition whitespace-nowrap {{ ($periode ?? '') === 'semua' ? 'bg-emerald-700 text-white shadow-xs' : 'bg-stone-100 text-stone-600 hover:bg-stone-200' }}">
                            Semua Waktu
                        </a>
                    </div>
                </div>

                {{-- Form Filter Lengkap --}}
                <form method="GET" action="{{ route('admin.penjualan.index') }}">
                    @if(($periode ?? 'hari_ini') !== 'custom')
                        <input type="hidden" name="periode" value="{{ $periode ?? 'hari_ini' }}">
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-12 gap-3">
                        {{-- Cari Nota --}}
                        <div class="lg:col-span-3">
                            <label class="block text-2xs font-semibold text-stone-600 mb-1">Cari Nota</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-stone-400">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. nota..."
                                    class="w-full pl-8 border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs placeholder:text-stone-400">
                            </div>
                        </div>

                        {{-- Status & Metode Bayar --}}
                        <div class="grid grid-cols-2 gap-2 sm:col-span-1 lg:col-span-3">
                            <div>
                                <label class="block text-2xs font-semibold text-stone-600 mb-1">Status</label>
                                <select name="status" class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs">
                                    <option value="">Semua Status</option>
                                    <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                                    <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-2xs font-semibold text-stone-600 mb-1">Metode Bayar</label>
                                <select name="metode_bayar" class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs">
                                    <option value="">Semua Metode</option>
                                    <option value="tunai" {{ request('metode_bayar') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                                    <option value="qris" {{ request('metode_bayar') === 'qris' ? 'selected' : '' }}>QRIS</option>
                                </select>
                            </div>
                        </div>

                        {{-- Rentang Tanggal --}}
                        <div class="grid grid-cols-2 gap-2 sm:col-span-1 lg:col-span-4">
                            <div>
                                <label class="block text-2xs font-semibold text-stone-600 mb-1">Dari Tanggal</label>
                                <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}"
                                    class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs">
                            </div>
                            <div>
                                <label class="block text-2xs font-semibold text-stone-600 mb-1">Sampai Tanggal</label>
                                <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"
                                    class="w-full border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs">
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="flex items-end gap-2 lg:col-span-2">
                            <button type="submit" class="flex-1 inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-xs font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                                <span>Filter</span>
                            </button>
                            @if(request()->hasAny(['search', 'status', 'metode_bayar', 'dari_tanggal', 'sampai_tanggal']) || (request('periode') !== null && request('periode') !== 'hari_ini'))
                                <a href="{{ route('admin.penjualan.index') }}" title="Reset ke Penjualan Hari Ini" class="inline-flex items-center justify-center px-3 py-2 bg-stone-100 text-stone-600 hover:text-stone-900 rounded-xl text-xs font-medium hover:bg-stone-200 transition">
                                    Reset
                                </a>
                            @endif
                        </div>
                    </div>
                </form>
            </div>

            {{-- Tabel Penjualan --}}
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80 text-xs font-bold text-stone-600 uppercase tracking-wider">
                            <tr>
                                <th class="text-left px-6 py-3.5">No. Nota</th>
                                <th class="text-left px-6 py-3.5">Karyawan</th>
                                <th class="text-left px-6 py-3.5">Waktu Transaksi</th>
                                <th class="text-center px-6 py-3.5">Metode Bayar</th>
                                <th class="text-center px-6 py-3.5">Bukti Bayar</th>
                                <th class="text-right px-6 py-3.5">Total Tagihan</th>
                                <th class="text-center px-6 py-3.5">Status</th>
                                <th class="text-right px-6 py-3.5">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-xs">
                            @forelse ($penjualans as $penjualan)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 font-bold text-emerald-950">
                                        <a href="{{ route('admin.penjualan.show', $penjualan) }}" class="hover:underline">
                                            {{ $penjualan->no_nota }}
                                        </a>
                                    </td>
                                    <td class="px-6 py-4 text-stone-700 font-medium">{{ $penjualan->user?->name ?? 'Karyawan' }}</td>
                                    <td class="px-6 py-4 text-stone-500">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                            {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $penjualan->metode_bayar }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($penjualan->bukti_bayar)
                                            <a href="{{ route('admin.penjualan.proof', $penjualan) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-700 hover:underline">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                                Foto Ada
                                            </a>
                                        @else
                                            <span class="text-stone-300">-</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-right font-extrabold text-stone-900">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold
                                            {{ $penjualan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                            {{ ucfirst($penjualan->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.penjualan.show', $penjualan) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 rounded-lg text-xs font-bold border border-emerald-200 transition">
                                            <span>Detail & Nota</span>
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-12 text-center text-stone-400">
                                        <div class="flex flex-col items-center justify-center gap-1.5">
                                            <span class="text-3xl">🧾</span>
                                            <p class="font-medium text-stone-600">
                                                @if(($periode ?? 'hari_ini') === 'hari_ini')
                                                    Belum ada transaksi penjualan hari ini ({{ \Carbon\Carbon::today()->translatedFormat('d M Y') }})
                                                @else
                                                    Belum ada data penjualan pada filter ini
                                                @endif
                                            </p>
                                            @if(($periode ?? 'hari_ini') === 'hari_ini')
                                                <a href="{{ route('admin.penjualan.index', ['periode' => 'semua']) }}" class="text-emerald-700 hover:underline text-xs font-semibold">
                                                    Lihat Semua Riwayat Transaksi 
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($penjualans as $penjualan)
                        <div class="p-4 hover:bg-emerald-50/30 transition duration-150 space-y-2">
                            <div class="flex justify-between items-start">
                                <div>
                                    <a href="{{ route('admin.penjualan.show', $penjualan) }}" class="font-bold text-emerald-950 text-sm hover:underline">
                                        {{ $penjualan->no_nota }}
                                    </a>
                                    <div class="text-[11px] text-stone-500">{{ $penjualan->user?->name ?? 'Karyawan' }} · {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y, H:i') }}</div>
                                </div>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold
                                    {{ $penjualan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-rose-100 text-rose-800' }}">
                                    {{ ucfirst($penjualan->status) }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center pt-1 border-t border-stone-100">
                                <div class="flex items-center gap-2">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase
                                        {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                        {{ $penjualan->metode_bayar }}
                                    </span>
                                    @if ($penjualan->bukti_bayar)
                                        <a href="{{ route('admin.penjualan.proof', $penjualan) }}" target="_blank" class="text-[10px] font-bold text-blue-700 underline">
                                            Foto QRIS
                                        </a>
                                    @endif
                                </div>
                                <div class="flex items-center gap-2">
                                    <span class="font-extrabold text-sm text-stone-900">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                                    <a href="{{ route('admin.penjualan.show', $penjualan) }}" class="p-1.5 rounded-lg bg-emerald-50 text-emerald-800 font-bold text-xs">
                                        &rarr;
                                    </a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-xs space-y-2">
                            <p class="font-medium text-stone-600">
                                @if(($periode ?? 'hari_ini') === 'hari_ini')
                                    Belum ada transaksi penjualan hari ini ({{ \Carbon\Carbon::today()->translatedFormat('d M Y') }})
                                @else
                                    Belum ada data penjualan pada filter ini
                                @endif
                            </p>
                            @if(($periode ?? 'hari_ini') === 'hari_ini')
                                <a href="{{ route('admin.penjualan.index', ['periode' => 'semua']) }}" class="inline-block text-emerald-700 font-bold hover:underline">
                                    Lihat Semua Riwayat Transaksi 
                                </a>
                            @endif
                        </div>
                    @endforelse
                </div>

                @if ($penjualans->hasPages())
                    <div class="px-6 py-4 border-t border-stone-100">
                        {{ $penjualans->links() }}
                    </div>
                @endif
            </div>

        </div>
    </div>
</x-app-layout>
