<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'
import api from '@/services/api'

const router = useRouter()
const route = useRoute()

const itemId = route.params.id

const isLoading = ref(true)
const isSubmitting = ref(false)
const errorMessage = ref('')

const form = ref({
  nama_barang: '',
  kategori: '',
  harga_sewa_per_hari: '',
  stok: ''
})

// Fetch detail item berdasarkan ID
const fetchItemDetail = async () => {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const response = await api.get(`/items/${itemId}`)
    const data = response.data.data || response.data

    form.value = {
      nama_barang: data.nama_barang || data.nama_item || '',
      kategori: data.kategori || '',
      harga_sewa_per_hari: data.harga_sewa_per_hari || data.harga_sewa || '',
      stok: data.stok || 0
    }
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Gagal mengambil detail data item.'
  } finally {
    isLoading.value = false
  }
}

// Update data item ke backend
const handleUpdate = async () => {
  isSubmitting.value = true
  errorMessage.value = ''

  try {
    await api.put(`/items/${itemId}`, {
      nama_barang: form.value.nama_barang,
      kategori: form.value.kategori,
      harga_sewa_per_hari: Number(form.value.harga_sewa_per_hari),
      stok: Number(form.value.stok)
    })

    alert(`Item #${itemId} berhasil diperbarui!`)
    router.push('/admin/items')
  } catch (error) {
    if (error.response && error.response.data) {
      errorMessage.value = error.response.data.message || 'Gagal memperbarui item.'
    } else {
      errorMessage.value = 'Gagal terhubung ke server backend.'
    }
  } finally {
    isSubmitting.value = false
  }
}

onMounted(() => {
  if (itemId) {
    fetchItemDetail()
  }
})
</script>

<template>
  <div class="form-container">
    <div class="form-header">
      <router-link to="/admin/items" class="btn-back">← Kembali ke Kelola Items</router-link>
      <h2>Edit Item #{{ itemId }}</h2>
      <p class="subtitle">Ubah informasi alat outdoor di bawah ini.</p>
    </div>

    <!-- Alert Error -->
    <div v-if="errorMessage" class="error-alert">
      ⚠️ {{ errorMessage }}
    </div>

    <!-- Loading Fetch Data -->
    <div v-if="isLoading" class="loading-state">
      Memuat detail item...
    </div>

    <form v-else @submit.prevent="handleUpdate" class="item-form">
      <div class="form-group">
        <label>Nama Item</label>
        <input 
          v-model="form.nama_barang" 
          type="text" 
          placeholder="Masukkan nama alat" 
          required 
        />
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <input 
          v-model="form.kategori" 
          type="text" 
          placeholder="Contoh: Tenda, Tas" 
          required 
        />
      </div>

      <div class="form-group">
        <label>Harga Sewa (Per Hari)</label>
        <input 
          v-model.number="form.harga_sewa_per_hari" 
          type="number" 
          min="0"
          placeholder="Contoh: 50000" 
          required 
        />
      </div>

      <div class="form-group">
        <label>Stok Barang</label>
        <input 
          v-model.number="form.stok" 
          type="number" 
          min="0"
          placeholder="Contoh: 10" 
          required 
        />
      </div>

      <div class="form-actions">
        <router-link to="/admin/items" class="btn-cancel">Batal</router-link>
        <button type="submit" class="btn-submit" :disabled="isSubmitting">
          {{ isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan' }}
        </button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.form-container {
  background: #ffffff;
  padding: 30px;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  max-width: 550px;
  margin: 20px auto;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.btn-back {
  display: inline-block;
  color: #2ec4b6;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.88rem;
  margin-bottom: 8px;
}

.form-header h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
}

.subtitle {
  color: #64748b;
  font-size: 0.85rem;
  margin-top: 4px;
  margin-bottom: 24px;
}

.error-alert {
  background: #fee2e2;
  color: #dc2626;
  padding: 12px 16px;
  border-radius: 8px;
  font-weight: 600;
  margin-bottom: 20px;
}

.loading-state {
  text-align: center;
  padding: 30px 0;
  color: #64748b;
  font-weight: 600;
}

.item-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 700;
  color: #475569;
}

.form-group input {
  padding: 11px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.9rem;
  outline: none;
  transition: border-color 0.2s;
}

.form-group input:focus {
  border-color: #2ec4b6;
  box-shadow: 0 0 0 3px rgba(46, 196, 182, 0.15);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 12px;
  margin-top: 10px;
}

.btn-cancel {
  text-decoration: none;
  color: #64748b;
  font-weight: 700;
  font-size: 0.88rem;
  padding: 10px 16px;
  border-radius: 8px;
  background: #f1f5f9;
}

.btn-submit {
  background: #2ec4b6;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.88rem;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-submit:hover {
  background: #25a094;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>