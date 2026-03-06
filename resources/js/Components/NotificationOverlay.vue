<template>
  <!-- Notifications -->
  <TransitionRoot as="template" :show="open">
    <Dialog as="div" class="fixed inset-0 overflow-hidden z-20" @close="open = false">
      <div class="absolute inset-0 overflow-hidden">
        <DialogOverlay class="absolute inset-0"/>
        <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10">
          <TransitionChild as="template" enter="transform transition ease-in-out duration-500 sm:duration-700"
                           enter-from="translate-x-full" enter-to="translate-x-0"
                           leave="transform transition ease-in-out duration-500 sm:duration-700"
                           leave-from="translate-x-0" leave-to="translate-x-full">
            <div class="pointer-events-auto w-screen max-w-md">
              <div class="flex h-full flex-col overflow-y-scroll py-6 shadow-xl
                    bg-white dark:bg-gray-800">
                <div class="px-4 sm:px-6">
                  <div class="flex items-start justify-between">
                    <DialogTitle class="text-lg font-medium text-gray-900 dark:text-gray-100">
                      {{ t('common.notifications') }}
                    </DialogTitle>
                    <div class="ml-3 flex h-7 items-center">
                      <button type="button"
                              class="p-2 rounded-md text-gray-400 hover:text-gray-500 focus:outline-none hover:bg-gray-100 dark:hover:bg-gray-900"
                              @click="open = false">
                        <span class="sr-only">{{ t('common.close_panel') }}</span>
                        <XIcon class="h-6 w-6" aria-hidden="true"/>
                      </button>
                    </div>
                  </div>
                </div>
                <div class="relative mt-6 flex-1">
                  <ul role="list" class="divide-y divide-gray-200" v-if="list.length">
                    <li v-for="message in list"
                        :key="message.uid"
                        class="relative bg-white py-4 px-4 sm:px-6 hover:bg-gray-50 focus-within:ring-2 focus-within:ring-inset focus-within:ring-indigo-600">
                      <div class="flex justify-between space-x-3">
                        <div class="min-w-0 flex-1">
                          <a href="#" class="block focus:outline-none">
                            <span class="absolute inset-0" aria-hidden="true"/>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">{{ message.sender }}</p>
                            <p class="text-sm text-gray-500 truncate">{{ message.subject }}</p>
                          </a>
                        </div>
                        <time :datetime="message.datetime"
                              class="flex-shrink-0 whitespace-nowrap text-sm text-gray-500">{{ message.time }}
                        </time>
                      </div>
                      <div class="mt-1">
                        <p class="line-clamp-2 text-sm text-gray-600 dark:text-gray-400">
                          {{ message.preview }}
                        </p>
                      </div>
                    </li>
                  </ul>
                  <div class="text-center" v-else>
                    <CheckIcon class="my-4 mx-auto h-12 w-12 text-gray-300 dark:text-gray-600"/>
                    <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">{{ t('common.no_notification') }}</h3>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-600">
                      {{ t('common.no_notification_message') }}
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </TransitionChild>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import {ref} from 'vue'
import {Dialog, DialogOverlay, DialogTitle, TransitionChild, TransitionRoot} from '@headlessui/vue'
import {CheckIcon, XIcon} from '@heroicons/vue/solid'
import { useI18n } from 'vue-i18n';

const { t } = useI18n();

let props = defineProps({
  list: {
    type: Array,
    required: true,
  },
  open: {
    type: Boolean,
    required: true,
  },
});

const list = ref([])
</script>
