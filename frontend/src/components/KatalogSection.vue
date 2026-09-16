<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import axios from 'axios'

// State Reaktif
const products = ref([])
const categories = ref([])
const selectedCategoryId = ref(null) // null = Semua Kategori
const isLoading = ref(true)
const errorMessage = ref('')
const isAdmin = ref(false) // <-- Tambahan state untuk cek admin

// State Toast Notification
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

// Helper URL Gambar
const getImageUrl = (imagePath) => {
  if (!imagePath) return 'https://via.placeholder.com/600x400?text=No+Image'
  if (imagePath.startsWith('http')) return imagePath
  return `http://${window.location.hostname}:8000/storage/${imagePath}`
}

// Fetch Data Barang & Kategori dari API Laravel
const fetchData = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const host = window.location.hostname
    const [resItems, resCategories] = await Promise.all([
      axios.get(`http://${host}:8000/api/items`),
      axios.get(`http://${host}:8000/api/categories`)
    ])
    
    products.value = resItems.data?.data || resItems.data || []
    categories.value = resCategories.data?.data || resCategories.data || []
  } catch (error) {
    console.error('Gagal mengambil data katalog:', error)
    errorMessage.value = 'Gagal memuat katalog alat camping. Pastikan server backend aktif.'
  } finally {
    isLoading.value = false
  }
}

// Filter Produk Berdasarkan Kategori
const filteredProducts = computed(() => {
  if (selectedCategoryId.value === null || selectedCategoryId.value === undefined) {
    return products.value
  }

  return products.value.filter(item => {
    const itemCatId = item.category_id ?? item.kategori_id ?? item.id_kategori ?? item.category?.id ?? item.kategori?.id
    return String(itemCatId) === String(selectedCategoryId.value)
  })
})

// Pilihan Kategori Tab
const filterCategory = (catId) => {
  selectedCategoryId.value = catId
}

// Handler Sinkronisasi Event Filter dari Navbar
const handleExternalFilter = (event) => {
  selectedCategoryId.value = event.detail
  
  const catalogEl = document.getElementById('katalog')
  if (catalogEl) {
    catalogEl.scrollIntoView({ behavior: 'smooth' })
  }
}

// Fungsi Tambah Barang ke Keranjang
const addToCart = (item) => {
  // Validasi tambahan di frontend: Blokir jika user adalah admin
  if (isAdmin.value) {
    triggerToast('Akses Ditolak', 'Akun admin tidak dapat menyewa barang.', 'warning')
    return
  }

  const itemStock = item.stok ?? 0
  if (itemStock <= 0) {
    triggerToast('Stok Habis', 'Maaf, perlengkapan ini sedang tidak tersedia.', 'warning')
    return
  }

  const savedCart = localStorage.getItem('cart_items')
  let cart = savedCart ? JSON.parse(savedCart) : []

  const existingIndex = cart.findIndex((cartItem) => cartItem.id === item.id)

  if (existingIndex !== -1) {
    if (cart[existingIndex].qty < itemStock) {
      cart[existingIndex].qty += 1
      triggerToast('Kuantitas Bertambah', `Jumlah ${item.nama_barang || item.nama_item || item.name} di keranjang diperbarui.`, 'info')
    } else {
      triggerToast('Batas Stok Maksimal', `Stok barang ini hanya tersedia ${itemStock} unit.`, 'warning')
      return
    }
  } else {
    cart.push({
      id: item.id,
      nama_barang: item.nama_barang || item.nama_item || item.name,
      harga_per_hari: item.harga_per_hari || item.harga_sewa_per_hari || item.harga,
      foto_barang: getImageUrl(item.foto_barang || item.gambar),
      qty: 1,
      lama_sewa: 1
    })
    triggerToast('Masuk Keranjang', `${item.nama_barang || item.nama_item || item.name} berhasil ditambahkan.`, 'success')
  }

  localStorage.setItem('cart_items', JSON.stringify(cart))
  window.dispatchEvent(new Event('cart-updated'))
}

onMounted(() => {
  fetchData()
  window.addEventListener('filter-category', handleExternalFilter)

  // Cek role user dari localStorage saat komponen dimuat
  try {
    const userData = JSON.parse(localStorage.getItem('user'))
    if (userData && (userData.peran === 'admin' || userData.role === 'admin')) {
      isAdmin.value = true
    }
  } catch (e) {
    console.error('Gagal membaca data user dari localStorage', e)
  }
})

onUnmounted(() => {
  window.removeEventListener('filter-category', handleExternalFilter)
})
</script>

