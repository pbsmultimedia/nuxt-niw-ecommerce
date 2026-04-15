<template>
  <div class="min-h-screen bg-slate-900 text-white p-8">
    <div class="max-w-4xl mx-auto">
      <header class="mb-12 flex justify-between items-center">
        <div>
          <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
            Shopping Cart - {{ test }}
          </h1>
          <p class="text-slate-400 mt-2">Review your items before checkout</p>
        </div>
        <NuxtLink to="/" class="text-blue-400 hover:text-blue-300 transition-colors flex items-center gap-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
          Continue Shopping
        </NuxtLink>
      </header>

      <!-- Loading State -->
      <div v-if="cart.isInitialLoading" class="flex flex-col items-center justify-center py-20 text-slate-400">
        <svg class="animate-spin h-10 w-10 mb-4 text-blue-500" fill="none" viewBox="0 0 24 24">
          <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
          <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        <p class="font-medium animate-pulse">Syncing your cart...</p>
      </div>

      <!-- Empty State -->
      <div v-else-if="cart.items.length === 0" class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-12 text-center">
        <div class="bg-slate-700 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-6">
          <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        </div>
        <h2 class="text-2xl font-bold mb-2">Your cart is empty</h2>
        <p class="text-slate-400 mb-8">Looks like you haven't added anything yet.</p>
        <NuxtLink to="/" class="inline-block py-3 px-8 bg-blue-600 hover:bg-blue-500 rounded-xl font-bold transition-all">
          Browse Products
        </NuxtLink>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- List of Items -->
        <div class="lg:col-span-2 space-y-4">
          <div v-for="item in cart.items" :key="item.id" 
               class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-6 flex items-center gap-6 group hover:border-slate-600 transition-all">
            <div class="bg-slate-700 w-16 h-16 rounded-xl flex items-center justify-center text-2xl font-bold text-slate-500">
              {{ item.title.charAt(0) }}
            </div>
            
            <div class="flex-1">
              <h3 class="font-bold text-lg group-hover:text-blue-400 transition-colors">{{ item.title }}</h3>
              <p class="text-xs text-slate-500 uppercase tracking-wider">{{ item.type }}</p>
              <p class="text-emerald-400 font-bold mt-1">${{ item.price }}</p>
            </div>

            <div class="flex items-center gap-3 bg-slate-900/50 p-2 rounded-xl border border-slate-700">
              <button @click="item.quantity > 1 ? item.quantity-- : cart.removeItem(item.id)" 
                      class="w-8 h-8 flex items-center justify-center hover:bg-slate-700 rounded-lg transition-colors">
                -
              </button>
              <span class="w-8 text-center font-bold">{{ item.quantity }}</span>
              <button @click="item.quantity++" 
                      class="w-8 h-8 flex items-center justify-center hover:bg-slate-700 rounded-lg transition-colors">
                +
              </button>
            </div>

            <button @click="cart.removeItem(item.id)" class="text-slate-500 hover:text-red-400 transition-colors">
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
          </div>
        </div>

        <!-- Summary -->
        <div class="lg:col-span-1">
          <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-6 sticky top-8">
            <h2 class="text-xl font-bold mb-6 pb-4 border-b border-slate-700">Order Summary</h2>
            
            <div class="space-y-4 mb-8">
              <div class="flex justify-between text-slate-400">
                <span>Subtotal ({{ cart.totalItems }} items)</span>
                <span>${{ cart.totalPrice }}</span>
              </div>
              <div class="flex justify-between text-slate-400">
                <span>Shipping</span>
                <span class="text-emerald-400">FREE</span>
              </div>
              <div class="flex justify-between text-xl font-bold pt-4 border-t border-slate-700">
                <span>Total</span>
                <span class="text-blue-400">${{ cart.totalPrice }}</span>
              </div>
            </div>

            <NuxtLink to="/checkout" class="w-full py-4 bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-500 hover:to-teal-500 rounded-xl font-bold text-lg transition-all shadow-lg shadow-emerald-900/20 active:scale-95 text-center block">
              Checkout
            </NuxtLink>
            
            <p class="text-center text-xs text-slate-500 mt-4">Secure payment powered by WinterCMS</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// testing layouts
definePageMeta({
  layout: 'custom'
})

const cart = useCartStore()
</script>
