<template>
    <div class="px-4 flex items-center">
        <div class="flex-shrink-0">
            <template v-if="$page.props.jetstream.managesProfilePhotos && $page.props.auth.user.profile_photo_url.indexOf('ui-avatars.com') == -1" class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                <img class="h-8 w-8 rounded-full object-cover" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" referrerpolicy="no-referrer">
            </template>
            <span v-else class="inline-flex items-center justify-center h-8 w-8 rounded-full bg-amber-500">
                <span class="text-sm font-medium leading-none text-white">
                    {{ getInitial($page.props.auth.user.name) }}
                </span>
            </span>
        </div>
        <div class="ml-3">
            <div class="text-base font-medium text-gray-800 dark:text-gray-200">
                {{ $page.props.auth.user.name }}
            </div>
            <div class="text-sm font-medium text-gray-500 dark:text-gray-400">
                {{ $page.props.auth.user.email }}
            </div>
        </div>
    </div>
    <div class="mt-3 px-2 space-y-1">
        <Link
            v-for="item in userNavigation"
            :key="item.name"
            :href="item.href"
            class="block rounded-md py-2 px-3 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:hover:text-gray-400 dark:hover:bg-gray-900">
            {{ item.name }}
        </Link>
        <form method="post" @submit.prevent="logout">
            <button type="submit" class="w-full text-left block rounded-md py-2 px-3 text-base font-medium text-gray-500 hover:bg-gray-50 hover:text-gray-900 dark:hover:text-gray-400 dark:hover:bg-gray-900">
                Sign out
            </button>
        </form>
    </div>
</template>

<script setup>
import {Link} from '@inertiajs/inertia-vue3';
import {Inertia} from "@inertiajs/inertia";

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

const props = defineProps({
  userNavigation: {
    type: Array,
    default: () => [
      {name: 'Manage account', href: route('central.account')},
    ],
  },
})
</script>
