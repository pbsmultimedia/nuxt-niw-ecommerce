<template>
  <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl overflow-hidden hover:border-blue-500/50 transition-all group">
    <div class="p-6">
      <div class="flex justify-between items-start mb-4">
        <span class="px-3 py-1 bg-slate-700 rounded-full text-xs font-semibold text-slate-300">
          {{ product.type.replace('Product', '') }}
        </span>
        <span class="text-2xl font-bold text-emerald-400">${{ product.price }}</span>
      </div>
      
      <h2 class="text-2xl font-bold mb-3 group-hover:text-blue-400 transition-colors">{{ product.title }}</h2>
      <div 
        class="text-slate-400 mb-6 text-sm line-clamp-2"
        v-html="product.description || 'No description available'"
      ></div>

      <div class="space-y-3 p-4 bg-slate-900/50 rounded-xl border border-slate-700/50">
        <h3 class="text-xs font-uppercase tracking-wider text-slate-500 font-bold">SPECIFICATIONS</h3>
        <component 
          :is="detailComponent" 
          :details="product.details" 
        />
      </div>

      <button 
        @click="cart.addItem(product)"
        class="w-full mt-6 py-3 px-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 rounded-xl font-bold transition-all shadow-lg shadow-blue-900/20 active:scale-95 flex items-center justify-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
        Add to Cart
      </button>
    </div>
  </div>
</template>

<script setup>
// import { computed, resolveComponent } from 'vue'
// import { useCartStore } from '~/stores/cart'

const cart = useCartStore()
const props = defineProps({
  product: Object
})

const detailComponent = computed(() => {
  if (props.product.type === 'PhysicalProduct') {
    return resolveComponent('PhysicalProductDetails')
  }
  if (props.product.type === 'DigitalProduct') {
    return resolveComponent('DigitalProductDetails')
  }
  return null
})
</script>
