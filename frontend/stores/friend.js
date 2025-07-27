import { defineStore } from "pinia"

export const useFriendStore = defineStore('friend', {
  state: () => ({
    searchFriendResult: [],
    friendUserSent: [],
    friendUserReceiver: [],
    friendUser: []
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
          }
        })
      } catch (err) {
        console.error('Gửi tin nhắn thất bại:', err)
        throw err
      }
    },

    async fetchFriendOfUser(status){
      const config = useRuntimeConfig()
      const url = `${config.public.apiBase}/api/friend`
      try {
        const res = await $fetch(url,{ 
          method: 'POST',
          headers: {
            Authorization: `Bearer ${useAuthStore().token}`
          },
          body: {
            status: status === 'accepted' ? 'accepted' : 'pending'
          }
        })
        let friendUserSenttmp = []
        let friendUserReceivertmp = []
        let friendUsertmp = []
        res.map((friend)=>{
          if (status === 'accepted') {
            friendUsertmp = [...friendUsertmp, friend]
          } 
          if(friend.friendships.user_id == useAuthStore().user.id){
            friendUserSenttmp = [...friendUserSenttmp, friend]
          }else{
            friendUserReceivertmp = [...friendUserReceivertmp, friend]
          }
        })
        this.friendUser = [...friendUsertmp]
        this.friendUserSent = [...friendUserSenttmp]
        this.friendUserReceiver = [...friendUserReceivertmp]
      }catch(err){
        console.log(err);
      }
    },
    
    async handleActionRequestFriend (id, action){
      const config = useRuntimeConfig()
      const url = `${config.public.apiBase}/api/friend-request/${id}/${action}`
      try{
        const res = await $fetch(url, {
          method: action === 'cancel' ? 'DELETE' : 'POST',
          headers: {
            Authorization: `Bearer ${useAuthStore().token}` 
          }
        })
        console.log(res);
        
      }catch(err){
        console.log(err);
      }
    }
  },
})
