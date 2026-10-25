<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\View\View;

class ForecastController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'periode' => ['sometimes', 'integer', 'between:2,12'],
        ]);
        $periodeBulan = (int) ($validated['periode'] ?? 3);

        $bulanBerjalan = Carbon::now()->startOfMonth();
        $awalPeriode = $bulanBerjalan->copy()->subMonths(12);
        $dataHarianHistoris = Penjualan::query()
            ->selectRaw('DATE(tanggal) as tanggal, COUNT(*) as jumlah_transaksi, SUM(total) as total_pendapatan')
            ->where('status', 'selesai')
            ->where('tanggal', '>=', $awalPeriode)
            ->where('tanggal', '<', $bulanBerjalan)
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('tanggal')
            ->get();

        $ringkasanPerBulan = $dataHarianHistoris->groupBy(
            fn (object $item): string => Carbon::parse($item->tanggal)->format('Y-m')
        );

        $dataHistoris = collect(range(0, 11))
            ->map(function (int $offset) use ($awalPeriode, $ringkasanPerBulan): object {
                $bulan = $awalPeriode->copy()->addMonths($offset);
                $ringkasan = $ringkasanPerBulan->get($bulan->format('Y-m'), collect());

                return (object) [
                    'label' => $bulan->translatedFormat('M Y'),
                    'periode' => $bulan->format('Y-m'),
                    'jumlah_transaksi' => (int) $ringkasan->sum('jumlah_transaksi'),
                    'total_pendapatan' => (float) $ringkasan->sum('total_pendapatan'),
                ];
            });

        $forecastPendapatan = $this->calculateSma(
            $dataHistoris->pluck('total_pendapatan')->toArray(),
            $periodeBulan
        );
        $forecastTransaksi = $this->calculateSma(
            $dataHistoris->pluck('jumlah_transaksi')->toArray(),
            $periodeBulan
        );

        $bulanBerikutnya = Carbon::now()->startOfMonth()->addMonth()->translatedFormat('F Y');
        $tren = $this->calculateTrend($dataHistoris);

        $penjualanHarian = Penjualan::query()
            ->selectRaw('DATE(tanggal) as tanggal, COUNT(*) as jumlah, SUM(total) as pendapatan')
            ->where('status', 'selesai')
            ->whereBetween('tanggal', [Carbon::now()->subDays(29)->startOfDay(), Carbon::now()])
            ->groupByRaw('DATE(tanggal)')
            ->orderBy('tanggal')
            ->get();

        return view('admin.forecast.index', compact(
            'dataHistoris',
            'forecastPendapatan',
            'forecastTransaksi',
            'bulanBerikutnya',
            'periodeBulan',
            'tren',
            'penjualanHarian',
        ));
    }

    /**
     * @param  array<int, float|int>  $data
     * @return array{forecast: float, sma_values: array<int, float>}
     */
    private function calculateSma(array $data, int $periode): array
    {
        $smaValues = [];
        $count = count($data);

        if ($count < $periode) {
            $forecast = $count > 0 ? array_sum($data) / $count : 0;

            return [
                'forecast' => round($forecast, 2),
                'sma_values' => [],
            ];
        }

        for ($i = $periode - 1; $i < $count; $i++) {
            $sum = 0;
            for ($j = 0; $j < $periode; $j++) {
                $sum += $data[$i - $j];
            }
            $smaValues[] = round($sum / $periode, 2);
        }

        return [
            'forecast' => $smaValues[array_key_last($smaValues)],
            'sma_values' => $smaValues,
        ];
    }

    /**
     * @return array{persentase: float, arah: string}
     */
    private function calculateTrend(Collection $dataHistoris): array
    {
        $count = $dataHistoris->count();

        if ($count < 2) {
            return ['persentase' => 0, 'arah' => 'stabil'];
        }

        $bulanIni = $dataHistoris[$count - 1]->total_pendapatan;
        $bulanLalu = $dataHistoris[$count - 2]->total_pendapatan;

        if ($bulanLalu == 0) {
            return [
                'persentase' => $bulanIni > 0 ? 100 : 0,
                'arah' => $bulanIni > 0 ? 'naik' : 'stabil',
            ];
        }

        $persentase = round((($bulanIni - $bulanLalu) / $bulanLalu) * 100, 1);

        if ($persentase > 0) {
            $arah = 'naik';
        } elseif ($persentase < 0) {
            $arah = 'turun';
        } else {
            $arah = 'stabil';
        }

        return ['persentase' => abs($persentase), 'arah' => $arah];
    }
}
