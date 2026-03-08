<script setup>
import { defineAsyncComponent } from 'vue';

defineProps({
    name: {
        type: String,
        default: null,
    },
    outline: {
        type: Boolean,
        default: false,
    },
    size: {
        type: [Number, String],
        default: null,
    },
});

const DynamicHeroicon = defineAsyncComponent(() =>
    import('vue-dynamic-heroicons').then((module) => module.DynamicHeroicon)
);
</script>

<template>
    <DynamicHeroicon
        :name="name"
        :outline="outline"
        :size="size"
        v-bind="$attrs"
    />
</template>
