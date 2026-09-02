<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_transaksi',
        'metode_bayar',
        'bukti_transfer',
        'jumlah_bayar',
        'status_bayar',
        'tgl_pembayaran',
    ];

    // Relasi ke Transaksi
    public function rental()
    {
        return $this->belongsTo(Rental::class, 'id_transaksi');
    }
}