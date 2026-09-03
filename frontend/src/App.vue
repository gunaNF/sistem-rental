<script setup>
import { computed } from 'vue'
import { useRoute, RouterView } from 'vue-router'

import BrandSection from '@/components/BrandSection.vue'
import FooterSection from '@/components/FooterSection.vue'

const route = useRoute()

// Brand & Footer HANYA akan dipanggil jika posisi rute di Halaman Utama ('/')
const isHomePage = computed(() => {
  return route.path === '/'
})
</script>

<template>
  <div id="app">
    <!-- RouterView merender halaman aktif secara dinamis (/login, /register, /admin, dll) -->
    <RouterView />

    <!-- Komponen tambahan khusus untuk Landing Page -->
    <template v-if="isHomePage">
      <BrandSection />
      <FooterSection />
    </template>
  </div>
</template>

<style>
/* Reset global style */
* {
  box-sizing: border-box;
}

body {
  margin: 0;
  padding: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
  background-color: #f8fafc;
  color: #0f172a;
}
</style>