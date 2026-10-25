<?php

use App\Models\DetailPenjualan;
use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use App\Models\VarianProduk;
use Carbon\Carbon;
use Database\Seeders\ForecastDemoSeeder;

it('creates repeatable forecast sales for the three previous complete months', function () {
    $this->travelTo(Carbon::parse('2026-10-25 12:00:00'));

    $kasir = User::factory()->karyawan()->create();
    $produk = Produk::factory()->create();
    $varian = VarianProduk::query()->create([
        'produk_id' => $produk->id,
        'kategori' => 'teh',
        'nama_varian' => 'Teh Demo',
        'harga_jual' => 5000,
        'harga_modal' => 2500,
        'is_active' => true,
    ]);

    $this->seed(ForecastDemoSeeder::class);
    $this->seed(ForecastDemoSeeder::class);

    expect(Penjualan::query()->count())->toBe(12)
        ->and(DetailPenjualan::query()->count())->toBe(12)
        ->and(Penjualan::query()->whereBetween('tanggal', ['2026-07-01', '2026-07-31 23:59:59'])->count())->toBe(4)
        ->and(Penjualan::query()->whereBetween('tanggal', ['2026-08-01', '2026-08-31 23:59:59'])->count())->toBe(4)
        ->and(Penjualan::query()->whereBetween('tanggal', ['2026-09-01', '2026-09-30 23:59:59'])->count())->toBe(4)
        ->and(Penjualan::query()->where('user_id', $kasir->id)->where('total', 45000)->count())->toBe(4);
});

it('explains which base data is required before seeding forecast sales', function () {
    expect(fn () => $this->seed(ForecastDemoSeeder::class))
        ->toThrow(RuntimeException::class, 'membutuhkan minimal satu user karyawan dan satu varian produk aktif');
});
