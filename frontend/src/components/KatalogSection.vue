<script setup>
import { ref } from 'vue'

// Data Dummy Alat Camping
const products = ref([
  {
    id: 1,
    nama_alat: 'Tenda Dome Eiger 4 Person',
    kategori: 'Tenda',
    harga_sewa: 50000,
    stok: 5,
    gambar: 'https://images.unsplash.com/photo-1504280390367-361c6d9f38f4?q=80&w=600'
  },
  {
    id: 2,
    nama_alat: 'Carrier Consina 60L',
    kategori: 'Tas Carrier',
    harga_sewa: 35000,
    stok: 8,
    gambar: 'https://images.unsplash.com/photo-1553062407-98eeb64c6a62?q=80&w=600'
  },
  {
    id: 3,
    nama_alat: 'Sleeping Bag Rei Thermal',
    kategori: 'Perlengkapan',
    harga_sewa: 15000,
    stok: 12,
    gambar: 'https://images.unsplash.com/photo-1510312305653-8ed496efae75?q=80&w=600'
  },
  {
    id: 4,
    nama_alat: 'Kompor Portable & Windshield',
    kategori: 'Masak',
    harga_sewa: 20000,
    stok: 6,
    gambar: 'https://images.unsplash.com/photo-1526772662000-3f88f10405ff?q=80&w=600'
  },
  {
    id: 5,
    nama_alat: 'Lampu Tenda LED Rechargeable',
    kategori: 'Perlengkapan',
    harga_sewa: 10000,
    stok: 10,
    gambar: 'https://images.unsplash.com/photo-1517824806704-9040b037703b?q=80&w=600'
  },
  {
    id: 6,
    nama_alat: 'Flysheet 3x4 Meter Anti Air',
    kategori: 'Tenda',
    harga_sewa: 15000,
    stok: 4,
    gambar: 'https://images.unsplash.com/photo-1478131143081-80f7f84ca84d?q=80&w=600'
  }
])

// Format Rupiah
const formatRupiah = (val) => {
  return new Intl.NumberFormat('id-ID', {
    style: 'currency',
    currency: 'IDR',
    maximumFractionDigits: 0
  }).format(val)
}
</script>

<template>
  <section class="katalog-container">
    <div class="header-section">
      <h2>🔥 Katalog Alat Camping</h2>
      <p>Pilih perlengkapan outdoor berkualitas untuk petualanganmu</p>
    </div>

    <div class="product-grid">
      <div v-for="item in products" :key="item.id" class="product-card">
        <div class="image-wrapper">
          <img :src="item.gambar" :alt="item.nama_alat" />
          <span class="badge-kategori">{{ item.kategori }}</span>
        </div>

        <div class="card-body">
          <h3 class="product-title">{{ item.nama_alat }}</h3>
          
          <div class="stock-info">
            <span>Stok: <strong>{{ item.stok }}</strong></span>
          </div>

          <div class="card-footer">
            <div class="price">
              <span class="price-val">{{ formatRupiah(item.harga_sewa) }}</span>
              <span class="price-unit">/hari</span>
            </div>
            
            <button class="btn-cart">+ Keranjang</button>
          </div>
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.katalog-container {
  padding: 50px 5%;
  background-color: #f8f9fa;
  color: #333;
  font-family: 'Plus Jakarta Sans', sans-serif;
  width: 100%;
  box-sizing: border-box;
}

.header-section {
  text-align: center;
  margin-bottom: 30px;
}

.header-section h2 {
  font-size: 2rem;
  font-weight: 800;
  color: #1a1a1a;
  margin-bottom: 6px;
}

.header-section p {
  color: #666;
  font-size: 0.95rem;
}

/* Mengunci layout jadi 4 Kolom Pas */
.product-grid {
  display: grid;
  grid-template-columns: repeat(4, 1fr); /* Pas 4 produk ke samping */
  gap: 20px;
}

.product-card {
  background: #ffffff;
  border-radius: 16px;
  overflow: hidden;
  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
  transition: transform 0.2s ease, box-shadow 0.2s ease;
  display: flex;
  flex-direction: column;
  border: 1px solid #eaeaea;
}

.product-card:hover {
  transform: translateY(-4px);
  box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
}

/* Mengatur proporsi foto agar tinggi & pas (tidak pipih) */
.image-wrapper {
  position: relative;
  width: 100%;
  height: 200px; /* Tinggi foto dibuat pas tegak */
  background-color: #f0f0f0;
}

.image-wrapper img {
  width: 100%;
  height: 100%;
  object-fit: cover; /* Agar gambar tidak gepeng */
}

.badge-kategori {
  position: absolute;
  top: 10px;
  left: 10px;
  background: rgba(0, 0, 0, 0.65);
  backdrop-filter: blur(4px);
  color: #fff;
  font-size: 0.7rem;
  font-weight: 700;
  padding: 4px 10px;
  border-radius: 12px;
}

.card-body {
  padding: 14px;
  display: flex;
  flex-direction: column;
  flex-grow: 1;
}

.product-title {
  font-size: 0.95rem;
  font-weight: 700;
  color: #222;
  margin-bottom: 6px;
  line-height: 1.3;
}

.stock-info {
  font-size: 0.8rem;
  color: #777;
  margin-bottom: 12px;
}

.card-footer {
  margin-top: auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  padding-top: 10px;
  border-top: 1px solid #f0f0f0;
}

.price-val {
  font-size: 0.95rem;
  font-weight: 800;
  color: #2ec4b6;
}

.price-unit {
  font-size: 0.75rem;
  color: #888;
}

.btn-cart {
  background: #ff9f1c;
  color: #ffffff;
  border: none;
  padding: 6px 12px;
  border-radius: 8px;
  font-weight: 700;
  font-size: 0.8rem;
  cursor: pointer;
  transition: background 0.2s;
}

.btn-cart:hover {
  background: #e08b12;
}

/* Responsif untuk Layar HP/Tablet */
@media (max-width: 1024px) {
  .product-grid {
    grid-template-columns: repeat(2, 1fr); /* 2 kolom di tablet */
  }
}

@media (max-width: 600px) {
  .product-grid {
    grid-template-columns: repeat(1, 1fr); /* 1 kolom di HP */
  }
}
</style>