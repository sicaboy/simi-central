<template>
  <svg width="396" height="163.1587982832618" viewBox="0 0 233 96" class="css-1j8o68f">
    <defs id="SvgjsDefs2735"></defs>
    <g
        transform="matrix(1,0,0,1,-12,0)">
      <g xmlns="http://www.w3.org/2000/svg">
        <path
            class="logo" :class="theme"
            d="M24,90h-3c-4.962,0-9-4.037-9-9V14c0-4.963,4.038-9,9-9h3v2h-3c-3.86,0-7,3.141-7,7v67c0,3.859,3.14,7,7,7   h3V90z"></path>
      </g>
      <g xmlns="http://www.w3.org/2000/svg">
        <path
            class="logo" :class="theme"
            d="M37.107,96H37c-4.962,0-9-4.037-9-9V9c0-4.963,4.038-9,9-9l0.211,0.022l30.69,6.636   C72.015,7.547,75,11.247,75,15.455v65.09c0,4.208-2.985,7.908-7.098,8.797L37.107,96z M36.896,2.001C33.084,2.057,30,5.175,30,9v78   c0,3.825,3.084,6.943,6.896,6.999l30.583-6.612C70.678,86.695,73,83.818,73,80.545v-65.09c0-3.273-2.322-6.15-5.521-6.842   L36.896,2.001z"></path>
      </g>
      <g xmlns="http://www.w3.org/2000/svg">
        <path
            class="logo" :class="theme"
            d="M38.625,52.75c-2.033,0-3.625-2.142-3.625-4.875S36.592,43,38.625,43s3.625,2.142,3.625,4.875   S40.658,52.75,38.625,52.75z M38.625,45C37.856,45,37,46.181,37,47.875s0.856,2.875,1.625,2.875s1.625-1.181,1.625-2.875   S39.394,45,38.625,45z"></path>
      </g>
    </g>
    <g
        transform="matrix(2.391581586106328,0,0,2.391581586106328,78.98214220548859,-11.820152688850623)"
    >
      <path
          class="text" :class="theme"
          d="M4.12 15.920000000000002 l0 4.8 c0 0.24 0.2 0.52 0.4 0.56 l18 5.96 c0.6 0.2 1.12 0.88 1.12 1.52 l0 4.96 c0 3.48 -2.84 6.28 -6.28 6.28 l-15.68 0 l0 -1.28 c0 -0.24 0.2 -0.44 0.44 -0.44 l15.24 0 c2.32 0 4.2 -1.88 4.2 -4.2 l0 -4.84 c0 -0.24 -0.2 -0.48 -0.44 -0.56 l-18 -5.96 c-0.6 -0.2 -1.08 -0.88 -1.08 -1.52 l0 -4.92 c0 -3.48 2.8 -6.28 6.28 -6.28 l14.44 0 l0 1.28 c0 0.24 -0.2 0.44 -0.44 0.44 l-14 0 c-2.32 0 -4.2 1.88 -4.2 4.2 z M27.919999999999998 33.64 l0 -23.24 c0 -0.24 0.2 -0.44 0.44 -0.44 l1.64 0 l0 24.04 c0 2.36 1.92 4.28 4.24 4.28 l12.44 0 c0.24 0 0.44 0.2 0.44 0.44 l0 1.28 l-12.88 0 c-3.48 0 -6.32 -2.84 -6.32 -6.36 z M48.88 39.56 l0 -1.28 l9.28 0 c2.32 0 4.16 -1.88 4.16 -4.16 l0 -22.4 l-5.12 0 c-0.24 0 -0.44 -0.2 -0.44 -0.44 l0 -1.28 l7.64 0 l0 23.76 c0 3.44 -2.8 6.24 -6.24 6.24 l-8.84 0 c-0.24 0 -0.44 -0.2 -0.44 -0.44 z"></path>
    </g>
  </svg>
</template>

<style lang="scss" scoped>
path {
  &.logo {
    &.primary {
      fill: #fa450b;
      @media (prefers-color-scheme: dark) {
        fill: #EA580C;
      }
    }
  }
  &.text {
    &.primary {
      fill: #36476f;
      @media (prefers-color-scheme: dark) {
        fill: #bcbcbc;
      }
    }
  }
}
</style>

<script setup>
/*
import {computed} from "vue";
*/
const props = defineProps({
  theme: {
    type: String,
    default: 'primary'
  }
})
/*
const color = computed(() => {
    switch (props.theme) {
        case 'primary':
            return '#4f46e5'
        case 'primary-dark':
            return '#4338ca'
        case 'secondary':
            return '#f97316'
        case 'secondary-dark':
            return '#f97316'
        default:
            return '#4f46e5'
    }
})*/
</script>
