<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard admin dengan ringkasan statistik utama.
     */
    public function index(): View
    {
        $totalProducts = Produk::count();
        $totalVariants = Produk::withCount('varians')->get()->sum('varians_count');
        $totalOrders = Penjualan::count();
        $totalUsers = User::count();
        $totalRevenue = Penjualan::where('status', 'selesai')->sum('total');

        // Penjualan hari ini
        $todayOrders = Penjualan::whereDate('tanggal', Carbon::today())->count();
        $todayRevenue = Penjualan::whereDate('tanggal', Carbon::today())
            ->where('status', 'selesai')
            ->sum('total');

        // Penjualan bulan ini
        $monthlyRevenue = Penjualan::whereMonth('tanggal', Carbon::now()->month)
            ->whereYear('tanggal', Carbon::now()->year)
            ->where('status', 'selesai')
            ->sum('total');

        // 5 penjualan terakhir
        $recentOrders = Penjualan::with('user')
            ->latest('tanggal')
            ->take(5)
            ->get();

        // Produk aktif beserta varian untuk form tambah pesanan langsung
        $produks = Produk::where('is_active', true)
            ->with(['varians' => function ($q) {
                $q->where('is_active', true)->orderBy('nama_varian');
            }])
            ->orderBy('nama')
            ->get();

        return view('admin.dashboard', compact(
            'totalProducts',
            'totalVariants',
            'totalOrders',
            'totalUsers',
            'totalRevenue',
            'todayOrders',
            'todayRevenue',
            'monthlyRevenue',
            'recentOrders',
            'produks',
        ));
    }
}
