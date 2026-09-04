<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

const items = ref([])
const isLoading = ref(true)
const errorMessage = ref('')

// READ: Fetch data barang dari API Laravel
const fetchItems = async () => {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const response = await api.get('/items')
    items.value = response.data.data || response.data
  } catch (error) {
    errorMessage.value = error.response?.data?.message || 'Gagal mengambil data items dari server.'
  } finally {
    isLoading.value = false
  }
}

// DELETE: Hapus item dari database via API
const deleteItem = async (id, namaItem) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus "${namaItem}"?`)) return

  try {
    await api.delete(`/items/${id}`)
    items.value = items.value.filter(item => item.id !== id)
  } catch (error) {
    alert(error.response?.data?.message || 'Gagal menghapus item.')
  }
}

onMounted(() => {
  fetchItems()
})
</script>

<template>
  <div class="kelola-container">
    <!-- Header Page -->
    <div class="page-header">
      <div>
        <router-link to="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</router-link>
        <h2>Kelola Data Items</h2>
        <p class="subtitle">Daftar seluruh alat outdoor yang tersedia untuk disewakan.</p>
      </div>
      <router-link to="/admin/items/tambah" class="btn-add">
        + Tambah Item Baru
      </router-link>
    </div>

    <!-- Alert Error -->
    <div v-if="errorMessage" class="error-alert">
      ⚠️ {{ errorMessage }}
    </div>

    <!-- Table Container -->
    <div class="table-card">
      <table class="crud-table">
        <thead>
          <tr>
            <th width="60">ID</th>
            <th width="80">Foto</th>
            <th>Nama Item</th>
            <th>Kategori</th>
            <th>Harga Sewa / Hari</th>
            <th>Stok</th>
            <th width="160" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- State Loading -->
          <tr v-if="isLoading">
            <td colspan="7" class="text-center empty-msg">Memuat data items...</td>
          </tr>

          <!-- State Data Kosong -->
          <tr v-else-if="items.length === 0">
            <td colspan="7" class="text-center empty-msg">Belum ada data item.</td>
          </tr>

          <!-- Data Items dari API -->
          <tr v-else v-for="item in items" :key="item.id">
            <td class="text-muted">#{{ item.id }}</td>
            
            <!-- Kolom Foto Barang -->
            <td>
              <img 
                :src="item.foto_barang ? `http://localhost:8000/storage/${item.foto_barang}` : 'https://via.placeholder.com/60?text=No+Img'" 
                alt="Foto Item" 
                class="item-img-thumb"
              />
            </td>

            <td class="font-bold">{{ item.nama_barang || item.nama_item }}</td>
            
            <!-- Mengambil kategori dari relasi category -->
            <td>
              <span class="badge">
                {{ item.category?.nama_kategori || item.category?.nama || '-' }}
              </span>
            </td>

            <!-- Mengambil harga per hari dari field database -->
            <td class="price">
              Rp {{ Number(item.harga_per_hari || item.harga_sewa_per_hari || 0).toLocaleString('id-ID') }}
            </td>

            <td>
              <span :class="['stok-tag', item.stok > 0 ? 'stok-ada' : 'stok-habis']">
                {{ item.stok }} unit
              </span>
            </td>
            <td class="text-center">
              <div class="action-buttons">
                <router-link :to="`/admin/items/edit/${item.id}`" class="btn-action edit">
                  ✏️ Edit
                </router-link>
                <button @click="deleteItem(item.id, item.nama_barang || item.nama_item)" class="btn-action delete">
                  🗑️ Hapus
                </button>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
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
  display: inline-block;
  color: #2ec4b6;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.88rem;
  margin-bottom: 8px;
  transition: opacity 0.2s;
}

.btn-back:hover {
  opacity: 0.8;
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

.btn-add {
  background: #ff9f1c;
  color: #ffffff;
  text-decoration: none;
  padding: 10px 18px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.9rem;
  box-shadow: 0 4px 10px rgba(255, 159, 28, 0.25);
  transition: all 0.2s ease;
}

.btn-add:hover {
  background: #e08b10;
  transform: translateY(-2px);
}

.error-alert {
  background: #fee2e2;
  color: #dc2626;
  padding: 12px 16px;
  border-radius: 8px;
  font-weight: 600;
  margin-bottom: 20px;
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

.item-img-thumb {
  width: 50px;
  height: 50px;
  object-fit: cover;
  border-radius: 8px;
  border: 1px solid #e2e8f0;
}

.text-muted { color: #94a3b8; }
.font-bold { font-weight: 700; color: #0f172a; }
.price { font-weight: 600; color: #0f172a; }
.text-center { text-align: center; }

.badge {
  background: #e0f2fe;
  color: #0284c7;
  padding: 4px 10px;
  border-radius: 20px;
  font-size: 0.78rem;
  font-weight: 700;
}

.stok-tag {
  font-size: 0.82rem;
  font-weight: 600;
  padding: 3px 8px;
  border-radius: 6px;
}

.stok-ada { background: #dcfce7; color: #15803d; }
.stok-habis { background: #fee2e2; color: #b91c1c; }

.action-buttons {
  display: flex;
  justify-content: center;
  gap: 8px;
}

.btn-action {
  text-decoration: none;
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
</style>