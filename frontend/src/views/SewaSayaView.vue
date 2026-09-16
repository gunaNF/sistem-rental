<template>
  <div class="sewa-saya-page">
    <div class="container">
      <!-- Navigasi Tombol Kembali ke Katalog -->
      <div class="nav-back">
        <router-link to="/" class="btn-back">
          ← Kembali ke Katalog
        </router-link>
      </div>

      <div class="header-section">
        <h2>📦 Riwayat Sewa Saya</h2>
        <p>Pantau status pesanan dan lakukan pembayaran di sini</p>
      </div>

      <!-- Loading State -->
      <div v-if="isLoading" class="loading-state">
        <p>Memuat data transaksi...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="rentals.length === 0" class="empty-state">
        <p>Belum ada transaksi penyewaan.</p>
        <router-link to="/" class="btn-primary">Mulai Sewa Alat</router-link>
      </div>

      <!-- Transaksi List -->
      <div v-else class="rentals-list">
        <div v-for="rental in rentals" :key="rental.id" class="rental-card">
          <div class="card-header">
            <div>
              <span class="kode-transaksi">{{ rental.kode_transaksi }}</span>
              <span class="tgl-order">Dibuat pada: {{ formatDate(rental.created_at) }}</span>
            </div>
            <span :class="['badge-status', rental.status_transaksi]">
              {{ rental.status_transaksi.toUpperCase() }}
            </span>
          </div>

          <div class="card-body">
            <!-- Detail Barang -->
            <div class="items-section">
              <h4>Barang yang Disewa:</h4>
              <div v-for="detail in rental.rental_items" :key="detail.id" class="item-row">
                <div class="item-info">
                  <span class="item-name">{{ detail.item?.nama_barang || 'Barang' }}</span>
                  <span class="item-qty">{{ detail.jumlah }}x @ {{ formatRupiah(detail.item?.harga_per_hari) }} /hari</span>
                </div>
                <span class="item-subtotal">{{ formatRupiah(detail.subtotal) }}</span>
              </div>
            </div>

            <hr class="divider" />

            <!-- Informasi Sewa -->
            <div class="info-grid">
              <div>
                <small>Tanggal Mulai Sewa</small>
                <p>{{ formatDate(rental.tgl_mulai_sewa) }}</p>
              </div>
              <div>
                <small>Tanggal Selesai Sewa</small>
                <p>{{ formatDate(rental.tgl_selesai_sewa) }}</p>
              </div>
              <div>
                <small>Total Biaya</small>
                <p class="total-price">{{ formatRupiah(rental.total_harga) }}</p>
              </div>
            </div>

            <!-- Detail Pembayaran & KTP -->
            <div v-if="rental.payment" class="payment-info-box">
              <div class="payment-row">
                <span class="label">Metode Pembayaran:</span>
                <span class="value uppercase">{{ rental.payment.metode_bayar || '-' }}</span>
              </div>
              <div class="payment-row">
                <span class="label">Status Pembayaran:</span>
                <span :class="['badge-bayar', rental.payment.status_bayar]">
                  {{ formatStatusBayar(rental.payment.status_bayar) }}
                </span>
              </div>

              <!-- Tombol Buka Preview Gambar Bukti/KTP -->
              <div v-if="rental.payment.bukti_transfer" class="payment-row file-row">
                <span class="label">Berkas Lampiran:</span>
                <button @click="openImageModal(rental.payment.bukti_transfer)" class="btn-preview-file">
                  📄 Lihat Bukti / KTP
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- MODAL POP-UP PREVIEW BUKTI / KTP -->
    <div v-if="selectedImage" class="modal-overlay" @click.self="closeImageModal">
      <div class="modal-content">
        <div class="modal-header">
          <h3>Pratinjau Berkas Lampiran</h3>
          <button @click="closeImageModal" class="btn-close-modal">✕ Kembali</button>
        </div>
        <div class="modal-body">
          <img :src="selectedImage" alt="Bukti / KTP" class="modal-image" />
        </div>
        <div class="modal-footer">
          <button @click="closeImageModal" class="btn-modal-back">← Kembali ke Riwayat</button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()
const rentals = ref([])
const isLoading = ref(true)
const selectedImage = ref(null)

const fetchRentals = async () => {
  const token = localStorage.getItem('access_token') || localStorage.getItem('token')
  if (!token) {
    router.push('/login')
    return
  }

  try {
    const apiUrl = `http://${window.location.hostname}:8000/api/rentals`
    const response = await axios.get(apiUrl, {
      headers: { Authorization: `Bearer ${token}` }
    })
    
    if (response.data?.data) {
      rentals.value = response.data.data
    }
  } catch (error) {
    console.error('Gagal mengambil data sewa:', error)
  } finally {
    isLoading.value = false
  }
}

const openImageModal = (path) => {
  selectedImage.value = getImageUrl(path)
}

const closeImageModal = () => {
  selectedImage.value = null
}

const formatRupiah = (val) => {
  if (!val || isNaN(val)) return 'Rp 0'
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(val)
}

const formatDate = (dateStr) => {
  if (!dateStr) return '-'
  const options = { year: 'numeric', month: 'short', day: 'numeric' }
  return new Date(dateStr).toLocaleDateString('id-ID', options)
}

const formatStatusBayar = (status) => {
  if (!status) return '-'
  return status.replace('_', ' ').toUpperCase()
}

