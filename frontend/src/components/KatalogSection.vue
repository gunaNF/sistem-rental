<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

// State Reaktif
const products = ref([])
const isLoading = ref(true)
const errorMessage = ref('')

// State Toast Notification Modern
const toast = ref({
  show: false,
  title: '',
  message: '',
  type: 'success' // 'success' | 'warning' | 'info'
})

// Trigger Notifikasi Toast
const triggerToast = (title, message, type = 'success') => {
  toast.value = { show: true, title, message, type }
  setTimeout(() => {
    toast.value.show = false
  }, 3000)
}

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

// Fungsi Tambah Barang ke Keranjang (LocalStorage)
const addToCart = (item) => {
  // Cek jika stok habis
  if (item.stok <= 0) {
    triggerToast('Stok Habis 🎒', 'Maaf, perlengkapan ini sedang tidak tersedia.', 'warning')
    return
  }
  //Simpan ke LocalStorage
  localStorage.setItem('cart_items', JSON.stringify(cart))

  // FIRING EVENT: Memberitahu Navbar bahwa data keranjang telah diperbarui
  window.dispatchEvent(new Event('cart-updated'))

  // Ambil data keranjang saat ini dari LocalStorage
  const savedCart = localStorage.getItem('cart_items')
  let cart = savedCart ? JSON.parse(savedCart) : []

  // Cek apakah produk sudah ada di keranjang
  const existingIndex = cart.findIndex((cartItem) => cartItem.id === item.id)

  if (existingIndex !== -1) {
    // Jika stok mencukupi, tambah kuantitas
    if (cart[existingIndex].qty < item.stok) {
      cart[existingIndex].qty += 1
      triggerToast('Kuantitas Bertambah ⚡', `Jumlah ${item.nama_barang} di keranjang diperbarui.`, 'info')
    } else {
      triggerToast('Batas Stok Maksimal ⚠️', `Stok barang ini hanya tersedia ${item.stok} unit.`, 'warning')
      return
    }
  } else {
    // Jika belum ada, masukkan item baru
    cart.push({
      id: item.id,
      nama_barang: item.nama_barang,
      harga_per_hari: item.harga_per_hari,
      foto_barang: getImageUrl(item.foto_barang || item.gambar),
      qty: 1,
      lama_sewa: 1
    })
    triggerToast('Masuk Keranjang ✨', `${item.nama_barang} siap untuk diproses.`, 'success')
  }

  // Simpan kembali ke LocalStorage
  localStorage.setItem('cart_items', JSON.stringify(cart))
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
            
            <!-- Event Handler Click -->
            <button 
              type="button"
              class="btn-cart" 
              @click="addToCart(item)"
              :disabled="item.stok <= 0"
            >
              {{ item.stok > 0 ? '+ Keranjang' : 'Habis' }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notification Melayang Elegan -->
    <transition name="toast-slide">
      <div v-if="toast.show" :class="['custom-toast', toast.type]">
        <div class="toast-content">
          <span class="toast-title">{{ toast.title }}</span>
          <p class="toast-message">{{ toast.message }}</p>
        </div>
      </div>
    </transition>
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
  position: relative;
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
  transition: background 0.2s, opacity 0.2s;
}

.btn-cart:hover:not(:disabled) {
  background: #e08b12;
}

.btn-cart:disabled {
  background: #ccc;
  cursor: not-allowed;
  opacity: 0.7;
}

/* --- Toast Notification Styles --- */
.custom-toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  min-width: 280px;
  max-width: 360px;
  padding: 14px 18px;
  border-radius: 12px;
  background: #ffffff;
  color: #2b2b2b;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
  border-left: 5px solid #2ec4b6;
  display: flex;
  align-items: center;
}

.custom-toast.success {
  border-left-color: #2ec4b6;
}

.custom-toast.warning {
  border-left-color: #e63946;
}

.custom-toast.info {
  border-left-color: #ff9f1c;
}

.toast-content {
  display: flex;
  flex-direction: column;
}

.toast-title {
  font-size: 0.9rem;
  font-weight: 800;
  color: #1a1a1a;
  margin-bottom: 2px;
}

.toast-message {
  font-size: 0.8rem;
  color: #666;
  margin: 0;
}

/* Toast Animation */
.toast-slide-enter-active,
.toast-slide-leave-active {
  transition: all 0.3s ease;
}

.toast-slide-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.toast-slide-leave-to {
  opacity: 0;
  transform: translateY(20px);
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
  
  .custom-toast {
    right: 16px;
    bottom: 16px;
    left: 16px;
    max-width: none;
  }
}
</style>