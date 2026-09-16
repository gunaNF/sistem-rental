<template>
  <div class="page-wrapper">
    <div class="container">
      <!-- Tombol Kembali -->
      <div class="nav-back">
        <router-link to="/" class="btn-back">
          ← Kembali ke Beranda
        </router-link>
      </div>

      <!-- Header Halaman -->
      <div class="page-header">
        <span class="badge">Kebijakan Rental</span>
        <h1>Syarat dan Ketentuan</h1>
        <p>
          Harap membaca dengan teliti syarat dan ketentuan penyewaan alat outdoor di ForRest.rent sebelum melakukan transaksi pemesanan.
        </p>
      </div>

      <!-- Konten Ketentuan -->
      <div class="terms-content">
        <div class="term-section">
          <h2>1. Persyaratan Identitas Penyewa</h2>
          <p>
            Setiap penyewa wajib meninggalkan kartu identitas resmi yang masih berlaku (seperti KTP, Kartu Pelajar/Mahasiswa, SIM, atau Paspor) sebagai jaminan selama masa penyewaan berlangsung. Identitas akan dikembalikan saat alat selesai dikembalikan dalam kondisi baik.
          </p>
        </div>

        <div class="term-section">
          <h2>2. Masa Sewa dan Keterlambatan</h2>
          <p>
            Perhitungan masa sewa dihitung per hari (24 jam) atau sesuai kesepakatan saat transaksi. Keterlambatan pengembalian tanpa konfirmasi sebelumnya akan dikenakan denda tambahan sesuai tarif harian per item yang disewa.
          </p>
        </div>

        <div class="term-section">
          <h2>3. Tanggung Jawab Kerusakan dan Kehilangan</h2>
          <p>
            Penyewa bertanggung jawab penuh atas keutuhan alat yang disewa. Jika terjadi kerusakan berat, robek parah, atau kehilangan komponen/alat selama masa sewa, penyewa wajib mengganti biaya perbaikan atau mengganti unit alat yang hilang sesuai dengan harga pasar.
          </p>
        </div>

        <div class="term-section">
          <h2>4. Pembatalan dan Perubahan Jadwal</h2>
          <p>
            Pembatalan pesanan atau perubahan jadwal sewa harap diinformasikan kepada admin kami melalui WhatsApp selambat-lambatnya 1 hari sebelum jadwal pengambilan alat.
          </p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'

onMounted(() => {
  window.scrollTo(0, 0)
})
</script>

<style scoped>
.page-wrapper {
  min-height: 100vh;
  padding: 40px 20px 80px 20px;
  background-color: #f8fafc;
  color: #0f172a;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.container {
  max-width: 900px;
  margin: 0 auto;
}

.nav-back {
  margin-bottom: 24px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  color: #475569;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  padding: 8px 16px;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.btn-back:hover {
  background: #f1f5f9;
  color: #0f172a;
}

.page-header {
  text-align: center;
  max-width: 700px;
  margin: 0 auto 40px auto;
}

.badge {
  display: inline-block;
  background: #f1f5f9;
  color: #475569;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 700;
  margin-bottom: 16px;
  border: 1px solid #cbd5e1;
}

.page-header h1 {
  font-size: 2.2rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 16px;
}

.page-header p {
  font-size: 1.05rem;
  color: #64748b;
  line-height: 1.6;
}

.terms-content {
  display: flex;
  flex-direction: column;
  gap: 24px;
}

.term-section {
  background: #ffffff;
  padding: 28px;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.term-section h2 {
  font-size: 1.15rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 10px;
}

.term-section p {
  font-size: 0.92rem;
  color: #64748b;
  line-height: 1.6;
  margin: 0;
}
</style>