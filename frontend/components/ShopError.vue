<template>
  <div class="bg-red-900/50 border border-red-500 p-6 rounded-xl text-center">
    <p class="text-red-200">{{ message || 'Something went wrong. Please try again later.' }}</p>
    <button @click="$emit('retry')" class="mt-4 text-sm text-red-300 underline hover:text-red-100 transition-colors">
      Try again
    </button>
  </div>
</template>

<script setup>
defineProps({
  message: String
})
defineEmits(['retry'])
</script>
