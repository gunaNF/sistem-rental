<script setup>
import { ref, computed, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

// State Data
const cartItems = ref([])
const isLoading = ref(false)
const errorMessage = ref('')

// File KTP State
const ktpFile = ref(null)
const ktpPreview = ref(null)

// Form State
const form = ref({
  tgl_sewa: '',
  lama_sewa: 1,
  metode_pembayaran: 'qris',
  catatan: ''
})

// Handle Upload File KTP & Preview
const handleFileUpload = (event) => {
  const file = event.target.files[0]
  if (file) {
    ktpFile.value = file
    ktpPreview.value = URL.createObjectURL(file)
  }
}

// Hitung Subtotal & Grand Total
const subtotalPerHari = computed(() => {
  return cartItems.value.reduce((total, item) => total + (item.harga_per_hari * item.qty), 0)
})

const grandTotal = computed(() => {
  return subtotalPerHari.value * (form.value.lama_sewa || 1)
})

const loadCart = () => {
  const savedCart = localStorage.getItem('cart_items')
  if (savedCart) {
    try {
      cartItems.value = JSON.parse(savedCart)
    } catch (e) {
      cartItems.value = []
    }
  }

  if (cartItems.value.length === 0) {
    router.push('/')
  }
}

const formatRupiah = (val) => {
  if (!val || isNaN(val)) return 'Rp 0'
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(val)
}

const minDate = computed(() => {
  const today = new Date()
  return today.toISOString().split('T')[0]
})

// Submit Order ke API Laravel (Menggunakan FormData untuk kirim file)
const processCheckout = async () => {
  if (!form.value.tgl_sewa) {
    errorMessage.value = 'Silakan pilih tanggal mulai sewa.'
    return
  }

  if (!ktpFile.value) {
    errorMessage.value = 'Silakan unggah/upload foto KTP kamu terlebih dahulu.'
    return
  }

  const token = localStorage.getItem('access_token')
  if (!token) {
    alert('Silakan login terlebih dahulu untuk melakukan checkout.')
    router.push('/login')
    return
  }

  isLoading.value = true
  errorMessage.value = ''

  // Menggunakan FormData agar bisa upload file KTP
  const formData = new FormData()
  formData.append('tgl_sewa', form.value.tgl_sewa)
  formData.append('lama_sewa', form.value.lama_sewa)
  formData.append('total_harga', grandTotal.value)
  formData.append('metode_pembayaran', form.value.metode_pembayaran)
  formData.append('catatan', form.value.catatan)
  formData.append('foto_ktp', ktpFile.value) // File KTP
  formData.append('items', JSON.stringify(cartItems.value.map(item => ({
    item_id: item.id,
    qty: item.qty,
    harga_per_hari: item.harga_per_hari
  }))))

  try {
    const apiUrl = `http://${window.location.hostname}:8000/api/rentals`
    await axios.post(apiUrl, formData, {
      headers: {
        Authorization: `Bearer ${token}`,
        'Content-Type': 'multipart/form-data'
      }
    })

    localStorage.removeItem('cart_items')
    window.dispatchEvent(new Event('cart-updated'))

    alert('Pesanan berhasil dibuat! Silakan cek status di Sewa Saya.')
    router.push('/sewa-saya')
  } catch (error) {
    console.error('Gagal checkout:', error)
    errorMessage.value = error.response?.data?.message || 'Gagal memproses pesanan. Coba lagi.'
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  loadCart()
})
</script>

<template>
  <div class="checkout-page">
    <div class="checkout-container">
      <div class="checkout-header">
        <h2>💳 Checkout Persewaan</h2>
        <p>Lengkapi detail penyewaan & identitas diri kamu</p>
      </div>

      <div class="checkout-grid">
        <!-- FORM DETAIL SEWA -->
        <div class="card form-card">
          <h3>📋 Detail Penyewaan</h3>

          <form @submit.prevent="processCheckout">
            <!-- Upload Foto KTP -->
            <div class="form-group">
              <label for="foto_ktp">Foto KTP / Identitas (Wajib)</label>
              <div class="ktp-upload-box">
                <input 
                  type="file" 
                  id="foto_ktp" 
                  accept="image/*" 
                  @change="handleFileUpload" 
                  required 
                />
                <div v-if="ktpPreview" class="ktp-preview">
                  <img :src="ktpPreview" alt="Preview KTP" />
                </div>
                <p v-else class="upload-hint">📁 Klik di sini untuk pilih foto KTP</p>
              </div>
            </div>

            <!-- Tanggal Sewa -->
            <div class="form-group">
              <label for="tgl_sewa">Tanggal Mulai Sewa</label>
              <input 
                type="date" 
                id="tgl_sewa" 
                v-model="form.tgl_sewa" 
                :min="minDate"
                required 
              />
            </div>

            <!-- Durasi / Lama Sewa -->
            <div class="form-group">
              <label for="lama_sewa">Lama Sewa (Hari)</label>
              <div class="duration-picker">
                <button 
                  type="button" 
                  @click="form.lama_sewa > 1 ? form.lama_sewa-- : null"
                  :disabled="form.lama_sewa <= 1"
                >-</button>
                <input type="number" id="lama_sewa" v-model.number="form.lama_sewa" min="1" required />
                <button type="button" @click="form.lama_sewa++">+</button>
              </div>
            </div>

            <!-- Metode Pembayaran -->
            <div class="form-group">
              <label>Metode Pembayaran</label>
              <div class="payment-options">
                <label :class="['pay-option', { active: form.metode_pembayaran === 'qris' }]">
                  <input type="radio" v-model="form.metode_pembayaran" value="qris" />
                  📱 QRIS / E-Wallet
                </label>
                <label :class="['pay-option', { active: form.metode_pembayaran === 'transfer' }]">
                  <input type="radio" v-model="form.metode_pembayaran" value="transfer" />
                  🏦 Transfer Bank
                </label>
                <label :class="['pay-option', { active: form.metode_pembayaran === 'cod' }]">
                  <input type="radio" v-model="form.metode_pembayaran" value="cod" />
                  💵 Bayar di Outlet (COD)
                </label>
              </div>
            </div>

            <!-- Catatan Tambahan -->
            <div class="form-group">
              <label for="catatan">Catatan Tambahan (Opsional)</label>
              <textarea 
                id="catatan" 
                v-model="form.catatan" 
                rows="2" 
                placeholder="Contoh: Ambil jam 10 pagi, butuh pasak ekstra, dll."
              ></textarea>
            </div>

            <div v-if="errorMessage" class="error-msg">
              ⚠️ {{ errorMessage }}
            </div>
          </form>
        </div>

        <!-- RINGKASAN PESANAN -->
        <div class="card summary-card">
          <h3>🛒 Ringkasan Pesanan</h3>

          <div class="items-list">
            <div v-for="item in cartItems" :key="item.id" class="item-row">
              <img :src="item.foto_barang" :alt="item.nama_barang" class="item-img" />
              <div class="item-info">
                <span class="item-name">{{ item.nama_barang }}</span>
                <span class="item-qty">{{ item.qty }}x @ {{ formatRupiah(item.harga_per_hari) }}/hari</span>
              </div>
              <span class="item-total">{{ formatRupiah(item.harga_per_hari * item.qty) }}</span>
            </div>
          </div>

          <hr class="divider" />

          <div class="price-summary">
            <div class="summary-row">
              <span>Sewa per Hari</span>
              <span>{{ formatRupiah(subtotalPerHari) }}</span>
            </div>
            <div class="summary-row">
              <span>Durasi Sewa</span>
              <span>{{ form.lama_sewa }} Hari</span>
            </div>
            <div class="summary-row total-row">
              <span>Total Pembayaran</span>
              <span class="grand-total">{{ formatRupiah(grandTotal) }}</span>
            </div>
          </div>

          <button 
            type="button" 
            class="btn-submit" 
            @click="processCheckout" 
            :disabled="isLoading"
          >
            {{ isLoading ? 'Memproses...' : 'Konfirmasi & Bayar 🚀' }}
          </button>

          <router-link to="/cart" class="btn-back">← Kembali ke Keranjang</router-link>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.checkout-page {
  min-height: 100vh;
  width: 100%;
  background: linear-gradient(rgba(15, 23, 42, 0.65), rgba(15, 23, 42, 0.75)), 
              url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=1920&auto=format&fit=crop') center/cover no-repeat fixed;
}

.checkout-container {
  padding: 50px 6% 80px 6%;
  max-width: 1100px;
  margin: 0 auto;
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: #333;
}

.checkout-header {
  text-align: center;
  margin-bottom: 30px;
}

.checkout-header h2 {
  font-size: 2.2rem;
  font-weight: 800;
  margin-bottom: 6px;
  color: #ffffff;
  text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
}

.checkout-header p {
  color: #e2e8f0;
  font-size: 1rem;
}

.checkout-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 30px;
}

