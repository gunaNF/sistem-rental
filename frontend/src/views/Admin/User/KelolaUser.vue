<script setup>
import { ref, onMounted } from 'vue'
import { RouterLink } from 'vue-router'
import api from '@/services/api'

const users = ref([])
const isLoading = ref(true)
const errorMessage = ref('')
const successMessage = ref('')

// 1. Ambil Data User dari API
const fetchUsers = async () => {
  isLoading.value = true
  errorMessage.value = ''
  try {
    const response = await api.get('/users')
    users.value = response.data.data || response.data || []
  } catch (err) {
    console.error('Fetch Users Error:', err)
    errorMessage.value = err.response?.data?.message || 'Gagal memuat data user dari server.'
  } finally {
    isLoading.value = false
  }
}

// 2. Fungsi Ubah Peran (Admin / Customer)
const handleRoleChange = async (user, newRole) => {
  try {
    await api.patch(`/users/${user.id}/role`, { peran: newRole })
    user.peran = newRole
    showSuccess(`Peran ${user.nama} berhasil diubah menjadi ${newRole}`)
  } catch (err) {
    console.error('Update Role Error:', err)
    alert(err.response?.data?.message || 'Gagal memperbarui peran user.')
    fetchUsers() // Reset ke data asli jika gagal
  }
}

// 3. Fungsi Hapus User
const handleDeleteUser = async (id, nama) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus akun "${nama}"?`)) return

  try {
    await api.delete(`/users/${id}`)
    users.value = users.value.filter(u => u.id !== id)
    showSuccess(`User "${nama}" berhasil dihapus.`)
  } catch (err) {
    console.error('Delete User Error:', err)
    alert(err.response?.data?.message || 'Gagal menghapus user.')
  }
}

const showSuccess = (msg) => {
  successMessage.value = msg
  setTimeout(() => {
    successMessage.value = ''
  }, 3000)
}

onMounted(() => {
  fetchUsers()
})
</script>

<template>
  <div class="kelola-container">
    <!-- Header Section -->
    <div class="page-header">
      <div>
        <router-link to="/admin/dashboard" class="btn-back">← Kembali ke Dashboard</router-link>
        <h2>Kelola Data User</h2>
        <p class="subtitle">Daftar seluruh akun terdaftar dan hak akses peran pada sistem.</p>
      </div>
      <button class="btn-refresh" @click="fetchUsers">🔄 Muat Ulang</button>
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
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Peran (Role)</th>
            <th style="width: 160px;" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <!-- State Loading -->
          <tr v-if="isLoading">
            <td colspan="6" class="text-center empty-msg">Memuat data user...</td>
          </tr>

          <!-- State Data Kosong -->
          <tr v-else-if="users.length === 0">
            <td colspan="6" class="text-center empty-msg">Belum ada data user.</td>
          </tr>

          <!-- Data Users -->
          <tr v-else v-for="(user, index) in users" :key="user.id || index">
            <td class="text-muted">#{{ index + 1 }}</td>
            <td class="font-bold">{{ user.nama }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.no_telepon || '-' }}</td>
            <td>
              <select 
                :value="user.peran" 
                @change="handleRoleChange(user, $event.target.value)"
                :class="['role-select', user.peran === 'admin' ? 'badge-admin' : 'badge-customer']"
              >
                <option value="customer">Customer</option>
                <option value="admin">Admin</option>
              </select>
            </td>
            <td class="text-center">
              <div class="action-buttons">
                <button @click="handleDeleteUser(user.id, user.nama)" class="btn-action delete">
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
.text-center { text-align: center; }

/* Selector Role (Dropdown Admin / Customer) */
.role-select {
  padding: 6px 12px;
  border-radius: 20px;
  font-weight: 700;
  font-size: 0.8rem;
  cursor: pointer;
  outline: none;
}

.badge-admin {
  background-color: #f3e8ff;
  color: #6b21a8;
  border: 1px solid #d8b4fe;
}

.badge-customer {
  background-color: #e0f2fe;
  color: #0369a1;
  border: 1px solid #bae6fd;
}

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