<script setup>
import logoImg from '../assets/forest-tanpa bg.png'

const scrollToTop = () => {
  window.scrollTo({ top: 0, behavior: 'smooth' })
}
</script>

<template>
  <footer class="footer-wrapper">
    <div class="footer-container">
      <!-- Kolom 1: Logo & Info Alamat -->
      <div class="footer-col brand-col">
        <div class="logo-area">
          <img 
            :src="logoImg" 
            alt="forrest.rent logo" 
            class="footer-logo-img" 
          />
        </div>
        
        <div class="contact-details">
          <p class="address">
            📍 Jl. H. Jian No.38, RT.9/RW.3, Cipete Utara, Kec. Kby. Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12150
          </p>
          <p class="hours">
            🕒 Setiap hari, 09:00 - 20:00 WIB
          </p>
          <p class="email">
            ✉️ forrest.rent@gmail.com
          </p>
        </div>
      </div>

      <!-- Kolom 2: Informasi -->
      <div class="footer-col">
        <h3>Informasi</h3>
        <ul>
          <li><a href="#">Cara sewa</a></li>
          <li><a href="#">Cara jadi member</a></li>
          <li><a href="#">Cara pengembalian</a></li>
          <li><a href="#">Syarat dan Ketentuan</a></li>
        </ul>
      </div>

      <!-- Kolom 3: Tentang Foresta -->
      <div class="footer-col">
        <h3>Tentang ForRest.rent</h3>
        <ul>
          <li><a href="#">Tentang Kami</a></li>
        </ul>
      </div>

      <!-- Kolom 4: Layanan Bantuan & WA -->
      <div class="footer-col">
        <h3>Layanan Bantuan</h3>
        <ul>
          <li><a href="#">Kontak Kami</a></li>
        </ul>

        <div class="wa-box">
          <span class="wa-label">📞 WhatsApp Kami</span>
          <a href="https://wa.me/6287812000155" target="_blank" class="wa-number">
            0878 1200 0155
          </a>
        </div>
      </div>
    </div>

    <!-- Garis Pemisah & Bottom Bar -->
    <div class="footer-bottom">
      <p class="copyright">
        © 2026 ForRest.rent. All rights reserved
      </p>

      <div class="social-links">
        <span>Follow us</span>
        <a href="#" aria-label="Instagram">📷</a>
        <a href="#" aria-label="Facebook">📘</a>
        <a href="#" aria-label="X/Twitter">𝕏</a>
      </div>
    </div>

    <!-- Tombol Scroll to Top -->
    <button class="btn-scroll-top" @click="scrollToTop" aria-label="Scroll to Top">
      ↑
    </button>
  </footer>
</template>

<style scoped>
.footer-wrapper {
  background-color: #111e2e;
  color: #cccccc;
  padding: 60px 6% 30px 6%;
  font-family: 'Plus Jakarta Sans', sans-serif;
  position: relative;
}

.footer-container {
  display: grid;
  grid-template-columns: 2.2fr 1fr 1fr 1.2fr;
  gap: 40px;
  margin-bottom: 50px;
}

.logo-area {
  margin-bottom: 20px;
}

.footer-logo-img {
  width: 300px;
  height: auto;
  display: block;
  object-fit: contain;
}

.contact-details p {
  font-size: 0.88rem;
  color: #9ca3af;
  line-height: 1.6;
  margin-bottom: 12px;
}

.footer-col h3 {
  color: #ffffff;
  font-size: 1.05rem;
  font-weight: 700;
  margin-bottom: 20px;
}

.footer-col ul {
  list-style: none;
  padding: 0;
  margin: 0;
}

.footer-col ul li {
  margin-bottom: 14px;
}

.footer-col ul li a {
  color: #9ca3af;
  text-decoration: none;
  font-size: 0.9rem;
  transition: color 0.2s ease;
}

.footer-col ul li a:hover {
  color: #ffffff;
}

.wa-box {
  margin-top: 24px;
}

.wa-label {
  display: block;
  color: #ffffff;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 6px;
}

.wa-number {
  color: #1bcc65;
  font-size: 1.3rem;
  font-weight: 800;
  text-decoration: none;
  letter-spacing: 0.5px;
}

.wa-number:hover {
  text-decoration: underline;
}

.footer-bottom {
  border-top: 1px solid #1d2d44;
  padding-top: 24px;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.copyright {
  font-size: 0.85rem;
  color: #9ca3af;
  margin: 0;
}

.social-links {
  display: flex;
  align-items: center;
  gap: 16px;
}

.social-links span {
  color: #ffffff;
  font-weight: 700;
  font-size: 0.9rem;
}

.social-links a {
  color: #ffffff;
  text-decoration: none;
  font-size: 1.1rem;
}

.btn-scroll-top {
  position: absolute;
  bottom: 24px;
  right: 24px;
  background-color: #1e2e42;
  color: #20e070;
  border: none;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  font-size: 1.2rem;
  font-weight: bold;
  cursor: pointer;
  display: flex;
  align-items: center;
  justify-content: center;
  transition: background-color 0.2s ease;
}

.btn-scroll-top:hover {
  background-color: #2a3f5a;
}

@media (max-width: 900px) {
  .footer-container {
    grid-template-columns: 1fr 1fr;
  }
}

@media (max-width: 600px) {
  .footer-container {
    grid-template-columns: 1fr;
  }
  
  .footer-bottom {
    flex-direction: column;
    gap: 16px;
    align-items: flex-start;
  }
}
</style>