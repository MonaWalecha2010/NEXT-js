<template>

    <Head title="Schematics"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :title="pageHeader"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">

                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">{{ pageHeader }}</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link :href="route('schematics.create')" class="jw-btn jw-btn__blue-dark">Add Schematic</Link>
                </div>

            </div>

            <div class="p-6 mt-8 flex flex-col bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <ul class="grid grid-cols-2 gap-x-4 gap-y-8 sm:grid-cols-3 sm:gap-x-6 lg:grid-cols-4 xl:gap-x-8"
                    role="list">
                    <li v-for="schematic in schematics.data" v-if="schematics.data.length > 0" class="relative">
                        <div
                            class="group aspect-w-10 aspect-h-7 block w-full overflow-hidden rounded-lg bg-gray-100 focus-within:ring-2 focus-within:ring-blue-500 focus-within:ring-offset-2 focus-within:ring-offset-gray-100">
                            <Link :href="route('schematics.show', { 'schematic' : schematic.id })">
                                <img :src="schematic.image"
                                     loading="lazy"
                                     alt=""
                                     class="pointer-events-none object-cover group-hover:opacity-75"/>
                            </Link>
                        </div>
                        <p class="mt-2 block text-md font-medium text-gray-900">{{ schematic.name }}</p>
                        <p class="mt-2 block text-sm font-medium text-gray-400">For:
                            <Link :href="route('airframes.show', { 'airframe' : schematic.airframe })">
                                <NameWithRevision :airframe="schematic.airframe" :classes="'ml-0'"/>
                            </Link>
                        </p>
                        <p class="mt-2 block text-sm font-sm text-gray-300"><a :href="schematic.image"
                                                                               target="_blank">View</a></p>
                    </li>
                </ul>

            </div>

            <Pagination :links="schematics.meta.links"/>

        </template>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue'
import NameWithRevision from '@/Jetworks/Airframe/NameWithRevision.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

defineProps({
    can: {
        type: Object,
        default: {},
    },
    schematics: {
        type: Object,
        default: {},
    },
    pageHeader: {
        type: String,
        default: 'Schematics',
    }
});
</script>
