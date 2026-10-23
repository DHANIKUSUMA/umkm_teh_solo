<?php

namespace Database\Seeders;

use App\Models\Produk;
use App\Models\VarianProduk;
use Illuminate\Database\Seeder;

class ProdukSeeder extends Seeder
{
    public function run(): void
    {
        $daftarProduk = [
            [
                'nama' => 'teh',
                'varians' => [
                    [
                        'kategori' => 'teh',
                        'nama_varian' => 'Teh Solo Original',
                        'harga_modal' => 2000,
                        'harga_jual' => 4000,
                    ],
                    [
                        'kategori' => 'teh',
                        'nama_varian' => 'Teh Solo Seger',
                        'harga_modal' => 2500,
                        'harga_jual' => 5000,
                    ],
                    [
                        'kategori' => 'teh',
                        'nama_varian' => 'Teh Solo Mocca',
                        'harga_modal' => 3000,
                        'harga_jual' => 6000,
                    ],
                    [
                        'kategori' => 'teh',
                        'nama_varian' => 'Teh Solo Kenagan',
                        'harga_modal' => 3000,
                        'harga_jual' => 6000,
                    ],
                    [
                        'kategori' => 'teh',
                        'nama_varian' => 'Teh Solo Maknyus',
                        'harga_modal' => 3500,
                        'harga_jual' => 7000,
                    ],
                ],
            ],
            [
                'nama' => 'thaiTea',
                'varians' => [
                    [
                        'kategori' => 'thaiTea',
                        'nama_varian' => 'Thai Tea Original',
                        'harga_modal' => 4000,
                        'harga_jual' => 8000,
                    ],
                    [
                        'kategori' => 'thaiTea',
                        'nama_varian' => 'Thai Tea Milk',
                        'harga_modal' => 4500,
                        'harga_jual' => 9000,
                    ],
                ],
            ],
            [
                'nama' => 'coklat',
                'varians' => [
                    [
                        'kategori' => 'coklat',
                        'nama_varian' => 'Coklat',
                        'harga_modal' => 4500,
                        'harga_jual' => 9000,
                    ],
                ],
            ],
            [
                'nama' => 'matcha',
                'varians' => [
                    [
                        'kategori' => 'matcha',
                        'nama_varian' => 'Matcha',
                        'harga_modal' => 5000,
                        'harga_jual' => 10000,
                    ],
                ],
            ],
        ];

        foreach ($daftarProduk as $item) {
            $produk = Produk::create([
                'nama' => $item['nama'],
                'is_active' => true,
            ]);

            foreach ($item['varians'] as $varian) {
                VarianProduk::create([
                    'produk_id' => $produk->id,
                    'kategori' => $varian['kategori'],
                    'nama_varian' => $varian['nama_varian'],
                    'harga_modal' => $varian['harga_modal'],
                    'harga_jual' => $varian['harga_jual'],
                    'is_active' => true,
                ]);
            }
        }
    }
}
