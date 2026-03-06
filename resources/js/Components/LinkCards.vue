<template>
    <div class="grid grid-cols-1 gap-y-4">
        <div v-for="item in list"
             :key="item.title"
             class="link-card relative rounded-lg border border-gray-300 dark:border-gray-700
              bg-white dark:bg-gray-800
               px-6 py-5 shadow-sm flex items-center space-x-3
               hover:border-gray-400 dark:hover:border-gray-600
               focus-within:ring-2 focus-within:ring-offset-2">
            <div class="flex-shrink-0">
                <img class="h-10 w-10 rounded-full object-cover
                            bg-gray-100 dark:bg-gray-700"
                     :src="item.imageUrl"
                     v-if="item.imageUrl"
                />
                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full
                             bg-gray-100 dark:bg-gray-700"
                    v-else>
                    <UserGroupIcon
                        class="h-6 w-6
                          text-gray-700 dark:text-gray-400"
                        aria-hidden="true"/>
                </span>
            </div>
            <div class="flex-1 min-w-0 pl-1">
                <ALink :href="item.url" class="focus:outline-none">
                    <span class="absolute inset-0" aria-hidden="true" />
                    <P class="font-bold text-gray-900">
                        {{ item.title }}
                    </P>
                    <p class="text-sm text-gray-500 truncate">
                        {{ item.description }}
                    </p>
                </ALink>
            </div>
            <div class="flex-shrink-0">
                <ChevronRightIcon class="h-5 w-5 text-gray-400" aria-hidden="true"/>
            </div>
        </div>
    </div>
</template>

<script setup>
import {UserGroupIcon} from '@heroicons/vue/outline'
import {ChevronRightIcon} from '@heroicons/vue/outline'
import ALink from "@/Components/ALink.vue";
import P from "@/Components/P.vue";
import {usePage} from "@inertiajs/inertia-vue3";

const page = usePage()
const themeColor = page.props.value.theme.color

defineProps({
    list: {
        type: Array,
        required: true
    }
})
</script>

<style scoped>
.link-card {
  --tw-ring-color: v-bind('themeColor');
}
</style>
