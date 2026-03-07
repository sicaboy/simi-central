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
    <AppAuthLayout :title="t('store.preparing_title')" width="medium">
        <div class="mx-auto overflow-hidden rounded-[2rem] border border-stone-200/80 bg-white/90 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.45)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
            <div class="border-b border-stone-200/80 bg-gradient-to-r from-stone-100 via-amber-50 to-white px-6 py-8 dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 sm:px-8">
                <div class="flex items-center justify-between">
                    <ApplicationLogo class="h-8 w-auto" />
                </div>
                <div class="mt-10">
                    <H2>
                        {{ t('store.preparing_heading') }}
                    </H2>
                    <P class="mt-3 text-sm text-stone-600 dark:text-slate-300">
                        We are provisioning your Simi workspace and preparing secure access for the team.
                    </P>
                </div>
            </div>

            <div class="px-6 py-10 text-center sm:px-8">
                <div class="mx-auto flex h-20 w-20 items-center justify-center rounded-full">
                    <spinner />
                </div>

                <div class="mt-6 space-y-2">
                    <P>
                        This usually takes a moment while Simi prepares the workspace container and redirect flow.
                    </P>
                </div>

                <div class="mt-8 rounded-2xl border border-stone-200 bg-stone-50 px-5 py-4 text-left dark:border-slate-800 dark:bg-slate-950/80">
                    <div class="text-sm font-semibold text-stone-900 dark:text-slate-100">What happens next</div>
                    <div class="mt-2 text-sm text-stone-600 dark:text-slate-300">
                        Your account will be redirected automatically as soon as the workspace is ready.
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
            </div>
        </div>
    </AppAuthLayout>
</template>
