<script setup>
import { computed, useSlots } from 'vue';
import SectionTitle from './SectionTitle.vue';

defineEmits(['submitted']);

const hasActions = computed(() => !! useSlots().actions);

let props = defineProps({
  styles: {
    type: String
  },
});
</script>

<template>

    <div class="mt-5 md:mt-0 md:col-span-2 bg-white dark:bg-gray-800 md:col-span-2 shadow sm:rounded-md">
        <form @submit.prevent="$emit('submitted')">
            <div
                class="px-4 py-5 sm:p-6 shadow"
                :style="styles"
            >
                <SectionTitle>
                    <template #title>
                        <slot name="title" />
                    </template>
                    <template #description>
                        <slot name="description" />
                    </template>
                </SectionTitle>
                <div class="mt-6 grid grid-cols-6 gap-6">
                    <slot name="form" />
                </div>
            </div>
            <div v-if="hasActions" class="flex items-center justify-end px-4 bg-gray-50 dark:bg-gray-800 text-right sm:px-6 shadow sm:rounded-bl-md sm:rounded-br-md">
                <slot name="actions" />
            </div>
        </form>
    </div>
</template>
