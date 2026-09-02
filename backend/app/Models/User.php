<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <--- Import ini

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <--- Tambahkan HasApiTokens di sini

    protected $fillable = [
        'nama',
        'email',
        'kata_sandi',
        'peran',
        'no_telepon',
        'alamat',
    ];

    protected $hidden = [
        'kata_sandi',
        'remember_token',
    ];

    // Relasi ke Transaksi (1 User punya Banyak Transaksi)
    public function rentals()
    {
        return $this->hasMany(Rental::class, 'id_pengguna');
    }
}