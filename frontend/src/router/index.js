import { createRouter, createWebHistory } from 'vue-router'
import HomeSection from '@/components/HomeSection.vue'
import CaraSewaSection from '@/components/CaraSewaSection.vue'
import LoginView from '@/views/Auth/LoginView.vue'
import RegisterView from '@/views/Auth/RegisterView.vue'

import AdminLayout from '@/layouts/AdminLayouts.vue'
import DashboardView from '@/views/Admin/DashboardView.vue'
import KelolaItems from '@/views/Admin/Items/KelolaItems.vue'
import TambahItems from '@/views/Admin/Items/TambahItems.vue'
import EditItems from '@/views/Admin/Items/EditItems.vue'

const routes = [
  { path: '/', name: 'home', component: HomeSection },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/register', name: 'register', component: RegisterView },
  { path: '/cara-sewa', name: 'cara-sewa', component: CaraSewaSection },
  
  // Rute Admin (Diproteksi)
  {
    path: '/admin',
    component: AdminLayout,
    redirect: '/admin/dashboard',
    meta: { requiresAuth: true, requiresAdmin: true },
    children: [
      { path: 'dashboard', name: 'admin-dashboard', component: DashboardView },
      { path: 'items', name: 'kelola-items', component: KelolaItems },
      { path: 'items/tambah', name: 'tambah-items', component: TambahItems },
      { path: 'items/edit/:id', name: 'edit-items', component: EditItems, props: true }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes
})

// Navigation Guard untuk Proteksi Akses Halaman
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('access_token')
  const userRole = localStorage.getItem('user_role')

  // Pastikan token benar-benar valid
  const isValidToken = Boolean(
    token && 
    token !== 'undefined' && 
    token !== 'null' && 
    token.trim() !== ''
  )

  // Hanya proteksi route yang membutuhkan Authenticated / Admin
  if (to.matched.some(record => record.meta.requiresAuth)) {
    if (!isValidToken) {
      alert('Silakan login terlebih dahulu!')
      return next({ name: 'login' })
    }

    if (to.matched.some(record => record.meta.requiresAdmin) && userRole !== 'admin') {
      alert('Akses ditolak! Anda bukan Admin.')
      return next({ name: 'home' })
    }
  }

  // Izinkan akses ke halaman publik (termasuk /login dan /register)
  next()
})

export default router