<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// State Reaktif
const products = ref([])
const isLoading = ref(true)
const errorMessage = ref('')

// Format angka ke mata uang Rupiah
const formatRupiah = (val) => {
  if (val === undefined || val === null || isNaN(val)) return 'Rp 0'
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(val)
}

// Helper untuk URL Gambar (Mendukung foto_barang / gambar dari storage Laravel)
const getImageUrl = (imagePath) => {
  if (!imagePath) return 'https://via.placeholder.com/600x400?text=No+Image'
  if (imagePath.startsWith('http')) return imagePath
  return `http://${window.location.hostname}:8000/storage/${imagePath}`
}

// Fetch Data dari API Laravel
const fetchProducts = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const apiUrl = `http://${window.location.hostname}:8000/api/items`
    const response = await axios.get(apiUrl)
    
    // Tangkap data dari response.data.data atau response.data
    products.value = response.data.data || response.data
  } catch (error) {
    console.error('Gagal mengambil data katalog:', error)
    errorMessage.value = 'Gagal memuat katalog alat camping. Pastikan backend Laravel aktif.'
  } finally {
    isLoading.value = false
  }
}

// Jalankan fetch data saat halaman di-load
onMounted(() => {
  fetchProducts()
})
</script>

<template>
  <section class="katalog-container">
    <div class="header-section">
      <h2>🔥 Katalog Alat Camping</h2>
      <p>Pilih perlengkapan outdoor berkualitas untuk petualanganmu</p>
    </div>

    <!-- State Loading -->
    <div v-if="isLoading" class="state-container">
      <div class="spinner"></div>
      <p>Memuat data alat camping...</p>
    </div>

    <!-- State Error -->
    <div v-else-if="errorMessage" class="state-container error-text">
      <p>⚠️ {{ errorMessage }}</p>
      <button @click="fetchProducts" class="btn-retry">Coba Lagi</button>
    </div>

    <!-- State Data Kosong -->
    <div v-else-if="products.length === 0" class="state-container">
      <p>Belum ada alat camping yang tersedia saat ini.</p>
    </div>

    <!-- Grid Produk -->
    <div v-else class="product-grid">
      <div v-for="item in products" :key="item.id" class="product-card">
        <div class="image-wrapper">
          <!-- Menggunakan foto_barang atau gambar sesuai model Laravel -->
          <img 
            :src="getImageUrl(item.foto_barang || item.gambar)" 
            :alt="item.nama_barang" 
          />
          <!-- Menggunakan relasi category sesuai fungsi relasi di Model -->
          <span class="badge-kategori">
            {{ item.category?.nama_kategori || item.category?.nama || 'Perlengkapan' }}
          </span>
        </div>

        <div class="card-body">
          <!-- Menggunakan nama_barang -->
          <h3 class="product-title">
            {{ item.nama_barang || 'Tanpa Nama' }}
          </h3>
          
          <!-- Menggunakan stok -->
          <div class="stock-info">
            <span>Stok: <strong>{{ item.stok ?? 0 }}</strong></span>
          </div>

          <div class="card-footer">
            <div class="price">
              <!-- Menggunakan harga_per_hari -->
              <span class="price-val">
                {{ formatRupiah(item.harga_per_hari) }}
              </span>
              <span class="price-unit">/hari</span>
            </div>
            
            <button class="btn-cart">+ Keranjang</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.katalog-container {
  padding: 50px 5%;
  background-color: #f8f9fa;
  color: #333;
  font-family: 'Plus Jakarta Sans', sans-serif;
  width: 100%;
  box-sizing: border-box;
}

.header-section {
  text-align: center;
  margin-bottom: 30px;
}

.header-section h2 {
  font-size: 2rem;
  font-weight: 800;
  color: #1a1a1a;
  margin-bottom: 6px;
}

.header-section p {
  color: #666;
  font-size: 0.95rem;
}

.state-container {
  text-align: center;
  padding: 40px 0;
  color: #666;
}

.error-text {
  color: #e63946;
}

.btn-retry {
  margin-top: 10px;
  padding: 8px 16px;
  background: #2ec4b6;
  color: white;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 600;
}

.spinner {
  width: 35px;
  height: 35px;
  border: 4px solid #e0e0e0;
  border-top: 4px solid #2ec4b6;
  border-radius: 50%;
  animation: spin 1s linear infinite;
  margin: 0 auto 10px auto;
}

@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.product-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr);
  gap: 20px;
}

.product-card {
  background: #ffffff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  display: flex;
  flex-direction: column;
  border: 1px solid #eaeaea;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.image-wrapper {
  position: relative;
  width: 100%;
  height: 200px;
  background-color: #f0f0f0;
}

.image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

.badge-kategori {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(0, 0, 0, 0.65);
  backdrop-filter: blur(4px);
  color: #fff;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 12px;
}

.card-body {
  padding: 14px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.product-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: #222;
  margin-bottom: 6px;
  line-height: 1.3;
}

.stock-info {
  font-size: 0.8rem;
  color: #777;
  margin-bottom: 12px;
}

.card-footer {
  margin-top: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 10px;
  border-top: 1px solid #f0f0f0;
}

.price-val {
  font-size: 0.95rem;
  font-weight: 800;
  color: #2ec4b6;
}

.price-unit {
  font-size: 0.75rem;
  color: #888;
}

.btn-cart {
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 6px 12px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.8rem;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-cart:hover {
  background: #e08b12;
}

@media (max-width: 1024px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr);
  }
}

@media (max-width: 600px) {
  .product-grid {
    grid-template-columns: repeat(1, 1fr);
  }
}
</style>