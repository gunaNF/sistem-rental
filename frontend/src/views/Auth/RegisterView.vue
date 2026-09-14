<script setup>
import { ref } from 'vue'
import axios from 'axios'
import { useRouter } from 'vue-router'

const router = useRouter()

// State Form
const name = ref('')
const email = ref('')
const phone = ref('')
const password = ref('')
const passwordConfirmation = ref('')

const showPassword = ref(false)
const isLoading = ref(false)
const errorMessage = ref('')

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

// Handle Register ke API Laravel
const handleRegister = async () => {
  if (password.value !== passwordConfirmation.value) {
    errorMessage.value = 'Konfirmasi kata sandi tidak cocok!'
    return
  }

  isLoading.value = true
  errorMessage.value = ''

  try {
    await axios.post('http://localhost:8000/api/register', {
      name: name.value,
      email: email.value,
      phone: phone.value,
      password: password.value,
      password_confirmation: passwordConfirmation.value
    })

    alert('Pendaftaran berhasil! Silakan masuk dengan akun Anda.')
    router.push('/login')
  } catch (error) {
    if (error.response && error.response.data) {
      errorMessage.value = error.response.data.message || 'Gagal mendaftar. Periksa kembali data Anda.'
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
    <!-- Header Top Navigation -->
    <header class="top-nav">
      <div class="logo">⛺ forrest.<span>rent</span></div>
      <router-link to="/" class="btn-beranda">← Beranda</router-link>
    </header>

    <!-- Main Container Card -->
    <div class="login-card">
      <div class="brand-header">
        <h2>Buat Akun Baru</h2>
        <p>Isi formulir di bawah untuk mulai menyewa alat camping</p>
      </div>

      <!-- Alert Error -->
      <div v-if="errorMessage" class="error-alert">
        ⚠️ {{ errorMessage }}
      </div>

      <form @submit.prevent="handleRegister" class="login-form">
        <!-- Nama Lengkap -->
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <input
            type="text"
            id="name"
            v-model="name"
            placeholder="Masukkan nama lengkap"
            class="form-input"
            required
          />
        </div>

        <!-- Alamat Email -->
        <div class="form-group">
          <label for="email">Alamat Email</label>
          <input
            type="email"
            id="email"
            v-model="email"
            placeholder="nama@email.com"
            class="form-input"
            required
          />
        </div>

        <!-- Nomor WhatsApp -->
        <div class="form-group">
          <label for="phone">Nomor WhatsApp</label>
          <input
            type="tel"
            id="phone"
            v-model="phone"
            placeholder="081234567890"
            class="form-input"
            required
          />
        </div>

        <!-- Kata Sandi -->
        <div class="form-group">
          <label for="password">Kata Sandi</label>
          <div class="password-wrapper">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password"
              v-model="password"
              placeholder="Masukkan kata sandi"
              class="form-input"
              required
            />
            <button 
              type="button" 
              class="toggle-password" 
              @click="togglePassword"
              tabindex="-1"
            >
              {{ showPassword ? '👁️' : '🙈' }}
            </button>
          </div>
        </div>

        <!-- Konfirmasi Kata Sandi -->
        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Kata Sandi</label>
          <div class="password-wrapper">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password_confirmation"
              v-model="passwordConfirmation"
              placeholder="Konfirmasi kata sandi"
              class="form-input"
              required
            />
            <button 
              type="button" 
              class="toggle-password" 
              @click="togglePassword"
              tabindex="-1"
            >
              {{ showPassword ? '👁️' : '🙈' }}
            </button>
          </div>
        </div>

        <!-- Tombol Submit Toska -->
        <button type="submit" class="btn-submit" :disabled="isLoading">
          <span v-if="isLoading">Mendaftarkan...</span>
          <span v-else>Daftar Sekarang</span>
        </button>
      </form>

      <!-- Footer Card -->
      <div class="register-link">
        Sudah punya akun? 
        <router-link to="/login">Masuk sekarang</router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.login-wrapper {
  min-height: 100vh;
  width: 100vw;
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  position: relative;
  font-family: 'Plus Jakarta Sans', system-ui, -apple-system, sans-serif;
  padding: 80px 20px 40px;
  background: url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2070&auto=format&fit=crop') no-repeat center center / cover;
  box-sizing: border-box;
}

/* Header di atas layar */
.top-nav {
  position: absolute;
  top: 24px;
  left: 36px;
  right: 36px;
  display: flex;
  justify-content: space-between;
  align-items: center;
  z-index: 10;
}

.logo {
  font-size: 1.35rem;
  font-weight: 800;
  color: #ffffff;
}

.logo span {
  color: #ff9f1c;
}

.btn-beranda {
  background: rgba(255, 255, 255, 0.15);
  color: #ffffff;
  text-decoration: none;
  padding: 8px 18px;
  border-radius: 20px;
  font-size: 0.85rem;
  font-weight: 600;
  backdrop-filter: blur(8px);
  border: 1px solid rgba(255, 255, 255, 0.2);
  transition: all 0.2s ease;
}

.btn-beranda:hover {
  background: rgba(255, 255, 255, 0.25);
}

/* Card Gelap Transparan (Dark Glassmorphism) */
.login-card {
  position: relative;
  z-index: 2;
  background: rgba(30, 35, 45, 0.85);
  backdrop-filter: blur(16px);
  -webkit-backdrop-filter: blur(16px);
  color: #ffffff;
  width: 100%;
  max-width: 420px;
  padding: 36px 32px;
  border-radius: 20px;
  border: 1px solid rgba(255, 255, 255, 0.1);
  box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
  box-sizing: border-box;
}

.brand-header {
  text-align: center;
  margin-bottom: 24px;
}

.brand-header h2 {
  font-size: 1.45rem;
  font-weight: 800;
  color: #ffffff;
  margin-bottom: 6px;
}

.brand-header p {
  font-size: 0.82rem;
  color: #94a3b8;
  line-height: 1.4;
}

.error-alert {
  background: rgba(220, 38, 38, 0.2);
  border: 1px solid #ef4444;
  color: #fca5a5;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 18px;
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
  font-size: 0.82rem;
  font-weight: 600;
  color: #cbd5e1;
}

.form-input {
  width: 100%;
  padding: 12px 14px;
  border: 1px solid rgba(255, 255, 255, 0.15);
  border-radius: 10px;
  font-size: 0.88rem;
  background-color: rgba(15, 23, 42, 0.6);
  color: #ffffff;
  outline: none;
  transition: all 0.2s ease;
  box-sizing: border-box;
}

.form-input::placeholder {
  color: #64748b;
}

.form-input:focus {
  background-color: rgba(15, 23, 42, 0.8);
  border-color: #2ec4b6;
  box-shadow: 0 0 0 3px rgba(46, 196, 182, 0.2);
}

/* Fix Autofill Warna Hitam Browser */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active {
  -webkit-box-shadow: 0 0 0 30px #1e293b inset !important;
  -webkit-text-fill-color: #ffffff !important;
  transition: background-color 5000s ease-in-out 0s;
}

.password-wrapper {
  position: relative;
  display: flex;
  align-items: center;
  width: 100%;
}

.password-wrapper .form-input {
  padding-right: 42px;
}

.toggle-password {
  position: absolute;
  right: 12px;
  background: none;
  border: none;
  cursor: pointer;
  font-size: 1.05rem;
  opacity: 0.7;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 0;
  user-select: none;
  transition: opacity 0.2s;
}

.toggle-password:hover {
  opacity: 1;
}

/* Tombol Toska */
.btn-submit {
  background: #2ec4b6;
  color: #0f172a;
  border: none;
  padding: 12px;
  border-radius: 10px;
  font-size: 0.92rem;
  font-weight: 800;
  cursor: pointer;
  margin-top: 8px;
  transition: all 0.2s ease;
}

.btn-submit:hover {
  background: #25a99d;
  transform: translateY(-1px);
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.register-link {
  text-align: center;
  margin-top: 22px;
  font-size: 0.82rem;
  color: #94a3b8;
}

.register-link a {
  color: #ff9f1c;
  font-weight: 700;
  text-decoration: none;
  margin-left: 4px;
}

.register-link a:hover {
  text-decoration: underline;
}
</style>