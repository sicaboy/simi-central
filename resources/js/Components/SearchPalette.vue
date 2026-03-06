<template>
  <TransitionRoot :show="open" as="template" @after-leave="query = ''" appear>
    <Dialog as="div" class="relative z-10" @close="onLeave">
      <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0" enter-to="opacity-100"
                       leave="ease-in duration-200" leave-from="opacity-100" leave-to="opacity-0">
        <div class="fixed inset-0 transition-opacity
        bg-gray-500 dark:bg-black bg-opacity-25 dark:bg-opacity-50"/>
      </TransitionChild>

      <div class="fixed inset-0 z-10 overflow-y-auto p-4 sm:p-6 md:p-20">
        <TransitionChild as="template" enter="ease-out duration-300" enter-from="opacity-0 scale-95"
                         enter-to="opacity-100 scale-100" leave="ease-in duration-200"
                         leave-from="opacity-100 scale-100" leave-to="opacity-0 scale-95">
          <DialogPanel
              class="mx-auto max-w-xl transform overflow-hidden rounded-xl bg-white dark:bg-gray-800 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
            <Combobox @update:modelValue="onSelect">
              <div class="relative">
                <DynamicHeroicon
                    name="search"
                    size="5"
                    outline=true
                    class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400" aria-hidden="true"/>
                <ComboboxInput
                    class="h-12 w-full border-0 bg-transparent pl-11 pr-4 text-gray-800 dark:text-gray-300 placeholder-gray-400 focus:ring-0 sm:text-sm"
                    :placeholder="t('common.search') + '...'"
                    @change="query = $event.target.value"/>
              </div>

              <div v-if="query === ''" class="border-t border-gray-100 dark:border-gray-700 py-14 px-6 text-center text-sm sm:px-14">
                <DynamicHeroicon
                    name="search"
                    size="8"
                    outline=true
                    class="mx-auto h-6 w-6 text-gray-400 dark:text-gray-600" aria-hidden="true"/>
                <p class="mt-4 font-semibold text-gray-900 dark:text-gray-100">
                  {{ t('common.search_for_anything') }}
                </p>
                <p class="mt-2 text-gray-500">
                  {{ t('common.search_description') }}
                </p>
              </div>

              <ComboboxOptions v-if="filteredItems.length > 0" static
                               class="max-h-80 scroll-pt-11 scroll-pb-2 space-y-2 overflow-y-auto pb-2">
                <li v-for="[category, items] in Object.entries(groups)" :key="category">
                  <h2 class="bg-gray-100 dark:bg-gray-900 py-2.5 px-4 text-xs font-semibold text-gray-900 dark:text-gray-100">
                    {{ category }}
                  </h2>
                  <ul class="mt-2 text-sm text-gray-800 dark:text-gray-200">
                    <ComboboxOption v-for="item in items" :key="item.id" :value="item" as="template"
                                    v-slot="{ active }">
                      <li :class="['cursor-default select-none px-4 py-2', active && 'bg-gray-500 text-white']">
                        {{ item.name }}
                      </li>
                    </ComboboxOption>
                  </ul>
                </li>
              </ComboboxOptions>

              <div v-if="query !== '' && filteredItems.length === 0"
                   class="border-t border-gray-100 dark:border-gray-700 py-14 px-6 text-center text-sm sm:px-14">
                <DynamicHeroicon
                    name="x"
                    size="8"
                    outline=true
                    class="mx-auto text-gray-400 dark:text-gray-600" aria-hidden="true"/>
                <p class="mt-4 font-semibold text-gray-900 dark:text-gray-100">
                  {{ t('common.no_results_found') }}
                </p>
                <p class="mt-2 text-gray-500">
                  {{ t('common.adjust_search') }}
                </p>
              </div>
            </Combobox>
          </DialogPanel>
        </TransitionChild>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {computed, ref} from 'vue'
import {DynamicHeroicon} from "vue-dynamic-heroicons";
import {
  Combobox,
  ComboboxInput,
  ComboboxOption,
  ComboboxOptions,
  Dialog,
  DialogPanel,
  TransitionChild,
  TransitionRoot,
} from '@headlessui/vue'
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

const items = [
  // {id: 1, name: 'Workflow Inc.', category: 'Clients', url: '#'},
  // // More items...
  // {id: 1, name: 'Workflow Inc.', category: 'Clients', url: '#'},
  // {id: 1, name: 'Workflow Inc.', category: 'Clients', url: '#'},
  // {id: 1, name: 'Workflow Inc.', category: 'DD', url: '#'},
]

const query = ref('')

const emit = defineEmits(['close'])

let props = defineProps({
  open: {
    type: Boolean,
    required: true,
  },
});

const filteredItems = computed(() =>
    query.value === ''
        ? []
        : items.filter((item) => {
          return item.name.toLowerCase().includes(query.value.toLowerCase())
        })
)
const groups = computed(() =>
    filteredItems.value.reduce((groups, item) => {
      return {...groups, [item.category]: [...(groups[item.category] || []), item]}
    }, {})
)

function onSelect(item) {
  window.location = item.url
}

function onLeave() {
  emit('close')
}
</script>