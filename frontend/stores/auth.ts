import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null,
    token: null,
    isInitialLoading: true
  }),

  getters: {
    isLoggedIn: (state) => !!state.token
  },

  actions: {
    async login(email, password) {
      const config = useRuntimeConfig()
      const baseURL = process.server ? 'http://proxy/api' : config.public.apiBase
      
      try {
        const response = await fetch(`${baseURL}/auth/login`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ email, password })
        })

        const data = await response.json()

        if (data.token) {
          this.token = data.token
          this.user = data.user
          
          const tokenCookie = useCookie('auth_token', { maxAge: 60 * 60 * 24 * 7 })
          tokenCookie.value = data.token
          
          return { success: true }
        } else {
          return { success: false, error: data.error || 'Login failed' }
        }
      } catch (err) {
        return { success: false, error: 'Connection error' }
      }
    },

    async fetchUser() {
      if (!this.token) return

      const config = useRuntimeConfig()
      const baseURL = process.server ? 'http://proxy/api' : config.public.apiBase

      try {
        const response = await fetch(`${baseURL}/auth/user`, {
          headers: {
            'Authorization': `Bearer ${this.token}`
          }
        })
        const data = await response.json()
        if (data.id) {
          this.user = data
        } else {
          this.logout()
        }
      } catch (err) {
        this.logout()
      }
    },

    logout() {
      this.token = null
      this.user = null
      const tokenCookie = useCookie('auth_token')
      tokenCookie.value = null
      navigateTo('/login')
    },

    async init() {
      this.isInitialLoading = true
      try {
        const tokenCookie = useCookie('auth_token')
        if (tokenCookie.value) {
          this.token = tokenCookie.value
          await this.fetchUser()
        }
        // Small delay so you can actually see the "Checking..." state in dev
        if (process.dev) await new Promise(r => setTimeout(r, 500))
      } finally {
        this.isInitialLoading = false
      }
    }
  }
})
