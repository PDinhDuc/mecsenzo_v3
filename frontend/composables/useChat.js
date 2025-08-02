import { ref, onMounted, onBeforeUnmount } from 'vue'
import { useNuxtApp } from '#app'
import { useAuthStore } from '@/stores/auth'

export default function useChat(conversationId) {
  const { $echo } = useNuxtApp()
  const messages = ref([])
  const isListening = ref(false)
  const error = ref(null)
  const currentPage = ref(1)
  const hasMore = ref(true)
  const isShowLoader = ref(true)

  let channel = null

  const listen = () => {
    if (!conversationId || !$echo) {
      error.value = 'Missing conversationId or Echo instance.'
      return
    }

    try {
      channel = $echo.private(`conversation.${conversationId}`)

      channel.listen('MessageSent', (e) => {
        if (e.message) {
          messages.value.unshift(e.message)
        }
      })

      isListening.value = true
    } catch (err) {
      error.value = err
    }
  }

  const stop = () => {
    if (channel) {
      $echo.leave(`private-conversation.${conversationId}`)
      isListening.value = false
    }
  }

  const loadMessages = async () => {
    if (!hasMore.value) return
    const url = `/api/conversations/${conversationId}/messages?page=${currentPage.value}`
    const res = await $fetch(url, {
      baseURL: useRuntimeConfig().public.apiBase,
      headers: {
        Authorization: `Bearer ${useAuthStore().token}`
      }
    })
    if (res.data.length < 10) hasMore.value = false
    isShowLoader.value = false
    messages.value = [...messages.value,...res.data]
    currentPage.value++
  }

  const sendMessage = async (content) => {
    try {
      const url = `/api/conversations/${conversationId}/send`
      await $fetch(url, {
        method: 'POST',
        baseURL: useRuntimeConfig().public.apiBase,
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`,
        },
        body: {
          "conversation_id": conversationId,
          content,
        },
      })
    } catch (err) {
      console.error('Gửi tin nhắn thất bại:', err)
      throw err
    }
  }

  onMounted(async () => {
    await loadMessages()
    listen()
  })

  onBeforeUnmount(() => {
    stop()
  })

  return {
    messages,
    isListening,
    error,
    loadMessages,
    sendMessage,
    listen,
    stop,
    isShowLoader
  }
}
