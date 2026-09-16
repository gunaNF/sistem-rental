<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

const payments = ref([])
const isLoading = ref(true)
const errorMessage = ref('')
const successMessage = ref('')

// Image Modal Preview State
const selectedImage = ref(null)

// 1. Fetch Data Payment dari API Backend
const fetchPayments = async () => {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const response = await api.get('/payments')
    payments.value = response.data.data || response.data || []
  } catch (err) {
    console.error('Fetch Payments Error:', err)
    errorMessage.value = err.response?.data?.message || 'Gagal mengambil data pembayaran dari server.'
  } finally {
    isLoading.value = false
  }
}

// 2. Update Status Pembayaran (Diverifikasi / Ditolak)
const updateStatus = async (id, statusBaru) => {
  if (!confirm(`Ubah status pembayaran menjadi "${statusBaru}"?`)) return

  try {
    await api.patch(`/payments/${id}/status`, { status_bayar: statusBaru })
    const item = payments.value.find(p => p.id === id)
    if (item) item.status_bayar = statusBaru

    showSuccess(`Status pembayaran berhasil diperbarui menjadi ${statusBaru}.`)
  } catch (err) {
    console.error('Update Status Error:', err)
    alert(err.response?.data?.message || 'Terjadi kesalahan saat memperbarui status.')
  }
}

const showSuccess = (msg) => {
  successMessage.value = msg
  setTimeout(() => { successMessage.value = '' }, 3000)
}

// Format Rupiah Helper
const formatRupiah = (angka) => {
  return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR' }).format(angka || 0)
}

onMounted(() => {
  fetchPayments()
})
</script>

<template>
  <div class="kelola-container">
    <!-- Header Section -->
    <div class="page-header">
      <div>
        <router-link to="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</router-link>
        <h2>Kelola Pembayaran</h2>
        <p class="subtitle">Verifikasi bukti transfer dan kelola status pembayaran transaksi.</p>
      </div>
      <button class="btn-refresh" @click="fetchPayments">🔄 Muat Ulang</button>
    </div>

    <!-- Alert Notifikasi -->
    <div v-if="successMessage" class="success-box">
      ✅ {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="error-box">
      ⚠️ {{ errorMessage }}
    </div>

    <!-- Table Container -->
    <div class="table-card">
      <table class="crud-table">
        <thead>
          <tr>
            <th style="width: 80px;">No</th>
            <th>ID Transaksi</th>
            <th>Metode Bayar</th>
            <th>Jumlah Bayar</th>
            <th>Bukti Transfer</th>
            <th>Tanggal Bayar</th>
            <th>Status</th>
            <th style="width: 180px;" class="text-center">Aksi Verifikasi</th>
          </tr>
        </thead>
        <tbody>
          <!-- State Loading -->
          <tr v-if="isLoading">
            <td colspan="8" class="text-center empty-msg">Memuat data pembayaran...</td>
          </tr>

          <!-- State Data Kosong -->
          <tr v-else-if="payments.length === 0">
            <td colspan="8" class="text-center empty-msg">Belum ada data pembayaran.</td>
          </tr>

          <!-- Data Payments -->
          <tr v-else v-for="(pay, index) in payments" :key="pay.id || index">
            <td class="text-muted">#{{ index + 1 }}</td>
            <td class="font-bold">#{{ pay.id_transaksi }}</td>
            <td>{{ pay.metode_bayar || '-' }}</td>
            <td class="price">{{ formatRupiah(pay.jumlah_bayar) }}</td>
            <td>
              <button 
                v-if="pay.bukti_transfer" 
                @click="selectedImage = pay.bukti_transfer"
                class="btn-preview"
              >
                🖼️ Lihat Bukti
              </button>
              <span v-else class="text-muted">Belum ada</span>
            </td>
            <td>{{ pay.tgl_pembayaran || '-' }}</td>
            <td>
              <span :class="['status-badge', pay.status_bayar]">
                {{ pay.status_bayar || 'pending' }}
              </span>
            </td>
            <td class="text-center">
              <div class="action-buttons">
                <button 
                  @click="updateStatus(pay.id, 'diverifikasi')" 
                  class="btn-action approve"
                  :disabled="pay.status_bayar === 'diverifikasi'"
                >
                  ✓ Setujui
                </button>
                <button 
                  @click="updateStatus(pay.id, 'ditolak')" 
                  class="btn-action reject"
                  :disabled="pay.status_bayar === 'ditolak'"
                >
                  ✕ Tolak
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Preview Bukti Transfer -->
    <div v-if="selectedImage" class="modal-overlay" @click="selectedImage = null">
      <div class="modal-card image-preview-card" @click.stop>
        <h3>Preview Bukti Transfer</h3>
        <img 
          :src="selectedImage.startsWith('http') ? selectedImage : `http://localhost:8000/storage/${selectedImage}`" 
          alt="Bukti Transfer" 
          class="preview-img"
        />
        <div class="modal-actions">
          <button class="btn-cancel" @click="selectedImage = null">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.kelola-container {
  padding: 10px 0;
}

.page-header {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 24px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 6px;
  background-color: #e0f2fe;
  color: #0284c7;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 700;
  padding: 8px 14px;
  border-radius: 8px;
  margin-bottom: 12px;
  transition: all 0.2s ease;
}

.btn-back:hover {
  background-color: #bae6fd;
  color: #0369a1;
}

.page-header h2 {
  font-size: 1.6rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
}

.subtitle {
  color: #64748b;
  font-size: 0.88rem;
  margin-top: 4px;
}

.btn-refresh {
  background: #ffffff;
  color: #334155;
  border: 1px solid #cbd5e1;
  padding: 9px 16px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.88rem;
  cursor: pointer;
  transition: all 0.2s ease;
}

.btn-refresh:hover {
  background: #f1f5f9;
}

/* Alert Box */
.success-box {
  background: #dcfce7;
  border: 1px solid #86efac;
  color: #15803d;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 0.88rem;
  font-weight: 600;
}

.error-box {
  background: #fee2e2;
  border: 1px solid #fca5a5;
  color: #dc2626;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 0.88rem;
  font-weight: 600;
}

/* TABLE STYLING */
.table-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
  overflow: hidden;
}

