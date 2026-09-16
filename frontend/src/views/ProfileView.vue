<template>
  <div class="profile-page-wrapper">
    <div class="profile-page">
      <div class="container">
        <!-- Navigasi Tombol Kembali -->
        <div class="nav-back">
          <router-link to="/" class="btn-back">
            ← Kembali ke Katalog
          </router-link>
        </div>

        <div class="header-section">
          <h2>👤 Profil Saya</h2>
          <p>Kelola informasi akun dan kata sandi Anda</p>
        </div>

        <!-- Alert Pesan Sukses / Error -->
        <div v-if="successMessage" class="alert alert-success">
          {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="alert alert-danger">
          {{ errorMessage }}
        </div>

        <!-- Loading State -->
        <div v-if="isLoading" class="loading-box">
          <p>Memuat data profil...</p>
        </div>

        <div v-else class="profile-grid">
          <!-- Card 1: Data Diri / Profil -->
          <div class="profile-card">
            <div class="card-header">
              <h3>Informasi Pribadi</h3>
            </div>
            <form @submit.prevent="updateProfile" class="card-body">
              <div class="form-group">
                <label>Nama Lengkap</label>
                <input 
                  type="text" 
                  v-model="profileForm.nama" 
                  required 
                  placeholder="Masukkan nama lengkap"
                />
              </div>

              <div class="form-group">
                <label>Alamat Email</label>
                <input 
                  type="email" 
                  v-model="profileForm.email" 
                  required 
                  placeholder="email@domain.com"
                />
              </div>

              <div class="form-group">
                <label>Nomor HP / WhatsApp</label>
                <input 
                  type="text" 
                  v-model="profileForm.no_telepon" 
                  placeholder="Contoh: 081234567890"
                />
              </div>

              <div class="form-group">
                <label>Alamat Lengkap</label>
                <textarea 
                  v-model="profileForm.alamat" 
                  rows="3" 
                  placeholder="Masukkan alamat domisili lengkap"
                ></textarea>
              </div>

              <button type="submit" class="btn-save" :disabled="isSavingProfile">
                {{ isSavingProfile ? 'Menyimpan...' : 'Simpan Perubahan Profil' }}
              </button>
            </form>
          </div>

          <!-- Card 2: Ubah Password & Session -->
          <div class="side-column">
            <!-- Ubah Password -->
            <div class="profile-card">
              <div class="card-header">
                <h3>Ubah Kata Sandi</h3>
              </div>
              <form @submit.prevent="updatePassword" class="card-body">
                <div class="form-group">
                  <label>Kata Sandi Lama</label>
                  <input 
                    type="password" 
                    v-model="passwordForm.current_password" 
                    required 
                    placeholder="••••••••"
                  />
                </div>

                <div class="form-group">
                  <label>Kata Sandi Baru</label>
                  <input 
                    type="password" 
                    v-model="passwordForm.password" 
                    required 
                    placeholder="Minimal 8 karakter"
                  />
                </div>

                <div class="form-group">
                  <label>Konfirmasi Kata Sandi Baru</label>
                  <input 
                    type="password" 
                    v-model="passwordForm.password_confirmation" 
                    required 
                    placeholder="Ulangi kata sandi baru"
                  />
                </div>

                <button type="submit" class="btn-save btn-warning" :disabled="isSavingPassword">
                  {{ isSavingPassword ? 'Memproses...' : 'Perbarui Kata Sandi' }}
                </button>
              </form>
            </div>

            <!-- Quick Action / Logout -->
            <div class="profile-card logout-card">
              <button type="button" @click="handleLogout" class="btn-logout">
                🚪 Keluar dari Akun (Logout)
              </button>
            </div>
          </div>
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

const isLoading = ref(true)
const isSavingProfile = ref(false)
const isSavingPassword = ref(false)
const successMessage = ref('')
const errorMessage = ref('')

const profileForm = ref({
  nama: '',
  email: '',
  no_telepon: '',
  alamat: ''
})

const passwordForm = ref({
  current_password: '',
  password: '',
  password_confirmation: ''
})

// Load Profile Data
const fetchUserProfile = async () => {
  isLoading.value = true
  const token = localStorage.getItem('access_token') || localStorage.getItem('token')
  
  if (!token) {
    router.push('/login')
    return
  }

  try {
    const apiUrl = `http://${window.location.hostname}:8000/api/me`
    const response = await axios.get(apiUrl, {
      headers: { Authorization: `Bearer ${token}` }
    })

    const user = response.data?.data || response.data
    if (user) {
      profileForm.value = {
        nama: user.nama || user.name || '',
        email: user.email || '',
        no_telepon: user.no_telepon || user.no_hp || user.phone || '',
        alamat: user.alamat || ''
      }
    }
  } catch (error) {
    console.error('Gagal mengambil data profil:', error)
    if (error.response?.status === 401) {
      localStorage.removeItem('access_token')
      localStorage.removeItem('token')
      router.push('/login')
    } else {
      errorMessage.value = 'Gagal memuat data profil dari server.'
    }
  } finally {
    isLoading.value = false
  }
}

// Update Profile
const updateProfile = async () => {
  const token = localStorage.getItem('access_token') || localStorage.getItem('token')
  isSavingProfile.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    const apiUrl = `http://${window.location.hostname}:8000/api/profile`
    await axios.put(apiUrl, profileForm.value, {
      headers: { Authorization: `Bearer ${token}` }
    })

    successMessage.value = 'Profil berhasil diperbarui!'
  } catch (error) {
    console.error('Gagal memperbarui profil:', error)
    if (error.response?.data?.errors) {
      const errs = error.response.data.errors
      const firstKey = Object.keys(errs)[0]
      errorMessage.value = `Gagal: ${errs[firstKey][0]}`
    } else {
      errorMessage.value = error.response?.data?.message || 'Gagal menyimpan perubahan profil.'
    }
  } finally {
    isSavingProfile.value = false
  }
}

