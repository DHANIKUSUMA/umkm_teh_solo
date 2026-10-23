<?php

namespace Database\Seeders;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\User;
use App\Models\VarianProduk;
use Illuminate\Database\Seeder;

class PenjualanSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::where('role', 'karyawan')->get();
        if ($users->isEmpty()) {
            $users = User::all();
        }

        $varians = VarianProduk::all();
        if ($varians->isEmpty() || $users->isEmpty()) {
            return;
        }

        $metodeBayarList = ['tunai', 'qris', 'transfer'];

        for ($i = 1; $i <= 10; $i++) {
            $kasir = $users->random();
            $tanggal = now()->subDays(rand(0, 7))->subHours(rand(1, 10))->subMinutes(rand(1, 50));
            $metode = $metodeBayarList[array_rand($metodeBayarList)];
            $status = ($i === 10) ? 'batal' : 'selesai'; // contoh 1 transaksi batal

            $penjualan = Penjualan::create([
                'no_nota' => 'NOTA-' . $tanggal->format('Ymd') . '-' . sprintf('%04d', $i),
                'user_id' => $kasir->id,
                'tanggal' => $tanggal,
                'total' => 0, // diupdate setelah detail dibuat
                'metode_bayar' => $metode,
                'status' => $status,
                'catatan' => $status === 'batal' ? 'Pelanggan membatalkan pesanan' : null,
            ]);

            $totalTransaksi = 0;
            $itemsCount = rand(1, 3);
            $randomVarians = $varians->random(min($itemsCount, $varians->count()));

            foreach ($randomVarians as $varian) {
                $qty = rand(1, 3);
                $subtotal = $qty * $varian->harga_jual;
                $totalTransaksi += $subtotal;

                DetailPenjualan::create([
                    'penjualan_id' => $penjualan->id,
                    'varian_produk_id' => $varian->id,
                    'qty' => $qty,
                    'harga_satuan' => $varian->harga_jual,
                    'harga_modal' => $varian->harga_modal,
                    'subtotal' => $subtotal,
                ]);
            }

            $penjualan->update(['total' => $totalTransaksi]);
        }
    }
}
