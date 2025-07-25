import { defineStore } from "pinia"

export const useUserStore = defineStore('user', {
  state: () => ({
    user: null,
    searchUserResult: [],
  }),

  actions: {
    async searchUser(query) {
      if (!query.trim()) {
        this.searchUserResult = []
        return
      }

      const config = useRuntimeConfig()
      const url = `${config.public.apiBase}/api/search-friends`

      try {
        const data = await $fetch(url, {
          method: 'GET',
          headers: {
            Authorization: `Bearer ${useAuthStore().token}`,
          },
          query: { query },
        })
        this.searchUserResult = data.data || []
      } catch (err) {
        console.error('Exception while searching:', err)
        this.searchUserResult = []
      }
    },
  },

  getters: {
    getUser: (state) => state.user,
  }
})
