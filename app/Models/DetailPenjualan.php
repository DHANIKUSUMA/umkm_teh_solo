<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailPenjualan extends Model
{
    use HasFactory;

    protected $table = 'detail_penjualans';
    protected $fillable = [
        'penjualan_id',
        'varian_produk_id',
        'qty', 
        'harga_satuan',
        'harga_modal', 
        'subtotal'
    ];

    public function penjualan() {
        return $this->belongsTo(Penjualan::class, 'penjualan_id');
    }

    public function varianProduk() {
        return $this->belongsTo(VarianProduk::class, 'varian_produk_id');
    }
}
