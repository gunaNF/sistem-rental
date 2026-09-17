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
        <span class="badge">Panduan Penyewa</span>
        <h1>Cara Pengembalian Alat</h1>
        <p>
          Ikuti panduan berikut agar proses pengembalian alat camping di ForRest.rent berjalan lancar, tepat waktu, dan tanpa kendala.
        </p>
      </div>

      <!-- Langkah-langkah Pengembalian -->
      <div class="steps-grid">
        <div class="step-card">
          <div class="step-number">1</div>
          <h3>Pengecekan Barang (Check-list)</h3>
          <p>
            Pastikan seluruh perlengkapan dan aksesori alat outdoor yang kamu sewa lengkap sesuai dengan daftar saat pertama kali diambil.
          </p>
        </div>

       <div class="step-card">
  <div class="step-number">2</div>
  <h3>Pembersihan Ringan / Serahkan ke Toko</h3>
  <p>
    Cukup bersihkan sisa lumpur tebal atau kotoran kasar di luar tenda/alat. Urusan cuci bersih selanjutnya akan ditangani langsung oleh tim basecamp ForRest.rent agar alat tetap terawat.
  </p>
</div>

        <div class="step-card">
          <div class="step-number">3</div>
          <h3>Datang ke Lokasi Toko</h3>
          <p>
            Kembalikan peralatan langsung ke store kami di <strong>Jl. Cisirung No.118, Kabupaten Bandung</strong> sesuai dengan batas waktu sewa.
          </p>
        </div>

        <div class="step-card">
          <div class="step-number">4</div>
          <h3>Pemeriksaan Akhir & Jaminan</h3>
          <p>
            Petugas kami akan memeriksa kondisi fisik alat. Jika semuanya aman dan sesuai, kartu identitas atau jaminan yang dititipkan akan dikembalikan.
          </p>
        </div>
      </div>

      <!-- Catatan Penting -->
      <div class="notice-box">
        <h3>⚠️ Catatan Keterlambatan</h3>
        <p>
          Harap konfirmasi melalui WhatsApp kami di <strong>0878 1200 0155</strong> jika mengalami kendala di perjalanan atau ingin memperpanjang masa sewa sebelum batas waktu berakhir untuk menghindari denda keterlambatan.
        </p>
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
  background: #e0f2fe;
  color: #0284c7;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 700;
  margin-bottom: 16px;
  border: 1px solid #bae6fd;
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

.steps-grid {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  gap: 24px;
  margin-bottom: 40px;
}

.step-card {
  background: #ffffff;
  padding: 30px;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  position: relative;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.step-number {
  width: 40px;
  height: 40px;
  background: #0f172a;
  color: #ffffff;
  font-weight: 800;
  display: flex;
  align-items: center;
  justify-content: center;
  border-radius: 50%;
  margin-bottom: 16px;
  font-size: 1.1rem;
}

.step-card h3 {
  font-size: 1.15rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 10px;
}

.step-card p {
  font-size: 0.9rem;
  color: #64748b;
  line-height: 1.6;
  margin: 0;
}

.notice-box {
  background: #fffbeb;
  border: 1px solid #fde68a;
  padding: 24px;
  border-radius: 14px;
  color: #92400e;
}

.notice-box h3 {
  font-size: 1.1rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.notice-box p {
  font-size: 0.92rem;
  line-height: 1.6;
  margin: 0;
}

@media (max-width: 768px) {
  .steps-grid {
    grid-template-columns: 1fr;
  }
}
</style>