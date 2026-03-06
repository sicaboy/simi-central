<script setup>
import NavigationMenuItem from "@/Components/NavigationMenuItem.vue";
import NavigationLowerMenuItem from "@/Components/NavigationLowerMenuItem.vue";
import NavigationMenuGroup from "@/Components/NavigationMenuGroup.vue";

defineProps({
  navigation: {
    type: Array,
    required: true
  },
  lowerNavigation: {
    type: Array,
    default: () => []
  }
});
</script>

<template>
  <div class="flex-1 h-0 overflow-y-auto divide-y divide-gray-200 dark:divide-gray-700">
    <nav class="px-1 py-2 space-y-1">
      <template v-for="item in navigation">
        <NavigationMenuGroup
            v-if="item.items?.length > 0"
            :group="item" />
        <NavigationMenuItem
            v-else
            :item="item" />
      </template>
    </nav>
    <nav class="px-1 py-2 space-y-1">
      <template v-for="item in lowerNavigation">
        <NavigationLowerMenuItem
            :item="item" />
      </template>
    </nav>
  </div>
</template>