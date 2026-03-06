<template>
  <div class="text-center">
    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
      <path vector-effect="non-scaling-stroke" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
    </svg>
    <H3 class="mt-2 text-sm font-medium text-gray-900">{{ t('store.no_projects') }}</H3>
    <P class="mt-1 text-sm text-gray-500">{{ t('store.get_started') }}</P>
    <div class="mt-6">
      <Button
          class-name="secondary"
          icon-name="Plus"
          :href="route('store.create')">
        {{ t('store.create_new_project') }}
      </Button>
    </div>
  </div>
</template>

<script setup>
import { PlusIcon } from '@heroicons/vue/solid'
import Button from "@/Components/Button.vue";
import P from "@/Components/P.vue";
import H3 from "@/Components/H3.vue";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>