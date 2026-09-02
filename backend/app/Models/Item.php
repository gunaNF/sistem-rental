<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_barang',
        'kategori',
        'deskripsi',
        'harga_per_hari',
        'stok',
        'foto_barang',
    ];

    // Relasi ke Detail Transaksi
    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class, 'id_barang');
    }
}