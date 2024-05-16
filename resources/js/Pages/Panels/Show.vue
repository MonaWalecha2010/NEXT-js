<template>

    <Head :title="`${pageHeader} : ${panel.name}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Panel</h2>
        </template>

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('panels.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="panel.name"/>
        </template>

        <template v-slot:default>

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                {{ panel.name }}
                We should show the schematics this panel can be found on
            </div>

        </template>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

defineProps({
    can: {
        type: Object,
        default: {},
    },
    panel: {
        type: Object,
        default: {},
    },
    pageHeader: {
        type: String,
        default: 'Panels',
    }
});
</script>

<style>
svg {
    width: 100%;
    height: auto;
}
</style>

