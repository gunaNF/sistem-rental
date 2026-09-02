<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_pengguna',
        'kode_transaksi',
        'tgl_mulai_sewa',
        'tgl_selesai_sewa',
        'total_harga',
        'status_transaksi',
    ];

    // Relasi ke User (Transaksi dimiliki oleh 1 User)
    public function user()
    {
        return $this->belongsTo(User::class, 'id_pengguna');
    }

    // Relasi ke Detail Transaksi (1 Transaksi punya Banyak Detail Barang)
    public function rentalItems()
    {
        return $this->hasMany(RentalItem::class, 'id_transaksi');
    }

    // Relasi ke Pembayaran (1 Transaksi punya 1 Catatan Pembayaran)
    public function payment()
    {
        return $this->hasOne(Payment::class, 'id_transaksi');
    }
}