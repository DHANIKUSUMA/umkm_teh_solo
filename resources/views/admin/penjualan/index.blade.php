<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-amber-700 rounded-full"></div>
            <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                {{ __('Data Penjualan') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            {{-- Ringkasan --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <p class="text-sm font-medium text-stone-500">Total Transaksi (Filter)</p>
                    <p class="text-2xl font-bold text-amber-900 mt-1">{{ number_format($totalTransaksi) }}</p>
                </div>
                <div class="bg-white/90 backdrop-blur-md rounded-xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <p class="text-sm font-medium text-stone-500">Total Pendapatan (Selesai)</p>
                    <p class="text-2xl font-bold text-emerald-900 mt-1">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</p>
                </div>
            </div>

            {{-- Filter --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6">
                <form method="GET" action="{{ route('admin.penjualan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-6 gap-3">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari no. nota..."
                        class="border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm placeholder:text-stone-400">
                    <select name="status" class="border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                        <option value="">Semua Status</option>
                        <option value="selesai" {{ request('status') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        <option value="batal" {{ request('status') === 'batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                    <select name="metode_bayar" class="border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                        <option value="">Semua Metode</option>
                        <option value="tunai" {{ request('metode_bayar') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                        <option value="qris" {{ request('metode_bayar') === 'qris' ? 'selected' : '' }}>QRIS</option>
                        <option value="transfer" {{ request('metode_bayar') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                    </select>
                    <input type="date" name="dari_tanggal" value="{{ request('dari_tanggal') }}"
                        class="border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm" placeholder="Dari tanggal">
                    <input type="date" name="sampai_tanggal" value="{{ request('sampai_tanggal') }}"
                        class="border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm" placeholder="Sampai tanggal">
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 inline-flex items-center justify-center px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-lg text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md">
                            Filter
                        </button>
                        @if(request()->hasAny(['search', 'status', 'metode_bayar', 'dari_tanggal', 'sampai_tanggal']))
                            <a href="{{ route('admin.penjualan.index') }}" class="inline-flex items-center px-3 py-2 bg-stone-100 text-stone-600 rounded-lg text-sm font-medium hover:bg-stone-200 transition">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            {{-- Tabel Penjualan --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">No. Nota</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Kasir</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Tanggal</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Metode</th>
                                <th class="text-right px-6 py-3 font-semibold text-stone-600">Total</th>
                                <th class="text-center px-6 py-3 font-semibold text-stone-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($penjualans as $penjualan)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-emerald-800">{{ $penjualan->no_nota }}</td>
                                    <td class="px-6 py-4 text-stone-600">{{ $penjualan->user?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-stone-500">{{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y, H:i') }}</td>
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
                                    <td colspan="6" class="px-6 py-8 text-center text-stone-400">Belum ada data penjualan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($penjualans as $penjualan)
                        <div class="p-4 hover:bg-emerald-50/30 transition duration-150">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-emerald-800 text-sm">{{ $penjualan->no_nota }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $penjualan->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($penjualan->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-stone-500">{{ $penjualan->user?->name ?? '-' }} · {{ \Carbon\Carbon::parse($penjualan->tanggal)->format('d M Y, H:i') }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $penjualan->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($penjualan->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($penjualan->metode_bayar) }}
                                </span>
                                <span class="font-bold text-stone-800">Rp {{ number_format($penjualan->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-sm">Belum ada data penjualan</div>
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
