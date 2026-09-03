<script setup>
import { ref } from 'vue'

// Contoh data brand (sesuaikan nama logo / image path kamu nanti)
const brands = ref([
  { name: 'Rei', logo: 'https://placehold.co/150x80?text=REI' },
  { name: 'Consina', logo: 'https://placehold.co/150x80?text=CONSINA' },
  { name: 'Eiger', logo: 'https://placehold.co/150x80?text=EIGER' },
  { name: 'Merapi Mountain', logo: 'https://placehold.co/150x80?text=MERAPI' },
  { name: 'Mokzhaware', logo: 'https://placehold.co/150x80?text=MOKZHA' },
  { name: 'Deuter', logo: 'https://placehold.co/150x80?text=DEUTER' },
  { name: 'Naturehike', logo: 'https://placehold.co/150x80?text=NATUREHIKE' }
])
</script>

<template>
  <section class="brand-section">
    <div class="section-header">
      <h2>Pilihan Brand</h2>
      <p>Dipilih dari merek terbaik untuk pengalaman terbaik.</p>
    </div>

    <!-- Wrapper Slider -->
    <div class="slider-container">
      <div class="slider-track">
        <!-- List Pertama -->
        <div 
          v-for="(brand, index) in brands" 
          :key="'b1-' + index" 
          class="brand-card"
        >
          <img :src="brand.logo" :alt="brand.name" />
        </div>

        <!-- Duplicate List (Wajib Duplikat Agar Efek Loop Mulus/Tidak Putus) -->
        <div 
          v-for="(brand, index) in brands" 
          :key="'b2-' + index" 
          class="brand-card"
        >
          <img :src="brand.logo" :alt="brand.name" />
        </div>
      </div>
    </div>
  </section>
</template>

<style scoped>
.brand-section {
  padding: 60px 0;
  background-color: #f9fafb;
  overflow: hidden;
}

.section-header {
  padding: 0 6%;
  margin-bottom: 28px;
}

.section-header h2 {
  font-size: 2rem;
  font-weight: 800;
  color: #111827;
  margin-bottom: 6px;
}

.section-header p {
  font-size: 0.95rem;
  color: #6b7280;
}

/* Slider Track & Animasinya */
.slider-container {
  display: flex;
  width: 100%;
  overflow: hidden;
  position: relative;
  /* Efek pudar di ujung kiri & kanan agar visual lebih estetik */
  mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
  -webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
}

.slider-track {
  display: flex;
  gap: 20px;
  width: max-content;
  /* Atur 25s untuk kecepatan geser. Makin besar angkanya makin pelan */
  animation: scroll 25s linear infinite;
}

/* Pause animasi saat kursor diarahkan ke logo */
.slider-container:hover .slider-track {
  animation-play-state: paused;
}

.brand-card {
  background: #ffffff;
  border: 1px solid #e5e7eb;
  border-radius: 16px;
  width: 180px;
  height: 90px;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 16px;
  box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
  flex-shrink: 0;
}

.brand-card img {
  max-width: 100%;
  max-height: 100%;
  object-fit: contain;
}

/* Keyframes untuk Pindah Posisi Kesamping */
@keyframes scroll {
  0% {
    transform: translateX(0);
  }
  100% {
    transform: translateX(-50%);
  }
}
</style>