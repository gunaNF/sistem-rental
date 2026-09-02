import { createRouter, createWebHistory } from 'vue-router'
import HeroSection from '../components/HeroSection.vue'
import LoginView from '../views/Auth/LoginView.vue'

const routes = [
  {
    path: '/',
    name: 'home',
    component: HeroSection
  },
  {
    path: '/login',
    name: 'login',
    component: LoginView
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router