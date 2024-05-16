<template>

    <Head title="Show Aeroplane Types"/>

    <AuthenticatedLayout>

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :title="pageHeader" />
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">{{ pageHeader }}</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link :href="route('airframes.create')" class="jw-btn jw-btn__blue-dark">Add Aeroplane Type</Link>
                </div>
            </div>

            <div class="mt-8 flex flex-col">

                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">

                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">

                            <table v-if="airframes.data.length > 0" class="jw-table">
                                <thead>
                                <tr>
                                    <th>Title</th>
                                    <th scope="col" class="text-center">Revision No.</th>
                                    <th scope="col" class="text-center">Aeroplanes</th>
                                    <th scope="col" class="text-center">Panels</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="airframe in airframes.data" :key="airframe.id"
                                    class="align-text-top">
                                    <td class="">
                                        <Link :href="route('airframes.show', airframe.id)">
                                            <span class="inline-block icon icon__plane w-4 h-4"><IconPlane/></span>
                                            <span class="airframe-name after-icon">{{
                                                    airframe.name
                                                }}</span>
                                        </Link>
                                    </td>
                                    <td class="text-center">{{ airframe.revision }}</td>
                                    <td class="text-center">
                                        {{ airframe.aeroplanes.length }}
                                    </td>
                                    <td class="text-center">{{ airframe.panels.length }}</td>
                                    <td class="text-center">
                                        <EntityIsActive :entity="airframe"/>
                                    </td>

                                    <td>
                                        <Link :href="route('airframes.edit', {'id' : airframe.id } )">
                                            <span class="inline-block icon icon__pen w-4 h-4"><IconPen/></span>
                                            <span class="sr-only">Edit</span>
                                        </Link>
                                    </td>
                                </tr>
                                </tbody>
                            </table>

                        </div>
                    </div>
                </div>

                <div class="mt-8 sm:flex sm:items-center">
                    <div class="sm:flex-auto">
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                        <Link :href="route('airframes.create')" class="jw-btn jw-btn__blue-dark">Add Aeroplane Type
                        </Link>
                    </div>
                </div>

            </div>

            <Pagination :links="airframes.meta.links"/>


        </template>

    </AuthenticatedLayout>

</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Pagination from '@/Components/Pagination.vue';
import EntityIsActive from '@/Jetworks/Common/EntityIsActive.vue';

import IconPlane from '@/Jetworks/Icons/Aeroplane.vue';
import IconPen from '@/Jetworks/Icons/Pen.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

defineProps({
    airframes: {
        type: Object,
        default: {},
    },
    pageHeader: {
        type: String,
        default: 'Aeroplane Types',
    }
});
</script>
