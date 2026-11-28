<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    use HasFactory;

    protected $table = 'produks';

    protected $fillable = [
        'nama',
        'is_active',
    ];

    protected static function booted(): void
    {
        static::saving(function (Produk $produk): void {
            $produk->nama = trim($produk->nama);
            $produk->nama_key = mb_strtolower($produk->nama);
        });
    }

    public function varians()
    {
        return $this->hasMany(VarianProduk::class, 'produk_id');
    }
}
