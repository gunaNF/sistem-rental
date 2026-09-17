<template>
  <div class="about-page-wrapper">
    <div class="container">
      <!-- Tombol Kembali -->
      <div class="nav-back">
        <router-link to="/" class="btn-back">
          <span>←</span> Kembali ke Beranda
        </router-link>
      </div>

      <!-- Hero About -->
      <div class="about-header">
        <div class="badge-wrapper">
          <span class="badge">⛺ Tentang ForRest.rent</span>
        </div>
        <h1>Solusi Terbaik Penjelajahan Alam Anda</h1>
        <p>
          ForRest.rent adalah platform penyewaan alat outdoor dan camping tepercaya yang menyediakan peralatan berkualitas premium, bersih, dan siap pakai untuk mendukung petualanganmu.
        </p>
      </div>

      <!-- Kartu Filosofi Nama ForRest.rent -->
      <div class="developer-story-card">
        <div class="story-glow"></div>
        <div class="story-icon-box">
          <span class="story-emoji">🌲</span>
        </div>
        <div class="story-content">
          <h2>Filosofi & Arti "ForRest.rent"</h2>
          <p>
            Nama <strong>ForRest.rent</strong> merupakan gabungan dari kata <em>"Forest"</em> (hutan) yang melambangkan alam bebas, dan <em>"Rent"</em> (penyewaan). Selain itu, nama ini juga bermakna <strong>"For Rest"</strong> — sebagai penyedia perlengkapan andal yang memastikan kenyamanan dan keamanan istirahatmu di alam bebas.
          </p>
        </div>
      </div>

      <!-- Kartu Cerita Pengembang (SMK Assalaam) -->
      <div class="developer-story-card">
        <div class="story-glow"></div>
        <div class="story-icon-box">
          <span class="story-emoji">🏫</span>
        </div>
        <div class="story-content">
          <h2>Karya 2 Siswa SMK Assalaam Kelas 12 RPL 1</h2>
          <p>
            Website <strong>ForRest.rent</strong> ini dikembangkan dengan penuh dedikasi oleh <strong>2 orang siswa SMK Assalaam kelas XII RPL 1</strong> sebagai bentuk implementasi keahlian rekayasa perangkat lunak. Proyek ini mendigitalisasi proses penyewaan alat outdoor agar pemesanan, pengecekan stok, dan transaksi menjadi lebih cepat serta transparan.
          </p>
        </div>
      </div>

      <!-- Grid Keunggulan / Visi Misi -->
      <div class="features-grid">
        <div class="feature-card">
          <div class="icon-box">⛺</div>
          <h3>Peralatan Terawat</h3>
          <p>Seluruh tenda, sleeping bag, dan alat masak selalu dibersihkan, disterilkan, dan dicek kelayakannya sebelum disewakan.</p>
        </div>

        <div class="feature-card">
          <div class="icon-box">⚡</div>
          <h3>Proses Cepat & Mudah</h3>
          <p>Pemesanan dapat dilakukan secara online melalui website, dengan dukungan konfirmasi instan via WhatsApp.</p>
        </div>

        <div class="feature-card">
          <div class="icon-box">💰</div>
          <h3>Harga Ramah Kantong</h3>
          <p>Nikmati tarif sewa harian yang terjangkau tanpa mengorbankan kualitas dan keamanan peralatan outdoor Anda.</p>
        </div>
      </div>

      <!-- Detail Informasi Toko -->
      <div class="info-section">
        <div class="info-card">
          <div class="card-header-icon">📍</div>
          <h2>Lokasi Operasional</h2>
          <p class="store-name"><strong>ForRest.rent Bandung</strong></p>
          <p class="address">
            Jl. Cisirung No.118, Cangkuang Kulon, Kec. Dayeuhkolot, Kabupaten Bandung, Jawa Barat 40239
          </p>
          <div class="operational-hours">
            <span class="hours-label">🕒 Jam Operasional:</span>
            <strong>Setiap Hari, 09:00 - 20:00 WIB</strong>
          </div>
        </div>

        <div class="info-card contact-card">
          <div class="card-header-icon">📞</div>
          <h2>Hubungi Kami</h2>
          <p class="contact-desc">Punya pertanyaan seputar alat camping atau kerjasama?</p>
          <div class="contact-links">
            <a href="https://wa.me/6285642194669" target="_blank" class="btn-contact wa">
              <span>💬</span> WhatsApp: 0856 4219 4669
            </a>
            <a href="mailto:forrest.rent@gmail.com" class="btn-contact email">
              <span>✉️</span> Email: forrest.rent@gmail.com
            </a>
          </div>
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
.about-page-wrapper {
  min-height: 100vh;
  padding: 50px 20px 90px 20px;
  background-color: #f8fafc;
  color: #0f172a;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.container {
  max-width: 1000px;
  margin: 0 auto;
}

.nav-back {
  margin-bottom: 30px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #334155;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.9rem;
  padding: 10px 18px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 12px;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.02);
  transition: all 0.2s ease;
}

