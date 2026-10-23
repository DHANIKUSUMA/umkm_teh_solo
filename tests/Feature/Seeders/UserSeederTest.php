<?php

use App\Models\User;
use Database\Seeders\UserSeeder;

test('user seeder creates and assigns roles to the seeded accounts', function () {
    $this->seed(UserSeeder::class);
    $this->seed(UserSeeder::class);

    $admin = User::where('email', 'owner@tehsolo.test')->firstOrFail();
    $karyawan = User::where('email', 'kasir1@tehsolo.test')->firstOrFail();

    $this->assertDatabaseHas('users', [
        'email' => 'owner@tehsolo.test',
        'username' => 'owner',
    ]);
    $this->assertDatabaseHas('users', [
        'email' => 'kasir1@tehsolo.test',
        'username' => 'kasir1',
    ]);

    expect($admin->hasRole('admin'))->toBeTrue()
        ->and($karyawan->hasRole('karyawan'))->toBeTrue()
        ->and(User::count())->toBe(2);
});
