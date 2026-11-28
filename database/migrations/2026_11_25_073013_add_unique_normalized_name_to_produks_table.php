<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $products = DB::table('produks')->orderBy('id')->get(['id', 'nama', 'is_active']);
        $groups = [];

        foreach ($products as $product) {
            $normalizedName = mb_strtolower(trim($product->nama));

            if ($normalizedName === '') {
                throw new RuntimeException("Produk ID {$product->id} memiliki nama kosong dan harus diperbaiki sebelum migrasi.");
            }

            $groups[$normalizedName][] = $product;
        }

        Schema::table('produks', function (Blueprint $table) {
            $table->string('nama_key', 100)->nullable();
        });

        DB::transaction(function () use ($groups): void {
            foreach ($groups as $normalizedName => $productsWithSameName) {
                $canonicalProduct = array_shift($productsWithSameName);
                $isActive = $canonicalProduct->is_active;

                foreach ($productsWithSameName as $duplicateProduct) {
                    DB::table('varian_produks')
                        ->where('produk_id', $duplicateProduct->id)
                        ->update(['produk_id' => $canonicalProduct->id]);

                    $isActive = $isActive || $duplicateProduct->is_active;

                    DB::table('produks')->where('id', $duplicateProduct->id)->delete();
                }

                DB::table('produks')
                    ->where('id', $canonicalProduct->id)
                    ->update([
                        'nama' => trim($canonicalProduct->nama),
                        'nama_key' => $normalizedName,
                        'is_active' => $isActive,
                    ]);
            }
        });

        Schema::table('produks', function (Blueprint $table) {
            $table->string('nama_key', 100)->nullable(false)->change();
            $table->unique('nama_key');
        });
    }

    /**
     * Remove the uniqueness constraint; merged product records stay merged.
     */
    public function down(): void
    {
        Schema::table('produks', function (Blueprint $table) {
            $table->dropUnique('produks_nama_key_unique');
            $table->dropColumn('nama_key');
        });
    }
};
