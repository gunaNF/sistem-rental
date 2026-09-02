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
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_transaksi')->constrained('rentals')->onDelete('cascade');
            $table->string('metode_bayar')->nullable();
            $table->string('bukti_transfer')->nullable();
            $table->decimal('jumlah_bayar', 12, 2);
            $table->enum('status_bayar', ['belum_dibayar', 'diverifikasi', 'ditolak'])->default('belum_dibayar');
            $table->timestamp('tgl_pembayaran')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};