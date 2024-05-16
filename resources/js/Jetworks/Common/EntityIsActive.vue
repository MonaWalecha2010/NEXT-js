<template>
    <span :class="classes" class="entity-is-active">
        <span v-if="entity.active" class="jw-status jw-status__active jw-status__green">Active</span>
        <span v-else class="jw-status jw-status__inactive jw-status__yellow">Inactive</span>
    </span>
</template>

<script setup>
const props = defineProps({
    entity: {
        type: Object,
        default: null,
    },
    classes: {
        type: String,
        default: '',
    }
});
</script>
