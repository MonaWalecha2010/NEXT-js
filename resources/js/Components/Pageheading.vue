<template>

    <header v-if="name" className="bg-white shadow">
        <div className="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ name }}</h2>
        </div>
    </header>

</template>

<script>
export default {
    name: "Pageheading"
}
</script>

<script setup>
defineProps({
    name: {
        type: String,
        default: null,
    }
});
</script>
