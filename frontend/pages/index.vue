<template>
  <div class="min-h-screen bg-slate-900 text-white p-8">
    <div class="max-w-6xl mx-auto">
      <div class="flex justify-end mb-4">
        <!-- Auth Loading -->
        <div v-if="auth.isInitialLoading" class="flex items-center gap-2 bg-slate-800/30 px-3 py-2 rounded-full border border-slate-700/50">
          <svg class="animate-spin h-4 w-4 text-blue-500" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
          </svg>
        </div>

        <!-- Auth User Bar -->
        <div v-else-if="auth.isLoggedIn" class="flex items-center gap-4 bg-slate-800/50 p-2 pl-4 rounded-full border border-slate-700">
          <NuxtLink to="/account/orders" class="text-sm font-medium text-slate-300 hover:text-blue-400 transition-colors">
            {{ auth.user?.name || 'User' }}
          </NuxtLink>
          <div class="w-px h-4 bg-slate-700"></div>
          <button @click="auth.logout()" class="bg-slate-700 hover:bg-slate-600 px-3 py-1 rounded-full text-xs transition-colors">
            Log out
          </button>
        </div>

        <!-- Login Link -->
        <NuxtLink v-else to="/login" class="text-blue-400 hover:text-blue-300 transition-colors text-sm font-bold">
          Log In
        </NuxtLink>
      </div>

      <header class="mb-12 text-center">
        <h1 class="text-5xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400 mb-4">
          Polymorphic Shop
        </h1>
        <p class="text-slate-400 text-xl">Nuxt 3 + WinterCMS Polymorphic Products Demo</p>
      </header>

      <div v-if="pending" class="flex justify-center py-20">
        <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
      </div>

      <ShopError
        v-else-if="error"
        message="Failed to load products. Make sure the backend is running."
        @retry="refresh"
      />

      <div v-else class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
        <ProductCard
          v-for="product in products?.data"
          :key="product.id"
          :product="product"
        />
      </div>

      <!-- Floating Cart Indicator -->
      <div v-if="cart.totalItems > 0" class="fixed bottom-8 right-8 z-50">
        <div class="bg-blue-600 p-2 rounded-2xl shadow-xl shadow-blue-500/30 border border-blue-400/50 flex items-center gap-2 animate-in fade-in slide-in-from-bottom-4 duration-300">
          <NuxtLink to="/cart" class="flex items-center gap-2 px-3 py-2 hover:bg-white/10 rounded-xl transition-colors">
            <div class="bg-white text-blue-600 font-bold px-2 py-0.5 rounded-lg text-sm">
              {{ cart.totalItems }}
            </div>
            <span class="font-bold text-lg">${{ cart.totalPrice }}</span>
          </NuxtLink>
          <div class="w-px h-8 bg-white/20"></div>
          <button @click="cart.clearCart()" class="p-2 text-blue-200 hover:text-white transition-colors" title="Clear Cart">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
useSeoMeta({
  title: 'Polymorphic Shop - Products',
  description: 'Explore our range of physical and digital products powered by Nuxt 3 and WinterCMS.'
})

const auth = useAuthStore()
const cart = useCartStore()
const config = useRuntimeConfig()
const baseURL = config.public.apiBase
const { data: products, pending, error, refresh } = await useFetch('/shop/products', {
  baseURL,
  server: false
})

</script>
