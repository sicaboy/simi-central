<template>
  <component
      :is="target === null ? Link : 'a'"
      :href="href"
      :target="target"
      class="cursor-pointer"
      :class="{
'inline-flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm uppercase font-semibold text-xs tracking-widest text-white focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-25 transition': type === 'button',
'alink-link': type === 'link',
'alink-button': type === 'button',
      }"
  >
    <slot/>
  </component>
</template>

<script setup>
import {Link, usePage} from '@inertiajs/inertia-vue3';

defineProps({
  href: {
    type: String,
  },
  target: {
    type: String,
    default: null,
  },
  type: {
    type: String,
    default: 'link', // 'button'
  },
})
const page = usePage()
const themeColor = page.props.value.theme.color
const themeColorLight = page.props.value.theme.color_light || page.props.value.theme.color
const themeColorDark = page.props.value.theme.color_dark || page.props.value.theme.color
</script>

<style scoped>
a.alink-link:not(.disable-theme-color) {
  color: v-bind('themeColor');
}

a.alink-link:not(.disable-theme-color):hover {
  color: v-bind('themeColorLight');
}

a.alink-button:not(.disable-theme-color) {
  background-color: v-bind('themeColor');
}

a.alink-button:not(.disable-theme-color):hover {
  background-color: v-bind('themeColorDark');
}

a.alink-button:not(.disable-theme-color):focus {
  --tw-ring-color: v-bind('themeColor');
}
</style>
