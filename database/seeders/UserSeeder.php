<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $karyawanRole = Role::firstOrCreate(['name' => 'karyawan', 'guard_name' => 'web']);

        $admin = User::updateOrCreate(
            ['email' => 'owner@tehsolo.test'],
            [
                'name' => 'Owner Teh Solo',
                'username' => 'owner',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'is_active' => true,
            ]
        );
        $admin->syncRoles([$adminRole]);

        $karyawan = User::updateOrCreate(
            ['email' => 'kasir1@tehsolo.test'],
            [
                'name' => 'Siti Rahmawati (Kasir 1)',
                'username' => 'kasir1',
                'password' => Hash::make('password123'),
                'role' => 'karyawan',
                'is_active' => true,
            ]
        );
        $karyawan->syncRoles([$karyawanRole]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
