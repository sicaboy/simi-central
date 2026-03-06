<script setup>
import {onMounted, ref} from 'vue';
import {usePage} from "@inertiajs/inertia-vue3";

let props = defineProps({
  modelValue: {
    type: String,
    required: false,
    default: '',
  },
  placeholder: {
    type: String,
    required: false,
    default: '',
  }
});

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus();
  }
});

defineExpose({focus: () => input.value.focus()});

const page = usePage()
const themeColor = page.props.value.theme.color
</script>

<template>
  <input
      ref="input"
      :placeholder="props.placeholder"
      class="
      border-gray-300 dark:border-gray-700
      dark:bg-gray-900
      dark:text-gray-300
      read-only:pointer-events-none
      disabled:opacity-40 rounded-md shadow-sm"
      :value="modelValue"
      @input="$emit('update:modelValue', $event.target.value)"
  />
</template>
<style scoped>
input:focus:not([readonly]):not([disabled]) {
  border-color: v-bind('themeColor');
  --tw-ring-color: v-bind('themeColor');
}
</style>
