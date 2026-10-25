<?php

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use App\Models\VarianProduk;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

it('allows admin to create a new product with variants', function () {
    $adminRole = Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    $response = $this->actingAs($admin)->post(route('admin.produk.store'), [
        'nama' => 'Teh Kampul Super',
        'varians' => [
            [
                'nama_varian' => 'Cup Jumbo',
                'kategori' => 'Teh Buah',
                'harga_modal' => 3000,
                'harga_jual' => 6000,
            ],
            [
                'nama_varian' => 'Cup Regular',
                'kategori' => 'Teh Buah',
                'harga_modal' => 2000,
                'harga_jual' => 4500,
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.produk.index'));
    $this->assertDatabaseHas('produks', ['nama' => 'Teh Kampul Super']);
    $this->assertDatabaseHas('varian_produks', ['nama_varian' => 'Cup Jumbo', 'harga_jual' => 6000]);
    $this->assertDatabaseHas('varian_produks', ['nama_varian' => 'Cup Regular', 'harga_jual' => 4500]);
});

it('allows karyawan to create a new order with qris and photo upload', function () {
    Storage::fake('public');

    $karyawanRole = Role::findOrCreate('karyawan', 'web');
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $karyawan->assignRole($karyawanRole);

    $produk = Produk::query()->create(['nama' => 'Es Teh Melati', 'is_active' => true]);
    $varian = VarianProduk::query()->create([
        'produk_id' => $produk->id,
        'kategori' => 'Teh',
        'nama_varian' => 'Regular',
        'harga_jual' => 4000,
        'harga_modal' => 2000,
        'is_active' => true,
    ]);

    $file = UploadedFile::fake()->image('bukti_qris.jpg');

    $response = $this->actingAs($karyawan)->post(route('karyawan.kasir.store'), [
        'items' => json_encode([
            ['varian_id' => $varian->id, 'qty' => 2],
        ]),
        'metode_bayar' => 'qris',
        'bukti_bayar' => $file,
        'catatan' => 'Less ice',
    ]);

    $response->assertRedirect(route('karyawan.kasir.index'));
    $this->assertDatabaseHas('penjualans', [
        'user_id' => $karyawan->id,
        'total' => 8000,
        'metode_bayar' => 'qris',
        'status' => 'selesai',
        'catatan' => 'Less ice',
    ]);

    $penjualan = Penjualan::latest('id')->first();
    expect($penjualan->bukti_bayar)->not->toBeNull();
    Storage::disk('public')->assertExists($penjualan->bukti_bayar);
});

it('allows admin to create a new order directly and view the receipt', function () {
    Storage::fake('public');

    $adminRole = Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    $produk = Produk::query()->create(['nama' => 'Teh Tarik', 'is_active' => true]);
    $varian = VarianProduk::query()->create([
        'produk_id' => $produk->id,
        'kategori' => 'Teh Susu',
        'nama_varian' => 'Large',
        'harga_jual' => 10000,
        'harga_modal' => 5000,
        'is_active' => true,
    ]);

    $file = UploadedFile::fake()->image('bukti_admin_qris.png');

    $response = $this->actingAs($admin)->post(route('admin.penjualan.store'), [
        'items' => json_encode([
            ['varian_id' => $varian->id, 'qty' => 3],
        ]),
        'metode_bayar' => 'qris',
        'bukti_bayar' => $file,
        'catatan' => 'Pesanan khusus',
    ]);

    $penjualan = Penjualan::latest('id')->first();
    $response->assertRedirect(route('admin.penjualan.show', $penjualan));
    $this->assertDatabaseHas('penjualans', [
        'id' => $penjualan->id,
        'user_id' => $admin->id,
        'total' => 30000,
        'metode_bayar' => 'qris',
    ]);

    $showResponse = $this->actingAs($admin)->get(route('admin.penjualan.show', $penjualan));
    $showResponse->assertOk();
    $showResponse->assertSee($penjualan->no_nota);
    $showResponse->assertSee('Teh Tarik');
});
