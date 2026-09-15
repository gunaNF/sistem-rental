<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import KatalogSection from '@/components/KatalogSection.vue'

const router = useRouter()
const route = useRoute()

const cartCount = ref(0)
const isLoggedIn = ref(false)
const userName = ref('')
const userRole = ref('')

// State Dropdown
const isMenuOpen = ref(false)
const isContactOpen = ref(false)

const checkAuth = () => {
  const token = localStorage.getItem('access_token')
  const userStr = localStorage.getItem('user_data')
  const roleStr = localStorage.getItem('user_role')

  if (token) {
    isLoggedIn.value = true
    userRole.value = roleStr || ''
    
    if (userStr) {
      try {
        const user = JSON.parse(userStr)
        userName.value = user.nama || user.name || 'Customer'
      } catch (e) {
        userName.value = 'Customer'
      }
    }
  } else {
    isLoggedIn.value = false
    userName.value = ''
    userRole.value = ''
  }
}

onMounted(() => {
  checkAuth()
})

watch(() => route.path, () => {
  checkAuth()
})

// Toggle Menus
const toggleMenu = () => {
  isMenuOpen.value = !isMenuOpen.value
  if (isMenuOpen.value) isContactOpen.value = false
}

const toggleContact = () => {
  isContactOpen.value = !isContactOpen.value
  if (isContactOpen.value) isMenuOpen.value = false
}

const closeContact = () => {
  isContactOpen.value = false
}

const goToLogin = () => {
  router.push('/login')
}

const goToAdminDashboard = () => {
  isMenuOpen.value = false
  router.push('/admin/dashboard')
}

const handleLogout = () => {
  isMenuOpen.value = false
  localStorage.removeItem('access_token')
  localStorage.removeItem('user_data')
  localStorage.removeItem('user_role')
  isLoggedIn.value = false
  window.location.reload()
}
</script>

