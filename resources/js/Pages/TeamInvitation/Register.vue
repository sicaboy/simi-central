<script setup>
import AppAuthLayout from '@/Layouts/AppAuthLayout.vue';
import Button from "@/Components/Button.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import H3 from "@/Components/H3.vue";
import P from "@/Components/P.vue";
import InformationBox from "@/Components/InformationBox.vue";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const props = defineProps({
  invitation: Object,
});
</script>

<template>
  <AppAuthLayout :title="$t('auth.team_invitation')">
    <div>
      <ApplicationLogo class="h-8 w-auto"/>
      <H3 class="mt-8">
        {{ $t('auth.team_invitation') }}
      </H3>
      <div class="mt-6">
        <P class="pb-6">
          {{ $t('auth.invited_to_join') }} <strong>{{ $page.props.invitation.team.name }}</strong> {{ $t('auth.team') }}.
        </P>
        <P class="pb-6">
          {{ $t('auth.accept_invitation_message') }} <strong>{{
            $page.props.invitation.email
          }}</strong>.
        </P>
      </div>
    </div>

    <div class="mt-6" v-if="!$page.props.auth.user">
      <Button class="w-full"
              :href="route('team-invitations.registering', { invitationUuid: $page.props.invitation.uuid })"
      >
        {{ $t('auth.register_and_accept') }}
      </Button>
    </div>
    <div class="mt-4" v-else-if="$page.props.auth.user.email !== $page.props.invitation.email">
      <InformationBox
          :color-scheme="'red'"
          :button-text="$t('auth.logout')"
          :button-href="route('logout')">
        {{ $t('auth.wrong_user_message') }} <strong>{{ $page.props.auth.user.email }}</strong>.
      </InformationBox>
    </div>

  </AppAuthLayout>
</template>
