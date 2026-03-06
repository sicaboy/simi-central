
<script setup>
import { ref, onMounted, onUnmounted, watch } from 'vue';

const tableContainer = ref(null);
const showLeftShadow = ref(false);
const showRightShadow = ref(false);
const shadowOffset = ref(0);

const updateShadows = () => {
  if (tableContainer.value) {
    const container = tableContainer.value;
    const maxScrollLeft = container.scrollWidth - container.clientWidth;
    if (maxScrollLeft <= 0) {
      showLeftShadow.value = false;
      showRightShadow.value = false;
      return;
    }

    showLeftShadow.value = Math.ceil(container.scrollLeft) > 0;
    showRightShadow.value = Math.ceil(container.scrollLeft) < maxScrollLeft;
    shadowOffset.value = container.scrollLeft;
  }
};

onMounted(() => {
  if (tableContainer.value) {
    // Initial check and setup scroll event listener
    updateShadows();
    tableContainer.value.addEventListener('scroll', updateShadows);
    window.addEventListener('resize', updateShadows);
  }
});

onUnmounted(() => {
  if (tableContainer.value) {
    // Cleanup: Remove scroll event listener
    tableContainer.value.removeEventListener('scroll', updateShadows);
    window.removeEventListener('resize', updateShadows);
  }
});

// Watch for changes in container size (optional, requires more setup)
watch(() => tableContainer.value?.clientWidth, updateShadows);
watch(() => tableContainer.value?.scrollWidth, updateShadows);

</script>

<style>
.pm-table-container {
  position: relative;
  overflow-x: auto;
  box-sizing: border-box;
}

.pm-table-container::before,
.pm-table-container::after {
  content: none;
  position: absolute;
  top: 0;
  bottom: 0;
  z-index: 2;
  pointer-events: none;
}

.pm-table-container::before {
  left: var(--shadow-offset, 0);
  width: 1rem; /* Adjust shadow width as desired */
  background: linear-gradient(to right, rgba(128,128,128,.2) 0%, transparent 100%);
}

.pm-table-container::after {
  right: 0;
  transform: translateX(var(--shadow-offset, 0));
  width: 1rem; /* Adjust shadow width as desired */
  background: linear-gradient(to left, rgba(128,128,128,.2) 0%, transparent 100%);
}

.pm-table-container.left-shadow::before {
  content: ' ';
}

.pm-table-container.right-shadow::after {
  content: ' ';
}
</style>
<template>
<div
    class="pm-table-container"
    ref="tableContainer"
    :class="{'left-shadow': showLeftShadow, 'right-shadow': showRightShadow}"
    :style="{
      '--shadow-offset': shadowOffset + 'px'
    }"
>
  <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
    <thead class="
    bg-gray-50 dark:bg-gray-800
    text-gray-500 dark:text-gray-300 uppercase tracking-wider
    ">
        <slot name="thead" />
    </thead>
    <tbody class="bg-white dark:bg-gray-900
     divide-y divide-gray-200 dark:divide-gray-800
     text-gray-900 dark:text-gray-100
     whitespace-nowrap
     ">
        <slot name="tbody" />
    </tbody>
  </table>
</div>
</template>
