<template>
  <ALink href="/">
    <img
        class="dark:hidden inline-block h-full"
        src="/img/logo.svg"
    />
    <img
        class="hidden dark:inline-block h-full"
        src="/img/logo-dark.svg"
    />
  </ALink>
</template>
<style scoped>
a {
  display: inline-block;
}
</style>
<script setup>
import ALink from "@/Components/ALink.vue";
</script>