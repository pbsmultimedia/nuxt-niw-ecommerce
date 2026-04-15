<template>
  <div class="min-h-screen bg-slate-900 flex items-center justify-center p-8">
    <div class="max-w-md w-full">
      <div class="text-center mb-10">
        <h1 class="text-4xl font-extrabold bg-clip-text text-transparent bg-gradient-to-r from-blue-400 to-emerald-400 mb-2">
          Welcome Back
        </h1>
        <p class="text-slate-400">Sign in to your account</p>
      </div>

      <div class="bg-slate-800/50 backdrop-blur-sm border border-slate-700 rounded-3xl p-8 shadow-2xl">
        <form @submit.prevent="handleLogin" class="space-y-6">
          <div v-if="error" class="bg-red-900/40 border border-red-500/50 p-4 rounded-xl text-red-200 text-sm animate-shake">
            {{ error }}
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Email Address</label>
            <input 
              v-model="form.email" 
              type="text" 
              :class="['w-full bg-slate-900 border rounded-xl p-4 outline-none transition-all text-white', hasError('email') ? 'border-red-500 focus:border-red-500' : 'border-slate-700 focus:border-blue-500']"
              placeholder="user@example.com"
            >
            <span v-if="hasError('email')" class="text-red-500 text-xs mt-1 block">{{ getErrors('email')[0] }}</span>
          </div>

          <div>
            <label class="block text-xs font-bold text-slate-500 mb-2 uppercase tracking-wide">Password</label>
            <input 
              v-model="form.password" 
              type="password" 
              :class="['w-full bg-slate-900 border rounded-xl p-4 outline-none transition-all text-white', hasError('password') ? 'border-red-500 focus:border-red-500' : 'border-slate-700 focus:border-blue-500']"
              placeholder="••••••••"
            >
            <span v-if="hasError('password')" class="text-red-500 text-xs mt-1 block">{{ getErrors('password')[0] }}</span>
          </div>

          <button 
            type="submit" 
            :disabled="loading"
            class="w-full py-4 bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-500 hover:to-indigo-500 disabled:opacity-50 rounded-xl font-bold text-lg transition-all shadow-lg shadow-blue-900/20 active:scale-95 flex items-center justify-center gap-2"
          >
            <svg v-if="loading" class="animate-spin h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            <span>{{ loading ? 'Signing in...' : 'Sign In' }}</span>
          </button>
        </form>

        <div class="mt-8 text-center text-sm text-slate-500">
          <p>Test credentials:</p>
          <p class="text-slate-400 font-mono mt-1">user@example.com / password123</p>
        </div>
      </div>

      <div class="mt-8 text-center">
        <NuxtLink to="/" class="text-slate-500 hover:text-blue-400 transition-colors">
          ← Back to Shop
        </NuxtLink>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { z } from 'zod'
import { useAuthStore } from '~/stores/auth'

const auth = useAuthStore()
const loading = ref(false)
const error = ref(null)

const form = reactive({
  email: '',
  password: ''
})

const errors = ref<Record<string, string[] | undefined>>({})

const loginSchema = z.object({
  email: z.string().min(1, 'Email is required').email('Invalid email format'),
  password: z.string().min(1, 'Password is required')
})

const hasError = (fieldName: string) => !!errors.value[fieldName]
const getErrors = (fieldName: string) => errors.value[fieldName] || []

const validate = () => {
  const result = loginSchema.safeParse(form)
  if (!result.success) {
    errors.value = result.error.flatten().fieldErrors
    return false
  }
  errors.value = {}
  return true
}

// Watch form changes and validate dynamically after first attempt
watch(form, () => {
  if (Object.keys(errors.value).length > 0) {
    validate()
  }
}, { deep: true })

const handleLogin = async () => {
  if (!validate()) {
    return
  }

  loading.value = true
  error.value = null
  
  const result = await auth.login(form.email, form.password)
  
  if (result.success) {
    navigateTo('/')
  } else {
    error.value = result.error
  }
  
  loading.value = false
}
</script>

<style scoped>
@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}
.animate-shake {
  animation: shake 0.2s ease-in-out 0s 2;
}
</style>
