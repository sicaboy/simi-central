<template>
  <Listbox as="div" v-model="selectedItem">
    <div class="relative mt-1">
      <ListboxButton
          class="relative w-full cursor-default rounded-md border py-2 pl-3 pr-10 text-left shadow-sm focus:outline-none focus:ring-1 sm:text-sm
                border-gray-300 disabled:opacity-40 bg-white dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600">
        <span class="block truncate">{{ selectedItem?.name || '&nbsp;' }}</span>
        <span class="pointer-events-none absolute inset-y-0 right-0 flex items-center pr-2">
          <ChevronDownIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
        </span>
      </ListboxButton>

      <transition leave-active-class="transition ease-in duration-100" leave-from-class="opacity-100"
                  leave-to-class="opacity-0">
        <ListboxOptions
            class="absolute z-10 mt-1 max-h-60 w-full overflow-auto rounded-md bg-white dark:bg-gray-900 py-1 text-base shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none sm:text-sm">
          <ListboxOption as="template" v-for="item in items" :key="item.id" :value="item"
                         v-slot="{ active, selectedItem }">
            <li :class="[active ? 'text-white dark:text-black bg-active' : 'text-gray-900 dark:text-gray-100', 'relative cursor-default select-none py-2 pl-3 pr-9']">
              <span :class="[selectedItem ? 'font-semibold' : 'font-normal', 'block truncate']">{{ item.name }}</span>

              <span v-if="selectedItem"
                    :class="[active ? 'text-white dark:text-black' : 'text-active', 'absolute inset-y-0 right-0 flex items-center pr-4']">
                <CheckIcon class="h-5 w-5" aria-hidden="true"/>
              </span>
            </li>
          </ListboxOption>
        </ListboxOptions>
      </transition>
    </div>
  </Listbox>
</template>


<script setup>

import {ref, watch} from 'vue'
import { Listbox, ListboxButton, ListboxOption, ListboxOptions } from '@headlessui/vue'
import { CheckIcon, ChevronDownIcon } from '@heroicons/vue/solid'
import {usePage} from "@inertiajs/inertia-vue3";

let props = defineProps({
  modelValue: {
    type: String,
    required: true,
  },
  items: {
    type: Array,
    required: true,
  }
})

const selectedItem = ref(props.items.find(item => item.value === props.modelValue) || null);

let emit = defineEmits(['update:modelValue']);

watch(selectedItem, (value) => {
  emit('update:modelValue', value.value)
})

const page = usePage()
const themeColor = page.props.value.theme.color
</script>

<style scoped>
button:focus {
  border-color: v-bind('themeColor');
  --tw-ring-color: v-bind('themeColor');
}
.bg-active {
  background-color: v-bind('themeColor');
}
.text-active {
  color: v-bind('themeColor');
}
</style>