<template>
  <section class="katalog-container" id="katalog">
    <div class="header-section">
      <h2>Katalog Alat Camping</h2>
      <p>Pilih perlengkapan outdoor berkualitas untuk petualangan Anda</p>

      <!-- Filter Tab Kategori -->
      <div v-if="categories.length > 0" class="category-tabs">
        <button 
          type="button"
          :class="['tab-btn', { active: selectedCategoryId === null }]" 
          @click="filterCategory(null)"
        >
          Semua
        </button>

        <button 
          v-for="cat in categories" 
          :key="cat.id" 
          type="button"
          :class="['tab-btn', { active: String(selectedCategoryId) === String(cat.id) }]" 
          @click="filterCategory(cat.id)"
        >
          {{ cat.nama_kategori || cat.nama || cat.name }}
        </button>
      </div>
    </div>

    <!-- State Loading -->
    <div v-if="isLoading" class="state-container">
      <div class="spinner"></div>
      <p>Memuat data alat camping...</p>
    </div>

    <!-- State Error -->
    <div v-else-if="errorMessage" class="state-container error-text">
      <p>{{ errorMessage }}</p>
      <button @click="fetchData" class="btn-retry">Coba Lagi</button>
    </div>

    <!-- State Data Kosong -->
    <div v-else-if="filteredProducts.length === 0" class="state-container">
      <p>Belum ada alat camping yang tersedia untuk kategori ini.</p>
    </div>

    <!-- Grid Produk -->
    <div v-else class="product-grid">
      <div v-for="item in filteredProducts" :key="item.id" class="product-card">
        <div class="image-wrapper">
          <img 
            :src="getImageUrl(item.foto_barang || item.gambar)" 
            :alt="item.nama_barang || item.nama_item || item.name" 
          />
          <span class="badge-kategori">
            {{ item.category?.nama_kategori || item.kategori?.nama_kategori || item.category?.nama || 'Perlengkapan' }}
          </span>
        </div>

        <div class="card-body">
          <h3 class="product-title">
            {{ item.nama_barang || item.nama_item || item.name || 'Tanpa Nama' }}
          </h3>
          
          <div class="stock-info">
            <span>Stok: <strong>{{ item.stok ?? 0 }}</strong></span>
          </div>

          <div class="card-footer">
            <div class="price">
              <span class="price-val">
                {{ formatRupiah(item.harga_per_hari || item.harga_sewa_per_hari || item.harga) }}
              </span>
              <span class="price-unit">/hari</span>
            </div>
            
            <!-- Tombol Keranjang (Disabled jika Admin) -->
            <button 
              type="button"
              class="btn-cart" 
              @click="addToCart(item)"
              :disabled="isAdmin || (item.stok ?? 0) <= 0"
            >
              {{ isAdmin ? 'Khusus Customer' : ((item.stok ?? 0) > 0 ? '+ Keranjang' : 'Habis') }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Toast Notification -->
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

.category-tabs {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 10px;
  flex-wrap: wrap;
  margin-top: 20px;
}

.tab-btn {
  padding: 8px 20px;
  border-radius: 20px;
  border: 1px solid #cbd5e1;
  background: #ffffff;
  color: #475569;
  font-weight: 600;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.tab-btn:hover {
  background: #f1f5f9;
  border-color: #2ec4b6;
  color: #0d9488;
}

.tab-btn.active {
  background: #0d9488;
  color: #ffffff;
  border-color: #0d9488;
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
  border-radius: 12px;
  overflow: hidden;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  display: flex;
  flex-direction: column;
  border: 1px solid #eaeaea;
}

.product-card:hover {
  transform: translateY(-3px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

.image-wrapper {
  position: relative;
  width: 100%;
  height: 190px;
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
  background: rgba(15, 23, 42, 0.75);
  color: #fff;
  font-size: 0.7rem;
  font-weight: 600;
  padding: 4px 10px;
  border-radius: 6px;
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
  color: #1e293b;
  margin-bottom: 6px;
  line-height: 1.3;
}

.stock-info {
  font-size: 0.8rem;
  color: #64748b;
  margin-bottom: 12px;
}

.card-footer {
  margin-top: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 10px;
  border-top: 1px solid #f1f5f9;
}

.price-val {
  font-size: 0.95rem;
  font-weight: 800;
  color: #0d9488;
}

.price-unit {
  font-size: 0.75rem;
  color: #64748b;
}

.btn-cart {
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 0.8rem;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-cart:hover:not(:disabled) {
  background: #e08b12;
}

.btn-cart:disabled {
  background: #cbd5e1;
  cursor: not-allowed;
}

/* Toast Styles */
.custom-toast {
  position: fixed;
  bottom: 24px;
  right: 24px;
  z-index: 9999;
  min-width: 260px;
  max-width: 340px;
  padding: 12px 16px;
  border-radius: 8px;
  background: #ffffff;
  color: #1e293b;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
  border-left: 4px solid #0d9488;
  display: flex;
  align-items: center;
}

.custom-toast.success { border-left-color: #0d9488; }
.custom-toast.warning { border-left-color: #ef4444; }
.custom-toast.info { border-left-color: #ff9f1c; }

.toast-content { display: flex; flex-direction: column; }
.toast-title { font-size: 0.85rem; font-weight: 700; color: #0f172a; margin-bottom: 2px; }
.toast-message { font-size: 0.8rem; color: #64748b; margin: 0; }

.toast-slide-enter-active,
.toast-slide-leave-active { transition: all 0.3s ease; }
.toast-slide-enter-from,
.toast-slide-leave-to { opacity: 0; transform: translateY(20px); }

@media (max-width: 1024px) {
  .product-grid { grid-template-columns: repeat(2, 1fr); }
}

@media (max-width: 600px) {
  .product-grid { grid-template-columns: repeat(1, 1fr); }
  .custom-toast { right: 16px; bottom: 16px; left: 16px; max-width: none; }
}
</style>