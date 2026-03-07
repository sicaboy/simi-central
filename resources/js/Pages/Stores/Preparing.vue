<script setup>
import {onMounted, onUnmounted, watch} from 'vue'
import AppAuthLayout from "@/Layouts/AppAuthLayout.vue";
import Spinner from "@/Components/Spinner.vue";
import H2 from "@/Components/H2.vue";
import P from "@/Components/P.vue";
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ALink from "@/Components/ALink.vue";
import {Inertia} from "@inertiajs/inertia";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

let props = defineProps({
    tenantId: String,
    isReady: Boolean,
})

let timer = setInterval(() => {
  Inertia.reload({ only: ['isReady'] })
}, 2000);

// Watch prop
watch(() => props.isReady, (value) => {
  if (value) {
    trigger();
  }
})

function trigger() {
  if (props.isReady) {
    window.location.href = route('store.redirect', {'tenantId': props.tenantId});
  }
}

function giveUp() {
  clearInterval(timer);
}

// Trigger on mount
onMounted(() => {
  trigger();
})

// Cancel interval on unmount
onUnmounted(() => {
  giveUp();
})

</script>

<template>
    <AppAuthLayout :title="t('store.preparing_title')">
        <div>
            <div class="flex items-center justify-between">
              <ApplicationLogo class="h-8 w-auto" />
            </div>
            <div class="mt-10">
                <H2>
                    {{ t('store.preparing_heading') }}
                </H2>
                <P class="mt-2 text-sm text-stone-600">
                    We are creating your Simi workspace so your team can start onboarding listings and image workflows.
                </P>
            </div>
        </div>
        <div class="mt-8">
            <div class="text-center">
                <spinner />
            </div>
        </div>
        <div class="mt-8" v-if="props.isReady">
            <P>
              {{ t('store.redirect_message') }}
              <ALink target="_self" :href="route('store.redirect', {'tenantId': props.tenantId})" @click="giveUp">
                {{ t('store.redirect_link') }}
              </ALink>
            </P>
        </div>
    </AppAuthLayout>
</template>
