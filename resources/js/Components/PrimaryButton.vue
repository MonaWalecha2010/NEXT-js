<script setup>
defineProps({
    type: {
        type: String,
        default: 'submit',
    },
});
</script>

<template>
    <button
        :type="type"
        class="inline-flex items-center border border-transparent rounded-md text-center text-md font-semibold text-white transition ease-in-out duration-150"
    >
        <slot/>
    </button>
</template>
