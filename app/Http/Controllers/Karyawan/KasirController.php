<?php

namespace App\Http\Controllers\Karyawan;

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
        $penjualanHariIni = Penjualan::with('detailPenjualans.varianProduk.produk')
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

    /**
     * Simpan transaksi pesanan baru dari kasir karyawan.
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

            return redirect()->route('karyawan.kasir.index')
                ->with('success', "Pesanan baru berhasil dicatat! No. Nota: {$penjualan->no_nota} (Total: Rp ".number_format($penjualan->total, 0, ',', '.').')');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menyimpan pesanan: '.$e->getMessage());
        }
    }
}
