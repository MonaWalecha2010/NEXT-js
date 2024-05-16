<template>
    <li>
        <div class="flex items-center">
            <IconArrowLeft class="mx-3 text-gray"/>
            <a v-if="url"
               :href="url"
               class="px-3 py-1.5  rounded-md bg-gray-300 text-gray-500">
                <span>{{ title }}</span>
            </a>
            <span v-else
                class="py-1.5 text-gray-500 opacity-50">
                <span>{{ title }}</span>
            </span>
        </div>
    </li>
</template>
<script>
import IconArrowLeft from "@/Jetworks/Icons/ArrowLeft.vue";

export default {
    name: 'BreadcrumbItem',
    components: {IconArrowLeft},
    props: {
        url: {
            type: String,
            default: null,
        },
        title: {
            type: String,
            default: '',
        }
    },
}
</script>
