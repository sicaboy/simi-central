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
import Button from '@/Components/Button.vue';
import ButtonWithSpinner from '@/Components/ButtonWithSpinner.vue';
import InputWithLeadingTailing from '@/Components/InputWithLeadingTailing.vue';
import { PencilIcon } from '@heroicons/vue/outline';
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
const businessMode = ref('search');
const businessConfirmed = ref(false);
const isCheckingSubdomain = ref(false);
let activeRequest = 0;
let activeSubdomainRequest = 0;
let subdomainDebounceTimer = null;

const hasSearchResults = computed(() => searchResults.value.length > 0);
const locationText = computed(() => [form.state, form.postcode].filter(Boolean).join(' '));
const hasBusinessDetails = computed(() => businessMode.value === 'manual');
const isManualEntry = computed(() => businessMode.value === 'manual');

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

async function updateSuggestedSubdomain() {
  const baseName = (form.name || form.business_name || '').trim();
  activeSubdomainRequest += 1;
  const requestId = activeSubdomainRequest;

  if (!baseName) {
    form.subdomain = '';
    return;
  }

  isCheckingSubdomain.value = true;

  try {
    const { data } = await axios.get(route('store.subdomain.suggest'), {
      params: { name: baseName },
    });

    if (requestId !== activeSubdomainRequest) {
      return;
    }

    form.subdomain = data.subdomain || convertToSlug(baseName);
  } catch (error) {
    if (requestId !== activeSubdomainRequest) {
      return;
    }

    form.subdomain = convertToSlug(baseName);
  } finally {
    if (requestId === activeSubdomainRequest) {
      isCheckingSubdomain.value = false;
    }
  }
}

function queueSuggestedSubdomainUpdate() {
  if (subdomainDebounceTimer) {
    clearTimeout(subdomainDebounceTimer);
  }

  subdomainDebounceTimer = setTimeout(() => {
    updateSuggestedSubdomain();
  }, 300);
}

function resetBusinessSelection(mode = 'search') {
  businessMode.value = mode;
  businessConfirmed.value = mode === 'manual';
  selectedBusinessId.value = '';
  businessQuery.value = '';
  searchResults.value = [];
  searchError.value = '';

  form.business_id = '';
  form.business_name = '';
  form.business_number = '';
  form.business_number_type = 'ABN';
  form.entity_type = '';
  form.state = '';
  form.postcode = '';
  form.suburb = '';
}

function startManualEntry() {
  resetBusinessSelection('manual');
}

function editBusinessSelection() {
  resetBusinessSelection('search');
}

function syncWorkspaceNameFromBusiness() {
  if (!form.name) {
    form.name = form.business_name;
  }

  queueSuggestedSubdomainUpdate();
}

async function searchBusinesses(query) {
  const trimmed = query.trim();
  activeRequest += 1;
  const requestId = activeRequest;

  if (trimmed.length < 2 || businessConfirmed.value || businessMode.value !== 'search') {
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

    businessConfirmed.value = true;
    businessMode.value = 'selected';
    searchResults.value = [];
    businessQuery.value = '';
    syncWorkspaceNameFromBusiness();
  } catch (error) {
    searchError.value = t('store.lookup_error');
  }
}

watch(() => businessQuery.value, (value) => {
  searchBusinesses(value);
});

watch(() => form.business_name, (value, oldValue) => {
  if (!value || businessConfirmed.value) {
    return;
  }

  if (!form.name || form.name === oldValue) {
    form.name = value;
  }

  queueSuggestedSubdomainUpdate();
});

watch(() => form.name, () => {
  queueSuggestedSubdomainUpdate();
});
</script>

