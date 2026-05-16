<script setup>
import AppAuthLayout from '@/Layouts/AppAuthLayout.vue';
import ApplicationLogo from "@shared-saas/Components/ApplicationLogo.vue";
import H3 from "@shared-saas/Components/H3.vue";
import ALink from "@shared-saas/Components/ALink.vue";
import InformationBox from "@shared-saas/Components/InformationBox.vue";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
  <AppAuthLayout :title="t('auth.login')">
    <div>
      <ApplicationLogo class="h-8 w-auto"/>
      <H3 class="mt-6">
        {{ t('auth.sign_in_to_manage') }}
      </H3>
    </div>

    <div class="mt-8">
      <div class="mt-6">
        <ALink
            type="button"
            target="_self"
            class="w-full"
            :href="route('usersau.login')"
        >
          {{ t('auth.sign_in') }}
        </ALink>
      </div>
      <div class="mt-6" v-if="$page.props.status">
        <InformationBox color-scheme="yellow">
          {{ $page.props.status }}
        </InformationBox>
      </div>
    </div>
  </AppAuthLayout>
</template>
