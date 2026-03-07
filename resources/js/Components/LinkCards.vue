<template>
    <div class="grid grid-cols-1 gap-4 xl:grid-cols-2">
        <div v-for="item in list"
             :key="item.title"
             class="link-card relative flex items-center space-x-3 rounded-2xl border border-stone-200 bg-white px-6 py-5 shadow-sm transition
               hover:border-stone-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900
               dark:hover:border-slate-700 focus-within:ring-2 focus-within:ring-offset-2 dark:focus-within:ring-offset-slate-950">
            <div class="flex-shrink-0">
                 <img class="h-10 w-10 rounded-full object-cover
                            bg-stone-100 dark:bg-slate-800"
                     :src="item.imageUrl"
                     v-if="item.imageUrl"
                 />
                 <span class="inline-flex items-center justify-center h-10 w-10 rounded-full
                             bg-stone-100 dark:bg-slate-800"
                     v-else>
                     <UserGroupIcon
                         class="h-6 w-6
                          text-stone-700 dark:text-slate-400"
                         aria-hidden="true"/>
                 </span>
             </div>
             <div class="flex-1 min-w-0 pl-1">
                 <ALink :href="item.url" class="focus:outline-none">
                     <span class="absolute inset-0" aria-hidden="true" />
                     <P class="font-bold text-stone-900 dark:text-slate-100">
                         {{ item.title }}
                     </P>
                     <p class="text-sm text-stone-500 dark:text-slate-400 truncate">
                         {{ item.description }}
                     </p>
                 </ALink>
             </div>
             <div class="flex-shrink-0">
                 <ChevronRightIcon class="h-5 w-5 text-stone-400 dark:text-slate-500" aria-hidden="true"/>
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
