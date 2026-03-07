<script setup>
import H2 from '@/Components/H2.vue';
import AppAuthLayout from "@/Layouts/AppAuthLayout.vue";
import ProfileDropdown from "@/Components/ProfileDropdown.vue";
import LinkCards from "@/Components/LinkCards.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import EmptyState from "@/Components/EmptyState.vue";
import Button from "@/Components/Button.vue";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

// Props
defineProps({
    stores: {
        type: Array,
        required: true,
    },
})
</script>

<template>
    <AppAuthLayout :title="t('store.projects_title')">
        <div>
            <div class="flex items-center justify-between">
                <ApplicationLogo class="h-8 w-auto" />
                <ProfileDropdown />
            </div>
            <div class="mt-10 flex items-center justify-between gap-4">
                <H2>
                    {{ t('store.your_projects') }}
                </H2>
                <div>
                  <Button
                       icon-name="plus"
                       :href="route('store.create')">
                       {{ t('store.new_project') }}
                  </Button>
                </div>
            </div>
        </div>
        <div class="mt-8">
            <p class="mb-4 max-w-2xl text-sm text-stone-600">
                Use an existing workspace to continue work on a property marketing project, or set up a new workspace when onboarding a new agency, team, or client account.
            </p>
            <LinkCards :list="stores" v-if="stores.length" />
            <EmptyState
                class="py-16 shadow rounded bg-white dark:bg-gray-800"
                v-else />
        </div>
    </AppAuthLayout>
</template>
