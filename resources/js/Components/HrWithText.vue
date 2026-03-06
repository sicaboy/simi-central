<template>
    <div class="absolute inset-0 flex items-center" aria-hidden="true">
        <div class="w-full border-t border-gray-300 dark:border-gray-700" />
    </div>
    <div class="relative flex justify-center text-sm">
        <span class="px-2 bg-gray-100 dark:bg-gray-900 text-gray-500 dark:text-gray-400">
            <slot />
        </span>
    </div>
</template>
