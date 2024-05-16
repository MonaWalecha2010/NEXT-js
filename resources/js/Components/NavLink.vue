<template>
    <Link :class="classes"
          :href="href"
          class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out">
        <slot/>
    </Link>
</template>

<script setup>
import {computed} from 'vue';


const props = defineProps(['href', 'active']);

const classes = computed(() =>
    props.active
        ? 'border-blue-400  text-gray-900 focus:border-blue-700'
        : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:text-gray-700 focus:border-gray-300'
);
</script>
