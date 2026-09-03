<script setup>
import { computed } from 'vue'
import { useRoute, RouterView } from 'vue-router'

import BrandSection from '@/components/BrandSection.vue'
import FooterSection from '@/components/FooterSection.vue'

const route = useRoute()

// Cek apakah user berada di halaman Home / Landing Page
const isHomePage = computed(() => {
  return route.path === '/'
})
</script>

<template>
  <div id="app">
    <RouterView />

    <!-- Hanya tampil HANYA di halaman Home (/) -->
    <template v-if="isHomePage">
      <BrandSection />
      <FooterSection />
    </template>
  </div>
</template>

<style>
body {
  margin: 0;
  font-family: 'Plus Jakarta Sans', sans-serif;
  background-color: #f8fafc;
}
</style>