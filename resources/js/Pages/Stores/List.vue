<script setup>
import H2 from '@/Components/H2.vue';
import P from '@/Components/P.vue';
import AppAuthLayout from '@/Layouts/AppAuthLayout.vue';
import ProfileDropdown from '@/Components/ProfileDropdown.vue';
import LinkCards from '@/Components/LinkCards.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import EmptyState from '@/Components/EmptyState.vue';
import Button from '@/Components/Button.vue';
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

defineProps({
    stores: {
        type: Array,
        required: true,
    },
});
</script>

<template>
    <AppAuthLayout :title="t('store.projects_title')" width="wide">
        <div class="overflow-hidden rounded-[2rem] border border-stone-200/80 bg-white/90 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.45)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
            <div class="border-b border-stone-200/80 bg-gradient-to-r from-stone-100 via-amber-50 to-white px-6 py-8 dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 sm:px-8 lg:px-10">
                <div class="flex items-center justify-between gap-4">
                    <ApplicationLogo class="h-8 w-auto" />
                    <ProfileDropdown />
                </div>

                <div class="mt-10 grid gap-6 lg:grid-cols-[minmax(0,1fr)_auto] lg:items-end">
                    <div class="max-w-3xl">
                        <H2>
                            {{ t('store.your_projects') }}
                        </H2>
                        <P class="mt-3 max-w-2xl text-sm sm:text-base">
                            Choose a workspace to continue, or create a new one for a new business or franchise.
                        </P>
                    </div>

                    <div class="lg:min-w-[220px]">
                        <Button icon-name="plus" :href="route('store.create')" class="w-full">
                            {{ t('store.new_project') }}
                        </Button>
                    </div>
                </div>
            </div>

            <div class="px-6 py-8 sm:px-8 lg:px-10 lg:py-10">
                <LinkCards :list="stores" v-if="stores.length" />
                <EmptyState class="rounded-2xl border border-stone-200 bg-stone-50 px-6 py-16 dark:border-slate-800 dark:bg-slate-950" v-else />
            </div>
        </div>
    </AppAuthLayout>
</template>
