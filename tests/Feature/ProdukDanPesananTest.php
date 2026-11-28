<?php

use App\Models\Penjualan;
use App\Models\Produk;
use App\Models\User;
use App\Models\VarianProduk;
use Database\Seeders\ProdukSeeder;
use Illuminate\Http\Client\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
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

it('rejects a product name that already exists regardless of case or outer whitespace', function () {
    $adminRole = Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    Produk::query()->create(['nama' => 'teh', 'is_active' => true]);

    $response = $this->actingAs($admin)->post(route('admin.produk.store'), [
        'nama' => ' TEH ',
        'varians' => [
            [
                'nama_varian' => 'Cup Regular',
                'harga_modal' => 2000,
                'harga_jual' => 4000,
            ],
        ],
    ]);

    $response->assertSessionHasErrors([
        'nama' => 'Nama produk sudah digunakan. Pilih nama produk yang berbeda.',
    ]);
    $this->assertDatabaseCount('produks', 1);
});

it('allows a different product type while another type already exists', function () {
    $adminRole = Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    Produk::query()->create(['nama' => 'teh', 'is_active' => true]);

    $response = $this->actingAs($admin)->post(route('admin.produk.store'), [
        'nama' => 'thaiTea',
        'varians' => [
            [
                'nama_varian' => 'Thai Tea Original',
                'harga_modal' => 2000,
                'harga_jual' => 4000,
            ],
        ],
    ]);

    $response->assertRedirect(route('admin.produk.index'));
    $this->assertDatabaseHas('produks', ['nama' => 'teh']);
    $this->assertDatabaseHas('produks', ['nama' => 'thaiTea']);
});

it('rejects renaming a product to another existing product name', function () {
    $adminRole = Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);
    $tea = Produk::query()->create(['nama' => 'teh', 'is_active' => true]);
    $thaiTea = Produk::query()->create(['nama' => 'thaiTea', 'is_active' => true]);

    $response = $this->actingAs($admin)->put(route('admin.produk.update', $thaiTea), [
        'nama' => ' TEH ',
    ]);

    $response->assertSessionHasErrors([
        'nama' => 'Nama produk sudah digunakan. Pilih nama produk yang berbeda.',
    ]);
    $this->assertDatabaseHas('produks', ['id' => $tea->id, 'nama' => 'teh']);
    $this->assertDatabaseHas('produks', ['id' => $thaiTea->id, 'nama' => 'thaiTea']);
});

it('does not duplicate seeded products or variants when the product seeder runs again', function () {
    $seeder = app(ProdukSeeder::class);
    $seeder->run();
    $seeder->run();

    $this->assertDatabaseCount('produks', 4);
    $this->assertDatabaseCount('varian_produks', 12);
});

it('allows karyawan to create a new order with qris and photo upload', function () {
    config([
        'services.supabase.url' => 'https://project.supabase.co',
        'services.supabase.service_role_key' => 'test-service-role-key',
        'services.supabase.storage_bucket' => 'bukti_pembayaran',
    ]);
    Http::preventStrayRequests();
    Http::fake([
        'https://project.supabase.co/storage/v1/object/bukti_pembayaran/*' => Http::response([], 200),
    ]);

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
    expect($penjualan->bukti_bayar)->toStartWith('bukti_bayar/');
    Http::assertSent(fn (Request $request) => $request->method() === 'POST'
        && str_starts_with($request->url(), 'https://project.supabase.co/storage/v1/object/bukti_pembayaran/bukti_bayar/')
        && $request->hasHeader('apikey', 'test-service-role-key'));
});

it('allows admin to create a new order directly and view the receipt', function () {
    config([
        'services.supabase.url' => 'https://project.supabase.co',
        'services.supabase.service_role_key' => 'test-service-role-key',
        'services.supabase.storage_bucket' => 'bukti_pembayaran',
    ]);
    Http::preventStrayRequests();
    Http::fake([
        'https://project.supabase.co/storage/v1/object/sign/bukti_pembayaran/*' => Http::response([
            'signedURL' => '/object/sign/bukti_pembayaran/private.jpg?token=test-token',
        ], 200),
        'https://project.supabase.co/storage/v1/object/bukti_pembayaran/*' => Http::response([], 200),
    ]);

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
    $showResponse->assertSee(
        'https://project.supabase.co/storage/v1/object/sign/bukti_pembayaran/private.jpg?token=test-token',
        false,
    );
    Http::assertSent(fn (Request $request) => str_contains(
        $request->url(),
        '/storage/v1/object/sign/bukti_pembayaran/',
    ));
});

