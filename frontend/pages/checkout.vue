<template>
  <div class="min-h-screen bg-slate-900 text-white p-8">
    <div class="max-w-4xl mx-auto">
      <header class="mb-12 text-center">
        <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400">
          Checkout
        </h1>
        <p class="text-slate-400 mt-2">Complete your order</p>
      </header>

      <div v-if="orderPlaced" class="bg-slate-800/50 backdrop-blur-sm border border-emerald-500/30 p-12 rounded-3xl text-center">
        <div class="bg-emerald-500/20 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6 text-emerald-400">
          <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        </div>
        <h2 class="text-3xl font-bold mb-4">Thank you for your order!</h2>
        <p class="text-slate-400 mb-8 max-w-md mx-auto">Your order has been placed successfully. A confirmation email has been sent to your inbox.</p>
        <NuxtLink to="/" class="inline-block py-3 px-8 bg-emerald-600 hover:bg-emerald-500 rounded-xl font-bold transition-all shadow-lg shadow-emerald-900/20">
          Return to Shop
        </NuxtLink>
      </div>

      <div v-else class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Checkout Form -->
        <div class="lg:col-span-2 space-y-8">
          <section class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-8">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
              <span class="w-8 h-8 rounded-full bg-blue-600 flex items-center justify-center text-sm">1</span>
              Shipping Information
            </h2>

            <div class="grid grid-cols-2 gap-4">
              <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">First Name</label>
                <input type="text" v-model="form.firstName" :class="['w-full bg-slate-900 border rounded-xl p-3 outline-none transition-all', hasError('firstName') ? 'border-red-500 focus:border-red-500' : 'border-slate-700 focus:border-blue-500']" placeholder="John">
                <span v-if="hasError('firstName')" class="text-red-500 text-xs mt-1 block">{{ getErrors('firstName')[0] }}</span>
              </div>
              <div class="col-span-2 md:col-span-1">
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Last Name</label>
                <input type="text" v-model="form.lastName" :class="['w-full bg-slate-900 border rounded-xl p-3 outline-none transition-all', hasError('lastName') ? 'border-red-500 focus:border-red-500' : 'border-slate-700 focus:border-blue-500']" placeholder="Doe">
                <span v-if="hasError('lastName')" class="text-red-500 text-xs mt-1 block">{{ getErrors('lastName')[0] }}</span>
              </div>
              <div class="col-span-2">
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Email Address</label>
                <input type="text" v-model="form.email" :class="['w-full bg-slate-900 border rounded-xl p-3 outline-none transition-all', hasError('email') ? 'border-red-500 focus:border-red-500' : 'border-slate-700 focus:border-blue-500']" placeholder="john@example.com">
                <span v-if="hasError('email')" class="text-red-500 text-xs mt-1 block">{{ getErrors('email')[0] }}</span>
              </div>
              <div class="col-span-2">
                <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Street Address</label>
                <input type="text" v-model="form.address" :class="['w-full bg-slate-900 border rounded-xl p-3 outline-none transition-all', hasError('address') ? 'border-red-500 focus:border-red-500' : 'border-slate-700 focus:border-blue-500']" placeholder="123 Main St">
                <span v-if="hasError('address')" class="text-red-500 text-xs mt-1 block">{{ getErrors('address')[0] }}</span>
              </div>
            </div>
          </section>

          <section class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-8 opacity-50 pointer-events-none">
            <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
              <span class="w-8 h-8 rounded-full bg-slate-600 flex items-center justify-center text-sm">2</span>
              Payment Method
            </h2>
            <p class="text-slate-400">Payment integration is currently disabled in demo mode.</p>
          </section>
        </div>

        <!-- Summary -->
        <div class="lg:col-span-1">
          <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-2xl p-6 sticky top-8">
            <h2 class="text-xl font-bold mb-6 pb-4 border-b border-slate-700">Order Summary</h2>

            <div v-if="cart.isInitialLoading" class="flex flex-col items-center justify-center py-20 text-slate-400">
              <svg class="animate-spin h-10 w-10 mb-4 text-blue-500" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              <p class="font-medium animate-pulse">Syncing your cart...</p>
            </div>

            <div v-else>
              <div class="space-y-4 mb-8">
                <div v-for="item in cart.items" :key="item.id" class="flex justify-between text-sm">
                  <span class="text-slate-400">{{ item.quantity }}x {{ item.title }}</span>
                  <span>${{ (item.price * item.quantity).toFixed(2) }}</span>
                </div>

                <div class="flex justify-between text-xl font-bold pt-4 border-t border-slate-700 mt-4">
                  <span>Total</span>
                  <span class="text-blue-400">${{ cart.totalPrice }}</span>
                </div>
              </div>
            </div>
            
            <button
              @click="submitOrder"
              class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed rounded-xl font-bold text-lg transition-all shadow-lg shadow-blue-900/20 active:scale-95 flex items-center justify-center gap-2"
            >
              <svg v-if="placingOrder" class="animate-spin h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
              <span>Place Order</span>
            </button>
            <NuxtLink to="/cart" class="block text-center mt-4 text-sm text-slate-500 hover:text-slate-300 transition-colors">← Back to Cart</NuxtLink>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { z } from 'zod'

const auth = useAuthStore()
const cart = useCartStore()
const orderPlaced = ref(false)
const placingOrder = ref(false)

const form = reactive({
  firstName: '',
  lastName: '',
  email: '',
  address: ''
})

const errors = ref<Record<string, string[] | undefined>>({})

const checkoutSchema = z.object({
  firstName: z.string().min(1, 'First name is required'),
  lastName: z.string().min(1, 'Last name is required'),
  email: z.string().min(1, 'Email is required').email('Invalid email format'),
  address: z.string().min(1, 'Address is required')
})

const isValid = computed(() => {
  return checkoutSchema.safeParse(form).success
})

const hasError = (fieldName: string) => !!errors.value[fieldName]
const getErrors = (fieldName: string) => errors.value[fieldName] || []

const validate = () => {
  const result = checkoutSchema.safeParse(form)
  if (!result.success) {
    errors.value = result.error.flatten().fieldErrors
    return false
  }
  errors.value = {}
  return true
}

// Watch form changes and validate dynamically
watch(form, () => {
  if (Object.keys(errors.value).length > 0) {
    validate()
  }
}, { deep: true })

watch(() => auth.isInitialLoading, (isLoading: boolean) => {
  if (!isLoading && auth.isLoggedIn && auth.user) {
    const names = auth.user.name.split(' ')
    form.firstName = names[0] || ''
    form.lastName = names.slice(1).join(' ') || ''
    form.email = auth.user.email || ''
  }
}, { immediate: true })

const submitOrder = async () => {
  if (!validate()) {
    return
  }

  if (cart.items.length === 0) {
    return
  }

  placingOrder.value = true

  try {
    const config = useRuntimeConfig()
    const result: any = await $fetch(`${config.public.apiBase}/shop/checkout`, {
      method: 'POST',
      body: {
        first_name: form.firstName,
        last_name: form.lastName,
        email: form.email,
        address: form.address,
        total: cart.totalPrice,
        items: cart.items
      }
    })

    if (result.success) {
      cart.clearCart()
      orderPlaced.value = true
    } else {
      alert('Failed to place order: ' + (result.error || 'Unknown error'))
    }
  } catch (err) {
    alert('An error occurred while placing your order.')
    console.error(err)
  } finally {
    placingOrder.value = false
  }
}
</script>