<template>
  <AppAuthLayout :title="t('store.create_title')" width="wide">
    <div class="overflow-hidden rounded-[2rem] border border-stone-200/80 bg-white/90 shadow-[0_30px_80px_-40px_rgba(15,23,42,0.45)] backdrop-blur dark:border-slate-800 dark:bg-slate-900/90">
      <div class="border-b border-stone-200/80 bg-gradient-to-r from-stone-100 via-amber-50 to-white px-6 py-8 dark:border-slate-800 dark:from-slate-900 dark:via-slate-900 dark:to-slate-950 sm:px-8 lg:px-10">
        <div class="flex items-center justify-between gap-4">
          <ApplicationLogo class="h-8 w-auto" />
          <ProfileDropdown />
        </div>

        <div class="mt-10">
          <div class="max-w-3xl">
            <H2>
              {{ t('store.create_heading') }}
            </H2>
            <P class="mt-3 max-w-2xl text-sm sm:text-base">
              {{ t('store.create_description') }}
            </P>
          </div>
        </div>
      </div>

      <div class="px-6 py-8 sm:px-8 lg:px-10 lg:py-10">
        <form class="space-y-8" @submit.prevent="submit">
          <section class="rounded-2xl border border-stone-200 bg-stone-50/70 p-6 dark:border-slate-800 dark:bg-slate-950/60">
              <div class="flex items-start justify-between gap-4">
                <div>
                  <h3 class="text-lg font-semibold text-stone-900 dark:text-slate-100">{{ t('store.business_search') }}</h3>
                  <P class="mt-1 text-sm text-stone-600 dark:text-slate-300">{{ t('store.business_search_help') }}</P>
                </div>
                <span class="rounded-full bg-white px-3 py-1 text-xs font-semibold uppercase tracking-[0.18em] text-stone-500 dark:bg-slate-900 dark:text-slate-300">AU</span>
              </div>

              <div v-if="!businessConfirmed && !isManualEntry" class="mt-5">
                <Label for="business_lookup" :value="t('store.business_search')" />
                <div class="mt-2">
                  <Input
                    id="business_lookup"
                    v-model="businessQuery"
                    type="text"
                    :placeholder="t('store.business_search_placeholder')"
                    class="block w-full"
                  />
                </div>

                <p v-if="isSearching" class="mt-3 text-sm text-stone-500 dark:text-slate-400">{{ t('store.searching') }}</p>
                <p v-else-if="searchError" class="mt-3 text-sm text-red-600 dark:text-red-400">{{ searchError }}</p>
                <p v-else-if="businessQuery.length >= 2 && !hasSearchResults" class="mt-3 text-sm text-stone-500 dark:text-slate-400">{{ t('store.no_results') }}</p>

                <div v-if="hasSearchResults" class="mt-5 grid gap-3">
                  <p class="text-sm font-medium text-stone-700 dark:text-slate-200">{{ t('store.search_results') }}</p>
                  <button
                    v-for="item in searchResults"
                    :key="item.id"
                    type="button"
                    class="w-full rounded-2xl border px-4 py-4 text-left transition border-stone-200 bg-white/90 hover:border-stone-300 hover:bg-white dark:border-slate-800 dark:bg-slate-900/70 dark:hover:border-slate-700 dark:hover:bg-slate-900"
                    @click="selectBusiness(item)"
                  >
                    <div class="flex flex-col gap-3 lg:flex-row lg:items-center lg:justify-between">
                      <div>
                        <div class="font-semibold text-stone-900 dark:text-slate-100">{{ item.name }}</div>
                        <div class="mt-1 text-sm text-stone-600 dark:text-slate-300">
                          <span v-if="item.state">{{ item.state }}</span>
                          <span v-if="item.state && item.postcode"> • </span>
                          <span v-if="item.postcode">{{ item.postcode }}</span>
                        </div>
                      </div>
                      <div class="text-sm text-stone-600 lg:text-right dark:text-slate-300">
                        <div>{{ item.abn || item.acn }}</div>
                        <div class="mt-1 font-medium text-stone-800 dark:text-amber-200">{{ t('store.use_business') }}</div>
                      </div>
                    </div>
                  </button>
                </div>

                <div class="mt-5">
                  <Button type="button" class-name="secondary" @click="startManualEntry">
                    {{ t('store.manual_entry') }}
                  </Button>
                </div>
              </div>

              <div v-else class="mt-5 rounded-2xl border border-stone-200 bg-white p-5 dark:border-slate-800 dark:bg-slate-900">
                <div class="flex items-start justify-between gap-4">
                  <div>
                    <div class="text-xs uppercase tracking-[0.18em] text-stone-500 dark:text-slate-400">
                      {{ isManualEntry ? t('store.manual_business') : t('store.selected_business') }}
                    </div>
                    <div class="mt-2 text-lg font-semibold text-stone-900 dark:text-slate-100">
                      {{ form.business_name || 'Business name required' }}
                    </div>
                    <div class="mt-1 text-sm text-stone-600 dark:text-slate-300">
                      <span v-if="form.business_number">{{ form.business_number }}</span>
                      <span v-if="form.business_number && locationText"> • </span>
                      <span v-if="locationText">{{ locationText }}</span>
                    </div>
                  </div>

                  <button
                    type="button"
                    class="inline-flex h-10 w-10 items-center justify-center rounded-full border border-stone-200 text-stone-600 transition hover:border-stone-300 hover:bg-stone-50 dark:border-slate-700 dark:text-slate-300 dark:hover:border-slate-600 dark:hover:bg-slate-950"
                    @click="editBusinessSelection"
                  >
                    <PencilIcon class="h-4 w-4" />
                  </button>
                </div>

                <p v-if="isManualEntry" class="mt-4 text-sm text-stone-600 dark:text-slate-300">
                  {{ t('store.manual_business_help') }}
                </p>
              </div>
          </section>

          <section v-if="hasBusinessDetails" class="rounded-2xl border border-stone-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-950/80">
            <div>
              <h3 class="text-lg font-semibold text-stone-900 dark:text-slate-100">Business Details</h3>
              <P class="mt-1 text-sm text-stone-600 dark:text-slate-300">{{ t('store.business_details_help') }}</P>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2 xl:grid-cols-3">
              <div class="xl:col-span-2">
                <Label for="business_name" :value="t('store.business_name')" />
                <P class="mt-1 text-xs text-stone-500 dark:text-slate-400">{{ t('store.business_name_hint') }}</P>
                <div class="mt-2">
                  <Input id="business_name" v-model="form.business_name" name="business_name" type="text" required class="block w-full" />
                  <InputError class="mt-2" :message="form.errors.business_name" attribute="business_name" />
                </div>
              </div>

              <div>
                <Label for="business_number" :value="t('store.business_number')" />
                <div class="mt-2">
                  <Input id="business_number" v-model="form.business_number" name="business_number" type="text" required class="block w-full" />
                  <InputError class="mt-2" :message="form.errors.business_number" attribute="business_number" />
                </div>
              </div>

              <div>
                <Label for="state" value="State" />
                <div class="mt-2">
                  <Input id="state" v-model="form.state" name="state" type="text" class="block w-full" />
                  <InputError class="mt-2" :message="form.errors.state" attribute="state" />
                </div>
              </div>

              <div>
                <Label for="postcode" value="Postcode" />
                <div class="mt-2">
                  <Input id="postcode" v-model="form.postcode" name="postcode" type="text" class="block w-full" />
                  <InputError class="mt-2" :message="form.errors.postcode" attribute="postcode" />
                </div>
              </div>
            </div>
          </section>

          <section class="rounded-2xl border border-stone-200 bg-white p-6 dark:border-slate-800 dark:bg-slate-950/80">
            <div>
              <h3 class="text-lg font-semibold text-stone-900 dark:text-slate-100">Workspace Setup</h3>
              <P class="mt-1 text-sm text-stone-600 dark:text-slate-300">Set a clear workspace name and private URL for the business or franchise.</P>
            </div>

            <div class="mt-6 grid gap-6 lg:grid-cols-2">
              <div>
                <Label for="name" :value="t('store.project_name')" />
                <P class="mt-1 text-xs text-stone-500 dark:text-slate-400">{{ t('store.workspace_name_hint') }}</P>
                <div class="mt-2">
                  <Input
                    id="name"
                    v-model="form.name"
                    name="name"
                    type="text"
                    required
                    autofocus
                    class="block w-full"
                  />
                  <InputError class="mt-2" :message="form.errors.name" attribute="name" />
                </div>
              </div>

              <div>
                <Label for="subdomain" :value="t('store.internal_domain')" />
                <P class="mt-1 text-xs text-stone-500 dark:text-slate-400">{{ t('store.internal_domain_hint') }}</P>
                <div class="mt-2">
                  <InputWithLeadingTailing
                    id="subdomain"
                    v-model="form.subdomain"
                    name="subdomain"
                    type="text"
                    required
                    readonly
                    class="block w-full"
                    leading="https://"
                    :tailing="'.' + $page.props.central.naked_domain"
                  />
                  <InputError class="mt-2" :message="form.errors.subdomain" attribute="subdomain" />
                </div>
              </div>
            </div>

            <div class="mt-8 flex border-t border-stone-200 pt-6 dark:border-slate-800 sm:justify-end">
              <div class="w-full sm:w-auto sm:min-w-[240px]">
                <ButtonWithSpinner type="submit" class="w-full" :processing="form.processing">
                  {{ t('store.create_project') }}
                </ButtonWithSpinner>
              </div>
            </div>
          </section>

          <input v-model="form.business_id" type="hidden" name="business_id" />
          <input v-model="form.business_number_type" type="hidden" name="business_number_type" />
          <input v-model="form.entity_type" type="hidden" name="entity_type" />
          <input v-model="form.suburb" type="hidden" name="suburb" />

          <p v-if="props.showBackButton" class="text-center text-sm text-stone-600 dark:text-slate-400">
            <ALink :href="route('store.list')">
              &laquo; {{ t('store.back_to_list') }}
            </ALink>
          </p>
        </form>
      </div>
    </div>
  </AppAuthLayout>
</template>
