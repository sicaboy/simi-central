<template>
    <div class="grid grid-cols-1 gap-4" :class="gridClass">
        <div v-for="item in list"
             :key="item.title"
             class="link-card relative flex items-center space-x-3 rounded-2xl border border-stone-200 bg-white shadow-sm transition
               hover:border-stone-300 hover:shadow-md dark:border-slate-800 dark:bg-slate-900
               dark:hover:border-slate-700 focus-within:ring-2 focus-within:ring-offset-2 dark:focus-within:ring-offset-slate-950">
            <div
                class="absolute inset-0 rounded-2xl bg-gradient-to-r from-amber-50/70 via-transparent to-white/40 opacity-0 transition dark:from-amber-500/10 dark:to-slate-900/10"
                :class="isSingleCard ? 'opacity-100' : ''"
                aria-hidden="true"
            />
            <div class="flex-shrink-0">
                 <img class="h-10 w-10 rounded-full object-cover
                            bg-stone-100 dark:bg-slate-800"
                     :class="isSingleCard ? 'h-14 w-14' : ''"
                     :src="item.imageUrl"
                     v-if="item.imageUrl"
                 />
                 <span class="inline-flex items-center justify-center h-10 w-10 rounded-full
                              bg-stone-100 dark:bg-slate-800"
                     :class="isSingleCard ? 'h-14 w-14' : ''"
                     v-else>
                     <UserGroupIcon
                         class="h-6 w-6
                           text-stone-700 dark:text-slate-400"
                         :class="isSingleCard ? 'h-7 w-7' : ''"
                         aria-hidden="true"/>
                  </span>
              </div>
              <div class="relative flex-1 min-w-0 pl-1" :class="isSingleCard ? 'pl-3' : ''">
                  <ALink :href="item.url" class="focus:outline-none">
                      <span class="absolute inset-0" aria-hidden="true" />
                     <P class="font-bold text-stone-900 dark:text-slate-100" :class="isSingleCard ? 'text-lg' : ''">
                          {{ item.title }}
                      </P>
                     <p class="text-sm text-stone-500 dark:text-slate-400" :class="isSingleCard ? 'mt-2 text-base leading-6' : 'truncate'">
                          {{ item.description }}
                      </p>
                  </ALink>
              </div>
              <div class="flex-shrink-0">
                 <ChevronRightIcon class="h-5 w-5 text-stone-400 dark:text-slate-500" :class="isSingleCard ? 'h-6 w-6' : ''" aria-hidden="true"/>
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

const props = defineProps({
    list: {
        type: Array,
        required: true
    }
})

const gridClass = props.list.length > 1 ? 'md:grid-cols-2' : 'max-w-3xl'
const isSingleCard = props.list.length === 1
</script>

<style scoped>
.link-card {
  --tw-ring-color: v-bind('themeColor');
  padding: v-bind('isSingleCard ? "2rem" : "1.25rem 1.5rem"');
}
</style>
