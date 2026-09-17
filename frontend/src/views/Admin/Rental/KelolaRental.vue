<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

const rentals = ref([])
const isLoading = ref(true)
const errorMessage = ref('')
const successMessage = ref('')

// Modal Detail State
const selectedRental = ref(null)

// 1. Fetch Data Rental/Sewa dari API Backend
const fetchRentals = async () => {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const response = await api.get('/rentals')
    rentals.value = response.data.data || response.data || []
  } catch (err) {
    console.error('Fetch Rentals Error:', err)
    errorMessage.value = err.response?.data?.message || 'Gagal mengambil data transaksi sewa dari server.'
  } finally {
    isLoading.value = false
  }
}

// 2. Update Status Transaksi Sewa (Diubah dari patch ke put)
const updateStatus = async (id, statusBaru) => {
  if (!confirm(`Ubah status transaksi menjadi "${statusBaru}"?`)) return

  try {
    await api.put(`/rentals/${id}/status`, { status_transaksi: statusBaru })
    const item = rentals.value.find(r => r.id === id)
    if (item) item.status_transaksi = statusBaru

    showSuccess(`Status transaksi berhasil diperbarui menjadi ${statusBaru}.`)
  } catch (err) {
    console.error('Update Status Error:', err)
    alert(err.response?.data?.message || 'Terjadi kesalahan saat memperbarui status transaksi.')
    fetchRentals() // Reset data jika gagal
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

// 3. Fungsi Cetak Nota / Struk Sewa (Thermal Style)
const printReceipt = (rental) => {
  const printWindow = window.open('', '_blank', 'width=400,height=600')
  
  const itemsHtml = rental.rental_items && rental.rental_items.length > 0
    ? rental.rental_items.map(item => `
        <tr>
          <td>${item.item?.nama_barang || item.nama_barang || 'Barang'}</td>
          <td>x${item.jumlah || 1}</td>
        </tr>
      `).join('')
    : '<tr><td colspan="2">Tidak ada rincian barang</td></tr>'

  printWindow.document.write(`
    <html>
      <head>
        <title>Nota Sewa - ${rental.kode_transaksi}</title>
        <style>
          body { font-family: monospace; font-size: 12px; padding: 10px; color: #000; }
          .header { text-align: center; margin-bottom: 15px; }
          .header h2 { margin: 0; font-size: 16px; }
          .divider { border-bottom: 1px dashed #000; margin: 10px 0; }
          .info p { margin: 4px 0; }
          table { width: 100%; border-collapse: collapse; margin-top: 10px; }
          th, td { text-align: left; padding: 4px; font-size: 11px; border-bottom: 1px dashed #ccc; }
          .total { text-align: right; font-weight: bold; margin-top: 10px; font-size: 13px; }
          .footer { text-align: center; margin-top: 20px; font-size: 10px; }
        </style>
      </head>
      <body>
        <div class="header">
          <h2>FORREST RENT</h2>
          <p>Penyewaan Alat Camping - Bansel</p>
        </div>
        <div class="divider"></div>
        <div class="info">
          <p><strong>Kode:</strong> ${rental.kode_transaksi}</p>
          <p><strong>Penyewa:</strong> ${rental.user?.nama || 'Customer'}</p>
          <p><strong>Periode:</strong> ${rental.tgl_mulai_sewa} s/d ${rental.tgl_selesai_sewa}</p>
          <p><strong>Status:</strong> ${rental.status_transaksi}</p>
        </div>
        <div class="divider"></div>
        <table>
          <thead>
            <tr>
              <th>Item</th>
              <th>Jml</th>
            </tr>
          </thead>
          <tbody>
            ${itemsHtml}
          </tbody>
        </table>
        <div class="total">
          Total: ${formatRupiah(rental.total_harga)}
        </div>
        <div class="divider"></div>
        <div class="footer">
          <p>Terima kasih telah menyewa di Forrest Rent!</p>
          <p>Barang harap dicek kembali saat pengambilan.</p>
        </div>
      </body>
    </html>
  `)
  
  printWindow.document.close()
  printWindow.focus()
  setTimeout(() => {
    printWindow.print()
    printWindow.close()
  }, 500)
}

// 4. Fungsi Export Data ke Excel (CSV)
const exportToExcel = () => {
  if (!rentals.value || rentals.value.length === 0) {
    alert('Tidak ada data transaksi untuk diexport.')
    return
  }

  let csvContent = "data:text/csv;charset=utf-8," 
    + "No,Kode Transaksi,Nama Penyewa,Email,Tgl Mulai,Tgl Selesai,Total Harga,Status\n"

  rentals.value.forEach((rental, index) => {
    const row = [
      index + 1,
      `"${rental.kode_transaksi || ''}"`,
      `"${rental.user?.nama || 'Customer'}"`,
      `"${rental.user?.email || '-'}"`,
      `"${rental.tgl_mulai_sewa || ''}"`,
      `"${rental.tgl_selesai_sewa || ''}"`,
      rental.total_harga || 0,
      `"${rental.status_transaksi || ''}"`
    ]
    csvContent += row.join(",") + "\n"
  })

  const encodedUri = encodeURI(csvContent)
  const link = document.createElement("a")
  link.setAttribute("href", encodedUri)
  link.setAttribute("download", `Laporan_Transaksi_ForrestRent_${new Date().toISOString().slice(0, 10)}.csv`)
  document.body.appendChild(link)
  link.click()
  document.body.removeChild(link)
}

onMounted(() => {
  fetchRentals()
})
</script>

<template>
  <div class="kelola-container">
    <!-- Header Section -->
    <div class="page-header">
      <div>
        <router-link to="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</router-link>
        <h2>Kelola Transaksi Sewa</h2>
        <p class="subtitle">Pantau seluruh pemesanan sewa, status transaksi, dan detail penyewa.</p>
      </div>
      <div style="display: flex; gap: 8px; align-items: center;">
        <!-- Tombol Export Excel -->
        <button class="btn-excel" @click="exportToExcel">
          📊 Unduh Excel
        </button>
        <button class="btn-refresh" @click="fetchRentals">🔄 Muat Ulang</button>
      </div>
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
            <th style="width: 70px;">No</th>
            <th>Kode Transaksi</th>
            <th>Penyewa</th>
            <th>Tgl Mulai</th>
            <th>Tgl Selesai</th>
            <th>Total Harga</th>
            <th>Status Transaksi</th>
            <th style="width: 220px;" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- State Loading -->
          <tr v-if="isLoading">
            <td colspan="8" class="text-center empty-msg">Memuat data transaksi sewa...</td>
          </tr>

          <!-- State Data Kosong -->
          <tr v-else-if="rentals.length === 0">
            <td colspan="8" class="text-center empty-msg">Belum ada data transaksi sewa.</td>
          </tr>

          <!-- Data Rentals -->
          <tr v-else v-for="(rental, index) in rentals" :key="rental.id || index">
            <td class="text-muted">#{{ index + 1 }}</td>
            <td class="font-bold text-code">{{ rental.kode_transaksi }}</td>
            <td>
              <div class="user-info">
                <strong>{{ rental.user?.nama || 'Pengguna #' + rental.id_pengguna }}</strong>
                <small class="text-muted" v-if="rental.user?.email">{{ rental.user?.email }}</small>
              </div>
            </td>
            <td>{{ rental.tgl_mulai_sewa || '-' }}</td>
            <td>{{ rental.tgl_selesai_sewa || '-' }}</td>
            <td class="price">{{ formatRupiah(rental.total_harga) }}</td>
            <td>
              <span :class="['status-badge', rental.status_transaksi]">
                {{ rental.status_transaksi || 'menunggu' }}
              </span>
            </td>
            <td class="text-center">
              <div class="action-buttons">
                <button @click="selectedRental = rental" class="btn-action detail">
                  👁️ Detail
                </button>
                <!-- Opsi Select Status -->
                <select 
                  :value="rental.status_transaksi" 
                  @change="updateStatus(rental.id, $event.target.value)"
                  class="status-select"
                >
                  <option value="menunggu">Menunggu</option>
                  <option value="disewa">Disewa</option>
                  <option value="selesai">Selesai</option>
                  <option value="terlambat">Terlambat</option>
                  <option value="dibatalkan">Dibatalkan</option>
                </select>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Detail Transaksi -->
    <div v-if="selectedRental" class="modal-overlay" @click="selectedRental = null">
      <div class="modal-card" @click.stop>
        <h3>Detail Transaksi Sewa</h3>
        
        <div class="detail-grid">
          <div class="detail-item">
            <span class="label">Kode Transaksi:</span>
            <span class="value font-bold text-code">{{ selectedRental.kode_transaksi }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Nama Penyewa:</span>
            <span class="value">{{ selectedRental.user?.nama || '-' }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Periode Sewa:</span>
            <span class="value">{{ selectedRental.tgl_mulai_sewa }} s/d {{ selectedRental.tgl_selesai_sewa }}</span>
          </div>
          <div class="detail-item">
            <span class="label">Total Biaya:</span>
            <span class="value price">{{ formatRupiah(selectedRental.total_harga) }}</span>
          </div>
        </div>

        <h4 class="section-title">Barang Disewa:</h4>
        <ul class="items-list" v-if="selectedRental.rental_items && selectedRental.rental_items.length > 0">
          <li v-for="item in selectedRental.rental_items" :key="item.id">
            <span>{{ item.item?.nama_barang || item.nama_barang || 'Barang #' + item.id_barang }}</span>
            <span class="font-bold">x{{ item.jumlah || 1 }}</span>
          </li>
        </ul>
        <p v-else class="text-muted empty-sub">Tidak ada rincian barang.</p>

        <div class="modal-actions">
          <!-- Tombol Cetak Nota -->
          <button class="btn-print" @click="printReceipt(selectedRental)">
            🖨️ Cetak Nota
          </button>
          <button class="btn-cancel" @click="selectedRental = null">Tutup</button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.status-badge.terlambat { background: #fee2e2; color: #dc2626; }
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

.btn-refresh, .btn-excel {
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

.btn-excel {
  background: #16a34a;
  color: #ffffff;
  border: none;
}

.btn-excel:hover {
  background: #15803d;
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
.text-code { font-family: monospace; font-size: 0.9rem; color: #0284c7; }
.text-center { text-align: center; }

.user-info {
  display: flex;
  flex-direction: column;
}

/* Badge Status Sesuai Enum Migration */
.status-badge {
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.78rem;
  font-weight: 700;
  text-transform: capitalize;
  display: inline-block;
}
.status-badge.menunggu { background: #fef3c7; color: #d97706; }
.status-badge.disewa { background: #e0f2fe; color: #0369a1; }
.status-badge.selesai { background: #dcfce7; color: #15803d; }
.status-badge.dibatalkan { background: #fee2e2; color: #b91c1c; }

/* Action Buttons */
.action-buttons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 8px;
}

.btn-action.detail {
  background: #f1f5f9;
  color: #334155;
  border: 1px solid #cbd5e1;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 700;
  font-size: 0.78rem;
  cursor: pointer;
}

.btn-action.detail:hover {
  background: #e2e8f0;
}

.status-select {
  padding: 5px 8px;
  border-radius: 6px;
  border: 1px solid #cbd5e1;
  font-size: 0.8rem;
  font-weight: 600;
  background: #fff;
  color: #334155;
  cursor: pointer;
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
  max-width: 500px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-card h3 {
  margin: 0 0 16px 0;
  font-size: 1.2rem;
  color: #0f172a;
}

.detail-grid {
  display: flex;
  flex-direction: column;
  gap: 8px;
  background: #f8fafc;
  padding: 12px 16px;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
  margin-bottom: 16px;
}

.detail-item {
  display: flex;
  justify-content: space-between;
  font-size: 0.88rem;
}

.detail-item .label {
  color: #64748b;
}

.section-title {
  font-size: 0.95rem;
  margin: 12px 0 8px 0;
  color: #0f172a;
}

.items-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.items-list li {
  display: flex;
  justify-content: space-between;
  padding: 8px 0;
  border-bottom: 1px dashed #e2e8f0;
  font-size: 0.88rem;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 8px;
  margin-top: 20px;
}

.btn-print {
  background: #0284c7;
  color: white;
  border: none;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.85rem;
  cursor: pointer;
}

.btn-print:hover {
  background: #0369a1;
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

.empty-sub {
  font-size: 0.85rem;
}
</style>