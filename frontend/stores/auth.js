import { defineStore } from 'pinia'
import { useStorage } from '@vueuse/core'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: useStorage('auth_token', '')
  }),

  getters: {
    isLoggedIn: (state) => !!state.token
  },

  actions: {
    async login(email, password) {
      try {
        const config = useRuntimeConfig()
        const res = await $fetch(`${config.public.apiBase}/api/auth/login`, {
          method: 'POST',
          body: { email, password }
        })
        if (!res?.token) {
          throw new Error('Token không tồn tại trong response')
        }

        this.token = res.token
        
        await this.fetchUser()
      } catch (err) {
        throw createError({
          statusCode: err.status || 500,
          statusMessage: err?.data?.message || 'Login failed'
        })
      }
    },

    async register(name, email, password) {
      try {
        const config = useRuntimeConfig()
        const res = await $fetch(`${config.public.apiBase}/api/auth/register`, {
          method: 'POST',
          body: { name, email, password }
        })

        if (!res?.token) {
          throw new Error('Token không tồn tại trong response')
        }

        this.token = res.token
        await this.fetchUser()
      } catch (err) {
        throw createError({
          statusCode: err.status || 500,
          statusMessage: err?.data?.message || 'Register failed'
        })
      }
    },

    async fetchUser() {
      if (!this.token) return
      try {
        const config = useRuntimeConfig()
        const res = await $fetch(`${config.public.apiBase}/api/auth/user`, {
          headers: {
            Authorization: `Bearer ${this.token}`
          }
        })
        this.user = res
      } catch (err) {
        this.logout()
      }
    },

    async restoreSession() {
      const tokentmp = localStorage.getItem('auth_token')
      if (tokentmp) {
        this.token = tokentmp
        try {
          const config = useRuntimeConfig()
          const { data, error } = await useFetch('/api/auth/user', {
            baseURL: config.public.apiBase,
            headers: {
              Authorization: `Bearer ${this.token}`,
            },
          })
          if (error.value) {
            console.log('error');
            
            this.logout()
            return false
          } else {
            this.user = data.value
            return true
          }
        } catch (e) {
          this.logout()
          return false
        }
      }
      return false
    },

    logout() {
      this.user = null
      this.token = ''
    }
  }
})