<template>
  <div class="home-container">
    <!-- BANNER HERO -->
    <div class="hero-wrapper">
      <div class="top-bar">
        <div class="top-info">
          <span>📞 0856-4219-4669</span>
          <span class="divider">|</span>
          <span>@forrestrent.com</span>
        </div>
        <div class="top-promo">
          <span>Lebih dari <strong>100+</strong> alat camping siap pakai!</span>
          <a href="#katalog" class="btn-top">Cek Katalog →</a>
        </div>
      </div>

      <!-- Header Navbar -->
      <header class="navbar">
        <div class="logo">
          <router-link to="/" class="logo-link">🏕️ forrest.<span>rent</span></router-link>
        </div>

        <nav class="nav-links">
          <a href="#katalog">Kategori ▾</a>
          <router-link to="/cara-sewa">Cara Sewa</router-link>
          <a href="#lokasi">Lokasi Pick-up</a>

          <!-- Dropdown Kontak -->
          <div class="nav-dropdown" @mouseleave="closeContact">
            <button type="button" class="btn-nav-dropdown" @click="toggleContact">
              Kontak ▾
            </button>

            <div v-if="isContactOpen" class="contact-dropdown-menu">
              <!-- WhatsApp -->
              <a 
                href="https://wa.me/6285642194669?text=Halo%20Admin%20Forrest%20Rent,%20saya%20ingin%20bertanya" 
                target="_blank" 
                class="contact-item"
              >
                <span class="icon">💬</span>
                <div class="info">
                  <span class="label">WhatsApp</span>
                  <span class="value">+62 856-4219-4669</span>
                </div>
              </a>

              <!-- Instagram -->
              <a 
                href="https://instagram.com/forrest.rent" 
                target="_blank" 
                class="contact-item"
              >
                <span class="icon">📸</span>
                <div class="info">
                  <span class="label">Instagram</span>
                  <span class="value">@forrest.rent</span>
                </div>
              </a>

              <!-- TikTok -->
              <a 
                href="https://tiktok.com/@forrest.rent" 
                target="_blank" 
                class="contact-item"
              >
                <span class="icon">🎵</span>
                <div class="info">
                  <span class="label">TikTok</span>
                  <span class="value">@forrest.rent</span>
                </div>
              </a>

              <!-- Email -->
              <a 
                href="mailto:info@forrestrent.com" 
                class="contact-item"
              >
                <span class="icon">✉️</span>
                <div class="info">
                  <span class="label">Email</span>
                  <span class="value">@forrestrent.com</span>
                </div>
              </a>
            </div>
          </div>
        </nav>

        <div class="nav-actions">
          <!-- Jika Belum Login -->
          <button v-if="!isLoggedIn" type="button" @click="goToLogin" class="btn-login">
            Masuk
          </button>

          <!-- Jika Sudah Login -->
          <template v-else>
            <!-- 1. Label Nama User -->
            <div class="user-pill">
              👤 {{ userName }}
            </div>

            <!-- 2. Tombol Titik Tiga -->
            <div class="dropdown-wrapper">
              <button type="button" @click="toggleMenu" class="btn-more-circle" aria-label="Menu">
                ⋮
              </button>

              <!-- Dropdown Menu Melayang -->
              <div v-if="isMenuOpen" class="dropdown-menu">
                <!-- Opsi Dashboard Admin HANYA muncul jika role == admin -->
                <button 
                  v-if="userRole === 'admin'"
                  type="button" 
                  @click="goToAdminDashboard" 
                  class="dropdown-item"
                >
                  ⚙️ Dashboard Admin
                </button>

                <button type="button" @click="handleLogout" class="dropdown-item text-danger">
                  🚪 Logout
                </button>
              </div>
            </div>
          </template>
          
          <!-- 3. Tombol Keranjang -->
          <div class="cart-btn">
            🛒
            <span class="cart-badge">{{ cartCount }}</span>
          </div>
        </div>
      </header>

      <!-- Main Hero Content -->
      <main class="hero-content">
        <div class="badge-tag">
          🌿 Sewa Alat Outdoor Tanpa Ribet
        </div>

        <h1 class="hero-title">
          Solusi Terbaik Penjelajahan Alam Anda
        </h1>

        <p class="hero-subtitle">
          Nikmati petualangan tanpa beban dengan persewaan alat outdoor berkualitas premium.
        </p>

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
            <span>Khusus BANSEL</span>
          </div>
        </div>

        <div class="cta-box">
          <a href="#katalog" class="btn-primary">Mulai Petualangan !</a>
        </div>
      </main>
    </div>

    <!-- KATALOG PRODUK -->
    <div id="katalog">
      <KatalogSection />
    </div>
  </div>
</template>

<style scoped>
.home-container {
  width: 100%;
}

.hero-wrapper {
  background: linear-gradient(rgba(0, 0, 0, 0.2), rgba(0, 0, 0, 0.3)),
              url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2000') center/cover no-repeat;
  color: #ffffff;
  font-family: 'Plus Jakarta Sans', sans-serif;
  width: 100%;
}

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
  text-decoration: none;
  padding: 4px 14px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.75rem;
  cursor: pointer;
}

.navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 20px 6%;
  position: relative;
  z-index: 50;
}

.logo-link {
  text-decoration: none;
  color: #ffffff;
  font-size: 1.6rem;
  font-weight: 800;
  letter-spacing: -0.5px;
}

.logo span {
  color: #ff9f1c;
}

