<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

// Rute umum yang sudah login
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () { return view('dashboard'); })->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
});

// 1. Khusus Admin
Route::middleware(['auth', 'role:admin'])->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('admin.dashboard');
    })->name('admin.dashboard');
});

// 2. Khusus Dosen
Route::middleware(['auth', 'role:karyawan'])->group(function () {
    Route::get('/karyawan/dashboard', function () {
        return view('karyawan.dashboard');
    })->name('karyawan.dashboard');
});


// 4. Contoh Multiple Role (Admin ATAU Dosen bisa akses) sesuai modul hal 12:
Route::middleware(['auth', 'role:admin|karyawan'])->group(function () {
    Route::get('/laporan-progres', function () {
        return "Halaman Laporan Progres (Dapat diakses Admin dan Karyawan)";
    })->name('laporan.progres');
});


require __DIR__.'/auth.php';
