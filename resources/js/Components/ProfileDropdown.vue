<template>
  <!-- Profile dropdown -->
  <Menu as="div" class="ml-3 relative z-30">
    <div>
      <MenuButton
          class="profile-menu-button max-w-xs flex items-center text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2">
          <span class="sr-only">{{ t('common.open_user_menu') }}</span>
          <span class="inline-block h-8 w-8 rounded-full overflow-hidden bg-gray-100 dark:bg-gray-900">
              <template
                  v-if="$page.props.jetstream.managesProfilePhotos && $page.props.auth.user.profile_photo_url.indexOf('ui-avatars.com') == -1"
                  class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition"
              >
                  <img class="h-8 w-8 rounded-full object-cover"
                       :src="$page.props.auth.user.profile_photo_url"
                       :alt="$page.props.auth.user.name" referrerpolicy="no-referrer">
              </template>
              <span v-else class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-amber-500">
                  <span class="text-sm font-medium leading-none text-white">
                      {{ getInitial($page.props.auth.user.name) }}
                  </span>
              </span>
          </span>

        <ChevronDownIcon class="-mr-1 ml-2 h-5 w-5 text-gray-500" aria-hidden="true"/>
      </MenuButton>
    </div>
    <transition enter-active-class="transition ease-out duration-100"
                enter-from-class="transform opacity-0 scale-95"
                enter-to-class="transform opacity-100 scale-100"
                leave-active-class="transition ease-in duration-75"
                leave-from-class="transform opacity-100 scale-100"
                leave-to-class="transform opacity-0 scale-95">
      <MenuItems
          class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 dark:divide-gray-700 focus:outline-none">
        <div class="px-4 py-3">
          <P class="text-sm">{{ t('auth.signed_in_as') }}</P>
          <p class="text-sm font-medium text-gray-900 dark:text-gray-100 truncate">
            {{ $page.props.auth.user.name }}
          </p>
        </div>
        <div class="py-1">
          <MenuItem v-for="item in userNavigation" :key="item.name" v-slot="{ active }">
            <Link :href="item.href"
                  :class="[
                      'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700',
                      'block w-full text-left px-4 py-2 text-sm']">
              {{ t(item.name) }}
            </Link>
          </MenuItem>
        </div>
        <div class="pt-1">
          <form method="post" @submit.prevent="logout">
            <MenuItem v-slot="{ active }">
              <button type="submit"
                      :class="[
                          'text-gray-700 dark:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700',
                          'block w-full text-left px-4 py-2 text-sm']">
                {{ t('auth.sign_out') }}
              </button>
            </MenuItem>
          </form>
        </div>
      </MenuItems>
    </transition>
  </Menu>
</template>

<script setup>
import {Menu, MenuButton, MenuItem, MenuItems,} from '@headlessui/vue'
import {ChevronDownIcon} from '@heroicons/vue/solid'
import {Link, usePage} from '@inertiajs/inertia-vue3';
import {Inertia} from "@inertiajs/inertia";
import { useI18n } from 'vue-i18n';
import P from '@/Components/P.vue';

const { t } = useI18n();

function getInitial(name) {
  // Split the string into an array of strings
  const nameArray = name.split(' ')
  if (nameArray.length > 1) {
    // Get the first letter of each name
    const initials = nameArray.map(name => name.charAt(0))
    // Join the first letters together limit to 2 chars
    return initials.join('').substring(0, 2).toUpperCase()
  }
  // If the name is only one word, just return the first 2 letters
  return name.substring(0, 2).toUpperCase()
}

function logout() {
  Inertia.post(route('logout'))
}

const props = defineProps(
    {
      userNavigation: {
        type: Array,
        default: () => [
          {name: 'auth.manage_account', href: route('central.account')},
        ]
      },
    }
);

const page = usePage()
const themeColorLight = page.props.value.theme.color_light
</script>

<style scoped>
.profile-menu-button {
  --tw-ring-color: v-bind('themeColorLight');
}
</style>
