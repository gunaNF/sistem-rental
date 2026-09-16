<template>
  <div class="about-page-wrapper">
    <div class="container">
      <!-- Tombol Kembali -->
      <div class="nav-back">
        <router-link to="/" class="btn-back">
          ← Kembali ke Beranda
        </router-link>
      </div>

      <!-- Hero About -->
      <div class="about-header">
        <span class="badge">🏕️ Tentang Kami</span>
        <h1>Solusi Terbaik Penjelajahan Alam Anda</h1>
        <p>
          ForRest.rent adalah platform penyewaan alat outdoor dan camping tepercaya yang menyediakan peralatan berkualitas premium, bersih, dan siap pakai untuk mendukung petualanganmu.
        </p>
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
          <h2>📍 Lokasi Operasional</h2>
          <p><strong>Bandung Selatan (BANSEL) & Sekitarnya</strong></p>
          <p class="address">
            Jl. H. Jian No.38, RT.9/RW.3, Cipete Utara, Kebayoran Baru, Jakarta Selatan, DKI Jakarta 12150
          </p>
          <div class="operational-hours">
            <span>🕒 Jam Operasional:</span>
            <strong>Setiap Hari, 09.00 - 20.00 WIB</strong>
          </div>
        </div>

        <div class="info-card contact-card">
          <h2>📞 Hubungi Kami</h2>
          <p>Punya pertanyaan seputar alat camping atau kerjasama?</p>
          <div class="contact-links">
            <a href="https://wa.me/6285642194669" target="_blank" class="btn-contact wa">
              💬 WhatsApp: +62 856-4219-4669
            </a>
            <a href="mailto:forrest.rent@gmail.com" class="btn-contact email">
              ✉️ Email: forrest.rent@gmail.com
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
  padding: 40px 20px 80px 20px;
  background-color: #f8fafc;
  color: #0f172a;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.container {
  max-width: 1000px;
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

.about-header {
  text-align: center;
  max-width: 750px;
  margin: 0 auto 50px auto;
}

.badge {
  display: inline-block;
  background: #e6fffa;
  color: #0d9488;
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 700;
  margin-bottom: 16px;
  border: 1px solid #99f6e4;
}

.about-header h1 {
  font-size: 2.2rem;
  font-weight: 800;
  color: #0f172a;
  line-height: 1.25;
  margin-bottom: 16px;
}

.about-header p {
  font-size: 1.05rem;
  color: #64748b;
  line-height: 1.6;
}

.features-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 24px;
  margin-bottom: 50px;
}

.feature-card {
  background: #ffffff;
  padding: 28px 20px;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  text-align: center;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.icon-box {
  font-size: 2.2rem;
  margin-bottom: 12px;
}

.feature-card h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #0f172a;
  margin-bottom: 8px;
}

.feature-card p {
  font-size: 0.88rem;
  color: #64748b;
  line-height: 1.5;
}

.info-section {
  display: grid;
  grid-template-columns: 1fr 1fr;
  gap: 24px;
}

.info-card {
  background: #ffffff;
  padding: 30px;
  border-radius: 16px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.info-card h2 {
  font-size: 1.25rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 12px;
}

.address {
  color: #64748b;
  font-size: 0.9rem;
  margin-top: 6px;
  line-height: 1.5;
}

.operational-hours {
  margin-top: 20px;
  padding-top: 16px;
  border-top: 1px solid #f1f5f9;
  font-size: 0.88rem;
  display: flex;
  flex-direction: column;
  gap: 4px;
  color: #475569;
}

.contact-links {
  display: flex;
  flex-direction: column;
  gap: 12px;
  margin-top: 20px;
}

.btn-contact {
  padding: 12px 16px;
  border-radius: 10px;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.9rem;
  text-align: center;
  transition: all 0.2s ease;
}

.btn-contact.wa {
  background: #25d366;
  color: #ffffff;
}

.btn-contact.wa:hover {
  background: #1eb956;
}

.btn-contact.email {
  background: #0f172a;
  color: #ffffff;
}

.btn-contact.email:hover {
  background: #1e293b;
}

@media (max-width: 868px) {
  .features-grid, .info-section {
    grid-template-columns: 1fr;
  }
}
</style>