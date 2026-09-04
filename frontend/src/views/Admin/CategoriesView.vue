<script setup>
import { ref, onMounted } from 'vue'
import axios from 'axios'

const categories = ref([])
const isLoading = ref(false)
const errorMessage = ref('')

// State Modal Tambah/Edit
const showModal = ref(false)
const isEdit = ref(false)
const currentId = ref(null)
const categoryName = ref('')

// Fetch daftar kategori dari backend (Endpoint Public)
const fetchCategories = async () => {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const response = await axios.get('http://127.0.0.1:8000/api/categories')
    categories.value = response.data.data || []
  } catch (err) {
    console.error('Fetch Categories Error:', err)
    errorMessage.value = err.response?.data?.message || 'Gagal memuat daftar kategori.'
  } finally {
    isLoading.value = false
  }
}

// Buka modal untuk Tambah
const openAddModal = () => {
  isEdit.value = false
  currentId.value = null
  categoryName.value = ''
  showModal.value = true
}

// Buka modal untuk Edit
const openEditModal = (cat) => {
  isEdit.value = true
  currentId.value = cat.id
  categoryName.value = cat.nama_kategori || cat.nama
  showModal.value = true
}

// Tutup Modal
const closeModal = () => {
  showModal.value = false
}

// Simpan (Tambah/Edit) Kategori (Endpoint Protected Admin)
const handleSave = async () => {
  if (!categoryName.value.trim()) return

  try {
    const token = localStorage.getItem('access_token')
    const headers = { 
      'Authorization': `Bearer ${token}`,
      'Accept': 'application/json'
    }

    if (isEdit.value) {
      await axios.put(`http://127.0.0.1:8000/api/categories/${currentId.value}`, {
        nama_kategori: categoryName.value
      }, { headers })
    } else {
      await axios.post('http://127.0.0.1:8000/api/categories', {
        nama_kategori: categoryName.value
      }, { headers })
    }

    closeModal()
    fetchCategories()
  } catch (err) {
    console.error('Save Category Error:', err)
    alert(err.response?.data?.message || 'Gagal menyimpan kategori.')
  }
}

// Hapus Kategori (Endpoint Protected Admin)
const handleDelete = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) return

  try {
    const token = localStorage.getItem('access_token')
    await axios.delete(`http://127.0.0.1:8000/api/categories/${id}`, {
      headers: { 
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
    fetchCategories()
  } catch (err) {
    console.error('Delete Category Error:', err)
    alert(err.response?.data?.message || 'Gagal menghapus kategori.')
  }
}

onMounted(() => {
  fetchCategories()
})
</script>

<template>
  <div class="categories-container">
    <div class="header-section">
      <div>
        <router-link to="/admin/dashboard" class="btn-back">
          ← Kembali ke Dashboard
        </router-link>
        <h2>Kelola Kategori</h2>
        <p>Tambahkan atau edit kategori barang rental</p>
      </div>
      <button class="btn-add" @click="openAddModal">+ Tambah Kategori</button>
    </div>

    <div v-if="errorMessage" class="error-box">
      ⚠️ {{ errorMessage }}
    </div>

    <!-- Table List Kategori -->
    <div class="table-card">
      <table class="category-table">
        <thead>
          <tr>
            <th style="width: 80px;">No</th>
            <th>Nama Kategori</th>
            <th style="width: 160px; text-align: center;">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="isLoading">
            <td colspan="3" class="text-center">Memuat data kategori...</td>
          </tr>
          <tr v-else-if="categories.length === 0">
            <td colspan="3" class="text-center">Belum ada kategori yang ditambahkan.</td>
          </tr>
          <tr v-for="(cat, index) in categories" :key="cat.id || index">
            <td>{{ index + 1 }}</td>
            <td class="font-bold">{{ cat.nama_kategori || cat.nama }}</td>
            <td class="action-buttons">
              <button class="btn-edit" @click="openEditModal(cat)">Edit</button>
              <button class="btn-delete" @click="handleDelete(cat.id)">Hapus</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Modal Form (Tambah / Edit) -->
    <div v-if="showModal" class="modal-overlay" @click.self="closeModal">
      <div class="modal-card">
        <h3>{{ isEdit ? 'Edit Kategori' : 'Tambah Kategori Baru' }}</h3>
        
        <div class="form-group">
          <label>Nama Kategori</label>
          <input 
            v-model="categoryName" 
            type="text" 
            placeholder="Contoh: Tenda, Tas, Sepatu"
            @keyup.enter="handleSave" 
          />
        </div>

        <div class="modal-actions">
          <button class="btn-cancel" @click="closeModal">Batal</button>
          <button class="btn-save" @click="handleSave">
            {{ isEdit ? 'Simpan Perubahan' : 'Tambah' }}
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<style scoped>
.categories-container {
  padding: 32px;
  max-width: 900px;
  margin: 0 auto;
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: #1f2937;
}

.header-section {
  display: flex;
  justify-content: space-between;
  align-items: flex-end;
  margin-bottom: 24px;
}

.btn-back {
  display: inline-block;
  color: #6b7280;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 8px;
  transition: color 0.2s;
}

.btn-back:hover {
  color: #1f2937;
}

.header-section h2 {
  font-size: 1.6rem;
  font-weight: 800;
  margin: 0;
}

.header-section p {
  color: #6b7280;
  margin: 4px 0 0 0;
  font-size: 0.9rem;
}

.btn-add {
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 10px 20px;
  border-radius: 10px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-add:hover {
  background: #e88e0e;
}

/* Table Styles */
.table-card {
  background: #ffffff;
  border-radius: 12px;
  box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  border: 1px solid #e5e7eb;
}

.category-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
}

.category-table th,
.category-table td {
  padding: 14px 20px;
  border-bottom: 1px solid #e5e7eb;
}

.category-table th {
  background: #f9fafb;
  font-size: 0.85rem;
  font-weight: 700;
  color: #4b5563;
  text-transform: uppercase;
}

.font-bold {
  font-weight: 600;
}

.text-center {
  text-align: center;
  color: #6b7280;
}

.action-buttons {
  display: flex;
  gap: 8px;
  justify-content: center;
}

.btn-edit {
  background: #2ec4b6;
  color: #ffffff;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.8rem;
  cursor: pointer;
}

.btn-delete {
  background: #ef4444;
  color: #ffffff;
  border: none;
  padding: 6px 12px;
  border-radius: 6px;
  font-weight: 600;
  font-size: 0.8rem;
  cursor: pointer;
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
  padding: 28px;
  border-radius: 16px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-card h3 {
  margin: 0 0 20px 0;
  font-size: 1.25rem;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  margin-bottom: 20px;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 700;
}

.form-group input {
  padding: 10px 14px;
  border: 1px solid #d1d5db;
  border-radius: 8px;
  outline: none;
}

.modal-actions {
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.btn-cancel {
  background: #e5e7eb;
  color: #374151;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
}

.btn-save {
  background: #2ec4b6;
  color: #ffffff;
  border: none;
  padding: 10px 16px;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
}

.error-box {
  background: #fee2e2;
  border: 1px solid #fca5a5;
  color: #dc2626;
  padding: 12px;
  border-radius: 8px;
  margin-bottom: 16px;
  font-size: 0.88rem;
}
</style>