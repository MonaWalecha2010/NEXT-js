<script setup>
import {onMounted, ref} from 'vue';

defineProps(['modelValue']);

defineEmits(['update:modelValue']);

const input = ref(null);

onMounted(() => {
    if (input.value.hasAttribute('autofocus')) {
        input.value.focus();
    }
});

defineExpose({focus: () => input.value.focus()});
</script>

<template>
    <input
        ref="input"
        :value="modelValue"
        class="block w-full text-sm placeholder-gray-400 text-gray-600 border-transparent outline-0 focus:border-gray-300 focus:ring-gray-300"
        @input="$emit('update:modelValue', $event.target.value)"
    />
</template>
