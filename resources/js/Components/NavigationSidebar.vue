<script setup>
import NavigationMenuGroup from "@/Components/NavigationMenuGroup.vue";
import NavigationMenuItem from "@/Components/NavigationMenuItem.vue";
import NavigationLowerMenuItem from "@/Components/NavigationLowerMenuItem.vue";

defineProps({
  navigation: {
    required: true
  },
  lowerNavigation: {
    type: Array,
    default: () => []
  }
});
</script>

<template>
  <!-- Navigation -->
  <div class="mt-1 flex-grow flex flex-col">
    <nav class="flex-1 px-2 pb-1 space-y-1">
      <template v-for="item in navigation" :key="item.name">
        <NavigationMenuGroup
            v-if="item.items?.length > 0"
            :group="item" />
        <NavigationMenuItem
            v-else
            :item="item" />
      </template>
    </nav>
  </div>
  <!-- Lower Navigation -->
  <div class="border-t border-gray-200 dark:border-gray-700 pt-1" v-if="lowerNavigation.length">
    <nav class="flex-1 px-2 pb-1 space-y-1">
      <template v-for="item in lowerNavigation">
        <NavigationLowerMenuItem
            :item="item" />
      </template>
    </nav>
  </div>
</template>

<style scoped>

</style>