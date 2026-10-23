<?php

namespace Database\Factories;

use App\Models\Produk;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Produk>
 */
class ProdukFactory extends Factory
{
    protected $model = Produk::class;

    public function definition(): array
    {
        $produkList = [
            'teh',
            'thaiTea',
            'coklat',
            'matcha',
        ];

        return [
            'nama' => fake()->randomElement($produkList) . ' ' . fake()->unique()->numberBetween(1, 1000),
            'is_active' => true,
        ];
    }
}
