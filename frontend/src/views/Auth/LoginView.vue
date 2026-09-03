<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import axios from 'axios'

const router = useRouter()

// State form login
const email = ref('')
const password = ref('')
const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

// Toggle intip kata sandi
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value
}

// Submit Login ke API
const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.post('http://127.0.0.1:8000/api/login', {
      email: email.value,
      kata_sandi: password.value
    })

    // Debugging: Buka Console (F12) untuk melihat struktur JSON asli dari backend
    console.log('Response dari Backend:', response.data)

    // Mengecek token dari berbagai kemungkinan kunci JSON Laravel
    const accessToken = 
      response.data.access_token || 
      response.data.token || 
      response.data.data?.access_token || 
      response.data.data?.token || 
      response.data.authorisation?.token

    // Mengecek objek user
    const user = 
      response.data.user || 
      response.data.data?.user || 
      response.data.data || 
      {}

    if (!accessToken) {
      throw new Error('Token autentikasi tidak ditemukan dari server.')
    }

    // Simpan data ke localStorage
    localStorage.setItem('access_token', accessToken)
    localStorage.setItem('user_data', JSON.stringify(user))
    localStorage.setItem('user_role', user?.peran || user?.role || 'customer')

    alert(`Selamat datang kembali, ${user?.nama || user?.name || 'User'}!`)

    // Redirect berdasarkan peran
    const role = user?.peran || user?.role
    if (role === 'admin') {
      router.push('/admin/dashboard')
    } else {
      router.push('/')
    }
  } catch (error) {
    console.error('Login Error:', error)
    if (error.response && error.response.data) {
      errorMessage.value = error.response.data.message || 'Email atau kata sandi salah.'
    } else if (error.message) {
      errorMessage.value = error.message
    } else {
      errorMessage.value = 'Gagal terhubung ke server backend.'
    }
  } finally {
    isLoading.value = false
  }
}
</script>

<template>
  <div class="login-wrapper">
    <!-- Navbar Transparent -->
    <header class="login-navbar">
      <router-link to="/" class="logo">
        🏕️ forrest.<span>rent</span>
      </router-link>
      <router-link to="/" class="btn-back">← Beranda</router-link>
    </header>

    <!-- Container & Card Center -->
    <main class="login-container">
      <div class="login-card">
        <div class="login-header">
          <h2>Selamat Datang Kembali!</h2>
          <p>Masuk ke akun Anda untuk mulai menyewa alat camping</p>
        </div>

        <!-- Alert Error -->
        <div v-if="errorMessage" class="error-box">
          ⚠️ {{ errorMessage }}
        </div>

        <form @submit.prevent="handleLogin" class="login-form">
          <div class="form-group">
            <label for="email">Alamat Email</label>
            <input 
              id="email" 
              v-model="email" 
              type="email" 
              placeholder="nama@email.com" 
              required 
            />
          </div>

          <div class="form-group">
            <label for="password">Kata Sandi</label>
            <div class="password-input-wrapper">
              <input 
                id="password" 
                v-model="password" 
                :type="showPassword ? 'text' : 'password'" 
                placeholder="Masukkan kata sandi" 
                required 
              />
              <button 
                type="button" 
                class="btn-toggle-eye" 
                @click="togglePasswordVisibility"
              >
                {{ showPassword ? '🙈' : '👁️' }}
              </button>
            </div>
          </div>

          <button type="submit" class="btn-submit" :disabled="isLoading">
            {{ isLoading ? 'Memproses...' : 'Masuk Sekarang' }}
          </button>
        </form>

        <div class="login-footer">
          <p>Belum punya akun? <router-link to="/register">Daftar sekarang</router-link></p>
        </div>
      </div>
    </main>
  </div>
</template>

