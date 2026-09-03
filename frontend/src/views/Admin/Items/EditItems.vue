<script setup>
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router'

const router = useRouter()
const route = useRoute()

const itemId = route.params.id

const form = ref({
  nama_item: '',
  kategori: '',
  harga_sewa: '',
  stok: ''
})

onMounted(() => {
  // Simulasi ambil data item berdasarkan ID dari route
  // Nanti diganti dengan request Axios: axios.get(`/api/items/${itemId}`)
  if (itemId) {
    form.value = {
      nama_item: 'Tenda Arpenaz 4.1',
      kategori: 'Tenda',
      harga_sewa: 75000,
      stok: 5
    }
  }
})

const handleUpdate = () => {
  // Logika update ke backend Laravel
  alert(`Item ID #${itemId} berhasil diperbarui!`)
  router.push('/admin/items')
}
</script>

<template>
  <div class="form-container">
    <div class="form-header">
      <router-link to="/admin/items" class="btn-back">← Kembali ke Kelola Items</router-link>
      <h2>Edit Item #{{ itemId }}</h2>
      <p class="subtitle">Ubah informasi alat outdoor di bawah ini.</p>
    </div>

    <form @submit.prevent="handleUpdate" class="item-form">
      <div class="form-group">
        <label>Nama Item</label>
        <input 
          v-model="form.nama_item" 
          type="text" 
          placeholder="Masukkan nama alat" 
          required 
        />
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <input 
          v-model="form.kategori" 
          type="text" 
          placeholder="Contoh: Tenda, Tas" 
          required 
        />
      </div>

      <div class="form-group">
        <label>Harga Sewa (Per Hari)</label>
        <input 
          v-model="form.harga_sewa" 
          type="number" 
          placeholder="Contoh: 50000" 
          required 
        />
      </div>

      <div class="form-group">
        <label>Stok Barang</label>
        <input 
          v-model="form.stok" 
          type="number" 
          placeholder="Contoh: 10" 
          required 
        />
      </div>

      <div class="form-actions">
        <router-link to="/admin/items" class="btn-cancel">Batal</router-link>
        <button type="submit" class="btn-submit">Simpan Perubahan</button>
      </div>
    </form>
  </div>
</template>

<style scoped>
.form-container {
  background: #ffffff;
  padding: 30px;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  max-width: 550px;
  margin: 20px auto;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.03);
}

.btn-back {
  display: inline-block;
  color: #2ec4b6;
  text-decoration: none;
  font-weight: 700;
  font-size: 0.88rem;
  margin-bottom: 8px;
}

.form-header h2 {
  font-size: 1.5rem;
  font-weight: 800;
  color: #0f172a;
  margin: 0;
}

.subtitle {
  color: #64748b;
  font-size: 0.85rem;
  margin-top: 4px;
  margin-bottom: 24px;
}

.item-form {
  display: flex;
  flex-direction: column;
  gap: 18px;
}

.form-group {
  display: flex;
  flex-direction: column;
  gap: 6px;
}

.form-group label {
  font-size: 0.85rem;
  font-weight: 700;
  color: #475569;
}

.form-group input {
  padding: 11px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.9rem;
  outline: none;
  transition: border-color 0.2s;
}

.form-group input:focus {
  border-color: #2ec4b6;
  box-shadow: 0 0 0 3px rgba(46, 196, 182, 0.15);
}

.form-actions {
  display: flex;
  justify-content: flex-end;
  align-items: center;
  gap: 12px;
  margin-top: 10px;
}

.btn-cancel {
  text-decoration: none;
  color: #64748b;
  font-weight: 700;
  font-size: 0.88rem;
  padding: 10px 16px;
  border-radius: 8px;
  background: #f1f5f9;
}

.btn-submit {
  background: #2ec4b6;
  color: white;
  border: none;
  padding: 10px 20px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.88rem;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-submit:hover {
  background: #25a094;
}
</style>