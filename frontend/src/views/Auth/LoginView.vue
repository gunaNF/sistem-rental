<script setup>
import { ref } from 'vue'
import axios from 'axios'

// State form login
const email = ref('')
const password = ref('')
const showPassword = ref(false) // State untuk intip password
const isLoading = ref(false)
const errorMessage = ref('')

// Fungsi toggle intip kata sandi
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value
}

// Fungsi Submit Login ke Backend Laravel
const handleLogin = async () => {
  isLoading.value = true
  errorMessage.value = ''

  try {
    const response = await axios.post('http://localhost:8000/api/login', {
      email: email.value,
      password: password.value
    })

    const token = response.data.token
    const user = response.data.user

    localStorage.setItem('auth_token', token)
    localStorage.setItem('user_data', JSON.stringify(user))

    alert(`Selamat datang kembali, ${user.name || 'User'}!`)
    window.location.href = '/'
  } catch (error) {
    if (error.response && error.response.data) {
      errorMessage.value = error.response.data.message || 'Email atau password salah.'
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
    <!-- Overlay gelap transparan agar form login tetap terbaca jelas -->
    <div class="bg-overlay"></div>

    <div class="login-box">
      <!-- Tombol Kembali ke Dashboard -->
      <router-link to="/" class="back-link">← Kembali ke Beranda</router-link>

      <div class="login-header">
        <div class="logo">🌲 forrest<span>rent.</span></div>
        <h2>Masuk ke Akun</h2>
        <p>Silakan masukkan email dan kata sandi Anda.</p>
      </div>

      <!-- Pesan Error -->
      <div v-if="errorMessage" class="error-alert">
        ⚠️ {{ errorMessage }}
      </div>

      <form @submit.prevent="handleLogin" class="login-form">
        <div class="form-group">
          <label for="email">Email</label>
          <input
            type="email"
            id="email"
            v-model="email"
            placeholder="nama@email.com"
            class="form-control"
            required
          />
        </div>

        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <div class="password-wrapper">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="password"
              placeholder="••••••••"
              class="form-control"
              required
            />
            <button 
              type="button" 
              class="toggle-password" 
              @click="togglePasswordVisibility"
              tabindex="-1"
            >
              {{ showPassword ? '👁️' : '🙈' }}
            </button>
          </div>
        </div>

        <button type="submit" class="btn-submit" :disabled="isLoading">
          <span v-if="isLoading">Memproses...</span>
          <span v-else>Masuk</span>
        </button>
      </form>
    </div>
  </div>
</template>

<style scoped>
.login-wrapper {
  min-height: 100vh;
  width: 100vw;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  font-family: 'Plus Jakarta Sans', sans-serif;
  padding: 20px;
  
  /* 🌄 SETTING GAMBAR BACKGROUND (BISA DIGANTI SENDIRI) 🌄 */
  /* Opsi 1: Menggunakan URL Gambar Online Unsplash */
  background: url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2070&auto=format&fit=crop') no-repeat center center / cover;
  
  /* Opsi 2: Jika pakai gambar lokal dari folder public, ubah baris di atas jadi: */
  /* background: url('/bg-mountain.jpg') no-repeat center center / cover; */
}

/* Overlay hitam transparan agar tulisan & form terlihat jelas di atas gambar */
.bg-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(8, 6, 13, 0.45);
  backdrop-filter: blur(4px); /* Efek blur halus pada gambar latar belakang */
  z-index: 1;
}

.login-box {
  position: relative;
  z-index: 2; /* Agar form berada di atas overlay */
  background: rgba(255, 255, 255, 0.95); /* Sedikit transparan/glassmorphism halus */
  color: #111827;
  width: 100%;
  max-width: 400px;
  padding: 36px 30px;
  border-radius: 20px;
  box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
}

.back-link {
  display: inline-block;
  color: #6b7280;
  text-decoration: none;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 20px;
  transition: color 0.2s ease;
}

.back-link:hover {
  color: #111827;
}

.login-header {
  text-align: center;
  margin-bottom: 24px;
}

.logo {
  font-size: 1.6rem;
  font-weight: 900;
  color: #132a1e;
  margin-bottom: 8px;
}

.logo span {
  color: #ff9f1c;
}

.login-header h2 {
  font-size: 1.4rem;
  font-weight: 800;
  margin-bottom: 6px;
}

.login-header p {
  font-size: 0.85rem;
  color: #6b7280;
}

.error-alert {
  background: #fee2e2;
  color: #dc2626;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 20px;
}

.login-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
  text-align: left;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 700;
  color: #374151;
}

/* Component Input */
.form-control {
  width: 100%;
  padding: 12px 14px;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  font-size: 0.9rem;
  background-color: #f9fafb;
  color: #111827;
  outline: none;
  transition: all 0.2s ease;
  box-sizing: border-box;
}

.form-control:focus {
  background-color: #ffffff;
  border-color: #ff9f1c;
  box-shadow: 0 0 0 3px rgba(255, 159, 28, 0.15);
}

.form-control::placeholder {
  color: #9ca3af;
}

/* Input Password & Toggle Eye */
.password-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.password-wrapper .form-control {
  padding-right: 42px;
}

.toggle-password {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.1rem;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  user-select: none;
  opacity: 0.7;
  transition: opacity 0.2s;
}

.toggle-password:hover {
  opacity: 1;
}

.btn-submit {
  background: #132a1e;
  color: #ffffff;
  border: none;
  padding: 13px;
  border-radius: 10px;
  font-size: 0.95rem;
  font-weight: 700;
  cursor: pointer;
  margin-top: 8px;
  transition: background-color 0.2s;
}

.btn-submit:hover {
  background: #0d1c14;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}
</style>