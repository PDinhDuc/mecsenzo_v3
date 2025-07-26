import { useAuthStore } from '@/stores/auth'

export default defineNuxtRouteMiddleware((to) => {
  const auth = useAuthStore()
  const publicPages = ['/login', '/register']
  if (publicPages.includes(to.path)) return
  if (!auth.user) {
    return navigateTo('/login')
  }
})