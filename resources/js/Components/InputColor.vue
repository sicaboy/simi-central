<script setup>
import {onMounted, ref} from 'vue';
import {usePage} from "@inertiajs/inertia-vue3";

defineProps({
  required: {
    type: Boolean,
    default: false,
  },
  modelValue: String,
  placeholder: {
    type: String,
    default: '',
  },
  name: {
    type: String,
    default: '',
  },
  id: {
    type: String,
    default: '',
  },
  type: {
    type: String,
    default: 'text',
  },
});

const emit = defineEmits(['update:modelValue']);

function normalizeColor(color) {
  let normalizedColor = color;
  if (!normalizedColor.startsWith('#')) {
    normalizedColor = '#' + normalizedColor;
  }
  if (normalizedColor.length === 4) {
    normalizedColor = normalizedColor.replace(/([a-f0-9])/gi, '$1$1');
  }
  if (normalizedColor.length === 7) {
    emit('update:modelValue', normalizedColor);
    // Set the color input value to the normalized color
    colorInput.value.value = normalizedColor;
  } else {
    emit('update:modelValue', '');
    // Set the color input value to the empty string
    colorInput.value.value = '';
  }
}

const input = ref(null);
const colorInput = ref(null);

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
  <div class="mt-1 flex rounded-md shadow-sm">
    <input
        ref="colorInput"
        type="color"
        class="inline-flex items-center rounded-none rounded-l-md border border-r-0 cursor-pointer
        border-gray-300 dark:border-gray-700 h-auto w-12
        bg-gray-50 dark:bg-gray-800 p-1 text-gray-500 dark:text-gray-400 sm:text-sm"
        id="hs-color-input"
        :value="modelValue"
        @input="$emit('update:modelValue', $event.target.value)"
        title="Choose color"
    />
    <input
        ref="input"
        :required="required"
        :type="type"
        :name="name"
        :id="id"
        class="block w-full border-gray-300 sm:text-md
 disabled:opacity-40 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600
 min-w-0 flex-1 px-3 py-2 rounded-none
 rounded-r-md
"
        :value="modelValue"
        :placeholder="placeholder"
        @blur="normalizeColor($event.target.value)"
    />
  </div>

</template>

<style scoped>
input:focus {
  border-color: v-bind('themeColor');
  --tw-ring-color: v-bind('themeColor');
}
</style>
