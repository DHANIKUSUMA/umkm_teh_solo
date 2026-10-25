<?php

namespace App\Http\Controllers\Karyawan;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use App\Models\Produk;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class KasirController extends Controller
{
    /**
     * Tampilkan halaman kasir untuk karyawan.
     *
     * Menampilkan daftar produk yang tersedia dan riwayat penjualan karyawan hari ini.
     */
    public function index(Request $request): View
    {
        // Produk aktif beserta varian aktifnya untuk dipilih kasir
        $produks = Produk::where('is_active', true)
            ->with(['varians' => function ($q) {
                $q->where('is_active', true)->orderBy('nama_varian');
            }])
            ->orderBy('nama')
            ->get();

        // Riwayat penjualan hari ini oleh karyawan yang sedang login
        $penjualanHariIni = Penjualan::with('detailPenjualans.varianProduk')
            ->where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->latest('tanggal')
            ->get();

        $totalHariIni = $penjualanHariIni->where('status', 'selesai')->sum('total');
        $jumlahTransaksiHariIni = $penjualanHariIni->where('status', 'selesai')->count();

        return view('karyawan.kasir.index', compact(
            'produks',
            'penjualanHariIni',
            'totalHariIni',
            'jumlahTransaksiHariIni',
        ));
    }
}
