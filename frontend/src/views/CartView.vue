<script setup>
import { ref, onMounted, computed } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()
const cartItems = ref([])

// Load data keranjang dari LocalStorage
const loadCart = () => {
  const savedCart = localStorage.getItem('cart_items')
  if (savedCart) {
    try {
      cartItems.value = JSON.parse(savedCart)
    } catch (e) {
      cartItems.value = []
    }
  }
}

// Simpan & Trigger Event Update Keranjang
const saveAndSyncCart = () => {
  localStorage.setItem('cart_items', JSON.stringify(cartItems.value))
  window.dispatchEvent(new Event('cart-updated'))
}

// Hapus item dari keranjang
const removeItem = (index) => {
  cartItems.value.splice(index, 1)
  saveAndSyncCart()
}

// Hitung Subtotal per barang (Harga per hari x Jumlah x Durasi)
const calculateSubtotal = (item) => {
  const qty = item.qty || 1
  const days = item.lama_sewa || 1
  return item.harga_per_hari * qty * days
}

// Hitung Total Belanja
const totalPrice = computed(() => {
  return cartItems.value.reduce((acc, item) => acc + calculateSubtotal(item), 0)
})

const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(val || 0)
}

const goBack = () => {
  router.push('/')
}

// Navigasi Langsung ke Halaman Checkout
const proceedToCheckout = () => {
  if (cartItems.value.length === 0) return
  router.push('/checkout')
}

onMounted(() => {
  loadCart()
})
</script>

<template>
  <div class="cart-page">
    <div class="cart-container">
      <!-- Top Header -->
      <div class="cart-header">
        <button type="button" @click="goBack" class="btn-back">
          ← Kembali ke Beranda
        </button>
        <h2>🛒 Keranjang Belanja</h2>
      </div>

      <!-- Jika Keranjang Kosong -->
      <div v-if="cartItems.length === 0" class="empty-cart">
        <div class="empty-icon">🎒</div>
        <h3>Keranjang Anda Masih Kosong</h3>
        <p>Yuk, cari dan sewa alat camping impianmu sekarang!</p>
        <button type="button" @click="goBack" class="btn-primary">
          Lihat Katalog
        </button>
      </div>

      <!-- Jika Keranjang Ada Isi -->
      <div v-else class="cart-content">
        <!-- List Item Keranjang -->
        <div class="cart-list">
          <div v-for="(item, index) in cartItems" :key="index" class="cart-item">
            <img 
              :src="item.foto_barang || item.image_url || 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?q=80&w=300'" 
              :alt="item.nama_barang" 
              class="item-img"
            />
            
            <div class="item-info">
              <h4 class="item-title">{{ item.nama_barang || 'Alat Camping' }}</h4>
              <p class="item-price">{{ formatRupiah(item.harga_per_hari) }} / hari</p>
              
              <div class="item-meta">
                <span>Jumlah: <strong>{{ item.qty || 1 }}</strong></span>
                <span v-if="item.lama_sewa"> • Durasi: <strong>{{ item.lama_sewa }} Hari</strong></span>
              </div>
            </div>

            <div class="item-action">
              <div class="item-subtotal">
                {{ formatRupiah(calculateSubtotal(item)) }}
              </div>
              <button type="button" @click="removeItem(index)" class="btn-delete" title="Hapus Item">
                🗑️ Hapus
              </button>
            </div>
          </div>
        </div>

        <!-- Ringkasan Pembayaran -->
        <div class="cart-summary">
          <h3 class="summary-title">Ringkasan Sewa</h3>
          
          <div class="summary-row">
            <span>Total Item</span>
            <span>{{ cartItems.length }} barang</span>
          </div>
          
          <div class="summary-row total-row">
            <span>Total Bayar</span>
            <strong class="total-price">{{ formatRupiah(totalPrice) }}</strong>
          </div>

          <button type="button" @click="proceedToCheckout" class="btn-checkout">
            Lanjut ke Checkout →
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.cart-page {
  min-height: 100vh;
  background-color: #f4f6f8;
  color: #2b2b2b;
  padding: 40px 20px;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.cart-container {
  max-width: 1000px;
  margin: 0 auto;
  background: #ffffff;
  padding: 32px;
  border-radius: 16px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.cart-header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  border-bottom: 1px solid #eef0f2;
  padding-bottom: 20px;
  margin-bottom: 28px;
}

.cart-header h2 {
  font-size: 1.5rem;
  font-weight: 800;
  margin: 0;
}

.btn-back {
  background: #f0f2f5;
  border: none;
  color: #4a5568;
  padding: 8px 16px;
  border-radius: 10px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-back:hover {
  background: #e2e8f0;
  color: #1a202c;
}

.empty-cart {
  text-align: center;
  padding: 60px 20px;
}

.empty-icon {
  font-size: 4rem;
  margin-bottom: 12px;
}

.empty-cart h3 {
  font-size: 1.3rem;
  font-weight: 700;
  margin-bottom: 8px;
}

.empty-cart p {
  color: #718096;
  margin-bottom: 24px;
}

.cart-content {
  display: grid;
  grid-template-columns: 1fr 340px;
  gap: 28px;
}

@media (max-width: 868px) {
  .cart-content {
    grid-template-columns: 1fr;
  }
}

.cart-list {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.cart-item {
  display: flex;
  align-items: center;
  gap: 16px;
  padding: 16px;
  border: 1px solid #edf2f7;
  border-radius: 12px;
  background: #fafbfc;
}

.item-img {
  width: 80px;
  height: 80px;
  object-fit: cover;
  border-radius: 10px;
}

.item-info {
  flex: 1;
}

.item-title {
  font-size: 1rem;
  font-weight: 700;
  margin: 0 0 4px 0;
}

.item-price {
  color: #2ec4b6;
  font-weight: 700;
  font-size: 0.9rem;
  margin: 0 0 6px 0;
}

.item-meta {
  font-size: 0.8rem;
  color: #718096;
}

.item-action {
  text-align: right;
  display: flex;
  flex-direction: column;
  gap: 8px;
  align-items: flex-end;
}

.item-subtotal {
  font-weight: 800;
  font-size: 1.05rem;
  color: #2d3748;
}

.btn-delete {
  background: #fff5f5;
  color: #e53e3e;
  border: 1px solid #fed7d7;
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-delete:hover {
  background: #fed7d7;
}

.cart-summary {
  background: #f8fafc;
  border: 1px solid #e2e8f0;
  padding: 24px;
  border-radius: 14px;
  height: fit-content;
}

.summary-title {
  font-size: 1.1rem;
  font-weight: 800;
  margin: 0 0 16px 0;
  border-bottom: 1px solid #e2e8f0;
  padding-bottom: 12px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
  color: #4a5568;
  margin-bottom: 12px;
}

.summary-row.total-row {
  font-size: 1.1rem;
  color: #1a202c;
  border-top: 1px dashed #cbd5e0;
  padding-top: 12px;
  margin-top: 16px;
}

.total-price {
  color: #ff9f1c;
}

.btn-primary, .btn-checkout {
  width: 100%;
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 12px;
  border-radius: 10px;
  font-size: 0.95rem;
  font-weight: 800;
  cursor: pointer;
  margin-top: 16px;
  box-shadow: 0 4px 12px rgba(255, 159, 28, 0.3);
  transition: transform 0.1s;
}

.btn-primary:hover, .btn-checkout:hover {
  transform: translateY(-1px);
  background: #f2930f;
}
</style>