.nav-links {
  display: flex;
  align-items: center;
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

/* Dropdown Kontak Nav Style */
.nav-dropdown {
  position: relative;
  display: inline-block;
}

.btn-nav-dropdown {
  background: none;
  border: none;
  color: #ffffff;
  font-weight: 600;
  font-size: 0.95rem;
  font-family: inherit;
  cursor: pointer;
  padding: 0;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.btn-nav-dropdown:hover {
  color: #2ec4b6;
}

.contact-dropdown-menu {
  position: absolute;
  top: 130%;
  left: 50%;
  transform: translateX(-50%);
  background: rgba(15, 23, 42, 0.92);
  backdrop-filter: blur(12px);
  -webkit-backdrop-filter: blur(12px);
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 14px;
  padding: 8px;
  min-width: 230px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
  z-index: 1000;
  display: flex;
  flex-direction: column;
  gap: 4px;
}

.contact-item {
  display: flex;
  align-items: center;
  gap: 12px;
  padding: 10px 14px;
  color: #ffffff !important;
  text-decoration: none;
  border-radius: 10px;
  transition: all 0.2s ease;
}

.contact-item:hover {
  background: rgba(255, 255, 255, 0.15);
  transform: translateX(3px);
}

.contact-item .icon {
  font-size: 1.2rem;
}

.contact-item .info {
  display: flex;
  flex-direction: column;
  text-align: left;
}

.contact-item .label {
  font-size: 0.85rem;
  font-weight: 700;
  color: #ffffff;
}

.contact-item .value {
  font-size: 0.75rem;
  color: rgba(255, 255, 255, 0.7);
}

.nav-actions {
  display: flex;
  align-items: center;
  gap: 12px;
}

.btn-login {
  background: #2ec4b6;
  color: #ffffff;
  border: none;
  font-weight: 700;
  font-size: 0.9rem;
  padding: 8px 20px;
  border-radius: 20px;
  cursor: pointer;
  transition: all 0.2s ease-in-out;
  display: inline-block;
  box-shadow: 0 4px 10px rgba(46, 196, 182, 0.3);
  position: relative;
  z-index: 100;
}

.btn-login:hover {
  background: #25a094;
  transform: translateY(-1px);
}

/* Kotak Nama User */
.user-pill {
  background: rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(8px);
  padding: 10px 16px;
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.25);
  font-size: 0.85rem;
  font-weight: 700;
  color: #ffffff;
}

/* Wrapper Dropdown */
.dropdown-wrapper {
  position: relative;
}

/* Tombol Lingkaran Titik Tiga terpisah */
.btn-more-circle {
  background: rgba(0, 0, 0, 0.35);
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.25);
  color: #ffffff;
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.2rem;
  font-weight: bold;
  cursor: pointer;
  transition: background 0.2s, transform 0.1s;
}

.btn-more-circle:hover {
  background: rgba(255, 255, 255, 0.2);
  transform: scale(1.05);
}

/* Menu Dropdown */
.dropdown-menu {
  position: absolute;
  top: 125%;
  right: 0;
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 8px 24px rgba(0, 0, 0, 0.18);
  padding: 6px 0;
  min-width: 170px;
  z-index: 999;
  display: flex;
  flex-direction: column;
  overflow: hidden;
  border: 1px solid #eee;
}

.dropdown-item {
  background: none;
  border: none;
  padding: 10px 16px;
  text-align: left;
  font-size: 0.85rem;
  font-weight: 600;
  color: #333;
  cursor: pointer;
  transition: background 0.2s;
  width: 100%;
}

.dropdown-item:hover {
  background-color: #f4f4f5;
}

.dropdown-item.text-danger {
  color: #e71d36;
}

.dropdown-item.text-danger:hover {
  background-color: #ffe5e8;
}

/* Tombol Keranjang */
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

.hero-content {
  padding: 40px 6% 80px 6%;
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
  margin-bottom: 16px;
  text-shadow: 0 4px 12px rgba(0,0,0,0.3);
  letter-spacing: -1px;
}

.hero-subtitle {
  font-size: 1.1rem;
  line-height: 1.6;
  margin-bottom: 24px;
  opacity: 0.95;
  text-shadow: 0 2px 6px rgba(0,0,0,0.3);
  max-width: 600px;
}

.features {
  display: flex;
  gap: 16px;
  margin-bottom: 28px;
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

.cta-box {
  display: flex;
  gap: 16px;
}

.btn-primary {
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 12px 28px;
  border-radius: 12px;
  font-size: 0.95rem;
  font-weight: 800;
  cursor: pointer;
  box-shadow: 0 6px 20px rgba(255, 159, 28, 0.4);
  text-decoration: none;
  display: inline-block;
}
</style>