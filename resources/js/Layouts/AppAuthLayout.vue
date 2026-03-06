<template>
    <Head :title="title" />

    <div class="min-h-screen flex">
      <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6">
            <div class="mx-auto w-full max-w-sm lg:w-96 mb-10">
                <slot />
                <Footer class="mt-8" />
            </div>
        </div>
    </div>
</template>

<script setup>
import { Head } from '@inertiajs/inertia-vue3';
import Footer from '@/Components/Footer.vue';

defineProps({
    title: {
        type: String,
        default: '',
    }
});
</script>
