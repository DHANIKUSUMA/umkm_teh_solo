<?php

namespace Database\Factories;

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\VarianProduk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\DetailPenjualan>
 */
class DetailPenjualanFactory extends Factory
{
    protected $model = DetailPenjualan::class;

    public function definition(): array
    {
        $qty = fake()->numberBetween(1, 4);
        $hargaModal = fake()->randomElement([2000, 2500, 3000, 4000]);
        $hargaSatuan = $hargaModal + fake()->randomElement([2000, 3000, 4000]);
        $subtotal = $qty * $hargaSatuan;

        return [
            'penjualan_id' => Penjualan::factory(),
            'varian_produk_id' => VarianProduk::factory(),
            'qty' => $qty,
            'harga_satuan' => $hargaSatuan,
            'harga_modal' => $hargaModal,
            'subtotal' => $subtotal,
        ];
    }
}
