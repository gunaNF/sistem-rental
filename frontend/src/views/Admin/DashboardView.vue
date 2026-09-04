<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="full-screen-wrapper">
    <div class="dashboard-container">
      
      <!-- Topbar Header -->
      <header class="admin-topbar">
        <div class="brand">
          <span class="logo-icon">⛺</span>
          <span class="brand-text">forrest.<span>admin</span></span>
        </div>
        <div class="user-menu">
          <span class="user-greeting">Halo, Admin</span>
          <router-link to="/login" class="btn-logout">🚪 Keluar</router-link>
        </div>
      </header>

      <!-- Welcome Banner -->
      <div class="welcome-text">
        <h2>Panel Pengelolaan Data</h2>
        <p>Pilih menu di bawah ini untuk mengelola data tabel backend sistem rental.</p>
      </div>

      <!-- GRID KOTAK-KOTAK MENU -->
      <div class="menu-grid">
        
        <!-- Card Items -->
        <router-link to="/admin/items" class="menu-card border-items">
          <div class="card-icon items-bg">🎒</div>
          <div class="card-info">
            <h3>Kelola Items</h3>
            <p>Daftar alat outdoor, stok barang, dan harga sewa harian.</p>
          </div>
          <span class="card-arrow">→</span>
        </router-link>

        <!-- Card Kategori -->
        <router-link to="/admin/kategori" class="menu-card border-kategori">
          <div class="card-icon kategori-bg">🏷️</div>
          <div class="card-info">
            <h3>Kelola Kategori</h3>
            <p>Kategori peralatan seperti Tenda, Carrier, Cooking Gear, dll.</p>
          </div>
          <span class="card-arrow">→</span>
        </router-link>

        <!-- Card Users -->
        <router-link to="/admin/users" class="menu-card border-users">
          <div class="card-icon users-bg">👥</div>
          <div class="card-info">
            <h3>Kelola Users</h3>
            <p>Data akun pengguna terdaftar dan peran akses (admin/customer).</p>
          </div>
          <span class="card-arrow">→</span>
        </router-link>

        <!-- Card Rental -->
        <router-link to="/admin/rentals" class="menu-card border-rentals">
          <div class="card-icon rentals-bg">📋</div>
          <div class="card-info">
            <h3>Kelola Transaksi Rental</h3>
            <p>Riwayat penyewaan, tanggal pinjam, dan peminjaman aktif.</p>
          </div>
          <span class="card-arrow">→</span>
        </router-link>

        <!-- Card Payment -->
        <router-link to="/admin/payments" class="menu-card border-payments">
          <div class="card-icon payments-bg">💳</div>
          <div class="card-info">
            <h3>Kelola Payments</h3>
            <p>Status pembayaran transaksi dan bukti transfer dari pelanggan.</p>
          </div>
          <span class="card-arrow">→</span>
        </router-link>

      </div>
    </div>
  </div>
</template>

<style scoped>
/* Wrapper Layar Penuh Off-White Clean */
.full-screen-wrapper {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  z-index: 9999;
  overflow-y: auto;
  background-color: #f8fafc;
  background-image: 
    radial-gradient(at 0% 0%, rgba(46, 196, 182, 0.08) 0px, transparent 40%),
    radial-gradient(at 100% 100%, rgba(255, 159, 28, 0.08) 0px, transparent 40%);
  box-sizing: border-box;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
}

.dashboard-container {
  max-width: 1100px;
  margin: 0 auto;
  padding: 24px 20px 60px;
}

/* Header Navbar Putih Floating */
.admin-topbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 14px 24px;
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 16px;
  box-shadow: 0 4px 20px -2px rgba(0, 0, 0, 0.03);
  margin-bottom: 36px;
}

.brand {
  display: flex;
  align-items: center;
  gap: 8px;
  font-size: 1.3rem;
  font-weight: 800;
  color: #0f172a;
}

.brand-text span {
  color: #ff9f1c;
}

.user-menu {
  display: flex;
  align-items: center;
  gap: 16px;
}

.user-greeting {
  color: #475569;
  font-size: 0.88rem;
  font-weight: 600;
}

.btn-logout {
  background: #fff1f2;
  color: #e11d48;
  border: 1px solid #fecdd3;
  padding: 8px 16px;
  border-radius: 10px;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 700;
  transition: all 0.2s ease;
}

.btn-logout:hover {
  background: #ffe4e6;
  color: #be123c;
}

/* Judul Banner */
.welcome-text {
  text-align: center;
  margin-bottom: 36px;
}

.welcome-text h2 {
  font-size: 2rem;
  font-weight: 800;
  color: #0f172a;
  letter-spacing: -0.5px;
}

.welcome-text p {
  color: #64748b;
  font-size: 0.95rem;
  margin-top: 6px;
}

/* GRID KOTAK */
.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
  gap: 22px;
}

/* Kartu Putih Minimalis & Elegant Shadow */
.menu-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 18px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 20px;
  text-decoration: none;
  color: inherit;
  transition: all 0.28s cubic-bezier(0.4, 0, 0.2, 1);
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.menu-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 16px 32px -8px rgba(0, 0, 0, 0.08);
}

/* Aksen Border & Shadow Berwarna saat Hover */
.menu-card.border-items:hover { border-color: #38bdf8; }
.menu-card.border-kategori:hover { border-color: #fbbf24; }
.menu-card.border-users:hover { border-color: #c084fc; }
.menu-card.border-rentals:hover { border-color: #4ade80; }
.menu-card.border-payments:hover { border-color: #f87171; }

/* Icon Badge Soft Colors */
.card-icon {
  width: 58px;
  height: 58px;
  border-radius: 16px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.75rem;
  flex-shrink: 0;
}

.items-bg { background-color: #e0f2fe; }
.kategori-bg { background-color: #fef3c7; }
.users-bg { background-color: #f3e8ff; }
.rentals-bg { background-color: #dcfce7; }
.payments-bg { background-color: #ffe4e6; }

.card-info {
  flex-grow: 1;
}

.card-info h3 {
  font-size: 1.15rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 4px;
}

.card-info p {
  font-size: 0.85rem;
  color: #64748b;
  line-height: 1.45;
  margin: 0;
}

.card-arrow {
  margin-left: auto;
  font-size: 1.25rem;
  color: #cbd5e1;
  font-weight: bold;
  transition: transform 0.25s ease, color 0.25s ease;
}

.menu-card:hover .card-arrow {
  color: #2ec4b6;
  transform: translateX(6px);
}
</style>