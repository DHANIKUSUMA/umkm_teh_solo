<?php

use App\Http\Controllers\Admin\ForecastController;
use App\Http\Controllers\Admin\LaporanController;
use App\Http\Controllers\Admin\PenjualanController;
use App\Http\Controllers\Admin\ProdukController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Karyawan\KasirController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Rute umum yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        $user = auth()->user();
        if ($user && $user->hasRole('admin')) {
            return redirect()->route('admin.dashboard');
        }
        if ($user && $user->hasRole('karyawan')) {
            return redirect()->route('karyawan.dashboard');
        }

        // Fallback to a generic dashboard view if needed
        return view('dashboard');
    })->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Khusus Admin
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Pengelolaan Karyawan/User
    Route::get('/user', [UserController::class, 'index'])->name('user.index');
    Route::get('/user/create', [UserController::class, 'create'])->name('user.create');
    Route::post('/user', [UserController::class, 'store'])->name('user.store');
    Route::get('/user/{user}/edit', [UserController::class, 'edit'])->name('user.edit');
    Route::put('/user/{user}', [UserController::class, 'update'])->name('user.update');
    Route::patch('/user/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('user.toggle-active');

    // Pengelolaan Produk & Varian (termasuk harga)
    Route::get('/produk', [ProdukController::class, 'index'])->name('produk.index');
    Route::get('/produk/create', [ProdukController::class, 'create'])->name('produk.create');
    Route::post('/produk', [ProdukController::class, 'store'])->name('produk.store');
    Route::get('/produk/{produk}', [ProdukController::class, 'show'])->name('produk.show');
    Route::get('/produk/{produk}/edit', [ProdukController::class, 'edit'])->name('produk.edit');
    Route::put('/produk/{produk}', [ProdukController::class, 'update'])->name('produk.update');
    Route::delete('/produk/{produk}', [ProdukController::class, 'destroy'])->name('produk.destroy');
    Route::patch('/produk/{produk}/toggle-active', [ProdukController::class, 'toggleActive'])->name('produk.toggle-active');

    // Varian Produk (nested under produk)
    Route::post('/produk/{produk}/varian', [ProdukController::class, 'storeVarian'])->name('produk.varian.store');
    Route::put('/produk/{produk}/varian/{varian}', [ProdukController::class, 'updateVarian'])->name('produk.varian.update');
    Route::delete('/produk/{produk}/varian/{varian}', [ProdukController::class, 'destroyVarian'])->name('produk.varian.destroy');
    Route::patch('/produk/{produk}/varian/{varian}/toggle-active', [ProdukController::class, 'toggleVarianActive'])->name('produk.varian.toggle-active');

    // Penjualan (pencatatan pesanan & pembayaran)
    Route::get('/penjualan', [PenjualanController::class, 'index'])->name('penjualan.index');
    Route::get('/penjualan/create', [PenjualanController::class, 'create'])->name('penjualan.create');
    Route::post('/penjualan', [PenjualanController::class, 'store'])->name('penjualan.store');
    Route::get('/penjualan/{penjualan}', [PenjualanController::class, 'show'])->name('penjualan.show');
    Route::patch('/penjualan/{penjualan}/status', [PenjualanController::class, 'updateStatus'])->name('penjualan.update-status');

    // Laporan (pemantauan penjualan & produk terlaris)
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Forecast Penjualan
    Route::get('/forecast', [ForecastController::class, 'index'])->name('forecast.index');
});

// Khusus Karyawan
Route::middleware(['auth', 'role:karyawan'])->prefix('karyawan')->name('karyawan.')->group(function () {
    Route::get('/dashboard', [KasirController::class, 'index'])->name('dashboard');
    Route::get('/kasir', [KasirController::class, 'index'])->name('kasir.index');
    Route::post('/kasir/pesanan', [KasirController::class, 'store'])->name('kasir.store');
});

// Admin ATAU Karyawan bisa akses
Route::middleware(['auth', 'role:admin|karyawan'])->group(function () {
    Route::get('/laporan-progres', function () {
        return 'Halaman Laporan Progres (Dapat diakses Admin dan Karyawan)';
    })->name('laporan.progres');
});

require __DIR__.'/auth.php';
