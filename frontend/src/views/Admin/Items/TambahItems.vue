<script setup>
import { ref } from 'vue'
import { useRouter } from 'vue-router'

const router = useRouter()

const form = ref({
  nama_item: '',
  kategori: '',
  harga_sewa: '',
  stok: ''
})

const handleSubmit = () => {
  // Logika simpan data ke backend / state nanti di sini
  alert('Data berhasil disimpan!')
  router.push('/admin/items')
}
</script>

<template>
  <div class="form-container">
    <div class="form-header">
      <router-link to="/admin/items" class="btn-back">← Kembali ke Kelola Items</router-link>
      <h2>Tambah Item Baru</h2>
    </div>

    <form @submit.prevent="handleSubmit" class="item-form">
      <div class="form-group">
        <label>Nama Item</label>
        <input v-model="form.nama_item" type="text" placeholder="Masukkan nama alat" required />
      </div>

      <div class="form-group">
        <label>Kategori</label>
        <input v-model="form.kategori" type="text" placeholder="Contoh: Tenda, Tas" required />
      </div>

      <div class="form-group">
        <label>Harga Sewa (Per Hari)</label>
        <input v-model="form.harga_sewa" type="number" placeholder="Contoh: 50000" required />
      </div>

      <div class="form-group">
        <label>Stok</label>
        <input v-model="form.stok" type="number" placeholder="Contoh: 10" required />
      </div>

      <button type="submit" class="btn-submit">Simpan Item</button>
    </form>
  </div>
</template>

<style scoped>
.form-container {
  background: #ffffff;
  padding: 24px;
  border-radius: 12px;
  border: 1px solid #e2e8f0;
  max-width: 600px;
  margin: 0 auto;
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
  font-size: 1.4rem;
  font-weight: 800;
  color: #0f172a;
  margin-bottom: 20px;
}

.item-form {
  display: flex;
  flex-direction: column;
  gap: 16px;
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
  padding: 10px 14px;
  border: 1px solid #cbd5e1;
  border-radius: 8px;
  font-size: 0.9rem;
  outline: none;
}

.form-group input:focus {
  border-color: #2ec4b6;
}

.btn-submit {
  background: #ff9f1c;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 8px;
  font-weight: 700;
  cursor: pointer;
  margin-top: 10px;
  transition: background 0.2s;
}

.btn-submit:hover {
  background: #e08b10;
}
</style>