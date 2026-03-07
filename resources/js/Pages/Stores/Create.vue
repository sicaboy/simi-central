<script setup>
import { computed, ref, watch } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import axios from 'axios';
import AppAuthLayout from '@/Layouts/AppAuthLayout.vue';
import ProfileDropdown from '@/Components/ProfileDropdown.vue';
import Input from '@/Components/Input.vue';
import InputError from '@/Components/InputError.vue';
import Label from '@/Components/Label.vue';
import H2 from '@/Components/H2.vue';
import P from '@/Components/P.vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import ALink from '@/Components/ALink.vue';
import ButtonWithSpinner from '@/Components/ButtonWithSpinner.vue';
import InputWithLeadingTailing from '@/Components/InputWithLeadingTailing.vue';
import { useI18n } from 'vue-i18n';

const props = defineProps({
  showBackButton: {
    type: Boolean,
    default: false,
  },
});

const { t } = useI18n();

const form = useForm({
  name: '',
  business_name: '',
  business_number: '',
  business_number_type: 'ABN',
  business_id: '',
  entity_type: '',
  state: '',
  postcode: '',
  suburb: '',
  subdomain: '',
});

const businessQuery = ref('');
const searchResults = ref([]);
const isSearching = ref(false);
const searchError = ref('');
const selectedBusinessId = ref('');
let activeRequest = 0;

const hasSearchResults = computed(() => searchResults.value.length > 0);
const locationText = computed(() => {
  return [form.suburb, form.state, form.postcode].filter(Boolean).join(', ');
});

const submit = () => {
  form.post(route('store.create.post'));
};

function convertToSlug(text) {
  return text
    .trim()
    .toLowerCase()
    .replace(/\s+/g, '-')
    .replace(/[^\w-]+/g, '')
    .replace(/--+/g, '-')
    .replace(/^-+|-+$/g, '');
}

function syncNamesFromBusiness() {
  if (!form.name || form.name === form.business_name) {
    form.name = form.business_name;
  }

  if (!form.subdomain || form.subdomain === convertToSlug(form.business_name)) {
    form.subdomain = convertToSlug(form.business_name);
  }
}

async function searchBusinesses(query) {
  const trimmed = query.trim();
  activeRequest += 1;
  const requestId = activeRequest;

  if (trimmed.length < 2) {
    searchResults.value = [];
    searchError.value = '';
    isSearching.value = false;
    return;
  }

  isSearching.value = true;
  searchError.value = '';

  try {
    const { data } = await axios.get(route('store.business.search'), {
      params: { q: trimmed, limit: 6 },
    });

    if (requestId !== activeRequest) {
      return;
    }

    searchResults.value = data.items || [];
  } catch (error) {
    if (requestId !== activeRequest) {
      return;
    }

    searchResults.value = [];
    searchError.value = t('store.lookup_error');
  } finally {
    if (requestId === activeRequest) {
      isSearching.value = false;
    }
  }
}

async function selectBusiness(item) {
  selectedBusinessId.value = item.id;
  searchError.value = '';

  try {
    const { data } = await axios.get(route('store.business.show', { id: item.id }));
    const business = data.item || item;

    form.business_id = business.id || item.id || '';
    form.business_name = business.name || item.name || '';
    form.business_number = business.abn || business.acn || '';
    form.business_number_type = business.abn ? 'ABN' : (business.acn ? 'ACN' : 'Business Number');
    form.entity_type = business.entityType || '';
    form.state = business.state || '';
    form.postcode = business.postcode || '';
    form.suburb = business.suburb || '';

    syncNamesFromBusiness();
  } catch (error) {
    searchError.value = t('store.lookup_error');
  }
}

watch(() => businessQuery.value, (value) => {
  searchBusinesses(value);
});

