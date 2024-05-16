<template>
    <span :class="classes" class="airframe-name after-icon">{{ airframe.name }}</span>
    <span v-if="airframe.revision" class="airframe-revision text-xs text-red-600 pl-1">(Rev: {{ airframe.revision }})</span>
</template>

<script setup>
const props = defineProps({
    airframe: {
        type: Object,
        default: null,
    },
    classes: {
        type: String,
        default: 'ml-2',
    }
});
</script>
