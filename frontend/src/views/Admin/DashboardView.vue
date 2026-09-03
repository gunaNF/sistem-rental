<script setup>
import { RouterLink } from 'vue-router'
</script>

<template>
  <div class="dashboard-container">
    <div class="welcome-text">
      <h2>Panel Pengelolaan Data</h2>
      <p>Pilih menu di bawah ini untuk mengelola data tabel backend sistem rental.</p>
    </div>

    <!-- GRID KOTAK-KOTAK MENU -->
    <div class="menu-grid">
      
      <!-- Card Items -->
      <router-link to="/admin/items" class="menu-card">
        <div class="card-icon items-bg">🎒</div>
        <div class="card-info">
          <h3>Kelola Items</h3>
          <p>Daftar alat outdoor, stok barang, dan harga sewa harian.</p>
        </div>
        <span class="card-arrow">→</span>
      </router-link>

      <!-- Card Kategori -->
      <router-link to="/admin/kategori" class="menu-card">
        <div class="card-icon kategori-bg">🏷️</div>
        <div class="card-info">
          <h3>Kelola Kategori</h3>
          <p>Kategori peralatan seperti Tenda, Carrier, Cooking Gear, dll.</p>
        </div>
        <span class="card-arrow">→</span>
      </router-link>

      <!-- Card Users -->
      <router-link to="/admin/users" class="menu-card">
        <div class="card-icon users-bg">👥</div>
        <div class="card-info">
          <h3>Kelola Users</h3>
          <p>Data akun pengguna terdaftar dan peran akses (admin/customer).</p>
        </div>
        <span class="card-arrow">→</span>
      </router-link>

      <!-- Card Rental -->
      <router-link to="/admin/rentals" class="menu-card">
        <div class="card-icon rentals-bg">📋</div>
        <div class="card-info">
          <h3>Kelola Transaksi Rental</h3>
          <p>Riwayat penyewaan, tanggal pinjam, dan peminjaman aktif.</p>
        </div>
        <span class="card-arrow">→</span>
      </router-link>

      <!-- Card Payment -->
      <router-link to="/admin/payments" class="menu-card">
        <div class="card-icon payments-bg">💳</div>
        <div class="card-info">
          <h3>Kelola Payments</h3>
          <p>Status pembayaran transaksi dan bukti transfer dari pelanggan.</p>
        </div>
        <span class="card-arrow">→</span>
      </router-link>

    </div>
  </div>
</template>

<style scoped>
.dashboard-container {
  padding: 10px 0;
}

.welcome-text {
  text-align: center;
  margin-bottom: 36px;
}

.welcome-text h2 {
  font-size: 1.8rem;
  font-weight: 800;
  color: #0f172a;
}

.welcome-text p {
  color: #64748b;
  font-size: 0.95rem;
  margin-top: 6px;
}

/* GRID KOTAK */
.menu-grid {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
  gap: 20px;
}

.menu-card {
  background: #ffffff;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  padding: 24px;
  display: flex;
  align-items: center;
  gap: 18px;
  text-decoration: none;
  color: inherit;
  transition: all 0.25 ease;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.02);
  position: relative;
}

.menu-card:hover {
  transform: translateY(-4px);
  border-color: #2ec4b6;
  box-shadow: 0 10px 20px rgba(46, 196, 182, 0.1);
}

.card-icon {
  width: 52px;
  height: 52px;
  border-radius: 12px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.6rem;
  flex-shrink: 0;
}

.items-bg { background-color: #e0f2fe; }
.kategori-bg { background-color: #fef3c7; }
.users-bg { background-color: #f3e8ff; }
.rentals-bg { background-color: #dcfce7; }
.payments-bg { background-color: #ffe4e6; }

.card-info h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #1e293b;
  margin-bottom: 4px;
}

.card-info p {
  font-size: 0.82rem;
  color: #64748b;
  line-height: 1.4;
}

.card-arrow {
  margin-left: auto;
  font-size: 1.2rem;
  color: #cbd5e1;
  font-weight: bold;
  transition: transform 0.2s, color 0.2s;
}

.menu-card:hover .card-arrow {
  color: #2ec4b6;
  transform: translateX(4px);
}
</style>