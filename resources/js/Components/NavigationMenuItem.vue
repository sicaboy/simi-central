<script setup>
import DynamicIcon from '@/Components/DynamicIcon.vue';
import {Link} from "@inertiajs/inertia-vue3";

defineProps({
  item: {
    type: Object,
    required: true
  }
});
</script>

<template>
  <Link
      :href="item.href"
      :target="item.target"
      :class="[
                        item.current
                        ? 'bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100'
                        : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100',
                        'group flex items-center px-2 py-2 text-sm font-medium rounded-md']">
    <DynamicIcon
        v-if="item.icon"
        :name="item.icon"
        :outline="true"
        :size="6"
        :class="[
          item.current
          ? 'text-gray-500'
          : 'text-gray-400 dark:text-gray-600 group-hover:text-gray-500',
           'mr-4 flex-shrink-0 h-6 w-6']"
    />
    <span v-else
        class="mr-4 flex-shrink-0 h-6 w-6"
    />
    {{ item.name }}
  </Link>
</template>

<style scoped>

</style>
