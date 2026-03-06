<template>
<!-- Breadcrumbs -->
<div class="max-w-7xl mx-auto pb-6">
    <nav class="flex" aria-label="Breadcrumb">
        <ol role="list" class="flex items-center space-x-4">
            <li>
                <div>
                    <a :href="route('dashboard')" class="text-gray-400 hover:text-gray-500">
                        <HomeIcon class="flex-shrink-0 h-5 w-5" aria-hidden="true" />
                        <span class="sr-only">Home</span>
                    </a>
                </div>
            </li>
            <li v-for="page in pageLevels" :key="page.name">
                <div class="flex items-center">
                    <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />
                    <a :href="page.href" class="ml-4 text-sm font-medium text-gray-500 hover:text-gray-700">
                        {{ page.name }}
                    </a>
                </div>
            </li>
            <li>
                <div class="flex items-center">
                    <ChevronRightIcon class="flex-shrink-0 h-5 w-5 text-gray-400" aria-hidden="true" />
                    <span class="ml-4 text-sm font-medium text-gray-500">
                        {{ currentTitle }}
                    </span>
                </div>
            </li>
        </ol>
    </nav>
</div>
</template>

<script>

import { ChevronRightIcon, HomeIcon } from '@heroicons/vue/solid'
export default {
    name: "Breadcrumbs",
    components: {
        ChevronRightIcon,
        HomeIcon
    },
    props: {
        pageLevels: {
            type: Array,
            required: false
        },
        currentTitle: {
            type: String,
            required: false
        },
    }
}
</script>

<style scoped>

</style>
