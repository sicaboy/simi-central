<script setup>
import AppAuthLayout from '@/Layouts/AppAuthLayout.vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ALink from "@/Components/ALink.vue";
import H3 from "@/Components/H3.vue";
import P from "@/Components/P.vue";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();
</script>

<template>
  <AppAuthLayout :title="$t('auth.team_invitation_accepted')">
    <div>
      <ApplicationLogo class="h-8 w-auto"/>
      <H3 class="mt-8">
        {{ $t('auth.you_are_part_of_team') }}
      </H3>

      <div class="mt-4">
        <P class="pb-6">
          {{ $t('auth.congratulations') }}
        </P>
        <P class="pb-6">
          <ALink
              type="button"
              class="w-full"
              :href="route('store.list')"
          >
            {{ $t('auth.continue_to_dashboard') }}
          </ALink>
        </P>
      </div>
    </div>

  </AppAuthLayout>
</template>
