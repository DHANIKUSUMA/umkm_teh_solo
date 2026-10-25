<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-amber-700 rounded-full"></div>
                <div>
                    <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                        {{ __('Data Transaksi Penjualan') }}
                    </h2>
                    <p class="text-xs text-stone-500 mt-0.5">Pemantauan riwayat nota transaksi, kasir, dan pembayaran QRIS/Tunai</p>
                </div>
            </div>
            <div>
                <a href="{{ route('admin.penjualan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-sm font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                    <span>+ Tambah Pesanan Baru</span>
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
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Transaksi (Filter)</p>
                    <p class="text-2xl font-extrabold text-amber-950 mt-1">{{ number_format($totalTransaksi) }} <span class="text-xs font-normal text-stone-500">transaksi</span></p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <p class="text-xs font-bold uppercase tracking-wider text-stone-400">Total Pendapatan (Selesai)</p>
                    <p class="text-2xl font-extrabold text-emerald-950 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white/90 backdrop-blur-md rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6">
                <form method="GET" action="{{ route('admin.penjualan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. nota..."
                        class="border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs placeholder:text-stone-400">
                    <select name="status" class="border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs">
                        <option value="">Semua Status</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                    <select name="metode_bayar" class="border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs">
                        <option value="">Semua Metode</option>
                        <option value="tunai" {{ request('metode_bayar') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="qris" {{ request('metode_bayar') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="transfer" {{ request('metode_bayar') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                    <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}"
                        class="border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs" placeholder="Dari tanggal">
                    <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"
                        class="border-stone-300 focus:border-emerald-600 focus:ring-emerald-600 rounded-xl shadow-2xs text-xs" placeholder="Sampai tanggal">
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-xs font-bold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'metode_bayar', 'dari_tanggal', 'sampai_tanggal']))
                            <a href="{{ route('admin.penjualan.index') }}" class="inline-flex items-center px-3 py-2 bg-stone-100 text-stone-600 rounded-xl text-xs font-medium hover:bg-stone-200 transition">
                                Reset
                            </a>
                        @endif
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
                                <th class="text-left px-6 py-3.5">Kasir / Petugas</th>
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
                                    <td class="px-6 py-4 text-stone-700 font-medium">{{ $penjualan->user?->name ?? 'Kasir' }}</td>
                                    <td class="px-6 py-4 text-stone-500">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase
                                            {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ $penjualan->metode_bayar }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if ($penjualan->bukti_bayar)
                                            <a href="{{ asset('storage/' . $penjualan->bukti_bayar) }}" target="_blank" class="inline-flex items-center gap-1 text-[11px] font-bold text-blue-700 hover:underline">
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
                                            <p class="font-medium text-stone-600">Belum ada transaksi penjualan</p>
                                            <a href="{{ route('admin.penjualan.create') }}" class="text-emerald-700 hover:underline text-xs font-semibold">
                                                + Tambah Pesanan Baru Sekarang
                                            </a>
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
                                    <div class="text-[11px] text-stone-500">{{ $penjualan->user?->name ?? 'Kasir' }} · {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y, H:i') }}</div>
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
                                        <a href="{{ asset('storage/' . $penjualan->bukti_bayar) }}" target="_blank" class="text-[10px] font-bold text-blue-700 underline">
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
                        <div class="p-6 text-center text-stone-400 text-xs">Belum ada data penjualan</div>
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
