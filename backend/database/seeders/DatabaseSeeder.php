<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Item;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Buat Akun Admin
        User::create([
            'nama' => 'Admin Rental',
            'email' => 'admin@gmail.com',
            'kata_sandi' => Hash::make('admin123'),
            'peran' => 'admin',
            'no_telepon' => '085642194669',
            'alamat' => 'Kantor Pusat Rental',
        ]);

        // 2. Buat Akun Customer
        User::create([
            'nama' => 'Rudy Gunawan',
            'email' => 'customer@gmail.com',
            'kata_sandi' => Hash::make('password123'),
            'peran' => 'customer',
            'no_telepon' => '089876543210',
            'alamat' => 'Jl. Merdeka No. 45',
        ]);

        // 3. Buat Data Barang Awal (Contoh: Peralatan Outdoor/Kamera)
        Item::create([
            'nama_barang' => 'Tenda kap2',
            'kategori' => 'Tenda',
            'deskripsi' => 'Tenda pendaki kapasitas untuk 2 orang',
            'harga_per_hari' => 150000,
            'stok' => 5,
            'foto_barang' => null,
        ]);

        Item::create([
            'nama_barang' => 'Tenda Camping 4 Person',
            'kategori' => 'Outdoor',
            'deskripsi' => 'Tenda waterproof muat hingga 4 orang.',
            'harga_per_hari' => 50000,
            'stok' => 10,
            'foto_barang' => null,
        ]);
    }
}