.card {
  background: #ffffff;
  border-radius: 16px;
  padding: 24px;
  border: 1px solid #eaeaea;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
}

.card h3 {
  font-size: 1.2rem;
  font-weight: 700;
  margin-bottom: 20px;
  border-bottom: 1px solid #eee;
  padding-bottom: 10px;
}

.form-group {
  margin-bottom: 20px;
}

.form-group label {
  display: block;
  font-size: 0.85rem;
  font-weight: 700;
  margin-bottom: 8px;
  color: #444;
}

/* KTP UPLOAD BOX STYLE */
.ktp-upload-box {
  border: 2px dashed #cbd5e1;
  border-radius: 12px;
  padding: 12px;
  text-align: center;
  background: #f8fafc;
  cursor: pointer;
  position: relative;
  transition: all 0.2s;
}

.ktp-upload-box:hover {
  border-color: #2ec4b6;
  background: #f0fdfa;
}

.ktp-upload-box input[type="file"] {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  opacity: 0;
  cursor: pointer;
}

.upload-hint {
  font-size: 0.85rem;
  color: #64748b;
  margin: 10px 0;
  font-weight: 600;
}

.ktp-preview img {
  max-width: 100%;
  max-height: 140px;
  border-radius: 8px;
  object-fit: contain;
}

