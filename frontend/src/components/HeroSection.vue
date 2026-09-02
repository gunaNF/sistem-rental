<script setup>
import { ref } from 'vue'

const cartCount = ref(0)
</script>

<template>
  <div class="hero-wrapper">
    <!-- Top Bar Cerah -->
    <div class="top-bar">
      <div class="top-info">
        <span>📞 0878-1200-0155</span>
        <span class="divider">|</span>
        <span>✉️ hello@forrestrent.com</span>
      </div>
      <div class="top-promo">
        <span>Lebih dari <strong>1.000+</strong> alat camping siap pakai!</span>
        <button class="btn-top">Cek Katalog →</button>
      </div>
    </div>

    <!-- Header Navbar Bersih & Terang -->
    <header class="navbar">
      <div class="logo">
        🏕️ forrest<span>rent.</span>
      </div>

      <nav class="nav-links">
        <a href="#">Kategori ▾</a>
        <a href="#">Cara Sewa</a>
        <a href="#">Lokasi Pick-up</a>
        <a href="#">Kontak</a>
      </nav>

<div class="nav-actions">
  <router-link to="/login" class="btn-login">Masuk</router-link>
  
  <div class="cart-btn">
    🛒
    <span class="cart-badge">{{ cartCount }}</span>
  </div>
</div>
    </header>

    <!-- Main Hero Content (Cerah & Kontras Tinggi) -->
    <main class="hero-content">
      <div class="badge-tag">
        🌿 Sewa Alat Outdoor Tanpa Ribet
      </div>

      <h1 class="hero-title">
        Solusi Terbaik Penjelajahan Alam Anda<br />
      </h1>

      <p class="hero-subtitle">
        Nikmati petualangan tanpa beban dengan persewaan alat outdoor berkualitas premium.</p>

      <!-- Checklist Fitur -->
      <div class="features">
        <div class="feature-card">
          <span class="icon">✅</span>
          <span>Stok Real-Time</span>
        </div>
        <div class="feature-card">
          <span class="icon">⚡</span>
          <span>Proses Cepat</span>
        </div>
        <div class="feature-card">
          <span class="icon">📍</span>
          <span>Khusus JADETABEK</span>
        </div>
      </div>

      <!-- Action Button -->
      <div class="cta-box">
        <button class="btn-primary">Sikat Gear Now</button>
        <button class="btn-secondary">Lihat Paket Camping</button>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Main Container dengan Gambar Cerah & Light Overlay */
.hero-wrapper {
  min-height: 100vh;
  /* Pakai foto latar terang dengan overlay hitam tipis 25% saja agar teks terbaca tajam */
  background: linear-gradient(rgba(0, 0, 0, 0.25), rgba(0, 0, 0, 0.35)),
              url('https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?q=80&w=1600') center/cover no-repeat;
  color: #ffffff;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Top Bar Terang */
.top-bar {
  background: rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(4px);
  padding: 10px 6%;
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.85rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.2);
}

.top-info {
  display: flex;
  align-items: center;
  gap: 12px;
}

.divider {
  opacity: 0.4;
}

.top-promo {
  display: flex;
  align-items: center;
  gap: 12px;
}

.top-promo strong {
  color: #2ec4b6;
}

.btn-top {
  background: #2ec4b6;
  color: #ffffff;
  border: none;
  padding: 4px 14px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.75rem;
  cursor: pointer;
}

/* Navbar */
.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 6%;
}

.logo {
  font-size: 1.6rem;
  font-weight: 800;
  letter-spacing: -0.5px;
}

.logo span {
  color: #ff9f1c;
}

.nav-links {
  display: flex;
  gap: 32px;
}

.nav-links a {
  color: #ffffff;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.95rem;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.nav-links a:hover {
  color: #2ec4b6;
}

.nav-actions {
  display: flex;
  align-items: center;
  gap: 20px;
}

.btn-login {
  color: #ffffff;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.95rem;
}

.cart-btn {
  background: #ffffff;
  color: #222;
  width: 44px;
  height: 44px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  cursor: pointer;
  position: relative;
  box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.cart-badge {
  position: absolute;
  top: -6px;
  right: -6px;
  background: #e71d36;
  color: #ffffff;
  font-size: 0.7rem;
  font-weight: 800;
  width: 20px;
  height: 20px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

/* Main Hero Content */
.hero-content {
  padding: 80px 6% 0 6%;
  max-width: 750px;
}

.badge-tag {
  display: inline-block;
  background: rgba(255, 255, 255, 0.25);
  backdrop-filter: blur(6px);
  padding: 6px 16px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 700;
  margin-bottom: 20px;
  border: 1px solid rgba(255, 255, 255, 0.3);
}

.hero-title {
  font-size: 3.8rem;
  font-weight: 900;
  line-height: 1.1;
  margin-bottom: 20px;
  text-shadow: 0 4px 12px rgba(0,0,0,0.3);
  letter-spacing: -1px;
}

.hero-subtitle {
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 32px;
  opacity: 0.95;
  text-shadow: 0 2px 6px rgba(0,0,0,0.3);
  max-width: 600px;
}

/* Features */
.features {
  display: flex;
  gap: 16px;
  margin-bottom: 36px;
  flex-wrap: wrap;
}

.feature-card {
  background: rgba(255, 255, 255, 0.2);
  backdrop-filter: blur(8px);
  padding: 8px 18px;
  border-radius: 30px;
  font-size: 0.9rem;
  font-weight: 700;
  display: flex;
  align-items: center;
  gap: 8px;
  border: 1px solid rgba(255, 255, 255, 0.25);
}

/* Buttons */
.cta-box {
  display: flex;
  gap: 16px;
}

.btn-primary {
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 14px 32px;
  border-radius: 14px;
  font-size: 1rem;
  font-weight: 800;
  cursor: pointer;
  box-shadow: 0 6px 20px rgba(255, 159, 28, 0.4);
}

.btn-secondary {
  background: rgba(255, 255, 255, 0.2);
  color: #ffffff;
  border: 2px solid #ffffff;
  padding: 14px 28px;
  border-radius: 14px;
  font-size: 1rem;
  font-weight: 700;
  cursor: pointer;
  backdrop-filter: blur(4px);
}
</style>