<style scoped>
/* Main Wrapper dengan Background Gambar Gunung */
.login-wrapper {
  min-height: 100vh;
  background: linear-gradient(rgba(0, 0, 0, 0.45), rgba(0, 0, 0, 0.65)),
              url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2000') center/cover no-repeat fixed;
  color: #ffffff;
  display: flex;
  flex-direction: column;
  font-family: 'Plus Jakarta Sans', sans-serif;
}

/* Navbar */
.login-navbar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding: 24px 6%;
}

.logo {
  font-size: 1.6rem;
  font-weight: 800;
  color: #ffffff;
  text-decoration: none;
}

.logo span {
  color: #ff9f1c;
}

.btn-back {
  color: rgba(255, 255, 255, 0.85);
  text-decoration: none;
  font-weight: 700;
  font-size: 0.9rem;
  background: rgba(255, 255, 255, 0.15);
  backdrop-filter: blur(8px);
  padding: 8px 18px;
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.25);
  transition: all 0.2s;
}

.btn-back:hover {
  background: rgba(255, 255, 255, 0.25);
  color: #ffffff;
}

/* Container & Glassmorphism Card */
.login-container {
  flex: 1;
  display: flex;
  justify-content: center;
  align-items: center;
  padding: 40px 20px;
}

.login-card {
  background: rgba(20, 25, 35, 0.65);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  border: 1px solid rgba(255, 255, 255, 0.18);
  border-radius: 20px;
  padding: 40px;
  width: 100%;
  max-width: 420px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
}

.login-header h2 {
  font-size: 1.7rem;
  font-weight: 800;
  color: #ffffff;
  margin: 0 0 8px 0;
  text-shadow: 0 2px 4px rgba(0,0,0,0.3);
}

.login-header p {
  font-size: 0.88rem;
  color: rgba(255, 255, 255, 0.75);
  margin: 0 0 24px 0;
  line-height: 1.5;
}

/* Alert Error */
.error-box {
  background: rgba(220, 38, 38, 0.25);
  border: 1px solid rgba(239, 68, 68, 0.5);
  color: #fca5a5;
  padding: 12px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 20px;
}

/* Form Controls */
.login-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 8px;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 700;
  color: rgba(255, 255, 255, 0.9);
}

.form-group input {
  width: 100%;
  padding: 12px 16px;
  border: 1px solid rgba(255, 255, 255, 0.25);
  border-radius: 12px;
  font-size: 0.9rem;
  background: rgba(255, 255, 255, 0.1);
  color: #ffffff;
  outline: none;
  box-sizing: border-box;
  transition: border-color 0.2s, background 0.2s;
}

.form-group input::placeholder {
  color: rgba(255, 255, 255, 0.45);
}

.form-group input:focus {
  border-color: #2ec4b6;
  background: rgba(255, 255, 255, 0.18);
  box-shadow: 0 0 0 3px rgba(46, 196, 182, 0.25);
}

.password-input-wrapper {
  position: relative;
  display: flex;
  align-items: center;
}

.btn-toggle-eye {
  position: absolute;
  right: 14px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
}

/* Submit Button */
.btn-submit {
  background: #2ec4b6;
  color: #ffffff;
  border: none;
  padding: 14px;
  border-radius: 12px;
  font-weight: 800;
  font-size: 0.95rem;
  cursor: pointer;
  transition: all 0.2s ease;
  margin-top: 10px;
  box-shadow: 0 6px 16px rgba(46, 196, 182, 0.35);
}

.btn-submit:hover {
  background: #25a094;
  transform: translateY(-1px);
}

.btn-submit:disabled {
  opacity: 0.65;
  cursor: not-allowed;
}

/* Footer Link */
.login-footer {
  margin-top: 24px;
  text-align: center;
  font-size: 0.88rem;
  color: rgba(255, 255, 255, 0.75);
}

.login-footer a {
  color: #ff9f1c;
  font-weight: 700;
  text-decoration: none;
}

.login-footer a:hover {
  text-decoration: underline;
}
</style>