input[type="date"],
textarea {
  width: 100%;
  padding: 10px 14px;
  border-radius: 8px;
  border: 1px solid #ccc;
  font-family: inherit;
  font-size: 0.9rem;
  box-sizing: border-box;
}

.duration-picker {
  display: flex;
  align-items: center;
  gap: 10px;
}

.duration-picker button {
  width: 38px;
  height: 38px;
  background: #f0f0f0;
  border: 1px solid #ccc;
  border-radius: 8px;
  font-weight: bold;
  cursor: pointer;
}

.duration-picker input {
  width: 70px;
  text-align: center;
  padding: 8px;
  border-radius: 8px;
  border: 1px solid #ccc;
}

.payment-options {
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.pay-option {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 12px;
  border: 1px solid #e0e0e0;
  border-radius: 10px;
  cursor: pointer;
  font-size: 0.9rem;
  font-weight: 600;
  transition: all 0.2s;
}

.pay-option.active {
  border-color: #2ec4b6;
  background-color: #e6f9f7;
}

.items-list {
  display: flex;
  flex-direction: column;
  gap: 14px;
  max-height: 250px;
  overflow-y: auto;
}

.item-row {
  display: flex;
  align-items: center;
  gap: 12px;
}

.item-img {
  width: 48px;
  height: 48px;
  border-radius: 8px;
  object-fit: cover;
}

.item-info {
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.item-name {
  font-size: 0.85rem;
  font-weight: 700;
}

.item-qty {
  font-size: 0.75rem;
  color: #777;
}

.item-total {
  font-size: 0.85rem;
  font-weight: 700;
}

.divider {
  border: 0;
  border-top: 1px solid #eee;
  margin: 18px 0;
}

.price-summary {
  display: flex;
  flex-direction: column;
  gap: 10px;
  margin-bottom: 20px;
}

.summary-row {
  display: flex;
  justify-content: space-between;
  font-size: 0.9rem;
  color: #555;
}

.total-row {
  font-size: 1.05rem;
  font-weight: 800;
  color: #1a1a1a;
  padding-top: 8px;
  border-top: 1px dashed #ddd;
}

.grand-total {
  color: #2ec4b6;
}

.btn-submit {
  width: 100%;
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 14px;
  border-radius: 10px;
  font-weight: 800;
  font-size: 1rem;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-submit:hover:not(:disabled) {
  background: #e08b12;
}

.btn-back {
  display: block;
  text-align: center;
  margin-top: 12px;
  font-size: 0.85rem;
  color: #666;
  text-decoration: none;
}

.error-msg {
  color: #e63946;
  font-size: 0.85rem;
  margin-top: 10px;
}

@media (max-width: 768px) {
  .checkout-grid {
    grid-template-columns: 1fr;
  }
}
</style>