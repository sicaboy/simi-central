<script setup>
import Button from '@/Components/Button.vue';

defineProps({
  type: {
    type: String,
    default: 'button',
  },
});
</script>

<template>
  <Button :type="type" class-name="secondary">
    <slot/>
  </Button>
</template>
