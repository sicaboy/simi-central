<template>
  <RadioGroup v-model="selected" @update:model-value="emit('update:modelValue', $event)">
    <div class="-space-y-px rounded-md bg-white dark:bg-gray-800">
      <RadioGroupOption
          as="template"
          v-for="(item, itemIdx) in items"
          :key="item.name"
          :value="item.value"
          v-slot="{ checked, active }"
      >
        <div :class="[
            itemIdx === 0 ? 'rounded-tl-md rounded-tr-md' : '',
            itemIdx === items.length - 1 ? 'rounded-bl-md rounded-br-md' : '',
            checked ? 'bg-gray-50 dark:bg-gray-700 z-10' : '',
            'relative border p-4 flex cursor-pointer focus:outline-none border-gray-200 dark:border-gray-700'
            ]">
          <span :class="[
              checked ? 'bg-theme border-transparent' : 'bg-white border-gray-300',
              'mt-0.5 h-4 w-4 shrink-0 cursor-pointer rounded-full border flex items-center justify-center'
              ]" aria-hidden="true">
            <span class="rounded-full bg-white w-1.5 h-1.5" />
          </span>
          <span class="ml-3 flex flex-col">
            <RadioGroupLabel as="span" :class="[
                'text-gray-800 dark:text-gray-200',
                'block text-sm font-medium'
            ]">{{ item.name }}</RadioGroupLabel>
            <RadioGroupDescription as="span" :class="[
                'text-gray-500 dark:text-gray-400',
               'block text-sm'
            ]">{{ item.description }}</RadioGroupDescription>
          </span>
        </div>
      </RadioGroupOption>
    </div>
  </RadioGroup>
</template>

<script setup>
import {ref} from 'vue'
import {RadioGroup, RadioGroupDescription, RadioGroupLabel, RadioGroupOption} from '@headlessui/vue'
import {usePage} from "@inertiajs/inertia-vue3";

const props = defineProps({
  items: {
    type: Array,
    required: true
  },
  modelValue: {
    type: String,
    required: true
  },
})

const emit = defineEmits(['update:modelValue'])
const selected = ref(props.modelValue);
const themeColor = usePage().props.value.theme.color
</script>

<style scoped>
.bg-theme {
  background-color: v-bind('themeColor');
}
</style>