watch(() => form.business_name, (value, oldValue) => {
  if (!value) {
    return;
  }

  const previousSlug = convertToSlug(oldValue || '');
  if (!form.name || form.name === oldValue) {
    form.name = value;
  }
  if (!form.subdomain || form.subdomain === previousSlug) {
    form.subdomain = convertToSlug(value);
  }
});
</script>

<template>
  <AppAuthLayout :title="t('store.create_title')">
    <div>
      <div class="flex items-center justify-between">
        <ApplicationLogo class="h-8 w-auto" />
        <ProfileDropdown />
      </div>
      <div class="mt-10 max-w-2xl">
        <H2>
          {{ t('store.create_heading') }}
        </H2>
        <P class="mt-2">
          {{ t('store.create_description') }}
        </P>
      </div>
    </div>

    <div class="mt-8">
      <form class="space-y-8" @submit.prevent="submit">
        <section class="rounded-2xl border border-stone-200 bg-stone-50/70 p-6">
          <div class="flex items-start justify-between gap-4">
            <div>
              <h3 class="text-lg font-semibold text-stone-900">{{ t('store.business_search') }}</h3>
              <P class="mt-1 text-sm text-stone-600">{{ t('store.business_search_help') }}</P>
            </div>
            <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-stone-500">AU</span>
          </div>

          <div class="mt-5">
            <Label for="business_lookup" :value="t('store.business_search')" />
            <div class="mt-1">
              <Input
                id="business_lookup"
                v-model="businessQuery"
                type="text"
                :placeholder="t('store.business_search_placeholder')"
                class="block w-full"
              />
            </div>
          </div>

          <p v-if="isSearching" class="mt-3 text-sm text-stone-500">{{ t('store.searching') }}</p>
          <p v-else-if="searchError" class="mt-3 text-sm text-red-600">{{ searchError }}</p>
          <p v-else-if="businessQuery.length >= 2 && !hasSearchResults" class="mt-3 text-sm text-stone-500">{{ t('store.no_results') }}</p>

          <div v-if="hasSearchResults" class="mt-4 space-y-3">
            <p class="text-sm font-medium text-stone-700">{{ t('store.search_results') }}</p>
            <button
              v-for="item in searchResults"
              :key="item.id"
              type="button"
              class="w-full rounded-xl border px-4 py-4 text-left transition"
              :class="selectedBusinessId === item.id ? 'border-stone-900 bg-white shadow-sm' : 'border-stone-200 bg-white/80 hover:border-stone-300 hover:bg-white'"
              @click="selectBusiness(item)"
            >
              <div class="flex flex-col gap-2 md:flex-row md:items-center md:justify-between">
                <div>
                  <div class="font-semibold text-stone-900">{{ item.name }}</div>
                  <div class="mt-1 text-sm text-stone-600">
                    {{ item.entityType || 'Business' }}
                    <span v-if="item.state"> • {{ item.state }}</span>
                    <span v-if="item.postcode"> {{ item.postcode }}</span>
                  </div>
                </div>
                <div class="text-sm text-stone-600 md:text-right">
                  <div>{{ item.abn || item.acn }}</div>
                  <div class="mt-1 font-medium text-stone-800">{{ t('store.use_business') }}</div>
                </div>
              </div>
            </button>
          </div>
        </section>

        <section class="grid gap-6 rounded-2xl border border-stone-200 bg-white p-6 md:grid-cols-2">
          <div class="md:col-span-2">
            <h3 class="text-lg font-semibold text-stone-900">Business Details</h3>
          </div>

          <div>
            <Label for="business_name" :value="t('store.business_name')" />
            <P class="mt-1 text-xs text-stone-500">{{ t('store.business_name_hint') }}</P>
            <div class="mt-1">
              <Input id="business_name" v-model="form.business_name" name="business_name" type="text" required class="block w-full" />
              <InputError class="mt-2" :message="form.errors.business_name" attribute="business_name" />
            </div>
          </div>

          <div>
            <Label for="business_number" :value="t('store.business_number')" />
            <div class="mt-1">
              <Input id="business_number" v-model="form.business_number" name="business_number" type="text" required class="block w-full" />
              <InputError class="mt-2" :message="form.errors.business_number" attribute="business_number" />
            </div>
          </div>

          <div>
            <Label for="business_number_type" :value="t('store.business_number_type')" />
            <div class="mt-1">
              <Input id="business_number_type" v-model="form.business_number_type" name="business_number_type" type="text" class="block w-full" />
              <InputError class="mt-2" :message="form.errors.business_number_type" attribute="business_number_type" />
            </div>
          </div>

          <div>
            <Label for="entity_type" :value="t('store.entity_type')" />
            <div class="mt-1">
              <Input id="entity_type" v-model="form.entity_type" name="entity_type" type="text" class="block w-full" />
              <InputError class="mt-2" :message="form.errors.entity_type" attribute="entity_type" />
            </div>
          </div>

          <div class="md:col-span-2">
            <Label for="location_preview" :value="t('store.location')" />
            <div class="mt-1 rounded-md border border-stone-200 bg-stone-50 px-4 py-3 text-sm text-stone-700">
              {{ locationText || 'Add a business lookup result or enter location details below.' }}
            </div>
          </div>

          <div>
            <Label for="suburb" value="Suburb" />
            <div class="mt-1">
              <Input id="suburb" v-model="form.suburb" name="suburb" type="text" class="block w-full" />
              <InputError class="mt-2" :message="form.errors.suburb" attribute="suburb" />
            </div>
          </div>

          <div>
            <Label for="state" value="State" />
            <div class="mt-1">
              <Input id="state" v-model="form.state" name="state" type="text" class="block w-full" />
              <InputError class="mt-2" :message="form.errors.state" attribute="state" />
            </div>
          </div>

          <div>
            <Label for="postcode" value="Postcode" />
            <div class="mt-1">
              <Input id="postcode" v-model="form.postcode" name="postcode" type="text" class="block w-full" />
              <InputError class="mt-2" :message="form.errors.postcode" attribute="postcode" />
            </div>
          </div>
        </section>

        <section class="grid gap-6 rounded-2xl border border-stone-200 bg-white p-6 md:grid-cols-2">
          <div class="md:col-span-2">
            <h3 class="text-lg font-semibold text-stone-900">Workspace Setup</h3>
          </div>

          <div>
            <Label for="name" :value="t('store.project_name')" />
            <P class="mt-1 text-xs text-stone-500">{{ t('store.workspace_name_hint') }}</P>
            <div class="mt-1">
              <Input
                id="name"
                v-model="form.name"
                name="name"
                type="text"
                required
                autofocus
                class="block w-full"
                @input="form.subdomain = convertToSlug(form.name)"
              />
              <InputError class="mt-2" :message="form.errors.name" attribute="name" />
            </div>
          </div>

          <div>
            <Label for="subdomain" :value="t('store.internal_domain')" />
            <P class="mt-1 text-xs text-stone-500">{{ t('store.internal_domain_hint') }}</P>
            <div class="mt-1">
              <InputWithLeadingTailing
                id="subdomain"
                v-model="form.subdomain"
                name="subdomain"
                type="text"
                required
                class="block w-full"
                leading="https://"
                :tailing="'.' + $page.props.central.naked_domain"
              />
              <InputError class="mt-2" :message="form.errors.subdomain" attribute="subdomain" />
            </div>
          </div>
        </section>

        <input v-model="form.business_id" type="hidden" name="business_id" />

        <div class="pt-2">
          <ButtonWithSpinner type="submit" class="w-full" :processing="form.processing">
            {{ t('store.create_project') }}
          </ButtonWithSpinner>
        </div>

        <p v-if="props.showBackButton" class="mt-4 text-center text-sm text-gray-600">
          <ALink :href="route('store.list')">
            &laquo; {{ t('store.back_to_list') }}
          </ALink>
        </p>
      </form>
    </div>
  </AppAuthLayout>
</template>