.btn-back:hover {
  background: #0f172a;
  color: #ffffff;
  border-color: #0f172a;
  transform: translateX(-3px);
}

.about-header {
  text-align: center;
  max-width: 750px;
  margin: 0 auto 40px auto;
}

.badge-wrapper {
  margin-bottom: 16px;
}

.badge {
  display: inline-block;
  background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
  color: #0369a1;
  padding: 6px 18px;
  border-radius: 30px;
  font-size: 0.85rem;
  font-weight: 800;
  letter-spacing: 0.3px;
  box-shadow: 0 2px 6px rgba(2, 132, 199, 0.1);
}

.about-header h1 {
  font-size: 2.5rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.2;
  margin-bottom: 16px;
  letter-spacing: -0.5px;
}

.about-header p {
  font-size: 1.05rem;
  color: #64748b;
  line-height: 1.7;
}

/* Developer Story Card Styling */
.developer-story-card {
  position: relative;
  background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
  color: #ffffff;
  padding: 32px;
  border-radius: 20px;
  display: flex;
  align-items: center;
  gap: 24px;
  margin-bottom: 24px;
  box-shadow: 0 10px 25px rgba(15, 23, 42, 0.12);
  overflow: hidden;
  border: 1px solid rgba(255, 255, 255, 0.08);
}

.story-glow {
  position: absolute;
  top: -50px;
  right: -50px;
  width: 180px;
  height: 180px;
  background: radial-gradient(circle, rgba(56, 189, 248, 0.25) 0%, transparent 70%);
  border-radius: 50%;
  pointer-events: none;
}

.story-icon-box {
  flex-shrink: 0;
  width: 70px;
  height: 70px;
  background: rgba(255, 255, 255, 0.08);
  border: 1px solid rgba(255, 255, 255, 0.12);
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 2rem;
}

.story-content h2 {
  font-size: 1.3rem;
  font-weight: 800;
  margin-bottom: 8px;
  color: #38bdf8;
  letter-spacing: -0.3px;
}

.story-content p {
  font-size: 0.95rem;
  color: #cbd5e1;
  line-height: 1.6;
  margin: 0;
}

/* Features Grid */
.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-top: 30px;
  margin-bottom: 40px;
}

.feature-card {
  background: #ffffff;
  padding: 32px 24px;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  text-align: center;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
  transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
}

.feature-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 12px 30px rgba(0, 0, 0, 0.06);
  border-color: #cbd5e1;
}

.icon-box {
  font-size: 2.5rem;
  margin-bottom: 16px;
  display: inline-block;
  transition: transform 0.3s ease;
}

.feature-card:hover .icon-box {
  transform: scale(1.1);
}

.feature-card h3 {
  font-size: 1.15rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 10px;
}

.feature-card p {
  font-size: 0.9rem;
  color: #64748b;
  line-height: 1.6;
}

/* Info Section */
.info-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}

.info-card {
  background: #ffffff;
  padding: 32px;
  border-radius: 20px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.02);
}

.card-header-icon {
  font-size: 1.8rem;
  margin-bottom: 12px;
}

.info-card h2 {
  font-size: 1.3rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 10px;
}

.store-name {
  color: #0f172a;
  font-size: 1.05rem;
  margin-bottom: 6px;
}

.address {
  color: #64748b;
  font-size: 0.92rem;
  line-height: 1.6;
  margin-bottom: 0;
}

.contact-desc {
  color: #64748b;
  font-size: 0.92rem;
  margin-bottom: 20px;
}

.operational-hours {
  margin-top: 20px;
  padding-top: 16px;
  border-top: 1px solid #f1f5f9;
  font-size: 0.9rem;
  display: flex;
  flex-direction: column;
  gap: 4px;
  color: #475569;
}

.operational-hours strong {
  color: #0f172a;
}

.contact-links {
  display: flex;
  flex-direction: column;
  gap: 12px;
}

.btn-contact {
  display: flex;
  align-items: center;
  justify-content: center;
  gap: 10px;
  padding: 13px 18px;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.92rem;
  transition: all 0.2s ease;
}

.btn-contact.wa {
  background: #22c55e;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(34, 197, 94, 0.2);
}

.btn-contact.wa:hover {
  background: #16a34a;
  transform: translateY(-2px);
}

.btn-contact.email {
  background: #0f172a;
  color: #ffffff;
  box-shadow: 0 4px 12px rgba(15, 23, 42, 0.2);
}

.btn-contact.email:hover {
  background: #1e293b;
  transform: translateY(-2px);
}

@media (max-width: 868px) {
  .about-header h1 {
    font-size: 2rem;
  }
  .developer-story-card {
    flex-direction: column;
    text-align: center;
    padding: 28px;
  }
  .story-glow {
    display: none;
  }
  .features-grid, .info-section {
    grid-template-columns: 1fr;
  }
}
</style>