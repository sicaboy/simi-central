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
                        <div class="inline-flex items-center rounded-full border border-amber-200 bg-white/80 px-3 py-1 text-xs font-semibold uppercase tracking-[0.22em] text-amber-800 dark:border-amber-500/30 dark:bg-amber-500/10 dark:text-amber-200">
                            Workspace Access
                        </div>
                        <H2 class="mt-5">
                            {{ t('store.your_projects') }}
                        </H2>
                        <P class="mt-3 max-w-2xl text-sm sm:text-base">
                            Existing customers choose their workspace here. New customers get onboarded by creating a workspace tied to their Australian business details.
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
                <div class="grid gap-6 lg:grid-cols-[minmax(0,1fr)_320px]">
                    <div>
                        <div class="mb-5 flex flex-wrap gap-3 text-sm">
                            <div class="rounded-xl bg-stone-100 px-4 py-3 text-stone-700 dark:bg-slate-950 dark:text-slate-200">
                                Continue an existing client workspace
                            </div>
                            <div class="rounded-xl bg-stone-100 px-4 py-3 text-stone-700 dark:bg-slate-950 dark:text-slate-200">
                                Onboard a new agency or team
                            </div>
                        </div>

                        <LinkCards :list="stores" v-if="stores.length" />
                        <EmptyState class="rounded-2xl border border-stone-200 bg-stone-50 px-6 py-16 dark:border-slate-800 dark:bg-slate-950" v-else />
                    </div>

                    <aside class="rounded-2xl border border-stone-200 bg-stone-50 p-6 dark:border-slate-800 dark:bg-slate-950/80">
                        <h3 class="text-lg font-semibold text-stone-900 dark:text-slate-100">When to create a new workspace</h3>
                        <div class="mt-5 space-y-4 text-sm text-stone-600 dark:text-slate-300">
                            <div class="rounded-xl bg-white px-4 py-4 dark:bg-slate-900">
                                Create one for a new real estate agency, photographer, or property marketing team starting on Simi.
                            </div>
                            <div class="rounded-xl bg-white px-4 py-4 dark:bg-slate-900">
                                Reuse an existing workspace when the customer already has a tenant and just needs to continue work.
                            </div>
                            <div class="rounded-xl bg-white px-4 py-4 dark:bg-slate-900">
                                Business lookup helps ensure the workspace is attached to the correct Australian entity.
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </AppAuthLayout>
</template>
