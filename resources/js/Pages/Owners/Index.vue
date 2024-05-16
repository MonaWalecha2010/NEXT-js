<template>

    <Head title="Owners"/>

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
                    <Link :href="route('owners.create')" class="jw-btn jw-btn__blue-dark">Add Owner</Link>
                </div>
            </div>

            <div class="mt-8 flex flex-col">

                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">

                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">

                            <table v-if="owners.data.length > 0" class="jw-table">
                                <thead>
                                <tr>
                                    <th class="first">Name</th>
                                    <th scope="col">Email</th>
                                    <th scope="col">Phone</th>
                                    <th scope="col">Aeroplanes</th>
                                    <th scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="owner in owners.data" :key="owner.id" class="align-text-top">
                                    <td>
                                                    <span class="owner-name after-icon">
                                                        <Link :href="route('owners.show', owner.id)">{{
                                                                owner.name
                                                            }}</Link>
                                                        </span>
                                    </td>
                                    <td>{{ owner.email }}</td>
                                    <td>{{ owner.phone }}</td>
                                    <td>
                                            <span v-for="aeroplane in owner.aeroplanes" v-if="owner.aeroplanes.length > 0"
                                                  class="aeroplane">
                                                <Link :href="route('aeroplanes.show', aeroplane.id)">{{
                                                        aeroplane.name
                                                    }}</Link>
                                                <br>
                                            </span>
                                    </td>
                                    <td>
                                        <EntityIsActive :entity="owner"/>
                                    </td>

                                    <td>
                                        <Link :href="route('owners.edit', {'id' : owner.id } )">
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
                        <Link :href="route('owners.create')" class="jw-btn jw-btn__blue-dark">Add Owner</Link>
                    </div>
                </div>

            </div>

            <Pagination :links="owners.meta.links"/>

        </template>

    </AuthenticatedLayout>

</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Pagination from '@/Components/Pagination.vue';
import EntityIsActive from '@/Jetworks/Common/EntityIsActive.vue';

import IconPlane from '@/Jetworks/Icons/Aeroplane.vue';
import IconTail from '@/Jetworks/Icons/Tail.vue';
import IconPen from '@/Jetworks/Icons/Pen.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

defineProps({
    owners: {
        type: Object,
        default: {},
    },
    pageHeader: {
        type: String,
        default: 'Owners',
    }
});
</script>
