<script setup>
import {usePage} from "@inertiajs/inertia-vue3";
import ALink from "@/Components/ALink.vue";
import {DynamicHeroicon} from "vue-dynamic-heroicons";

defineProps({
  type: {
    type: String,
    default: null,
  },
  className: {
    type: String,
    default: 'primary',
  },
  iconName: {
    type: String,
    default: null,
  },
  disabled: Boolean,
});

const page = usePage()
const themeColor = page.props.value.theme.color
const themeColorDark = page.props.value.theme.color_dark || page.props.value.theme.color
</script>

<template>
    <component
        :is="type ? 'button' : ALink"
        :type="type"
        class="inline-flex justify-center py-2.5 px-4 border border-transparent rounded-md shadow-sm uppercase
         font-semibold text-xs tracking-widest text-white bg-indigo-600 hover:bg-indigo-700
         focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-25 transition
         "
        :class="[className, { 'opacity-25': disabled}]"
        :disabled="disabled"
    >
      <DynamicHeroicon
          :name="iconName"
          class="-ml-1 mr-2 h-4 w-4" aria-hidden="true"
          v-if="iconName" />
      <slot />
    </component>
</template>

<style scoped>
.primary {
  background-color: v-bind('themeColor');
}
.primary:hover {
  background-color: v-bind('themeColorDark');
}
.primary:focus {
  --tw-ring-color: v-bind('themeColor');
}
.secondary {
  background-color: transparent;
  border-color: v-bind('themeColor');
  color: v-bind('themeColor');
}
.secondary:hover {
  background-color: v-bind('themeColor');
  color: #fff;
}
.secondary:focus {
  --tw-ring-color: v-bind('themeColor');
}
.tertiary {
  background-color: transparent;
  border-color: transparent;
  color: v-bind('themeColor');
}
.tertiary:hover {
  background-color: transparent;
  border-color: transparent;
  color: v-bind('themeColorDark');
}
.tertiary:focus {
  --tw-ring-color: v-bind('themeColor');
}
</style>