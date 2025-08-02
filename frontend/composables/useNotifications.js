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
      .notification(() => {
        loadNotification()
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
    res.notifications.map((noti)=>{
      if(noti.read_at === null) unreadCount.value++
    })
    if(res.notifications.length < 10) hasMore.value = false
    notifications.value = [...notifications.value, ...res.notifications]
  }

  const getUnReadNotification = async ()=>{
    const url = `/api/notifications/unread`
    try{
      const res = await $fetch(url,{
        baseURL: useRuntimeConfig().public.apiBase,
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`
        }
      })
      console.log(res);
    }catch(err){
      console.log(err);
    }
  }

  const markAsRead = async (id) =>{
    const url = `/api/notifications/read/${id}`
    try{
      const res = await $fetch(url,{
        baseURL: useRuntimeConfig().public.apiBase,
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`
        }
      })
      unreadCount.value--
    }catch(err){
      console.log(err);
    }
  }

  const markAllAsRead = async () => {
    const url = `/api/notifications/read-all`
    try{
      await $fetch(url,{
        baseURL: useRuntimeConfig().public.apiBase,
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`
        }
      })
      unreadCount.value = 0
    }catch(err){
      console.log(err);
    }
  }

  const deleteNotifi = async (id)=>{
    const url = `/api/notifications/${id}`
    try{
      await $fetch(url,{
        baseURL: useRuntimeConfig().public.apiBase,
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`
        },
        method: 'DELETE'
      })
    }catch(err){
      console.log(err);
    }
  }

  const deleteAll = async ()=> {
    const url = `/api/notifications`
    try{
      await $fetch(url,{
        baseURL: useRuntimeConfig().public.apiBase,
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`
        },
        method: 'DELETE'
      })
    }catch(err){
      console.log(err);
    }
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
    getUnReadNotification,
    unreadCount,
    listen,
    markAsRead,
    markAllAsRead,
    deleteNotifi,
    deleteAll
  }
}
