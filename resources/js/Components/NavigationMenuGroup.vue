<script setup>

import NavigationMenuItem from "@/Components/NavigationMenuItem.vue";
import DynamicIcon from '@/Components/DynamicIcon.vue';

import {Disclosure, DisclosureButton, DisclosurePanel} from '@headlessui/vue'
import {computed, onMounted, ref} from "vue";

const props = defineProps({
  group: {
    type: Object,
    required: true
  }
});


const defaultOpen = computed(() => {
  return props.group.items.some(item => item.current) || props.group.current;
});
</script>

<template>
  <!-- Collapsed items -->
  <Disclosure as="div" class="space-y-1" v-slot="{ open }" :default-open="defaultOpen">
    <DisclosureButton
        :class="[
             group.items.some(item => item.current) || group.current
              ? 'bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100'
              : 'text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-gray-900 dark:hover:text-gray-100',
              'group flex items-center px-2 py-2 text-sm font-medium rounded-md w-full'
        ]">
      <DynamicIcon
          v-if="group.icon"
          :name="group.icon"
          :outline="true"
          :size="6"
          :class="[
               group.current
                ? 'text-gray-500'
                : 'text-gray-400 dark:text-gray-600 group-hover:text-gray-500',
                 'mr-4 flex-shrink-0 h-6 w-6']"
      />
      <span class="mr-auto">
          {{ group.name }}
        </span>
      <svg
          :class="[open ? 'text-gray-400 dark:text-gray-500 rotate-90' : 'text-gray-300 dark:text-gray-700', 'ml-3 h-5 w-5 flex-shrink-0 transform transition-colors duration-150 ease-in-out group-hover:text-gray-400']"
          viewBox="0 0 20 20" aria-hidden="true">
        <path d="M6 6L14 10L6 14V6Z" fill="currentColor"/>
      </svg>
    </DisclosureButton>

      <DisclosurePanel class="space-y-1 pl-4">
        <NavigationMenuItem
            v-for="item in group.items"
            :item="item"/>
      </DisclosurePanel>

  </Disclosure>
</template>
