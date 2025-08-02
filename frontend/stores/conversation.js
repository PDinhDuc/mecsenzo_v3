import { defineStore } from 'pinia'

export const useConversationStore = defineStore('chat', {
  state: () => ({
    conversationsIndividual: [],
    conversationsSpace: [],
    conversationInfor: {},
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

    async getConversationInfor(conversationId){
      const url = `${useRuntimeConfig().public.apiBase}/api/conversations/${conversationId}`
      try{
        const res = await $fetch(url,{
          headers: {
            Authorization: `Bearer ${useAuthStore().token}`
          }
        })
        this.conversationInfor = res
      }catch(err){
        console.log(err);
      }
    }
  },
})
