<template>
  <div class="text-sm">
    Hello
    <div class="flex items-center text-blue-400 mb-1">
      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
      </svg>
      <span>Downloadable Content</span>
    </div>
    <div class="text-slate-500">Limit: {{ details.limit }} downloads</div>
  </div>
</template>

<script setup>
defineProps({
  details: Object
})
</script>
