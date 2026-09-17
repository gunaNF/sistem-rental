<script setup>
import { RouterLink } from 'vue-router'

const steps = [
  {
    number: '01',
    icon: '🎒',
    title: 'Pilih Perlengkapan',
    desc: 'Cari dan pilih alat outdoor seperti tenda, carrier, atau alat masak dari katalog yang tersedia.',
    badge: 'Katalog Lengkap'
  },
  {
    number: '02',
    icon: '📅',
    title: 'Tentukan Tanggal',
    desc: 'Atur durasi dan tanggal pengambilan barang sesuai jadwal petualangan camping kamu.',
    badge: 'Jadwal Fleksibel'
  },
  {
    number: '03',
    icon: '💳',
    title: 'Bayar & Konfirmasi',
    desc: 'Lakukan pembayaran digital secara aman dan dapatkan bukti sewa otomatis.',
    badge: 'Aman & Praktis'
  },
  {
    number: '04',
    icon: '🏕️',
    title: 'Ambil & Jelajah',
    desc: 'Tunjukkan bukti booking di lokasi toko/basecamp kami, dan kamu siap berpetualang!',
    badge: 'Siap Pakai'
  }
]
</script>

<template>
  <section class="cara-sewa-page">
    <!-- Tombol Kembali ke Beranda -->
    <div class="nav-back-container">
      <router-link to="/" class="btn-back">
        ← Kembali ke Beranda
      </router-link>
    </div>

    <!-- Header Banner Cerah -->
    <div class="banner-wrapper">
      <div class="banner-content">
        <span class="badge-sub">⚡ Panduan Transaksi</span>
        <h2>Cara Mudah Sewa Alat Outdoor</h2>
        <p>Sewa perlengkapan camping tanpa ribet hanya dalam 4 langkah praktis.</p>
      </div>
    </div>

    <!-- Cards Section -->
    <div class="container">
      <div class="steps-grid">
        <div 
          v-for="(step, index) in steps" 
          :key="index" 
          class="step-card"
        >
          <div class="card-top">
            <span class="step-badge">{{ step.badge }}</span>
            <span class="step-number">{{ step.number }}</span>
          </div>

          <div class="icon-wrapper">
            <span class="step-icon">{{ step.icon }}</span>
          </div>

          <h3>{{ step.title }}</h3>
          <p>{{ step.desc }}</p>

          <div class="card-footer-line"></div>
        </div>
      </div>

      <!-- Info Box Syarat Pengambilan -->
      <div class="info-banner">
        <div class="info-icon">💡</div>
        <div class="info-text">
          <h4>Syarat Pengambilan Barang</h4>
          <p>
            Pastikan membawa <strong>Kartu Identitas Asli (KTP/SIM/KTM)</strong> sebagai jaminan saat verifikasi pengambilan alat di store kami.
          </p>
        </div>
        <router-link to="/" class="btn-action">
          Lihat Katalog
        </router-link>
      </div>
    </div>
  </section>
</template>

<style scoped>
.cara-sewa-page {
  min-height: 100vh;
  background-color: #f8fafc; /* Latar Belakang Cerah Clean */
  color: #1e293b;
  padding-bottom: 80px;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Tombol Kembali */
.nav-back-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 24px 24px 0 24px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  background: #ffffff;
  color: #475569;
  border: 1px solid #cbd5e1;
  padding: 8px 16px;
  border-radius: 8px;
  font-size: 0.88rem;
  font-weight: 600;
  text-decoration: none;
  transition: all 0.2s ease;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.02);
}

.btn-back:hover {
  background: #f1f5f9;
  color: #0f172a;
  border-color: #94a3b8;
}

/* Banner Header */
.banner-wrapper {
  background: #ffffff;
  padding: 40px 24px 40px;
  text-align: center;
  border-bottom: 1px solid #e2e8f0;
  margin-top: 16px;
}

.banner-content {
  max-width: 600px;
  margin: 0 auto;
}

.badge-sub {
  display: inline-block;
  background: rgba(46, 196, 182, 0.12);
  color: #2ec4b6;
  font-weight: 700;
  font-size: 0.8rem;
  letter-spacing: 1px;
  text-transform: uppercase;
  padding: 6px 14px;
  border-radius: 20px;
  margin-bottom: 14px;
}

.banner-content h2 {
  font-size: 2.2rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 10px;
}

.banner-content p {
  color: #64748b;
  font-size: 0.98rem;
  margin: 0;
}

/* Main Container */
.container {
  max-width: 1100px;
  margin: 40px auto 0;
  padding: 0 24px;
}

/* Grid Design */
.steps-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
  gap: 24px;
}

.step-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  padding: 26px;
  display: flex;
  flex-direction: column;
  transition: all 0.25s ease;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.step-card:hover {
  transform: translateY(-6px);
  border-color: #2ec4b6;
  box-shadow: 0 12px 24px rgba(46, 196, 182, 0.12);
}

.card-top {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 16px;
}

.step-badge {
  font-size: 0.75rem;
  font-weight: 700;
  color: #0284c7;
  background: #e0f2fe;
  padding: 4px 10px;
  border-radius: 6px;
}

.step-number {
  font-weight: 900;
  font-size: 1.4rem;
  color: #ff9f1c;
}

.icon-wrapper {
  width: 52px;
  height: 52px;
  background: #fef3c7;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 18px;
}

.step-icon {
  font-size: 1.8rem;
}

.step-card h3 {
  font-size: 1.15rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 8px;
}

.step-card p {
  font-size: 0.88rem;
  color: #64748b;
  line-height: 1.5;
  margin: 0;
  flex-grow: 1;
}

.card-footer-line {
  height: 3px;
  width: 30px;
  background: #cbd5e1;
  border-radius: 2px;
  margin-top: 20px;
  transition: all 0.3s ease;
}

.step-card:hover .card-footer-line {
  width: 100%;
  background: #ff9f1c;
}

/* Info Box Cerah */
.info-banner {
  margin-top: 40px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-left: 5px solid #2ec4b6;
  border-radius: 12px;
  padding: 22px 28px;
  display: flex;
  align-items: center;
  gap: 18px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
  flex-wrap: wrap;
}

.info-icon {
  font-size: 1.8rem;
}

.info-text {
  flex: 1;
  min-width: 250px;
}

.info-text h4 {
  margin: 0 0 4px 0;
  font-size: 1.05rem;
  color: #0f172a;
  font-weight: 800;
}

.info-text p {
  margin: 0;
  color: #64748b;
  font-size: 0.88rem;
  line-height: 1.5;
}

.btn-action {
  background: #ff9f1c;
  color: #ffffff;
  text-decoration: none;
  font-weight: 700;
  padding: 10px 20px;
  border-radius: 8px;
  font-size: 0.88rem;
  transition: background 0.2s ease;
  white-space: nowrap;
}

.btn-action:hover {
  background: #e08b10;
}

/* Responsive */
@media (max-width: 768px) {
  .banner-content h2 {
    font-size: 1.7rem;
  }
  
  .info-banner {
    flex-direction: column;
    text-align: center;
  }
  
  .btn-action {
    width: 100%;
    text-align: center;
  }
}
</style>  