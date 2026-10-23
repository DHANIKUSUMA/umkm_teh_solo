<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VarianProduk extends Model
{
    use HasFactory;

    protected $table = 'varian_produks';

    protected $fillable = [
        'produk_id',
        'kategori',
        'nama_varian',
        'harga_jual',
        'harga_modal',
        'is_active'
    ];

    public function produk() {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function detailPenjualans() {
        return $this->hasMany(DetailPenjualan::class, 'varian_produk_id');
    }
}
