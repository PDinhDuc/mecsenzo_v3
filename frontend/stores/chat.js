import { defineStore } from 'pinia'

export const useChatStore = defineStore('chat', {
  state: () => ({
    conversations: [],
    messages: {},
  }),

  actions: {
    async fetchConversations() {
      this.conversations = await $fetch(`${useRuntimeConfig().public.apiBase}/api/conversations`, {
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`,
        },
      })
    },

    async fetchMessages(conversationId) {
      const res = await $fetch(`${useRuntimeConfig().public.apiBase}/api/conversations/${conversationId}/messages`, {
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`,
        },
      })
      this.messages[conversationId] = res
    },

    async sendMessage(conversationId, content) {
      try {
        const url = `/api/conversations/${conversationId}/send`
        await $fetch(url, {
          method: 'POST',
          baseURL: useRuntimeConfig().public.apiBase,
          headers: {
            Authorization: `Bearer ${useAuthStore().token}`,
          },
          body: {
            content,
          },
        })
      } catch (err) {
        console.error('Gửi tin nhắn thất bại:', err)
        throw err
      }
    },
  },
})
