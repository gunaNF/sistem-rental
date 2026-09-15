<script setup>
import { ref, onMounted } from 'vue'

const users = ref([])
const loading = ref(false)
const errorMessage = ref('')
const successMessage = ref('')

// 1. Ambil Data User dari API Temanmu
const fetchUsers = async () => {
  loading.value = true
  errorMessage.value = ''
  try {
    const token = localStorage.getItem('access_token')
    const res = await fetch('http://127.0.0.1:8000/api/users', {
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })
    const responseData = await res.json()
    if (res.ok) {
      // Sesuaikan nama field 'data' dengan response dari BE temenmu
      users.value = responseData.data || responseData
    } else {
      errorMessage.value = responseData.message || 'Gagal mengambil data user.'
    }
  } catch (err) {
    errorMessage.value = 'Tidak dapat terhubung ke server backend.'
  } finally {
    loading.value = false
  }
}

// 2. Fungsi Ubah Peran (Admin / Customer)
const handleRoleChange = async (user, newRole) => {
  try {
    const token = localStorage.getItem('access_token')
    const res = await fetch(`http://127.0.0.1:8000/api/users/${user.id}/role`, {
      method: 'PATCH',
      headers: {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ peran: newRole })
    })

    const responseData = await res.json()
    if (res.ok) {
      user.peran = newRole
      showSuccess(`Peran ${user.nama} berhasil diubah menjadi ${newRole}`)
    } else {
      alert(responseData.message || 'Gagal memperbarui peran user.')
      fetchUsers() // Reset ke data asli jika gagal
    }
  } catch (err) {
    alert('Terjadi kesalahan koneksi saat mengubah peran.')
  }
}

// 3. Fungsi Hapus User
const handleDeleteUser = async (id, nama) => {
  if (!confirm(`Apakah Anda yakin ingin menghapus akun "${nama}"?`)) return

  try {
    const token = localStorage.getItem('access_token')
    const res = await fetch(`http://127.0.0.1:8000/api/users/${id}`, {
      method: 'DELETE',
      headers: {
        'Authorization': `Bearer ${token}`,
        'Accept': 'application/json'
      }
    })

    const responseData = await res.json()
    if (res.ok) {
      users.value = users.value.filter(u => u.id !== id)
      showSuccess(`User "${nama}" berhasil dihapus.`)
    } else {
      alert(responseData.message || 'Gagal menghapus user.')
    }
  } catch (err) {
    alert('Terjadi kesalahan koneksi saat menghapus user.')
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
  <div class="page-container">
    <div class="header-section">
      <div>
        <h2>👥 Kelola Data User</h2>
        <p>Daftar seluruh akun terdaftar dan hak akses peran pada sistem.</p>
      </div>
      <button class="btn-refresh" @click="fetchUsers">🔄 Muat Ulang</button>
    </div>

    <!-- Alert Notifikasi -->
    <div v-if="successMessage" class="alert alert-success">
      ✅ {{ successMessage }}
    </div>
    <div v-if="errorMessage" class="alert alert-danger">
      ⚠️ {{ errorMessage }}
    </div>

    <!-- Table User -->
    <div class="table-card">
      <table class="custom-table" v-if="!loading">
        <thead>
          <tr>
            <th>ID</th>
            <th>Nama Lengkap</th>
            <th>Email</th>
            <th>No. Telepon</th>
            <th>Peran (Role)</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="user in users" :key="user.id">
            <td>#{{ user.id }}</td>
            <td><strong>{{ user.nama }}</strong></td>
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
              <button @click="handleDeleteUser(user.id, user.nama)" class="btn-delete">
                🗑️ Hapus
              </button>
            </td>
          </tr>
          <tr v-if="users.length === 0">
            <td colspan="6" class="text-center empty-state">Belum ada data user.</td>
          </tr>
        </tbody>
      </table>

      <div v-else class="loading-state">
        ⏳ Memuat data user...
      </div>
    </div>
  </div>
</template>

<style scoped>
/* Container Utama */
.page-container {
  padding: 24px;
  font-family: 'Plus Jakarta Sans', sans-serif;
  color: #1e293b !important; /* Memaksa warna teks utama gelap */
  background-color: #f8fafc; /* Latar halaman terang */
  min-height: 100vh;
}

.header-section {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.header-section h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: #0f172a !important;
  margin: 0;
}

.header-section p {
  color: #64748b !important;
  font-size: 0.88rem;
  margin-top: 4px;
}

.btn-refresh {
  background: #ffffff;
  color: #334155 !important;
  border: 1px solid #cbd5e1;
  padding: 8px 16px;
  border-radius: 8px;
  font-weight: 600;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-refresh:hover {
  background: #f1f5f9;
}

/* Alert Notifikasi */
.alert {
  padding: 12px 16px;
  border-radius: 10px;
  font-weight: 600;
  margin-bottom: 16px;
  font-size: 0.9rem;
}

.alert-success { background: #dcfce7; color: #15803d !important; }
.alert-danger { background: #ffe4e6; color: #be123c !important; }

/* Kartu Tabel Putih Terang */
.table-card {
  background: #ffffff !important;
  border: 1px solid #e2e8f0;
  border-radius: 14px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
  overflow: hidden;
}

.custom-table {
  width: 100%;
  border-collapse: collapse;
  text-align: left;
  background-color: #ffffff !important;
  color: #334155 !important;
}

.custom-table th, 
.custom-table td {
  padding: 14px 18px;
  border-bottom: 1px solid #e2e8f0;
  font-size: 0.9rem;
  color: #334155 !important; /* Warna teks dalam tabel hitam/abu gelap */
}

/* Header Tabel */
.custom-table th {
  background-color: #f1f5f9 !important;
  color: #0f172a !important;
  font-weight: 700;
}

/* Efek Hover Baris Tabel */
.custom-table tbody tr:hover {
  background-color: #f8fafc !important;
}

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
  background-color: #f3e8ff !important;
  color: #6b21a8 !important;
  border: 1px solid #d8b4fe;
}

.badge-customer {
  background-color: #e0f2fe !important;
  color: #0369a1 !important;
  border: 1px solid #bae6fd;
}

/* Tombol Hapus */
.btn-delete {
  background: #fff1f2 !important;
  color: #e11d48 !important;
  border: 1px solid #fecdd3;
  padding: 6px 12px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.8rem;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-delete:hover {
  background: #ffe4e6 !important;
  color: #be123c !important;
}

.loading-state, 
.empty-state {
  padding: 30px;
  color: #64748b !important;
  font-weight: 600;
}
</style>