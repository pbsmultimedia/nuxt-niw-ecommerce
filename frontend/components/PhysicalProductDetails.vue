<template>
  <div class="grid grid-cols-2 gap-2 text-sm">
    <div><span class="text-slate-500">Weight:</span> {{ details.weight }}kg</div>
    <div><span class="text-slate-500">Dim:</span> {{ details.dimensions.w }}x{{ details.dimensions.h }}cm</div>
  </div>
</template>

<script setup>
defineProps({
  details: Object
})
</script>
