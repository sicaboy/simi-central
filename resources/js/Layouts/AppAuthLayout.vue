<template>
    <Head :title="title" />

    <div class="min-h-screen bg-stone-100 text-stone-900 transition-colors dark:bg-slate-950 dark:text-slate-100">
      <div class="mx-auto flex min-h-screen w-full max-w-7xl flex-col px-4 py-8 sm:px-6 lg:px-8">
            <div class="mx-auto mb-10 w-full" :class="contentWidthClass">
                <slot />
                <Footer class="mt-8" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { computed } from 'vue';
import { Head } from '@inertiajs/inertia-vue3';
import Footer from '@/Components/Footer.vue';

const props = defineProps({
    title: {
        type: String,
        default: '',
    },
    width: {
        type: String,
        default: 'compact',
    }
});

const contentWidthClass = computed(() => {
    if (props.width === 'wide') {
        return 'max-w-6xl';
    }

    if (props.width === 'medium') {
        return 'max-w-3xl';
    }

    return 'max-w-sm lg:max-w-md';
});
</script>
