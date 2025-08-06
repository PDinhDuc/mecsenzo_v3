import { watch } from 'vue'
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

export default defineNuxtPlugin((nuxtApp) => {
  if (!process.client) return

  const config = useRuntimeConfig()
  const authStore = useAuthStore()
  window.Pusher = Pusher

  watch(() => authStore.token, (token) => {
  if (!token) return

  const tokenCopy = token

  if (!window.__NUXT_ECHO__) {
    window.__NUXT_ECHO__ = new Echo({
      broadcaster: 'pusher',
      key: config.public.pusherKey,
      cluster: 'mt1',
      wsHost: config.public.wsHost,
      wsPort: config.public.wsPort,
      forceTLS: false,
      disableStats: true,
      enabledTransports: ['ws', 'wss'],
      authEndpoint: `${config.public.apiBase}/broadcasting/auth`,
      auth: {
        headers: {
          Authorization: `Bearer ${tokenCopy}`
        }
      }
    })
  }

  if (!nuxtApp.$echo) {
    nuxtApp.provide('echo', window.__NUXT_ECHO__)
  }
}, { immediate: true })
})
