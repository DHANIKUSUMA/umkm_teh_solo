<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Penjualan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PenjualanController extends Controller
{
    /**
     * Tampilkan daftar semua transaksi penjualan.
     */
    public function index(Request $request): View
    {
        $query = Penjualan::with(['user', 'detailPenjualans.varianProduk']);

        // Filter berdasarkan pencarian no_nota
        if ($request->filled('search')) {
            $query->where('no_nota', 'like', '%'.$request->search.'%');
        }

        // Filter berdasarkan status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter berdasarkan metode pembayaran
        if ($request->filled('metode_bayar')) {
            $query->where('metode_bayar', $request->metode_bayar);
        }

        // Filter berdasarkan rentang tanggal
        if ($request->filled('dari_tanggal')) {
            $query->whereDate('tanggal', '>=', $request->dari_tanggal);
        }

        if ($request->filled('sampai_tanggal')) {
            $query->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $penjualans = $query->latest('tanggal')->paginate(15)->withQueryString();

        // Statistik ringkasan (tanpa pagination untuk akurasi)
        $statsQuery = Penjualan::query();

        if ($request->filled('search')) {
            $statsQuery->where('no_nota', 'like', '%'.$request->search.'%');
        }
        if ($request->filled('status')) {
            $statsQuery->where('status', $request->status);
        }
        if ($request->filled('metode_bayar')) {
            $statsQuery->where('metode_bayar', $request->metode_bayar);
        }
        if ($request->filled('dari_tanggal')) {
            $statsQuery->whereDate('tanggal', '>=', $request->dari_tanggal);
        }
        if ($request->filled('sampai_tanggal')) {
            $statsQuery->whereDate('tanggal', '<=', $request->sampai_tanggal);
        }

        $totalTransaksi = $statsQuery->count();
        $totalPendapatan = (clone $statsQuery)->where('status', 'selesai')->sum('total');

        return view('admin.penjualan.index', compact(
            'penjualans',
            'totalTransaksi',
            'totalPendapatan',
        ));
    }

    /**
     * Tampilkan detail satu transaksi penjualan.
     */
    public function show(Penjualan $penjualan): View
    {
        $penjualan->load([
            'user',
            'detailPenjualans.varianProduk.produk',
        ]);

        // Hitung total modal transaksi ini
        $totalModal = $penjualan->detailPenjualans->sum(function ($detail) {
            return $detail->harga_modal * $detail->qty;
        });

        $totalLaba = $penjualan->total - $totalModal;

        return view('admin.penjualan.show', compact(
            'penjualan',
            'totalModal',
            'totalLaba',
        ));
    }

    /**
     * Update status transaksi (selesai/batal).
     */
    public function updateStatus(Request $request, Penjualan $penjualan): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:selesai,batal'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $penjualan->update([
            'status' => $validated['status'],
            'catatan' => $validated['catatan'] ?? $penjualan->catatan,
        ]);

        $statusText = $validated['status'] === 'selesai' ? 'diselesaikan' : 'dibatalkan';

        return redirect()->route('admin.penjualan.show', $penjualan)
            ->with('success', "Transaksi berhasil {$statusText}.");
    }
}
