import { defineStore } from "pinia"

export const useFriendStore = defineStore('friend', {
  state: () => ({
    searchFriendResult: [],
  }),

  actions: {
    async searchFriend(query) {
      if (!query.trim()) {
        this.searchFriendResult = []
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
        this.searchFriendResult = data.data || []
      } catch (err) {
        console.error('Exception while searching:', err)
        this.searchFriendResult = []
      }
    },

    async handleRequestFriend (friend_id, action){
      const config = useRuntimeConfig()
      let url
      switch (action){
        case 'send':
          url = `${config.public.apiBase}/api/friend-request/invitation`;
          break;
        case 'accept':
          url = `${config.public.apiBase}/api/friend-request/${friend_id}/accept`;
          break;
        case 'cancel':
          url = `${config.public.apiBase}/api/friend-request/${friend_id}/cancel`;
      }

      try {
        await $fetch(url, {
          method: 'POST',
          headers: {
            Authorization: `Bearer ${useAuthStore().token}`,
          },
          body: {
            friend_id,
          }
        })
      } catch (err) {
        console.error('Gửi tin nhắn thất bại:', err)
        throw err
      }
    }
  },
})
