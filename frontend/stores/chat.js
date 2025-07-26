import { defineStore } from 'pinia'

export const useChatStore = defineStore('chat', {
  state: () => ({
    conversationsIndividual: [],
    conversationsSpace: [],
    messages: {},
    pageOfIndividual: 1,
    pageOfSpace: 1
  }),

  actions: {
    async fetchConversationsIndividual() {
      try {
        const res = await $fetch(`${useRuntimeConfig().public.apiBase}/api/conversations-individual?page=${this.pageOfIndividual}`, {
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`,
        },
        })
        this.conversationsIndividual = [...this.conversationsIndividual, ...res]
        this.pageOfIndividual += 1
      }catch(err){
        this.conversationsIndividual = []
        this.pageOfIndividual = 1
        console.log(err);
      }
    },

    async fetchConversationsSpace() {
      try {
        const res = await $fetch(`${useRuntimeConfig().public.apiBase}/api/conversations-space?page=${this.pageOfSpace}`, {
        headers: {
          Authorization: `Bearer ${useAuthStore().token}`,
        },
        })
        this.conversationsSpace = [...this.conversationsSpace, ...res]
        this.pageOfSpace += 1
      }catch(err){
        this.conversationsSpace = []
        this.pageOfSpace = 1
        console.log(err);
      }
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
            "conversation_id": conversationId,
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
