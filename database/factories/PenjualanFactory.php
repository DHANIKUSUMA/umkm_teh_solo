<?php

namespace Database\Factories;

use App\Models\Penjualan;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Penjualan>
 */
class PenjualanFactory extends Factory
{
    protected $model = Penjualan::class;

    public function definition(): array
    {
        return [
            'no_nota' => 'NOTA-' . now()->format('Ymd') . '-' . fake()->unique()->numerify('#####'),
            'user_id' => User::factory(),
            'tanggal' => fake()->dateTimeBetween('-1 month', 'now'),
            'total' => fake()->randomElement([5000, 10000, 15000, 20000, 25000, 30000]),
            'metode_bayar' => fake()->randomElement(['tunai', 'qris', 'transfer']),
            'status' => fake()->randomElement(['selesai', 'selesai', 'selesai', 'batal']),
            'catatan' => fake()->optional(0.3)->sentence(3),
        ];
    }
}