.crud-table {
  width: 100%;
  border-collapse: collapse;
}

.crud-table th {
  background: #f8fafc;
  padding: 14px 18px;
  font-size: 0.8rem;
  font-weight: 700;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  text-align: left;
  border-bottom: 1px solid #e2e8f0;
}

.crud-table td {
  padding: 16px 18px;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.9rem;
  color: #334155;
  vertical-align: middle;
}

.crud-table tbody tr:hover {
  background-color: #f8fafc;
}

.text-muted { color: #94a3b8; }
.font-bold { font-weight: 700; color: #0f172a; }
.price { font-weight: 700; color: #0f172a; }
.text-center { text-align: center; }

/* Badge Status */
.status-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.78rem;
  font-weight: 700;
  text-transform: capitalize;
  display: inline-block;
}
.status-badge.diverifikasi { background: #dcfce7; color: #15803d; }
.status-badge.ditolak { background: #fee2e2; color: #b91c1c; }
.status-badge.belum_dibayar,
.status-badge.pending { background: #fef3c7; color: #d97706; }

/* Action Buttons */
.action-buttons {
  display: flex;
  justify-content: center;
  gap: 8px;
}

.btn-preview {
  background: #f1f5f9;
  color: #334155;
  border: 1px solid #cbd5e1;
  padding: 5px 10px;
  border-radius: 6px;
  cursor: pointer;
  font-weight: 700;
  font-size: 0.78rem;
  transition: all 0.2s;
}

.btn-preview:hover {
  background: #e2e8f0;
}

.btn-action {
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 0.78rem;
  cursor: pointer;
  transition: opacity 0.2s;
}

.btn-action.approve {
  background: #dcfce7;
  color: #15803d;
}

.btn-action.reject {
  background: #fee2e2;
  color: #dc2626;
}

.btn-action:disabled {
  opacity: 0.4;
  cursor: not-allowed;
}

.btn-action:not(:disabled):hover {
  opacity: 0.8;
}

.empty-msg {
  padding: 30px;
  color: #94a3b8;
}

/* Modal Overlay */
.modal-overlay {
  position: fixed;
  top: 0;
  left: 0;
  width: 100vw;
  height: 100vh;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 100;
}

.modal-card {
  background: #ffffff;
  padding: 24px;
  border-radius: 16px;
  width: 100%;
  max-width: 450px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-card h3 {
  margin: 0 0 16px 0;
  font-size: 1.2rem;
  color: #0f172a;
}

.preview-img {
  width: 100%;
  max-height: 350px;
  object-fit: contain;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  background: #f8fafc;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  margin-top: 16px;
}

.btn-cancel {
  background: #e5e7eb;
  color: #374151;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
}
</style>