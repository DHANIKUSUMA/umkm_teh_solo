<?php

use App\Models\Penjualan;
use App\Models\User;
use Carbon\Carbon;
use Spatie\Permission\Models\Role;

it('redirects guests to login', function () {
    $response = $this->get(route('admin.forecast.index'));

    $response->assertRedirect(route('login'));
});

it('forbids employees from viewing the forecast', function () {
    $employeeRole = Role::create(['name' => 'karyawan', 'guard_name' => 'web']);
    $employee = User::factory()->create(['role' => 'karyawan']);
    $employee->assignRole($employeeRole);

    $response = $this->actingAs($employee)->get(route('admin.forecast.index'));

    $response->assertForbidden();
});

it('shows the next month forecast from completed months only', function () {
    $this->travelTo(Carbon::parse('2026-10-25 12:00:00'));

    $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    Penjualan::factory()->for($admin)->create([
        'tanggal' => '2026-07-10 10:00:00',
        'total' => 10000,
        'status' => 'selesai',
    ]);
    Penjualan::factory()->for($admin)->create([
        'tanggal' => '2026-08-10 10:00:00',
        'total' => 20000,
        'status' => 'selesai',
    ]);
    Penjualan::factory()->for($admin)->create([
        'tanggal' => '2026-09-10 10:00:00',
        'total' => 30000,
        'status' => 'selesai',
    ]);
    Penjualan::factory()->for($admin)->create([
        'tanggal' => '2026-09-20 10:00:00',
        'total' => 500000,
        'status' => 'batal',
    ]);
    Penjualan::factory()->for($admin)->create([
        'tanggal' => '2026-10-10 10:00:00',
        'total' => 900000,
        'status' => 'selesai',
    ]);

    $response = $this->actingAs($admin)->get(route('admin.forecast.index', ['periode' => 3]));

    $response->assertOk()
        ->assertSee('November 2026')
        ->assertSee('Rp 20.000')
        ->assertSee('Riwayat dan prediksi pendapatan');
});

it('shows an empty state when there are no completed sales', function () {
    $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    $response = $this->actingAs($admin)->get(route('admin.forecast.index'));

    $response->assertOk()
        ->assertSee('Belum ada transaksi selesai dalam 12 bulan penuh terakhir.')
        ->assertSee('Rp 0');
});

it('rejects a moving average period outside the supported range', function () {
    $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    $response = $this->actingAs($admin)->get(route('admin.forecast.index', ['periode' => 1]));

    $response->assertSessionHasErrors('periode');
});
