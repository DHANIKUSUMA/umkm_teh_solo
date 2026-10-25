<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-2 h-8 bg-gradient-to-b from-amber-500 to-emerald-700 rounded-full"></div>
            <div>
                <h2 class="font-bold text-xl text-emerald-950 leading-tight">Forecast Penjualan</h2>
                <p class="text-sm text-stone-500 mt-1">Perkiraan pendapatan dan transaksi untuk membantu merencanakan stok.</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 sm:py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 sm:p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-5">
                    <div>
                        <p class="text-sm font-semibold uppercase tracking-wide text-emerald-700">Prediksi bulan berikutnya</p>
                        <h3 class="text-2xl sm:text-3xl font-bold text-emerald-950 mt-1">{{ $bulanBerikutnya }}</h3>
                        <p class="text-sm text-stone-500 mt-2">Metode Simple Moving Average dari 12 bulan penuh terakhir.</p>
                    </div>

                    <form method="GET" action="{{ route('admin.forecast.index') }}" class="flex flex-col sm:flex-row sm:items-end gap-3">
                        <div>
                            <label for="periode" class="block text-sm font-semibold text-stone-700 mb-1">Periode rata-rata</label>
                            <select id="periode" name="periode" class="w-full sm:w-auto border-stone-300 focus:border-emerald-500 focus:ring-emerald-500 rounded-lg shadow-sm text-sm">
                                @for ($periode = 2; $periode <= 12; $periode++)
                                    <option value="{{ $periode }}" {{ $periodeBulan === $periode ? 'selected' : '' }}>
                                        {{ $periode }} bulan
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <button type="submit" class="inline-flex justify-center items-center px-4 py-2 bg-gradient-to-r from-emerald-700 to-emerald-600 text-white rounded-lg text-sm font-semibold hover:from-emerald-600 hover:to-emerald-500 transition-all shadow-md">
                            Hitung forecast
                        </button>
                    </form>
                </div>
            </section>

            <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-6" aria-label="Ringkasan forecast">
                <article class="bg-white/90 rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-500 to-emerald-600"></div>
                    <p class="text-sm font-medium text-stone-500">Prediksi Pendapatan</p>
                    <p class="text-2xl sm:text-3xl font-bold text-emerald-900 mt-2">Rp {{ number_format($forecastPendapatan['forecast'], 0, ',', '.') }}</p>
                    <p class="text-xs text-stone-400 mt-2">Estimasi omzet bulan depan</p>
                </article>

                <article class="bg-white/90 rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-amber-600"></div>
                    <p class="text-sm font-medium text-stone-500">Prediksi Transaksi</p>
                    <p class="text-2xl sm:text-3xl font-bold text-amber-900 mt-2">{{ number_format($forecastTransaksi['forecast'], 1, ',', '.') }}</p>
                    <p class="text-xs text-stone-400 mt-2">Rata-rata transaksi selesai per bulan</p>
                </article>

                <article class="bg-white/90 rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 p-5 relative overflow-hidden">
                    <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r {{ $tren['arah'] === 'turun' ? 'from-red-400 to-red-500' : 'from-emerald-500 to-emerald-600' }}"></div>
                    <p class="text-sm font-medium text-stone-500">Tren Pendapatan</p>
                    <div class="flex items-center gap-2 mt-2">
                        <span class="text-2xl sm:text-3xl font-bold {{ $tren['arah'] === 'turun' ? 'text-red-700' : 'text-emerald-900' }}">
                            @if ($tren['arah'] === 'naik')
                                <span aria-hidden="true">↑</span>
                            @elseif ($tren['arah'] === 'turun')
                                <span aria-hidden="true">↓</span>
                            @else
                                <span aria-hidden="true">→</span>
                            @endif
                            {{ number_format($tren['persentase'], 1, ',', '.') }}%
                        </span>
                        <span class="text-sm text-stone-500">{{ ucfirst($tren['arah']) }} dari bulan sebelumnya</span>
                    </div>
                    <p class="text-xs text-stone-400 mt-2">Dibandingkan dengan bulan penuh terakhir</p>
                </article>
            </section>

            <section class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-emerald-600 via-amber-500 to-emerald-700"></div>
                <div class="p-5 sm:p-6 border-b border-stone-200/60 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-bold text-emerald-900">Riwayat dan prediksi pendapatan</h3>
                        <p class="text-sm text-stone-500 mt-1">Pendapatan per bulan; bulan berjalan tidak disertakan karena belum lengkap.</p>
                    </div>
                    <div class="flex items-center gap-4 text-xs font-medium text-stone-500">
                        <span class="inline-flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-emerald-600"></span> Aktual</span>
                        <span class="inline-flex items-center gap-2"><span class="w-3 h-3 rounded-sm bg-amber-500"></span> Prediksi</span>
                    </div>
                </div>

                @php
                    $maksimumGrafik = max($dataHistoris->max('total_pendapatan') ?? 0, $forecastPendapatan['forecast'], 1);
                @endphp

                <div class="p-5 sm:p-6">
                    @if ($dataHistoris->sum('jumlah_transaksi') === 0)
                        <div class="mb-5 rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-900">
                            Belum ada transaksi selesai dalam 12 bulan penuh terakhir. Prediksi akan mulai terbentuk setelah penjualan dicatat.
                        </div>
                    @endif

                    <div class="overflow-x-auto">
                        <div class="min-w-[760px]">
                            <div class="h-56 flex items-end gap-3 border-b border-stone-200 px-2">
                                @foreach ($dataHistoris as $item)
                                    @php
                                        $tinggiBatang = $item->total_pendapatan > 0
                                            ? max(5, ($item->total_pendapatan / $maksimumGrafik) * 82)
                                            : 1;
                                    @endphp
                                    <div class="flex-1 h-full flex flex-col justify-end items-center gap-2 group" title="{{ $item->label }}: Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}">
                                        <span class="text-[10px] text-stone-400 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                            Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}
                                        </span>
                                        <div class="w-full max-w-10 rounded-t-md bg-gradient-to-t from-emerald-700 to-emerald-500 transition-all group-hover:from-emerald-600 group-hover:to-emerald-400" style="height: {{ $tinggiBatang }}%"></div>
                                        <span class="absolute sr-only">{{ $item->label }}: Rp {{ number_format($item->total_pendapatan, 0, ',', '.') }}</span>
                                    </div>
                                @endforeach
                                @php
                                    $tinggiForecast = $forecastPendapatan['forecast'] > 0
                                        ? max(5, ($forecastPendapatan['forecast'] / $maksimumGrafik) * 82)
                                        : 1;
                                @endphp
                                <div class="flex-1 h-full flex flex-col justify-end items-center gap-2 group" title="Prediksi {{ $bulanBerikutnya }}: Rp {{ number_format($forecastPendapatan['forecast'], 0, ',', '.') }}">
                                    <span class="text-[10px] text-amber-700 opacity-0 group-hover:opacity-100 transition-opacity whitespace-nowrap">
                                        Rp {{ number_format($forecastPendapatan['forecast'], 0, ',', '.') }}
                                    </span>
                                    <div class="w-full max-w-10 rounded-t-md bg-gradient-to-t from-amber-600 to-amber-400" style="height: {{ $tinggiForecast }}%"></div>
                                </div>
                            </div>
                            <div class="flex gap-3 px-2 pt-3">
                                @foreach ($dataHistoris as $item)
                                    <span class="flex-1 text-center text-[10px] sm:text-xs text-stone-500 whitespace-nowrap">{{ $item->label }}</span>
                                @endforeach
                                <span class="flex-1 text-center text-[10px] sm:text-xs font-semibold text-amber-700 whitespace-nowrap">{{ $bulanBerikutnya }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="bg-white/90 backdrop-blur-md rounded-xl sm:rounded-2xl shadow-md border border-stone-200/80 relative overflow-hidden">
                <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-amber-500 to-emerald-600"></div>
                <div class="p-5 sm:p-6 border-b border-stone-200/60">
                    <h3 class="text-lg font-bold text-emerald-900">Aktivitas penjualan terbaru</h3>
                    <p class="text-sm text-stone-500 mt-1">Transaksi selesai dalam 30 hari terakhir</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead class="bg-stone-50/80">
                            <tr>
                                <th class="text-left px-5 sm:px-6 py-3 font-semibold text-stone-600">Tanggal</th>
                                <th class="text-right px-5 sm:px-6 py-3 font-semibold text-stone-600">Transaksi</th>
                                <th class="text-right px-5 sm:px-6 py-3 font-semibold text-stone-600">Pendapatan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100">
                            @forelse ($penjualanHarian as $hari)
                                <tr class="hover:bg-emerald-50/30 transition duration-150">
                                    <td class="px-5 sm:px-6 py-4 text-stone-600">{{ \Carbon\Carbon::parse($hari->tanggal)->translatedFormat('d M Y') }}</td>
                                    <td class="px-5 sm:px-6 py-4 text-right text-stone-700">{{ number_format($hari->jumlah) }}</td>
                                    <td class="px-5 sm:px-6 py-4 text-right font-semibold text-emerald-700">Rp {{ number_format($hari->pendapatan, 0, ',', '.') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="px-6 py-8 text-center text-stone-400">Belum ada penjualan selesai dalam 30 hari terakhir.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="px-5 sm:px-6 py-4 border-t border-stone-100">
                    <p class="text-xs text-stone-400">Forecast merupakan estimasi berdasarkan pola historis, bukan jaminan hasil penjualan.</p>
                </div>
            </section>
        </div>
    </div>
</x-app-layout>
