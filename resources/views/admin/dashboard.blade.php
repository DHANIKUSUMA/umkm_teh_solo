<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-2 h-8 bg-gradient-to-b from-emerald-500 to-emerald-700 rounded-full"></div>
            <h2 class="font-bold text-xl text-emerald-950 leading-tight">
                {{ __('Dashboard Admin') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 space-y-6">

            {{-- Statistik Cards --}}
            <div class="grid grid-cols-4 gap-2 sm:gap-4 lg:gap-6">
                {{-- Total Produk --}}
                <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-xs sm:shadow-md border border-stone-200/80 p-2.5 sm:p-5 lg:p-6 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <div class="flex items-start sm:items-center justify-between gap-1 sm:gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] sm:text-sm font-medium text-stone-500 truncate">Total Produk</p>
                            <p class="text-base sm:text-2xl lg:text-3xl font-bold text-emerald-900 mt-0.5 sm:mt-1 truncate">{{ $totalProducts }}</p>
                            <p class="text-[10px] sm:text-xs text-stone-400 mt-0.5 sm:mt-1 truncate">{{ $totalVariants }} varian</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 bg-emerald-50 rounded-xl items-center justify-center border border-emerald-200/60 shrink-0     ">
                            <svg class="w-3.5 h-3.5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total Transaksi --}}
                <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-xs sm:shadow-md border border-stone-200/80 p-2.5 sm:p-5 lg:p-6 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <div class="flex items-start sm:items-center justify-between gap-1 sm:gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] sm:text-sm font-medium text-stone-500 truncate">Total Transaksi</p>
                            <p class="text-base sm:text-2xl lg:text-3xl font-bold text-amber-900 mt-0.5 sm:mt-1 truncate">{{ $todayOrders }}</p>
                            <p class="text-[10px] sm:text-xs text-stone-400 mt-0.5 sm:mt-1 truncate">hari ini</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 bg-emerald-50 rounded-xl items-center justify-center border border-emerald-200/60 shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-6 sm:h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Pendapatan Hari Ini --}}
                <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-xs sm:shadow-md border border-stone-200/80 p-2.5 sm:p-5 lg:p-6 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 to-amber-500"></div>
                    <div class="flex items-start sm:items-center justify-between gap-1 sm:gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] sm:text-sm font-medium text-stone-500 truncate" title="Pendapatan Hari Ini">Pendapatan<span class="hidden sm:inline"> Hari Ini</span></p>
                            <p class="text-xs sm:text-2xl lg:text-3xl font-bold text-emerald-900 mt-0.5 sm:mt-1 truncate">Rp {{ number_format($todayRevenue, 0, ',', '.') }}</p>
                            <p class="text-[10px] sm:text-xs text-stone-400 mt-0.5 sm:mt-1 truncate" title="Bulan ini: Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}"><span class="hidden sm:inline">Bulan ini: </span><span class="sm:hidden">Bln: </span>Rp {{ number_format($monthlyRevenue, 0, ',', '.') }}</p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 bg-emerald-50 rounded-xl items-center justify-center border border-emerald-200/60 shrink-0     ">
                            <svg class="w-3.5 h-3.5 sm:w-6 sm:h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                {{-- Total User --}}
                <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-xs sm:shadow-md border border-stone-200/80 p-2.5 sm:p-5 lg:p-6 relative overflow-hidden group hover:shadow-lg transition-shadow duration-300">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-stone-400 to-stone-500"></div>
                    <div class="flex items-start sm:items-center justify-between gap-1 sm:gap-2">
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] sm:text-sm font-medium text-stone-500 truncate">Total <span class="hidden sm:inline">Pengguna</span><span class="sm:hidden">User</span></p>
                            <p class="text-base sm:text-2xl lg:text-3xl font-bold text-stone-800 mt-0.5 sm:mt-1 truncate">{{ $totalUsers }}</p>
                            <p class="text-[10px] sm:text-xs text-stone-400 mt-0.5 sm:mt-1 truncate">Admin & <span class="hidden sm:inline">Karyawan</span><span class="sm:hidden">Staf</span></p>
                        </div>
                        <div class="hidden sm:flex w-12 h-12 bg-emerald-50 rounded-xl items-center justify-center border border-emerald-200/60 shrink-0">
                            <svg class="w-3.5 h-3.5 sm:w-6 sm:h-6 text-stone-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <a href="{{ route('admin.penjualan.create') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-xl text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md shadow-emerald-700/20">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    <span>Tambah Transaksi Baru</span>
                </a>

            {{-- Penjualan Terakhir --}}
            <div class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>
                <div class="p-5 sm:p-6 border-b border-stone-200/60">
                    <h3 class="text-lg font-bold text-emerald-900">Penjualan Terakhir</h3>
                    <p class="text-sm text-stone-500 mt-0.5">5 transaksi terbaru</p>
                </div>
                

                {{-- Desktop Table --}}
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">No. Nota</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Karyawan</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Tanggal</th>
                                <th class="text-left px-6 py-3 font-semibold text-stone-600">Metode</th>
                                <th class="text-right px-6 py-3 font-semibold text-stone-600">Total</th>
                                <th class="text-center px-6 py-3 font-semibold text-stone-600">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($recentOrders as $order)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-6 py-4 font-medium text-emerald-800">{{ $order->no_nota }}</td>
                                    <td class="px-6 py-4 text-stone-600">{{ $order->user?->name ?? '-' }}</td>
                                    <td class="px-6 py-4 text-stone-500">{{ \Carbon\Carbon::parse($order->tanggal)->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $order->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($order->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                            {{ ucfirst($order->metode_bayar) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right font-semibold text-stone-800">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $order->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($order->status) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-8 text-center text-stone-400">Belum ada transaksi penjualan</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Mobile Cards --}}
                <div class="sm:hidden divide-y divide-stone-100">
                    @forelse ($recentOrders as $order)
                        <div class="p-4 hover:bg-emerald-50/30 transition duration-150">
                            <div class="flex justify-between items-start mb-2">
                                <span class="font-medium text-emerald-800 text-sm">{{ $order->no_nota }}</span>
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $order->status === 'selesai' ? 'bg-emerald-100 text-emerald-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <p class="text-xs text-stone-500">{{ $order->user?->name ?? '-' }} · {{ \Carbon\Carbon::parse($order->tanggal)->format('d M Y, H:i') }}</p>
                            <div class="flex justify-between items-center mt-2">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium
                                    {{ $order->metode_bayar === 'tunai' ? 'bg-emerald-100 text-emerald-800' : ($order->metode_bayar === 'qris' ? 'bg-blue-100 text-blue-800' : 'bg-amber-100 text-amber-800') }}">
                                    {{ ucfirst($order->metode_bayar) }}
                                </span>
                                <span class="font-bold text-stone-800">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                            </div>
                        </div>
                    @empty
                        <div class="p-6 text-center text-stone-400 text-sm">Belum ada transaksi penjualan</div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
