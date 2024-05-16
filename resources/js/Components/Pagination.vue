<template>

    <div v-if="links.length > 3" class="py-12">
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, p) in links" :key="p">
                <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
                     v-html="link.label"/>
                <Link v-else
                      :class="{ 'bg-blue-700 text-white': link.active }"
                      :href="link.url" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-blue-500 focus:text-blue-500" v-html="link.label"/>
            </template>
        </div>
    </div>
</template>

<script setup>


defineProps({
    links: {
        type: Array,
        default: [],
    },
});
</script>
