<template>

    <Head :title="pageHeader"/>

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
                    <Link :href="route('aeroplanes.create')" class="jw-btn jw-btn__blue-dark">Add Aeroplane</Link>
                </div>
            </div>

            <div class="mt-8 flex flex-col">

                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">

                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">

                            <table v-if="aeroplanes.data.length > 0" class="jw-table">
                                <thead>
                                <tr>
                                    <th class=" first" scope="col title">Title</th>
                                    <th scope="col type">Type</th>
                                    <th scope="col workpacks">Assigned Work Packs</th>
                                    <th scope="col status">Status</th>
                                    <th class=" -hidden" scope="col actions">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="aeroplane in aeroplanes.data" :key="aeroplane.id">
                                    <td class="title whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                        <Link :href="route('aeroplanes.show', aeroplane.id)">
                                            <span class="inline-block icon icon__tail w-4 h-4"><IconTail/></span>
                                            <span class="airplane-name after-icon">{{ aeroplane.name }}</span>
                                        </Link>
                                    </td>
                                    <td class="type">
                                        <span class="inline-block icon icon__plane w-4 h-4"><IconPlane/></span>
                                        <NameWithRevision :airframe="aeroplane.airframe"/>
                                    </td>
                                    <td class="workpacks text-center">{{ aeroplane.workpacks.length }}</td>
                                    <td class="status">
                                        <EntityIsActive :entity="aeroplane"/>
                                    </td>
                                    <td class="actions">
                                        <Link :href="route('aeroplanes.edit', {'id' : aeroplane.id } )">
                                            <span class="inline-block icon icon__pen w-4 h-4"><IconPen/></span>
                                            <span class="sr-only">Edit</span>
                                        </Link>
                                        <Link :href="route('aeroplanes.show', {'id' : aeroplane.id } )">
                                            <span class="inline-block icon icon__eye w-4 h-4 ml-4"><IconEye/></span>
                                            <span class="sr-only">View</span>
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
                        <Link :href="route('aeroplanes.create')" class="jw-btn jw-btn__blue-dark">Add Aeroplane</Link>
                    </div>
                </div>

            </div>

            <Pagination :links="aeroplanes.meta.links"/>

        </template>

    </AuthenticatedLayout>

</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Pagination from '@/Components/Pagination.vue';
import NameWithRevision from '@/Jetworks/Airframe/NameWithRevision.vue';
import EntityIsActive from '@/Jetworks/Common/EntityIsActive.vue';
import IconPlane from "@/Jetworks/Icons/Aeroplane.vue";
import IconTail from "@/Jetworks/Icons/Tail.vue";
import IconPen from "@/Jetworks/Icons/Pen.vue";
import {Panelhelper} from "@/Jetworks/Panelhelper";
import IconEye from "@/Jetworks/Icons/Eye.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

export default {
    components: {
        BreadcrumbItem,
        IconEye,
        AuthenticatedLayout,
        Pagination,
        NameWithRevision,
        EntityIsActive,
        IconPlane,
        IconTail,
        IconPen
    },
    data() {
        return {}
    },
    props: {
        aeroplanes: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Aeroplanes',
        }
    },
    computed: {
        Panelhelper() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
    },
}
</script>
