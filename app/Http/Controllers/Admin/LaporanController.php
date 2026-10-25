<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class LaporanController extends Controller
{
    /**
     * Tampilkan halaman laporan penjualan dengan ringkasan per periode.
     */
    public function index(Request $request): View
    {
        // Default: laporan bulan ini
        $bulan = $request->input('bulan', Carbon::now()->month);
        $tahun = $request->input('tahun', Carbon::now()->year);

        // Total penjualan di bulan terpilih
        $totalPenjualan = Penjualan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'selesai')
            ->count();

        $totalPendapatan = Penjualan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'selesai')
            ->sum('total');

        // Hitung total modal dari detail penjualan di bulan tersebut
        $totalModal = DetailPenjualan::whereHas('penjualan', function ($q) use ($bulan, $tahun) {
            $q->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->where('status', 'selesai');
        })->sum(DB::raw('harga_modal * qty'));

        $totalLaba = $totalPendapatan - $totalModal;

        // Transaksi batal
        $totalBatal = Penjualan::whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'batal')
            ->count();

        // Penjualan per hari di bulan tersebut (untuk grafik)
        $penjualanPerHari = Penjualan::selectRaw('DATE(tanggal) as tanggal, COUNT(*) as jumlah, SUM(total) as pendapatan')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'selesai')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        // Produk terlaris (top 10)
        $produkTerlaris = DetailPenjualan::with('varianProduk.produk')
            ->whereHas('penjualan', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal', $bulan)
                    ->whereYear('tanggal', $tahun)
                    ->where('status', 'selesai');
            })
            ->selectRaw('varian_produk_id, SUM(qty) as total_qty, SUM(subtotal) as total_pendapatan')
            ->groupBy('varian_produk_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get();

        // Data per metode bayar (untuk pie chart)
        $metodeBayarData = Penjualan::selectRaw('metode_bayar, COUNT(*) as jumlah, SUM(total) as total')
            ->whereMonth('tanggal', $bulan)
            ->whereYear('tanggal', $tahun)
            ->where('status', 'selesai')
            ->groupBy('metode_bayar')
            ->get();

        // Rata-rata transaksi per hari
        $rataRataPerHari = $penjualanPerHari->count() > 0
            ? round($totalPendapatan / $penjualanPerHari->count(), 2)
            : 0;

        return view('admin.laporan.index', compact(
            'bulan',
            'tahun',
            'totalPenjualan',
            'totalPendapatan',
            'totalModal',
            'totalLaba',
            'totalBatal',
            'penjualanPerHari',
            'produkTerlaris',
            'metodeBayarData',
            'rataRataPerHari',
        ));
    }
}
