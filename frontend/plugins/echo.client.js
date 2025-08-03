// plugins/echo.client.ts
import Echo from 'laravel-echo'
import Pusher from 'pusher-js'

export default defineNuxtPlugin(async(nuxtApp) => {
  await $fetch('http://localhost:8000/sanctum/csrf-cookie', {
  credentials: 'include'
})
  const config = useRuntimeConfig()
  const authStore = useAuthStore()
  

  window.Pusher = Pusher

  const echo = new Echo({
    broadcaster: 'pusher',
    key: config.public.pusherKey,
    cluster: 'mt1',
    wsHost: config.public.wsHost,
    wsPort: config.public.wsPort,
    forceTLS: false,
    encrypted: false,
    disableStats: true,
    enabledTransports: ['ws', 'wss'],
    authEndpoint: `${config.public.apiBase}/broadcasting/auth`,
    auth: {
      headers: {
        Authorization: `Bearer ${authStore.token}`
      }
    },
    withCredentials: true,
  })

  nuxtApp.provide('echo', echo)
})

