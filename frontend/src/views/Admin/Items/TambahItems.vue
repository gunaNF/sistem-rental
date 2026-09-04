<script setup>
import { ref, onMounted } from 'vue'
import { useRouter } from 'vue-router'
import api from '@/services/api'

const router = useRouter()

const isLoading = ref(false)
const isLoadingCategories = ref(false)
const errorMessage = ref('')
const validationErrors = ref(null)
const categories = ref([])
const imagePreview = ref(null)

const form = ref({
  nama_barang: '',
  id_kategori: '',
  harga_per_hari: '',
  stok: '',
  deskripsi: '',
  foto_barang: null
})

// Fetch daftar kategori untuk dropdown
const fetchCategories = async () => {
  isLoadingCategories.value = true
  try {
    const response = await api.get('/categories')
    categories.value = response.data.data || []
  } catch (error) {
    console.error('Gagal mengambil daftar kategori:', error)
  } finally {
    isLoadingCategories.value = false
  }
}

// Handle perubahan file foto
const handleFileChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.value.foto_barang = file
    imagePreview.value = URL.createObjectURL(file)
  }
}

const handleSubmit = async () => {
  isLoading.value = true
  errorMessage.value = ''
  validationErrors.value = null

  // Gunakan FormData untuk mengirim berkas file
  const formData = new FormData()
  formData.append('nama_barang', form.value.nama_barang)
  formData.append('id_kategori', form.value.id_kategori)
  formData.append('harga_per_hari', form.value.harga_per_hari)
  formData.append('stok', form.value.stok)
  formData.append('deskripsi', form.value.deskripsi || '')

  if (form.value.foto_barang) {
    formData.append('foto_barang', form.value.foto_barang)
  }

  try {
    await api.post('/items', formData, {
      headers: {
        'Content-Type': 'multipart/form-data'
      }
    })

    alert('Item berhasil disimpan!')
    router.push('/admin/items')
  } catch (error) {
    if (error.response && error.response.status === 422) {
      errorMessage.value = error.response.data.message || 'Validasi gagal'
      validationErrors.value = error.response.data.errors
    } else if (error.response && error.response.data) {
      errorMessage.value = error.response.data.message || 'Gagal menyimpan item.'
    } else {
      errorMessage.value = 'Gagal terhubung ke server backend.'
    }
  } finally {
    isLoading.value = false
  }
}

onMounted(() => {
  fetchCategories()
})
</script>

<template>
  <div class="form-container">
    <div class="form-header">
      <router-link to="/admin/items" class="btn-back">← Kembali ke Kelola Items</router-link>
      <h2>Tambah Item Baru</h2>
    </div>

    <!-- Alert Error -->
    <div v-if="errorMessage" class="error-alert">
      <p style="margin: 0; font-weight: 700;">⚠️ {{ errorMessage }}</p>
      <ul v-if="validationErrors" class="error-list">
        <li v-for="(messages, field) in validationErrors" :key="field">
          <strong>{{ field }}</strong>: {{ messages.join(', ') }}
        </li>
      </ul>
    </div>

    <form @submit.prevent="handleSubmit" class="item-form">
      <div class="form-group">
        <label>Nama Item</label>
        <input v-model="form.nama_barang" type="text" placeholder="Masukkan nama alat" required />
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <select v-model="form.id_kategori" required :disabled="isLoadingCategories">
          <option value="" disabled>
            {{ isLoadingCategories ? 'Memuat kategori...' : '-- Pilih Kategori --' }}
          </option>
          <option 
            v-for="cat in categories" 
            :key="cat.id" 
            :value="cat.id"
          >
            {{ cat.nama_kategori || cat.nama }}
          </option>
        </select>
      </div>

      <!-- Input Field Foto Produk -->
      <div class="form-group">
        <label>Foto Produk</label>
        <input type="file" @change="handleFileChange" accept="image/*" />
        <div v-if="imagePreview" class="preview-container">
          <img :src="imagePreview" alt="Preview Foto" class="preview-img" />
        </div>
      </div>

      <div class="form-group">
        <label>Harga Sewa (Per Hari)</label>
        <input v-model.number="form.harga_per_hari" type="number" min="0" placeholder="Contoh: 50000" required />
      </div>

      <div class="form-group">
        <label>Stok</label>
        <input v-model.number="form.stok" type="number" min="1" placeholder="Contoh: 10" required />
      </div>

      <div class="form-group">
        <label>Deskripsi (Opsional)</label>
        <textarea v-model="form.deskripsi" rows="3" placeholder="Masukkan deskripsi barang..."></textarea>
      </div>

      <button type="submit" class="btn-submit" :disabled="isLoading">
        {{ isLoading ? 'Memproses...' : 'Simpan Item' }}
      </button>
    </form>
  </div>
</template>

<style scoped>
.form-container {
  background: #ffffff;
  padding: 24px;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  max-width: 600px;
  margin: 0 auto;
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
  font-size: 1.4rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 20px;
}

.error-alert {
  background: #fee2e2;
  color: #dc2626;
  padding: 12px 16px;
  border-radius: 8px;
  margin-bottom: 20px;
}

.error-list {
  margin: 8px 0 0 16px;
  padding: 0;
  font-size: 0.85rem;
}

.item-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
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

.form-group input,
.form-group select,
.form-group textarea {
  padding: 10px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.9rem;
  outline: none;
  background-color: #ffffff;
  font-family: inherit;
}

.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus {
  border-color: #2ec4b6;
}

.preview-container {
  margin-top: 8px;
}

.preview-img {
  width: 100px;
  height: 100px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #cbd5e1;
}

.btn-submit {
  background: #ff9f1c;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 10px;
  transition: background 0.2s;
}

.btn-submit:hover {
  background: #e08b10;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>