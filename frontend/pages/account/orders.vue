<template>
  <div class="min-h-screen bg-slate-900 text-white p-8">
    <div class="max-w-6xl mx-auto">
      <header class="mb-12">
        <NuxtLink to="/" class="text-slate-500 hover:text-blue-400 transition-colors text-sm mb-4 inline-block">
          ← Back to Shop
        </NuxtLink>
        <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
          My Orders
        </h1>
        <p class="text-slate-400 mt-2">Manage your past purchases and downloads</p>
      </header>

      <div v-if="loading" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>

      <div v-else-if="orders.length === 0" class="bg-slate-800/50 border border-slate-700 rounded-3xl p-12 text-center">
        <p class="text-slate-400 text-lg mb-6">You haven't placed any orders yet.</p>
        <NuxtLink to="/" class="inline-block py-3 px-8 bg-blue-600 hover:bg-blue-500 rounded-xl font-bold transition-all">
          Start Shopping
        </NuxtLink>
      </div>

      <div v-else class="space-y-6">
        <div v-for="order in orders" :key="order.id" class="bg-slate-800/50 border border-slate-700 rounded-2xl p-6 hover:border-slate-600 transition-colors">
          <div class="flex flex-wrap justify-between items-start gap-4 mb-6">
            <div>
              <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Order ID</p>
              <h3 class="text-xl font-mono font-bold text-white">#{{ order.id.toString().padStart(6, '0') }}</h3>
            </div>
            <div>
              <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Date</p>
              <p class="font-medium">{{ formatDate(order.created_at) }}</p>
            </div>
            <div>
              <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Status</p>
              <span :class="getStatusClass(order.status)" class="px-3 py-1 rounded-full text-xs font-bold uppercase">
                {{ order.status }}
              </span>
            </div>
            <div class="text-right">
              <p class="text-xs font-bold text-slate-500 uppercase tracking-widest mb-1">Total</p>
              <p class="text-2xl font-bold text-blue-400">${{ order.total }}</p>
            </div>
          </div>

          <div class="border-t border-slate-700 pt-6">
            <h4 class="text-sm font-bold text-slate-400 mb-4 uppercase tracking-wide">Items</h4>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="item in order.items" :key="item.id" class="flex items-center gap-4 bg-slate-900/50 p-4 rounded-xl border border-slate-700/50">
                <div class="w-10 h-10 rounded-lg bg-slate-800 flex items-center justify-center text-blue-500 font-bold shrink-0">
                  {{ item.quantity }}
                </div>
                <div class="flex-1 min-w-0">
                  <p class="font-bold text-white truncate">{{ item.product?.title || 'Unknown Product' }}</p>
                  <p class="text-xs text-slate-500">${{ item.price }} / item</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()
const orders = ref([])
const loading = ref(true)

onMounted(async () => {
  if (!auth.isLoggedIn) {
    navigateTo('/login')
    return
  }

  try {
    const config = useRuntimeConfig()
    const response = await fetch(`${config.public.apiBase}/shop/orders/my`, {
      headers: {
        'Authorization': `Bearer ${auth.token}`
      }
    })
    const data = await response.json()
    orders.value = data.orders || []
  } catch (err) {
    console.error('Failed to load orders', err)
  } finally {
    loading.value = false
  }
})

const formatDate = (dateString) => {
  return new Date(dateString).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  })
}

const getStatusClass = (status) => {
  switch (status.toLowerCase()) {
    case 'delivered': return 'bg-emerald-500/20 text-emerald-400'
    case 'shipped': return 'bg-blue-500/20 text-blue-400'
    case 'pending': return 'bg-amber-500/20 text-amber-400'
    case 'cancelled': return 'bg-red-500/20 text-red-400'
    default: return 'bg-slate-500/20 text-slate-400'
  }
}
</script>
