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
    const response = await axios.post('http://localhost:8000/api/register', {
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
  <div class="auth-wrapper">
    <div class="bg-overlay"></div>

    <div class="auth-box">
      <router-link to="/" class="back-link">← Kembali ke Beranda</router-link>

      <div class="auth-header">
        <div class="logo">🌲 forrest<span>rent.</span></div>
        <h2>Buat Akun Baru</h2>
        <p>Bergabunglah untuk mulai menyewa peralatan outdoor premium.</p>
      </div>

      <div v-if="errorMessage" class="error-alert">
        ⚠️ {{ errorMessage }}
      </div>

      <form @submit.prevent="handleRegister" class="auth-form">
        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <input
            type="text"
            id="name"
            v-model="name"
            placeholder="Nama Lengkap"
            class="form-control"
            required
          />
        </div>

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
          <label for="phone">Nomor WhatsApp</label>
          <input
            type="tel"
            id="phone"
            v-model="phone"
            placeholder="081234567890"
            class="form-control"
            required
          />
        </div>

        <!-- Input Kata Sandi -->
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
              @click="togglePassword"
              tabindex="-1"
            >
              {{ showPassword ? '👁️' : '🙈' }}
            </button>
          </div>
        </div>

        <!-- Input Konfirmasi Kata Sandi (Menggunakan Fitur Intip yang Sama) -->
        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Kata Sandi</label>
          <div class="password-wrapper">
            <input
              :type="showPassword ? 'text' : 'password'"
              id="password_confirmation"
              v-model="passwordConfirmation"
              placeholder="••••••••"
              class="form-control"
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

        <button type="submit" class="btn-submit" :disabled="isLoading">
          <span v-if="isLoading">Mendaftarkan...</span>
          <span v-else>Daftar Akun</span>
        </button>
      </form>

      <div class="auth-footer">
        Sudah punya akun? 
        <router-link to="/login" class="link">Masuk di sini</router-link>
      </div>
    </div>
  </div>
</template>

<style scoped>
.auth-wrapper {
  min-height: 100vh;
  width: 100vw;
  display: flex;
  align-items: center;
  justify-content: center;
  position: relative;
  font-family: 'Plus Jakarta Sans', sans-serif;
  padding: 40px 20px;
  background: url('https://images.unsplash.com/photo-1464822759023-fed622ff2c3b?q=80&w=2070&auto=format&fit=crop') no-repeat center center / cover;
}

.bg-overlay {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background: rgba(8, 6, 13, 0.5);
  backdrop-filter: blur(4px);
  z-index: 1;
}

.auth-box {
  position: relative;
  z-index: 2;
  background: rgba(255, 255, 255, 0.96);
  color: #111827;
  width: 100%;
  max-width: 440px;
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
  margin-bottom: 16px;
  transition: color 0.2s ease;
}

.back-link:hover {
  color: #111827;
}

.auth-header {
  text-align: center;
  margin-bottom: 20px;
}

.logo {
  font-size: 1.6rem;
  font-weight: 900;
  color: #132a1e;
  margin-bottom: 6px;
}

.logo span {
  color: #ff9f1c;
}

.auth-header h2 {
  font-size: 1.35rem;
  font-weight: 800;
  margin-bottom: 4px;
}

.auth-header p {
  font-size: 0.82rem;
  color: #6b7280;
}

.error-alert {
  background: #fee2e2;
  color: #dc2626;
  padding: 10px 14px;
  border-radius: 10px;
  font-size: 0.85rem;
  font-weight: 600;
  margin-bottom: 16px;
}

.auth-form {
  display: flex;
  flex-direction: column;
  gap: 14px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
  text-align: left;
}

.form-group label {
  font-size: 0.82rem;
  font-weight: 700;
  color: #374151;
}

.form-control {
  width: 100%;
  padding: 11px 14px;
  border: 1.5px solid #e5e7eb;
  border-radius: 10px;
  font-size: 0.88rem;
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

/* Fix Autofill Browser */
input:-webkit-autofill,
input:-webkit-autofill:hover, 
input:-webkit-autofill:focus, 
input:-webkit-autofill:active {
  -webkit-box-shadow: 0 0 0 30px #f9fafb inset !important;
  -webkit-text-fill-color: #111827 !important;
  transition: background-color 5000s ease-in-out 0s;
}

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

.btn-submit {
  background: #132a1e;
  color: #ffffff;
  border: none;
  padding: 12px;
  border-radius: 10px;
  font-size: 0.95rem;
  font-weight: 700;
  cursor: pointer;
  margin-top: 6px;
  transition: background-color 0.2s;
}

.btn-submit:hover {
  background: #0d1c14;
}

.btn-submit:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

.auth-footer {
  text-align: center;
  margin-top: 20px;
  font-size: 0.85rem;
  color: #6b7280;
}

.auth-footer .link {
  color: #ff9f1c;
  font-weight: 700;
  text-decoration: none;
  margin-left: 4px;
}

.auth-footer .link:hover {
  text-decoration: underline;
}
</style>