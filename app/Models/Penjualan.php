<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penjualan extends Model
{
    use HasFactory;

    protected $table = 'penjualans';

    protected $fillable = [
        'no_nota',
        'user_id',
        'tanggal',
        'total',
        'metode_bayar',
        'bukti_bayar',
        'status',
        'catatan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function detailPenjualans()
    {
        return $this->hasMany(DetailPenjualan::class, 'penjualan_id');
    }
}
