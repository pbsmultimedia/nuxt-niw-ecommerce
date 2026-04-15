import { defineStore } from 'pinia'

export const useCartStore = defineStore('cart', {
  state: () => ({
    items: [],
    sessionId: null,
    isInitialLoading: true
  }),
  
  getters: {
    totalItems: (state) => state.items.reduce((total, item) => total + item.quantity, 0),
    totalPrice: (state) => state.items.reduce((total, item) => total + (item.price * item.quantity), 0).toFixed(2),
  },
  
  actions: {
    async init() {
      this.isInitialLoading = true
      try {
        if (process.client) {
          // Handle Session ID
          const sessionCookie = useCookie('shop_session', { maxAge: 60 * 60 * 24 * 30 })
          if (!sessionCookie.value) {
            sessionCookie.value = crypto.randomUUID()
          }
          this.sessionId = sessionCookie.value

          // Try to fetch from backend first
          await this.fetchFromBackend()
          
          // If backend empty, check localStorage (migration/fallback)
          if (this.items.length === 0) {
            const savedCart = localStorage.getItem('shop_cart')
            if (savedCart) {
              try {
                this.items = JSON.parse(savedCart)
                await this.syncWithBackend()
              } catch (e) {}
            }
          }
          // Small delay for dev visibility
          if (process.dev) await new Promise(r => setTimeout(r, 500))
        }
      } finally {
        this.isInitialLoading = false
      }
    },

    async fetchFromBackend() {
      const config = useRuntimeConfig()
      const baseURL = process.server ? 'http://proxy/api' : config.public.apiBase
      
      try {
        const response = await fetch(`${baseURL}/shop/cart/get?session_id=${this.sessionId}`)
        const data = await response.json()
        if (data.items) {
          this.items = data.items
        }
      } catch (err) {
        console.error('Failed to fetch cart from backend', err)
      }
    },

    async syncWithBackend() {
      const config = useRuntimeConfig()
      const auth = useAuthStore()
      const baseURL = process.server ? 'http://proxy/api' : config.public.apiBase
      
      try {
        await fetch(`${baseURL}/shop/cart/sync`, {
          method: 'POST',
          headers: { 
            'Content-Type': 'application/json',
            ...(auth.token ? { 'Authorization': `Bearer ${auth.token}` } : {})
          },
          body: JSON.stringify({
            session_id: this.sessionId,
            items: this.items
          })
        })
        
        // Also keep localStorage as cache
        localStorage.setItem('shop_cart', JSON.stringify(this.items))
      } catch (err) {
        console.error('Failed to sync cart with backend', err)
      }
    },

    async addItem(product) {
      const existingItem = this.items.find(item => item.id === product.id)
      if (existingItem) {
        existingItem.quantity++
      } else {
        this.items.push({
          id: product.id,
          title: product.title,
          price: parseFloat(product.price),
          type: product.type.replace('Product', ''),
          quantity: 1
        })
      }
      await this.syncWithBackend()
    },
    
    async removeItem(productId) {
      this.items = this.items.filter(item => item.id !== productId)
      await this.syncWithBackend()
    },
    
    async clearCart() {
      this.items = []
      if (process.client) {
        localStorage.removeItem('shop_cart')
      }
      await this.syncWithBackend()
    }
  }
})
