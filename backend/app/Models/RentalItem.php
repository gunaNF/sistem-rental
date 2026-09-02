<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RentalItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_transaksi',
        'id_barang',
        'jumlah',
        'subtotal',
    ];

    // Relasi ke Transaksi
    public function rental()
    {
        return $this->belongsTo(Rental::class, 'id_transaksi');
    }

    // Relasi ke Barang
    public function item()
    {
        return $this->belongsTo(Item::class, 'id_barang');
    }
}