const getImageUrl = (path) => {
  if (!path) return '#'
  if (path.startsWith('http://') || path.startsWith('https://')) return path
  const cleanPath = path.startsWith('/') ? path.substring(1) : path
  return `http://${window.location.hostname}:8000/storage/${cleanPath}`
}

onMounted(() => {
  fetchRentals()
})
</script>

<style scoped>
.sewa-saya-page {
  min-height: 100vh;
  padding: 40px 20px;
  background-color: #f8fafc;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.container {
  max-width: 800px;
  margin: 0 auto;
}

.nav-back {
  margin-bottom: 20px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #475569;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  padding: 8px 16px;
  background: #ffffff;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.btn-back:hover {
  background: #f1f5f9;
  color: #0f172a;
  border-color: #94a3b8;
}

.header-section {
  margin-bottom: 30px;
  text-align: center;
}

.header-section h2 {
  font-size: 1.8rem;
  font-weight: 800;
  color: #0f172a;
}

.header-section p {
  color: #64748b;
  margin-top: 4px;
}

.loading-state, .empty-state {
  text-align: center;
  padding: 60px 20px;
  background: white;
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
}

.rentals-list {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.rental-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
  overflow: hidden;
}

.card-header {
  background: #f8fafc;
  padding: 16px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e2e8f0;
}

.kode-transaksi {
  font-weight: 800;
  color: #1e293b;
  display: block;
}

.tgl-order {
  font-size: 0.8rem;
  color: #64748b;
}

.badge-status {
  padding: 6px 12px;
  border-radius: 20px;
  font-size: 0.75rem;
  font-weight: 700;
}

.badge-status.menunggu { background: #fef3c7; color: #d97706; }
.badge-status.disewa { background: #dbeafe; color: #2563eb; }
.badge-status.selesai { background: #dcfce7; color: #16a34a; }
.badge-status.dibatalkan { background: #fee2e2; color: #dc2626; }

.card-body {
  padding: 20px;
}

.items-section h4 {
  font-size: 0.85rem;
  color: #64748b;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin-bottom: 12px;
}

.item-row {
  display: flex;
  justify-content: space-between;
  margin-bottom: 8px;
  font-size: 0.95rem;
}

.item-name {
  font-weight: 600;
  color: #0f172a;
}

.item-qty {
  font-size: 0.85rem;
  color: #64748b;
  margin-left: 8px;
}

.item-subtotal {
  font-weight: 600;
  color: #0f172a;
}

.divider {
  border: 0;
  border-top: 1px solid #f1f5f9;
  margin: 16px 0;
}

.info-grid {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  gap: 15px;
}

.info-grid small {
  color: #64748b;
  font-size: 0.75rem;
}

.info-grid p {
  font-weight: 700;
  color: #0f172a;
  margin-top: 4px;
}

.total-price {
  color: #0D9488 !important;
}

.payment-info-box {
  margin-top: 20px;
  padding: 16px;
  background: #f8fafc;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  display: flex;
  flex-direction: column;
  gap: 10px;
}

.payment-row {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 0.875rem;
}

.payment-row .label {
  color: #64748b;
}

.payment-row .value {
  font-weight: 600;
  color: #0f172a;
}

.uppercase {
  text-transform: uppercase;
}

.badge-bayar {
  padding: 4px 10px;
  border-radius: 6px;
  font-size: 0.75rem;
  font-weight: 700;
}

.badge-bayar.belum_dibayar { background: #fee2e2; color: #dc2626; }
.badge-bayar.diverifikasi { background: #dcfce7; color: #16a34a; }
.badge-bayar.ditolak { background: #fef3c7; color: #d97706; }

.btn-preview-file {
  background: none;
  border: none;
  color: #2563eb;
  font-weight: 700;
  cursor: pointer;
  font-size: 0.875rem;
}

.btn-preview-file:hover {
  text-decoration: underline;
}

/* MODAL STYLES */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(15, 23, 42, 0.75);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 9999;
  padding: 20px;
  box-sizing: border-box;
}

.modal-content {
  background: white;
  border-radius: 16px;
  max-width: 600px;
  width: 100%;
  overflow: hidden;
  box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2);
  display: flex;
  flex-direction: column;
}

.modal-header {
  padding: 16px 20px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  border-bottom: 1px solid #e2e8f0;
}

.modal-header h3 {
  font-size: 1.1rem;
  font-weight: 700;
  color: #0f172a;
  margin: 0;
}

.btn-close-modal {
  background: #f1f5f9;
  border: none;
  font-weight: 700;
  color: #64748b;
  padding: 6px 12px;
  border-radius: 6px;
  cursor: pointer;
}

.btn-close-modal:hover {
  background: #e2e8f0;
  color: #0f172a;
}

.modal-body {
  padding: 20px;
  display: flex;
  justify-content: center;
  background: #f8fafc;
  max-height: 70vh;
  overflow-y: auto;
}

.modal-image {
  max-width: 100%;
  max-height: 60vh;
  object-fit: contain;
  border-radius: 8px;
}

.modal-footer {
  padding: 16px 20px;
  display: flex;
  justify-content: flex-end;
  border-top: 1px solid #e2e8f0;
}

.btn-modal-back {
  background: #0d9488;
  color: white;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
}

.btn-modal-back:hover {
  background: #0f766e;
}
</style>