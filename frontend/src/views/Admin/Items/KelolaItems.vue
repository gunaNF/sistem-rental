<script setup>
import { ref } from 'vue'
import { RouterLink } from 'vue-router'

// Sample data dummy (nanti diganti dengan fetch API dari backend Laravel)
const items = ref([
  { id: 1, nama_item: 'Tenda Arpenaz 4.1', kategori: 'Tenda', harga_sewa: 75000, stok: 5 },
  { id: 2, nama_item: 'Carrier Eiger 60L', kategori: 'Tas', harga_sewa: 45000, stok: 8 },
  { id: 3, nama_item: 'Kompor Portable Novar', kategori: 'Cooking Gear', harga_sewa: 20000, stok: 12 },
  { id: 4, nama_item: 'Sleeping Bag Deuter', kategori: 'Perlengkapan', harga_sewa: 25000, stok: 10 }
])

const deleteItem = (id) => {
  if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
    items.value = items.value.filter(item => item.id !== id)
  }
}
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

    <!-- Table Container -->
    <div class="table-card">
      <table class="crud-table">
        <thead>
          <tr>
            <th width="60">ID</th>
            <th>Nama Item</th>
            <th>Kategori</th>
            <th>Harga Sewa / Hari</th>
            <th>Stok</th>
            <th width="160" class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="item in items" :key="item.id">
            <td class="text-muted">#{{ item.id }}</td>
            <td class="font-bold">{{ item.nama_item }}</td>
            <td><span class="badge">{{ item.kategori }}</span></td>
            <td class="price">Rp {{ item.harga_sewa.toLocaleString('id-ID') }}</td>
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
                <button @click="deleteItem(item.id)" class="btn-action delete">
                  🗑️ Hapus
                </button>
              </div>
            </td>
          </tr>

          <tr v-if="items.length === 0">
            <td colspan="6" class="text-center empty-msg">Belum ada data item.</td>
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