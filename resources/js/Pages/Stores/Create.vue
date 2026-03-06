<script setup>
import {useForm} from "@inertiajs/inertia-vue3";
import AppAuthLayout from "@/Layouts/AppAuthLayout.vue";
import ProfileDropdown from "@/Components/ProfileDropdown.vue";
import Input from '@/Components/Input.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import H2 from '@/Components/H2.vue';
import P from '@/Components/P.vue';
import ApplicationLogo from "@/Components/ApplicationLogo.vue";
import ALink from "@/Components/ALink.vue";
import ButtonWithSpinner from "@/Components/ButtonWithSpinner.vue";
import InputWithLeadingTailing from "@/Components/InputWithLeadingTailing.vue";
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const form = useForm({
  name: '',
  subdomain: '',
});

const submit = () => {
  form.post(route('store.create.post'));
}

function convertToSlug(Text) {
  return Text
      .trim()
      .toLowerCase()
      .replace(/ /g, '-')
      .replace(/[^\w-]+/g, '');
}
</script>

<template>
  <AppAuthLayout :title="t('store.create_title')">
    <div>
      <div class="flex items-center justify-between">
        <ApplicationLogo class="h-8 w-auto"/>
        <ProfileDropdown/>
      </div>
      <div class="mt-10">
        <H2>
          {{ t('store.create_heading') }}
        </H2>
        <P class="mt-2">
          {{ t('store.create_description') }}
        </P>
      </div>
    </div>
    <div class="mt-8">
      <form @submit.prevent="submit" class="space-y-6">
        <div>
          <Label for="name"
                 :value="t('store.project_name')"/>
          <div class="mt-1">
            <Input id="name"
                   name="name"
                   v-model="form.name"
                   type="text"
                   required
                   autofocus
                   @input="form.subdomain = convertToSlug(form.name)"
                   class="block w-full"
            />
            <InputError class="mt-2" :message="form.errors.name" attribute="name"/>
          </div>
        </div>

        <div>
          <Label for="subdomain"
                 :value="t('store.internal_domain')"/>
          <div class="mt-1">
            <InputWithLeadingTailing
                id="subdomain"
                name="subdomain"
                v-model="form.subdomain"
                type="text"
                required
                class="block w-full"
                leading="https://"
                :tailing="'.' + $page.props.central.naked_domain"
            />

            <InputError class="mt-2" :message="form.errors.subdomain" attribute="subdomain"/>
          </div>
        </div>

        <div class="pt-2">
          <ButtonWithSpinner
              type="submit"
              class="w-full"
              :processing="form.processing"
          >
            {{ t('store.create_project') }}
          </ButtonWithSpinner>
        </div>
        <p class="mt-4 text-sm text-gray-600 text-center">
          <ALink :href="route('store.list')">
            &laquo; {{ t('store.back_to_list') }}
          </ALink>
        </p>
      </form>
    </div>
  </AppAuthLayout>
</template>
