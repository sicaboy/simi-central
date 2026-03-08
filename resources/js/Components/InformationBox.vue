<script setup>
import DynamicIcon from '@/Components/DynamicIcon.vue';

const props = defineProps({
  iconName: {
    type: String,
    default: 'information-circle'
  },
  colorScheme: {
    type: String,
    default: 'blue'
  },
  buttonText: {
    type: String
  },
  buttonHref: {
    type: String
  }
})
</script>

<template>
  <div
      class="p-4"
      :class="`bg-${colorScheme}-50 dark:bg-${colorScheme}-950`"
  >
    <!-- bg-blue-50 dark:bg-blue-950 -->
    <!-- bg-red-50 dark:bg-red-950 -->
    <!-- bg-green-50 dark:bg-green-950 -->
    <!-- bg-yellow-50 dark:bg-yellow-950 -->
    <div class="flex">
      <div class="flex-shrink-0">
        <DynamicIcon
            :name="iconName"
            class="h-5 w-5" aria-hidden="true"
            :class="`text-${colorScheme}-400 dark:text-${colorScheme}-500`"
        />
        <!-- text-blue-400 dark:text-blue-500 -->
        <!-- text-red-400 dark:text-red-500 -->
        <!-- text-green-400 dark:text-green-500 -->
        <!-- text-yellow-400 dark:text-yellow-500 -->
      </div>
      <div class="ml-3 flex-1 md:flex md:justify-between">
        <p
            class="text-sm"
            :class="`text-${colorScheme}-700 dark:text-${colorScheme}-300`"
        >
          <!-- text-blue-700 dark:text-blue-300 -->
          <!-- text-red-700 dark:text-red-300 -->
          <!-- text-green-700 dark:text-green-300 -->
          <!-- text-yellow-700 dark:text-yellow-300 -->
          <slot/>
        </p>
        <p class="mt-3 text-sm md:mt-0 md:ml-6"
           v-if="buttonText && buttonHref">
          <a
              :href="buttonHref"
              class="whitespace-nowrap font-medium"
              :class="`text-${colorScheme}-700 dark:text-${colorScheme}-300 hover:text-${colorScheme}-600 dark:hover:text-${colorScheme}-400`"
          >
            <!-- text-blue-700 dark:text-blue-300 hover:text-blue-600 dark:hover:text-blue-400 -->
            <!-- text-red-700 dark:text-red-300 hover:text-red-600 dark:hover:text-red-400 -->
            <!-- text-green-700 dark:text-green-300 hover:text-green-600 dark:hover:text-green-400 -->
            <!-- text-yellow-700 dark:text-yellow-300 hover:text-yellow-600 dark:hover:text-yellow-400 -->
            {{ buttonText }}
            <span aria-hidden="true"> &rarr;</span>
          </a>
        </p>
      </div>
    </div>
  </div>
</template>
