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
  
  // Rute Admin
  {
    path: '/admin',
    component: AdminLayout,
    redirect: '/admin/dashboard',
    children: [
      { path: 'dashboard', name: 'admin-dashboard', component: DashboardView },
      { path: 'items', name: 'kelola-items', component: KelolaItems },
      { path: 'items/tambah', name: 'tambah-items', component: TambahItems },
      { path: 'items/edit/:id', name: 'edit-items', component: EditItems, props: true }
    ]
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router