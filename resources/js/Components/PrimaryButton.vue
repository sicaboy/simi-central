<script setup>
import Button from '@/Components/Button.vue';

defineProps({
  type: {
    type: String,
    default: 'submit',
  },
});
</script>

<template>
  <Button :type="type">
    <slot/>
  </Button>
</template>

