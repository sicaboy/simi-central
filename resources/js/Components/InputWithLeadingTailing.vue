<script setup>
import {onMounted, ref} from 'vue';
import Input from '@/Components/Input.vue';
import {usePage} from "@inertiajs/inertia-vue3";
import DynamicIcon from '@/Components/DynamicIcon.vue';
import Tooltip from "@/Components/Tooltip.vue";

defineProps({
  required: {
    type: Boolean,
    default: false,
  },
  leading: {
    type: String,
    default: null,
  },
  tailing: {
    type: String,
    default: null,
  },
  tailingButtonIcon: {
    type: String,
    default: null,
  },
  tailingButtonText: {
    type: String,
    default: null,
  },
  tailingButtonTooltip: {
    type: String,
    default: null
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
  readonly: {
    type: Boolean,
    default: false,
  },
});

defineEmits([
  'update:modelValue',
  'tailingButtonClicked'
]);

const input = ref(null);

defineExpose({focus: () => input.value.focus()});

const page = usePage()
const themeColor = page.props.value.theme.color
</script>

<template>

  <div class="mt-1 flex rounded-md shadow-sm">
    <span
        v-if="leading"
        class="inline-flex items-center rounded-l-md border border-r-0
        border-gray-300 dark:border-gray-700
         bg-gray-50 dark:bg-gray-800 px-3 text-gray-500 dark:text-gray-400 sm:text-sm"
    >
      {{ leading }}
    </span>

    <Input
        ref="input"
        :required="required"
        :type="type"
        :name="name"
        :id="id"
        :readonly="readonly"
        class="block w-full border-gray-300 sm:text-md
 disabled:opacity-40 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600
 min-w-0 flex-1 px-3 py-2 rounded-none
"
        :class="[
          !leading ? 'rounded-l-md' : '',
          !tailing && !tailingButtonIcon ? 'rounded-r-md' : '',
        ]"
        :value="modelValue"
        :placeholder="placeholder"
        @input="$emit('update:modelValue', $event.target.value)"
    />
    <span
        v-if="tailing && !tailingButtonIcon"
        class="inline-flex items-center rounded-r-md border border-l-0
        border-gray-300 dark:border-gray-700
         bg-gray-50 dark:bg-gray-800 px-3 text-gray-500 dark:text-gray-400 sm:text-sm
        ">
      {{ tailing }}
    </span>

    <button
        v-if="tailingButtonIcon || tailingButtonText"
        type="button"
        @click="$emit('tailingButtonClicked', $event)"
        class="relative -ml-px inline-flex items-center space-x-2 rounded-r-md border
         border-gray-300 dark:border-gray-700
         bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800
         focus:border-indigo-500 focus:ring-indigo-500
         px-4 py-2 text-sm font-medium  focus:outline-none focus:ring-1 ">

      <div v-if="tailingButtonTooltip" class="absolute z-10 -top-10 block">
        <Tooltip :text="tailingButtonTooltip"/>
      </div>

      <DynamicIcon
          v-if="tailingButtonIcon"
          class="h-5 w-5 text-gray-400" aria-hidden="true"
          :name="tailingButtonIcon"
      />
      <span v-if="tailingButtonText">
        {{ tailingButtonText }}
      </span>
    </button>
  </div>

</template>

<style scoped>
input:focus, button:focus {
  border-color: v-bind('themeColor');
  --tw-ring-color: v-bind('themeColor');
}
</style>
