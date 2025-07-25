import { useAuthStore } from "@/stores/auth"
import {ref, onMounted} from 'vue'

export const useNotifications = () => {
  const notifications = ref([])
  const unreadCount = ref(0)
  const hasMore = ref(true)

  const { $echo } = useNuxtApp()
  const user = useAuthStore().user

  const listen = () => {
    if (!user || !user.id) return

    $echo.private(`App.Models.User.${user.id}`)
      .notification((notification) => {
        notifications.value.unshift(notification)
        unreadCount.value++
      })
  }

  const stop = ()=>{
    $echo.leave(`App.Models.User.${user.id}`)
  }

  const loadNotification = async () =>{
    const url = `/api/notifications`
    const res = await $fetch(url,{
      baseURL: useRuntimeConfig().public.apiBase,
      headers: {
        Authorization: `Bearer ${useAuthStore().token}`
      }
    })
    if(res.notifications.length < 10) hasMore.value = false
    notifications.value = [...notifications.value, ...res.notifications]
  }

  const markAllAsRead = () => {
    unreadCount.value = 0
  }

  onMounted( async () => {
    listen()
    await loadNotification()
  })

  onBeforeUnmount( ()=>{
    stop()
  })

  return {
    notifications,
    unreadCount,
    listen,
    markAllAsRead
  }
}
