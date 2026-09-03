<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_kategori',
        'deskripsi',
    ];

    // Relasi: Satu Kategori memiliki Banyak Barang
    public function items()
    {
        return $this->hasMany(Item::class, 'id_kategori');
    }
}