<script setup>
import { RouterView, RouterLink, useRouter } from 'vue-router'

const router = useRouter()

// 1. Pindah ke Halaman Beranda Utama (Tanpa Logout)
const goToHome = () => {
  router.push('/')
}

// 2. Logout Lengkap (Hapus Token + Pindah ke Beranda / Login)
const handleLogout = () => {
  localStorage.removeItem('access_token')
  localStorage.removeItem('user_data')
  localStorage.removeItem('user_role')
  
  router.push('/') // Mengarahkan ke halaman beranda setelah logout
}
</script>

<template>
  <div class="admin-wrapper">
    <!-- Navbar Atas Clean -->
    <header class="admin-header">
      <div class="header-container">
        <router-link to="/admin/dashboard" class="logo">
          🏕️ forrest.<span>admin</span>
        </router-link>

        <div class="user-action">
          <span class="user-name">Halo, Admin</span>

          <!-- Tombol Ke Beranda Utama (Tanpa Hapus Token) -->
          <button type="button" @click="goToHome" class="btn-action btn-home">
            🏠 Beranda
          </button>

          <!-- Tombol Logout (Hapus Token & Keluar Sesi) -->
          <button type="button" @click="handleLogout" class="btn-action btn-logout">
            🚪 Logout
          </button>
        </div>
      </div>
    </header>

    <!-- Content Utama -->
    <main class="main-content">
      <div class="container">
        <RouterView />
      </div>
    </main>
  </div>
</template>

<style scoped>
.admin-wrapper {
  min-height: 100vh;
  background-color: #f8fafc;
  color: #1e293b;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.admin-header {
  background: #ffffff;
  border-bottom: 1px solid #e2e8f0;
  box-shadow: 0 2px 10px rgba(0, 0, 0, 0.02);
  position: sticky;
  top: 0;
  z-index: 50;
}

.header-container {
  max-width: 1000px;
  margin: 0 auto;
  padding: 16px 24px;
  display: flex;
  align-items: center;
  justify-content: space-between;
}

.logo {
  font-size: 1.3rem;
  font-weight: 800;
  color: #0f172a;
  text-decoration: none;
}

.logo span {
  color: #ff9f1c;
}

.user-action {
  display: flex;
  align-items: center;
  gap: 10px;
}

.user-name {
  font-size: 0.88rem;
  font-weight: 600;
  color: #475569;
  margin-right: 6px;
}

/* Base style tombol aksi */
.btn-action {
  padding: 6px 14px;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s ease;
  border: 1px solid transparent;
}

/* Style Tombol Beranda */
.btn-home {
  color: #0284c7;
  background-color: #f0f9ff;
  border-color: #bae6fd;
}

.btn-home:hover {
  background-color: #e0f2fe;
}

/* Style Tombol Logout */
.btn-logout {
  color: #ef4444;
  background-color: #fef2f2;
  border-color: #fecaca;
}

.btn-logout:hover {
  background-color: #fee2e2;
}

.main-content {
  padding: 40px 24px;
}

.container {
  max-width: 1000px;
  margin: 0 auto;
}
</style>