<template>
    <div :class="[stateClass, 'rounded-md bg-red-50 p-4']"
    >
        <div class="flex">
            <div class="flex-shrink-0">
                <component :is="stateIcon"
                           :class="[stateIconClass, 'h-5 w-5']"
                           aria-hidden="true" />
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-medium text-red-800">
                    {{ message }}
                </h3>

                <div class="mt-2 text-sm text-red-700" v-if="reasons||description">
                    <p class="space-y-1" v-if="description">
                        {{ description }}
                    </p>
                    <ul role="list" class="list-disc pl-5 space-y-1" v-if="reasons">
                        <li v-for="reason in reasons">
                            {{ reason }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import {computed} from "vue";

const props = defineProps(
    {
        message: {
            type: String,
            required: true,
        },
        description: {
            type: String,
            required: false,
        },
        reasons: {
            type: Array,
            required: false,
        },
        state: {
            type: String,
            required: false,
        }
    }
)
const stateClass = computed(() => {
    switch (props.state) {
        case 'success':
            return 'bg-green-50 border-green-500 text-green-700';
        case 'error':
            return 'bg-red-50 border-red-500 text-red-700';
        default:
            return 'bg-yellow-50 border-yellow-500 text-yellow-700';
    }
})
const stateIcon = computed(() => {
    switch (props.state) {
        case 'success':
            return 'CheckCircleIcon';
        case 'error':
            return 'XCircleIcon';
        default:
            return 'ExclamationIcon';
    }
})
const stateIconClass = computed(() => {
    switch (props.state) {
        case 'success':
            return 'text-green-400';
        case 'error':
            return 'text-red-400';
        default:
            return 'text-yellow-400';
    }
})
</script>
