<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
{
    Schema::create('rentals', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_pengguna')->constrained('users')->onDelete('cascade');
        $table->string('kode_transaksi')->unique();
        $table->date('tgl_mulai_sewa');
        $table->date('tgl_selesai_sewa');
        $table->decimal('total_harga', 12, 2);
        $table->enum('status_transaksi', ['menunggu', 'disewa', 'selesai', 'dibatalkan'])->default('menunggu');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};