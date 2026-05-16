<script setup>
import AppAuthLayout from '@/Layouts/AppAuthLayout.vue';
import ApplicationLogo from "@shared-saas/Components/ApplicationLogo.vue";
import H3 from "@shared-saas/Components/H3.vue";
import P from "@shared-saas/Components/P.vue";
import ALink from "@shared-saas/Components/ALink.vue";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
  <AppAuthLayout :title="t('waitlist.login')">
    <div>
      <ApplicationLogo class="h-8 w-auto"/>
      <H3 class="mt-6">
        {{ t('waitlist.join_heading') }}
      </H3>
    </div>

    <div class="mt-4">
      <P class="pb-2">
        {{ t('waitlist.thank_you_message') }}
      </P>
      <P class="pb-6">
        {{ t('waitlist.secure_spot_message') }}
      </P>
      <ALink
          type="button"
          class="w-full"
          :href="route('waitlist-join')"
      >
        {{ t('waitlist.join_button') }}
      </ALink>
      <P class="mt-4 text-sm text-gray-600 text-center">
        <ALink
            class="w-full"
            :href="route('logout')"
        >
          {{ t('common.logout') }}
        </ALink>
      </P>
    </div>
  </AppAuthLayout>
</template>
