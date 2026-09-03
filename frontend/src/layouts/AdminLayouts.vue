<script setup>
import { RouterView, RouterLink } from 'vue-router'
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
          <router-link to="/" class="btn-logout">
            🚪 Keluar
          </router-link>
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
  gap: 16px;
}

.user-name {
  font-size: 0.88rem;
  font-weight: 600;
  color: #475569;
}

.btn-logout {
  text-decoration: none;
  color: #ef4444;
  background-color: #fef2f2;
  padding: 6px 14px;
  border-radius: 6px;
  font-size: 0.85rem;
  font-weight: 700;
  border: 1px solid #fecaca;
  transition: background-color 0.2s ease;
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