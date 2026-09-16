<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

const categories = ref([])
const isLoading = ref(false)
const errorMessage = ref('')

// State Modal Tambah/Edit
const showModal = ref(false)
const isEdit = ref(false)
const currentId = ref(null)
const categoryName = ref('')

// Fetch daftar kategori dari backend
const fetchCategories = async () => {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const response = await api.get('/categories')
    categories.value = response.data.data || response.data || []
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

// Simpan (Tambah/Edit) Kategori
const handleSave = async () => {
  if (!categoryName.value.trim()) return

  try {
    if (isEdit.value) {
      await api.put(`/categories/${currentId.value}`, {
        nama_kategori: categoryName.value
      })
    } else {
      await api.post('/categories', {
        nama_kategori: categoryName.value
      })
    }

    closeModal()
    fetchCategories()
  } catch (err) {
    console.error('Save Category Error:', err)
    alert(err.response?.data?.message || 'Gagal menyimpan kategori.')
  }
}

// Hapus Kategori
const handleDelete = async (id) => {
  if (!confirm('Apakah Anda yakin ingin menghapus kategori ini?')) return

  try {
    await api.delete(`/categories/${id}`)
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
    <!-- Header Section -->
    <div class="header-section">
      <div>
        <router-link to="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</router-link>
        <h2>Kelola Kategori</h2>
        <p class="subtitle">Tambahkan atau edit kategori barang rental outdoor.</p>
      </div>
      <button class="btn-add" @click="openAddModal">+ Tambah Kategori</button>
    </div>

    <!-- Alert Error -->
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
            <th style="width: 160px;" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-if="isLoading">
            <td colspan="3" class="text-center empty-msg">Memuat data kategori...</td>
          </tr>
          <tr v-else-if="categories.length === 0">
            <td colspan="3" class="text-center empty-msg">Belum ada kategori yang ditambahkan.</td>
          </tr>
          <tr v-else v-for="(cat, index) in categories" :key="cat.id || index">
            <td class="text-muted">#{{ index + 1 }}</td>
            <td class="font-bold">{{ cat.nama_kategori || cat.nama }}</td>
            <td class="text-center">
              <div class="action-buttons">
                <button class="btn-action edit" @click="openEditModal(cat)">✏️ Edit</button>
                <button class="btn-action delete" @click="handleDelete(cat.id)">🗑️ Hapus</button>
              </div>
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
  padding: 10px 0;
}

.header-section {
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

.header-section h2 {
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

.btn-add {
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.9rem;
  cursor: pointer;
  box-shadow: 0 4px 10px rgba(255, 159, 28, 0.25);
  transition: all 0.2s ease;
}

.btn-add:hover {
  background: #e08b10;
  transform: translateY(-2px);
}

/* Table Styles */
.table-card {
  background: #ffffff;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
  overflow: hidden;
}

.category-table {
  width: 100%;
  border-collapse: collapse;
}

.category-table th {
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

.category-table td {
  padding: 16px 18px;
  border-bottom: 1px solid #f1f5f9;
  font-size: 0.9rem;
  color: #334155;
  vertical-align: middle;
}

.category-table tbody tr:hover {
  background-color: #f8fafc;
}

.text-muted { color: #94a3b8; }
.font-bold { font-weight: 700; color: #0f172a; }
.text-center { text-align: center; }

.action-buttons {
  display: flex;
  justify-content: center;
  gap: 8px;
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

.btn-action.edit {
  background: #ffefd5;
  color: #d97706;
}

.btn-action.delete {
  background: #fee2e2;
  color: #dc2626;
}

.btn-action:hover {
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
  padding: 28px;
  border-radius: 16px;
  width: 100%;
  max-width: 400px;
  box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
}

.modal-card h3 {
  margin: 0 0 20px 0;
  font-size: 1.25rem;
  color: #0f172a;
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
  color: #334155;
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
  background: #ff9f1c;
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