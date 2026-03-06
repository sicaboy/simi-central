<script setup>
let props = defineProps({
  'text': {
    type: String,
    default: null
  },
})
</script>

<template>
  <div
      id="tooltip-top"
      role="tooltip"
      :class="[{'invisible opacity-0 ': !props.text}]"
      class="absolute z-10 block px-3 py-2 text-sm font-medium
      text-white bg-gray-500 dark:bg-gray-700
      rounded-lg shadow-sm tooltip">
    {{ props.text }}
  </div>
</template>

<style scoped>
</style>