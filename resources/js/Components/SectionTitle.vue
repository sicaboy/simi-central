<template>
    <div>
        <h2 class="text-lg leading-6 text-gray-900 dark:text-gray-100">
            <slot name="title" />
        </h2>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
            <slot name="description" />
        </p>
        <div class="px-4 sm:px-0">
            <slot name="aside" />
        </div>
    </div>
</template>
