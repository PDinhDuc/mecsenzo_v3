export const useNotifications = () => {
  const notifications = ref([])
  const unreadCount = ref(0)

  const { $echo } = useNuxtApp()
  const authStore = useAuthStore()
  const user = authStore.user

  const listen = () => {
    if (!user || !user.id) return

    $echo.private(`App.Models.User.${user.id}`)
      .notification((notification) => {
        notifications.value.unshift(notification)
        unreadCount.value++
      })
  }

  const markAllAsRead = () => {
    unreadCount.value = 0
  }

  return {
    notifications,
    unreadCount,
    listen,
    markAllAsRead
  }
}