// Update Password
const updatePassword = async () => {
  if (passwordForm.value.password !== passwordForm.value.password_confirmation) {
    errorMessage.value = 'Konfirmasi kata sandi baru tidak cocok.'
    return
  }

  const token = localStorage.getItem('access_token') || localStorage.getItem('token')
  isSavingPassword.value = true
  successMessage.value = ''
  errorMessage.value = ''

  try {
    const apiUrl = `http://${window.location.hostname}:8000/api/change-password`
    await axios.put(apiUrl, passwordForm.value, {
      headers: { Authorization: `Bearer ${token}` }
    })

    successMessage.value = 'Kata sandi berhasil diperbarui!'
    passwordForm.value = { current_password: '', password: '', password_confirmation: '' }
  } catch (error) {
    console.error('Gagal mengubah password:', error)
    errorMessage.value = error.response?.data?.message || 'Gagal memperbarui kata sandi.'
  } finally {
    isSavingPassword.value = false
  }
}

// Logout
const handleLogout = async () => {
  if (!confirm('Apakah Anda yakin ingin keluar?')) return

  const token = localStorage.getItem('access_token') || localStorage.getItem('token')
  
  try {
    if (token) {
      const apiUrl = `http://${window.location.hostname}:8000/api/logout`
      await axios.post(apiUrl, {}, {
        headers: { Authorization: `Bearer ${token}` }
      })
    }
  } catch (e) {
    console.warn('Gagal logout di server:', e)
  } finally {
    localStorage.clear()
    router.push('/login')
  }
}

onMounted(() => {
  fetchUserProfile()
})
</script>

<style scoped>
/* Memaksa Latar Belakang Terang dan Menutup Banner Hero */
.profile-page-wrapper {
  position: relative;
  z-index: 99;
  width: 100%;
  min-height: 100vh;
  background-color: #f8fafc !important;
}

.profile-page {
  padding: 40px 20px;
  background-color: #f8fafc !important;
  color: #0f172a !important;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

.container {
  max-width: 900px;
  margin: 0 auto;
}

.nav-back {
  margin-bottom: 20px;
}

.btn-back {
  display: inline-flex;
  align-items: center;
  gap: 8px;
  color: #475569 !important;
  text-decoration: none;
  font-weight: 600;
  font-size: 0.9rem;
  padding: 8px 16px;
  background: #ffffff !important;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  transition: all 0.2s ease;
}

.btn-back:hover {
  background: #f1f5f9 !important;
  color: #0f172a !important;
}

.header-section {
  margin-bottom: 25px;
  text-align: center;
}

.header-section h2 {
  font-size: 1.8rem;
  font-weight: 800;
  color: #0f172a !important;
}

.header-section p {
  color: #64748b !important;
  margin-top: 4px;
}

.loading-box {
  text-align: center;
  padding: 40px;
  background: white !important;
  color: #0f172a !important;
  border-radius: 12px;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
}

.alert {
  padding: 12px 16px;
  border-radius: 8px;
  font-size: 0.9rem;
  font-weight: 600;
  margin-bottom: 20px;
}

.alert-success {
  background-color: #dcfce7 !important;
  color: #166534 !important;
  border: 1px solid #bbf7d0;
}

.alert-danger {
  background-color: #fee2e2 !important;
  color: #991b1b !important;
  border: 1px solid #fecaca;
}

.profile-grid {
  display: grid;
  grid-template-columns: 1.2fr 1fr;
  gap: 20px;
}

@media (max-width: 768px) {
  .profile-grid {
    grid-template-columns: 1fr;
  }
}

.side-column {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.profile-card {
  background: #ffffff !important;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);
  overflow: hidden;
}

.card-header {
  background: #f8fafc !important;
  padding: 16px 20px;
  border-bottom: 1px solid #e2e8f0;
}

.card-header h3 {
  font-size: 1rem;
  font-weight: 700;
  color: #1e293b !important;
  margin: 0;
}

.card-body {
  padding: 20px;
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 600;
  color: #475569 !important;
}

.form-group input,
.form-group textarea {
  padding: 10px 12px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.9rem;
  font-family: inherit;
  outline: none;
  background: #ffffff !important;
  color: #0f172a !important;
  transition: border-color 0.2s;
}

.form-group input:focus,
.form-group textarea:focus {
  border-color: #0d9488;
}

.btn-save {
  margin-top: 10px;
  padding: 12px;
  background: #0d9488 !important;
  color: white !important;
  border: none;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-save:hover {
  background: #0f766e !important;
}

.btn-warning {
  background: #d97706 !important;
}

.btn-warning:hover {
  background: #b45309 !important;
}

.logout-card {
  padding: 16px;
}

.btn-logout {
  width: 100%;
  padding: 12px;
  background: #fee2e2 !important;
  color: #dc2626 !important;
  border: 1px solid #fca5a5;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  transition: all 0.2s;
}

.btn-logout:hover {
  background: #fecaca !important;
}
</style>