<script setup>
import Span from "@/Components/Span.vue";
import {Switch, SwitchGroup, SwitchLabel} from '@headlessui/vue'
import {ref} from "vue";
import {usePage} from "@inertiajs/inertia-vue3";

let props = defineProps({
  enabled: {
    type: Boolean,
    required: true,
    default: false
  }
})

let enabled = ref(props.enabled)

let emit = defineEmits(['update:enabled'])

function updateEnabled() {
  enabled.value = !enabled.value
  emit('update:enabled', enabled.value)
}

const page = usePage()
const themeColor = page.props.value.theme.color
</script>

<template>
  <SwitchGroup as="div" class="flex">
    <Switch
        :value="enabled ? 'enabled' : 'disabled'"
        @click="updateEnabled"
        :style="{ backgroundColor: enabled ? themeColor : '' }"
        :class="[
            enabled ? 'bg-enabled' : 'bg-gray-200 dark:bg-gray-600',
            'relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out'
        ]"
    >
      <span
          aria-hidden="true"
          :class="[enabled ? 'translate-x-5' : 'translate-x-0', 'pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out']"
      />
    </Switch>
    <SwitchLabel as="span" class="ml-3 cursor-pointer">
      <slot/>
    </SwitchLabel>
  </SwitchGroup>
</template>

<style scoped>
</style>
