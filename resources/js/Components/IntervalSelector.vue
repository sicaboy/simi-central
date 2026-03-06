<template>
    <div class="flex justify-center">
        <span class="mr-3 text-sm font-medium text-gray-900">Pay
            <template v-if="$page.props.defaultInterval == 'monthly'">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium">
                    {{ __('Monthly') }}
                </span>
                <span
                    v-if="monthlyToggleDescription"
                    class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium bg-blue-100 text-blue-800">
                    {{ monthlyToggleDescription }}
                </span>
            </template>
            <template v-if="$page.props.defaultInterval == 'yearly'">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium">
                    {{ __('Yearly') }}
                </span>
                <span
                    v-if="yearlyToggleDescription"
                    class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium bg-blue-100 text-blue-800">
                    {{ yearlyToggleDescription }}
                </span>
            </template>
        </span>
    <!--    <toggle :checked="billAnnually" v-model:checked="billAnnually" />-->
        <button role="checkbox" tabindex="0" aria-checked="false" :class="$page.props.brandColor"
                class="bg-indigo-700 relative inline-flex flex-shrink-0 h-6 w-11 border-2 border-transparent rounded-full cursor-pointer transition-colors ease-in-out duration-200 focus:outline-none" @click="$emit('toggled')">
                <span aria-hidden="true"
                      :class="{ 'translate-x-0': showingDefaultIntervalPlans, 'translate-x-5': ! showingDefaultIntervalPlans }"
                      class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition ease-in-out duration-200">
                </span>
        </button>
        <span class="ml-3 text-sm font-medium text-gray-900">
            <template v-if="$page.props.defaultInterval == 'yearly'">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium">
                    {{ __('Monthly') }}
                </span>
                <span
                    v-if="monthlyToggleDescription"
                    class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium bg-blue-100 text-blue-800">
                    {{ monthlyToggleDescription }}
                </span>
            </template>
            <template v-if="$page.props.defaultInterval == 'monthly'">
                <span class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium">
                    {{ __('Yearly') }}
                </span>
                <span
                    v-if="yearlyToggleDescription"
                    class="inline-flex items-center px-2 py-0.5 rounded text-sm font-medium bg-blue-100 text-blue-800">
                    {{ yearlyToggleDescription }}
                </span>
            </template>
        </span>
        <!--  <span class="ml-0.5 text-sm text-gray-500">(Save 10%)</span>-->
    </div>
</template>

<script>
    export default {
        props: ['showingDefaultIntervalPlans', 'yearlyToggleDescription', 'monthlyToggleDescription'],
    }
</script>
