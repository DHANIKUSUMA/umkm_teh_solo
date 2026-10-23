<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('penjualans', function (Blueprint $table) {
            $table->id();
            $table->string('no_nota', 30)->unique();
            $table->foreignId('user_id')->constrained('users'); // Kasir/Pengguna yang mencatat
            $table->dateTime('tanggal');
            $table->decimal('total', 12, 2);
            $table->enum('metode_bayar', ['tunai', 'qris', 'transfer']);
            $table->enum('status', ['selesai', 'batal'])->default('selesai');
            $table->string('catatan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penjualans');
    }
};
