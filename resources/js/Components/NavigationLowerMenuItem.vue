<script setup>
import {DynamicHeroicon} from "vue-dynamic-heroicons";
import ALink from "@/Components/ALink.vue";

defineProps({
  item: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <ALink
         :key="item.name"
         :href="item.href"
         :target="item.target"
         :classes="['disable-theme-color']"
         :class="[
                   'disable-theme-color',
                   item.current
                   ? 'bg-gray-100 text-gray-900 dark:bg-gray-900 dark:text-gray-100'
                   : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 hover:text-gray-900 dark:hover:bg-gray-700 dark:hover:text-gray-100',
                   'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
    <DynamicHeroicon
        v-if="item.icon"
        :name="item.icon"
        :outline="true"
        :size="6"
        :class="[
                      item.current
                      ? 'text-gray-500'
                      : 'text-gray-400 dark:text-gray-600 group-hover:text-gray-500',
                       'mr-3 flex-shrink-0 h-6 w-6']"
    />
    {{ item.name }}
  </ALink>
</template>

<style scoped>

</style>