it('allows karyawan to open only their own private payment proof', function () {
    config([
        'services.supabase.url' => 'https://project.supabase.co',
        'services.supabase.service_role_key' => 'test-service-role-key',
        'services.supabase.storage_bucket' => 'bukti_pembayaran',
    ]);
    Http::preventStrayRequests();
    Http::fake([
        'https://project.supabase.co/storage/v1/object/sign/bukti_pembayaran/*' => Http::response([
            'signedURL' => '/object/sign/bukti_pembayaran/private.jpg?token=test-token',
        ], 200),
    ]);

    $karyawanRole = Role::findOrCreate('karyawan', 'web');
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $karyawan->assignRole($karyawanRole);
    $penjualan = Penjualan::factory()->create([
        'user_id' => $karyawan->id,
        'bukti_bayar' => 'bukti_bayar/private.jpg',
    ]);

    $response = $this->actingAs($karyawan)->get(route('karyawan.kasir.proof', $penjualan));

    $response->assertRedirect('https://project.supabase.co/storage/v1/object/sign/bukti_pembayaran/private.jpg?token=test-token');
    Http::assertSent(fn (Request $request) => str_contains(
        $request->url(),
        '/storage/v1/object/sign/bukti_pembayaran/bukti_bayar/private.jpg',
    ));
});

it('hides another karyawan payment proof', function () {
    Http::preventStrayRequests();

    $karyawanRole = Role::findOrCreate('karyawan', 'web');
    $karyawan = User::factory()->create(['role' => 'karyawan']);
    $karyawan->assignRole($karyawanRole);
    $otherKaryawan = User::factory()->create(['role' => 'karyawan']);
    $penjualan = Penjualan::factory()->create([
        'user_id' => $otherKaryawan->id,
        'bukti_bayar' => 'bukti_bayar/private.jpg',
    ]);

    $response = $this->actingAs($karyawan)->get(route('karyawan.kasir.proof', $penjualan));

    $response->assertNotFound();
});

it('falls back to local storage when supabase service role key is not configured', function () {
    config([
        'services.supabase.service_role_key' => null,
    ]);
    Storage::fake('public');

    $adminRole = Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    $produk = Produk::query()->create(['nama' => 'Teh Manis', 'is_active' => true]);
    $varian = VarianProduk::query()->create([
        'produk_id' => $produk->id,
        'kategori' => 'Original',
        'nama_varian' => 'Reguler',
        'harga_jual' => 4000,
        'harga_modal' => 2000,
        'is_active' => true,
    ]);

    $file = UploadedFile::fake()->image('bukti_qris_local.jpg');

    $response = $this->actingAs($admin)->post(route('admin.penjualan.store'), [
        'items' => json_encode([
            ['varian_id' => $varian->id, 'qty' => 1],
        ]),
        'metode_bayar' => 'qris',
        'bukti_bayar' => $file,
    ]);

    $penjualan = Penjualan::latest('id')->first();
    $response->assertRedirect(route('admin.penjualan.show', $penjualan));
    expect($penjualan->bukti_bayar)->not->toBeNull();
    Storage::disk('public')->assertExists($penjualan->bukti_bayar);
});

it('defaults sales transaction list to today and allows period filtering', function () {
    $adminRole = Role::findOrCreate('admin', 'web');
    $admin = User::factory()->create(['role' => 'admin']);
    $admin->assignRole($adminRole);

    // Penjualan hari ini
    $todayOrder = Penjualan::factory()->create([
        'user_id' => $admin->id,
        'tanggal' => now(),
        'status' => 'selesai',
        'total' => 20000,
    ]);

    // Penjualan 2 hari yang lalu
    $pastOrder = Penjualan::factory()->create([
        'user_id' => $admin->id,
        'tanggal' => now()->subDays(2),
        'status' => 'selesai',
        'total' => 15000,
    ]);

    // Default akses (hanya hari ini)
    $response = $this->actingAs($admin)->get(route('admin.penjualan.index'));
    $response->assertOk();
    $response->assertSee($todayOrder->no_nota);
    $response->assertDontSee($pastOrder->no_nota);

    // Akses dengan periode=semua
    $responseAll = $this->actingAs($admin)->get(route('admin.penjualan.index', ['periode' => 'semua']));
    $responseAll->assertOk();
    $responseAll->assertSee($todayOrder->no_nota);
    $responseAll->assertSee($pastOrder->no_nota);
});
