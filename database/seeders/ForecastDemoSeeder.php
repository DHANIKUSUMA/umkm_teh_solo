<?php

namespace Database\Seeders;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\User;
use App\Models\VarianProduk;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ForecastDemoSeeder extends Seeder
{
    public function run(): void
    {
        $kasir = User::query()
            ->where('role', 'karyawan')
            ->orderBy('id')
            ->first();
        $varian = VarianProduk::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->first();

        if ($kasir === null || $varian === null) {
            throw new RuntimeException('ForecastDemoSeeder membutuhkan minimal satu user karyawan dan satu varian produk aktif. Jalankan UserSeeder dan ProdukSeeder terlebih dahulu.');
        }

        $bulanPertama = Carbon::now()->startOfMonth()->subMonths(3);
        $metodeBayar = ['tunai', 'qris', 'transfer', 'tunai'];

        for ($bulanIndex = 0; $bulanIndex < 3; $bulanIndex++) {
            $bulan = $bulanPertama->copy()->addMonths($bulanIndex);
            $jumlahPerProduk = ($bulanIndex + 1) * 3;

            for ($transaksiIndex = 0; $transaksiIndex < 4; $transaksiIndex++) {
                $nomorNota = sprintf('FC-%s-%02d', $bulan->format('Ym'), $transaksiIndex + 1);
                $tanggal = $bulan->copy()->day(5 + ($transaksiIndex * 7))->setTime(10, 0);
                $total = (float) $varian->harga_jual * $jumlahPerProduk;

                DB::transaction(function () use (
                    $kasir,
                    $varian,
                    $nomorNota,
                    $tanggal,
                    $total,
                    $jumlahPerProduk,
                    $metodeBayar,
                    $transaksiIndex,
                ): void {
                    $penjualan = Penjualan::query()->firstOrCreate(
                        ['no_nota' => $nomorNota],
                        [
                            'user_id' => $kasir->id,
                            'tanggal' => $tanggal,
                            'total' => $total,
                            'metode_bayar' => $metodeBayar[$transaksiIndex],
                            'status' => 'selesai',
                            'catatan' => 'Data demo forecast',
                        ],
                    );

                    if (! $penjualan->wasRecentlyCreated) {
                        return;
                    }

                    DetailPenjualan::query()->create([
                        'penjualan_id' => $penjualan->id,
                        'varian_produk_id' => $varian->id,
                        'qty' => $jumlahPerProduk,
                        'harga_satuan' => $varian->harga_jual,
                        'harga_modal' => $varian->harga_modal,
                        'subtotal' => $total,
                    ]);
                });
            }
        }
    }
}
