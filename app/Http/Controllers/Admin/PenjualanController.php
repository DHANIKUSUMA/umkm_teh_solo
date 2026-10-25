<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\VarianProduk;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
     * Tampilkan form kasir / buat pesanan baru untuk Admin.
     */
    public function create(): View
    {
        $produks = Produk::where('is_active', true)
            ->with(['varians' => function ($q) {
                $q->where('is_active', true)->orderBy('nama_varian');
            }])
            ->orderBy('nama')
            ->get();

        return view('admin.penjualan.create', compact('produks'));
    }

    /**
     * Simpan transaksi pesanan baru oleh Admin.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'items' => ['required', 'string'],
            'metode_bayar' => ['required', 'in:tunai,qris,transfer'],
            'bukti_bayar' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:4096'],
            'catatan' => ['nullable', 'string', 'max:255'],
        ]);

        $cartItems = json_decode($validated['items'], true);

        if (! is_array($cartItems) || empty($cartItems)) {
            return back()->with('error', 'Keranjang pesanan masih kosong. Silakan pilih minimal 1 item produk.');
        }

        // Upload bukti pembayaran jika diunggah (khususnya QRIS / Transfer)
        $buktiPath = null;
        if ($request->hasFile('bukti_bayar')) {
            $buktiPath = $request->file('bukti_bayar')->store('bukti_bayar', 'public');
        }

        try {
            $penjualan = DB::transaction(function () use ($cartItems, $validated, $buktiPath) {
                $today = Carbon::today()->format('Ymd');
                $countToday = Penjualan::whereDate('tanggal', Carbon::today())->count() + 1;
                $noNota = 'NOTA-'.$today.'-'.sprintf('%04d', $countToday);
                while (Penjualan::where('no_nota', $noNota)->exists()) {
                    $countToday++;
                    $noNota = 'NOTA-'.$today.'-'.sprintf('%04d', $countToday);
                }

                $total = 0;
                $detailsToInsert = [];

                foreach ($cartItems as $item) {
                    $varianId = $item['varian_id'] ?? null;
                    $qty = (int) ($item['qty'] ?? 1);

                    if (! $varianId || $qty <= 0) {
                        continue;
                    }

                    $varian = VarianProduk::findOrFail($varianId);
                    $subtotal = $varian->harga_jual * $qty;
                    $total += $subtotal;

                    $detailsToInsert[] = [
                        'varian_produk_id' => $varian->id,
                        'qty' => $qty,
                        'harga_satuan' => $varian->harga_jual,
                        'harga_modal' => $varian->harga_modal,
                        'subtotal' => $subtotal,
                    ];
                }

                if (empty($detailsToInsert)) {
                    throw new \Exception('Item pesanan tidak valid.');
                }

                $penjualan = Penjualan::create([
                    'no_nota' => $noNota,
                    'user_id' => Auth::id(),
                    'tanggal' => now(),
                    'total' => $total,
                    'metode_bayar' => $validated['metode_bayar'],
                    'bukti_bayar' => $buktiPath,
                    'status' => 'selesai',
                    'catatan' => $validated['catatan'] ?? null,
                ]);

                foreach ($detailsToInsert as $detail) {
                    $detail['penjualan_id'] = $penjualan->id;
                    DetailPenjualan::create($detail);
                }

                return $penjualan;
            });

            return redirect()->route('admin.penjualan.show', $penjualan)
                ->with('success', "Pesanan baru berhasil dicatat dengan No. Nota: {$penjualan->no_nota}!");
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan pesanan: '.$e->getMessage());
        }
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
