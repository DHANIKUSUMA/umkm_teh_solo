# UMKM Teh Solo

Aplikasi web untuk membantu operasional UMKM Teh Solo. Aplikasi ini menyediakan pengelolaan produk dan varian, pencatatan transaksi melalui kasir, serta pemantauan laporan dan perkiraan penjualan.

## Fitur

- **Admin**
  - Dashboard ringkasan usaha.
  - Pengelolaan akun admin dan karyawan, termasuk status aktif.
  - Pengelolaan produk, varian, harga modal, dan harga jual.
  - Pencatatan dan pengelolaan transaksi, metode pembayaran, bukti pembayaran, dan status transaksi.
  - Laporan penjualan bulanan, pendapatan, modal, laba, transaksi batal, metode pembayaran, dan produk terlaris.
  - Perkiraan pendapatan dan jumlah transaksi menggunakan *Simple Moving Average* (SMA), dengan periode 2–12 bulan.
- **Karyawan**
  - Kasir untuk mencatat transaksi dari produk dan varian yang aktif.
  - Riwayat dan ringkasan transaksi harian milik karyawan yang sedang login.
- Hak akses berbasis peran untuk admin dan karyawan.
- Profil pengguna dan autentikasi.

## Teknologi

- PHP 8.3 atau lebih baru
- Laravel 13
- SQLite (konfigurasi awal)
- Node.js dan npm
- Vite, Tailwind CSS, dan Alpine.js
- Spatie Laravel Permission untuk peran pengguna

## Persiapan

Pastikan PHP, Composer, Node.js, dan npm sudah terpasang.

1. Pasang dependensi PHP:

   ```bash
   composer install
   ```

2. Siapkan file konfigurasi lingkungan. Salin `.env.example` menjadi `.env` jika file `.env` belum tersedia.

   ```powershell
   Copy-Item .env.example .env
   ```

3. Buat application key:

   ```bash
   php artisan key:generate
   ```

4. Pastikan `.env` menggunakan SQLite:

   ```dotenv
   DB_CONNECTION=sqlite
   ```

   Basis data lokal menggunakan `database/database.sqlite`. Jika file tersebut belum ada, buat file kosong sebelum menjalankan migrasi:

   ```powershell
   New-Item -ItemType File database\database.sqlite
   ```

5. Jalankan migrasi dan data awal:

   ```bash
   php artisan migrate --seed
   ```

6. Pasang dependensi frontend dan buat symbolic link untuk file unggahan:

   ```bash
   npm install
   php artisan storage:link
   ```

7. Jalankan aplikasi. Buka dua terminal dari direktori proyek:

   ```bash
   php artisan serve
   ```

   ```bash
   npm run dev
   ```

   Aplikasi tersedia di `http://localhost:8000`.

Untuk membuat aset frontend tanpa menjalankan Vite dalam mode pengembangan:

```bash
npm run build
```

## Akun demo

Seeder menyediakan akun berikut untuk penggunaan lokal:

| Peran | Email | Kata sandi |
| --- | --- | --- |
| Admin | `owner@tehsolo.test` | `password123` |
| Karyawan | `kasir1@tehsolo.test` | `password123` |

Akun dan kata sandi ini hanya untuk pengembangan. Ganti kredensial sebelum aplikasi digunakan di lingkungan lain.

## Pengujian

Jalankan seluruh pengujian:

```bash
php artisan test --compact
```

## Struktur penting

- `app/Http/Controllers/Admin` — dashboard dan fitur administrasi.
- `app/Http/Controllers/Karyawan` — fitur kasir karyawan.
- `app/Models` — model produk, varian, penjualan, detail penjualan, dan pengguna.
- `database/migrations` — skema basis data.
- `database/seeders` — akun demo, produk, transaksi, dan data contoh forecast.
- `resources/views` — tampilan Blade.
- `routes/web.php` — rute aplikasi dan pembatasan akses berdasarkan peran.
