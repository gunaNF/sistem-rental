<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_kategori', 
        'nama_barang',
        'deskripsi',
        'stok',
        'harga_per_hari',
        'foto_barang', 
        'gambar',
    ];

    // Relasi: Barang milik Satu Kategori
    public function category()
    {
        return $this->belongsTo(Category::class, 'id_kategori');
    }

    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class, 'id_barang');
    }
}