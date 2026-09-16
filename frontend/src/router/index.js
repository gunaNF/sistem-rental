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
import CategoriesView from '@/views/Admin/CategoriesView.vue'
import KelolaUser from '@/views/Admin/User/KelolaUser.vue'
import CartView from '../views/CartView.vue'
import CheckoutView from '../views/CheckoutView.vue'
import KelolaPayment from '@/views/Admin/Payment/KelolaPayment.vue'
import KelolaRental from '@/views/Admin/Rental/KelolaRental.vue'

const routes = [
  { path: '/', name: 'home', component: HomeSection },
  { path: '/login', name: 'login', component: LoginView, meta: { requiresGuest: true } },
  { path: '/register', name: 'register', component: RegisterView, meta: { requiresGuest: true } },
  { path: '/cara-sewa', name: 'cara-sewa', component: CaraSewaSection },
  
  // Route Keranjang & Checkout Customer
  { path: '/cart', name: 'cart', component: CartView },
  { path: '/checkout', name: 'checkout', component: CheckoutView },

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
      { path: 'items/edit/:id', name: 'edit-items', component: EditItems, props: true },
      { path: 'kategori', name: 'kelola-kategori', component: CategoriesView },
      { path: 'users', name: 'kelola-user', component: KelolaUser },
      { path: 'payments', name: 'kelola-payments', component: KelolaPayment },
      { path: 'rentals', name: 'kelola-rentals', component: KelolaRental }
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

  const isValidToken = Boolean(
    token && 
    token !== 'undefined' && 
    token !== 'null' && 
    token.trim() !== ''
  )

  const isTargetProtected = to.matched.some(record => record.meta.requiresAuth)
  const isTargetAdminOnly = to.matched.some(record => record.meta.requiresAdmin)
  const isTargetGuestOnly = to.matched.some(record => record.meta.requiresGuest)

  // 1. Jika sudah login & mencoba buka /login atau /register
  if (isTargetGuestOnly && isValidToken) {
    if (userRole === 'admin') {
      return next({ name: 'admin-dashboard' })
    }
    return next({ name: 'home' })
  }

  // 2. Jika butuh login tapi belum ada token
  if (isTargetProtected && !isValidToken) {
    alert('Silakan login terlebih dahulu!')
    return next({ name: 'login' })
  }

  // 3. Jika halaman khusus admin tapi role bukan admin
  if (isTargetAdminOnly && userRole !== 'admin') {
    alert('Akses ditolak! Anda bukan Admin.')
    return next({ name: 'home' })
  }

  // 4. Izinkan navigasi
  next()
})

export default router