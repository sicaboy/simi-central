<script setup>
import ButtonSpinner from "@/Components/ButtonSpinner.vue";
import {usePage} from "@inertiajs/inertia-vue3";

defineProps({
  type: {
    type: String,
    default: 'submit',
  },
  processing: Boolean,
});

const page = usePage()
const themeColor = page.props.value.theme.color
const themeColorDark = page.props.value.theme.color_dark || page.props.value.theme.color
</script>

<template>
  <button
      :type="type"
      class="inline-flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm uppercase font-semibold text-xs tracking-widest text-white bg-indigo-600 hover:bg-indigo-700
      focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 processing:opacity-25 transition"
      :class="{ 'opacity-25': processing }"
      :disabled="processing"
  >
    <ButtonSpinner
        class="-my-0.5"
        v-if="processing"/>
    <slot/>
  </button>
</template>

<style scoped>
button {
  background-color: v-bind('themeColor');
}
button:hover {
  background-color: v-bind('themeColorDark');
}
button:focus {
  --tw-ring-color: v-bind('themeColor');
}
</style>
