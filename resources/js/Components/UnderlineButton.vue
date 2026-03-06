<template>
    <button class="flex items-center" @click="$emit('click')">
        <div class="text-sm text-gray-600 underline">
            <slot />
        </div>
    </button>
</template>

<script>
export default {

}
</script>

<style scoped>

</style>
