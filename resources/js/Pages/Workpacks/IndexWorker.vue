<template>

    <Head :title="pageHeader"/>

    <AuthenticatedLayout>

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :title="pageHeader"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Work Packs</h1>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="jw-table min-w-full">
                                <thead class="bg-white">

                                <tr>
                                    <th scope="col status">
                                        Status
                                    </th>
                                    <th scope="col title">
                                        Title
                                    </th>
                                    <th scope="col progress text-center">
                                        Progress
                                    </th>
                                    <th scope="col team text-center">
                                        Team
                                    </th>
                                    <th scope="col created">
                                        Created
                                    </th>
                                    <th scope="col notes">
                                        Completed
                                    </th>
                                    <th scope="col actions">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>

                                </thead>

                                <tbody class="bg-white">
                                <WorkpackRows :workpacks="workpacks_mine" title="My Active Work Packs" />
                                <WorkpackRows :workpacks="workpacks_active" title="Active Work Packs" />
                                <WorkpackRows :workpacks="workpacks_available" title="Available Work Packs" />
                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

        </template>

    </AuthenticatedLayout>

</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Pagination from '@/Components/Pagination.vue';
import IconPen from '@/Jetworks/Icons/Pen.vue';
import {Panelhelper} from "@/Jetworks/Panelhelper";
import UsersInitialsGroup from '@/Jetworks/Users/InitialsGroup.vue';
import StatusByUser from "@/Jetworks/Workpacks/StatusByUser.vue";
import IconEye from "@/Jetworks/Icons/Eye.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import {Userhelper} from "@/Jetworks/Userhelper";
import WorkpackRows from "@/Jetworks/Workpacks/WorkpackRows.vue";

export default {
    components: {
        WorkpackRows,
        BreadcrumbItem,
        IconEye,
        AuthenticatedLayout,
        Pagination,
        IconPen,
        UsersInitialsGroup,
        StatusByUser
    },
    data() {
        return {}
    },
    props: {
        workpacks_mine: {
            type: Object,
            default: {},
        },
        workpacks_active: {
            type: Object,
            default: {},
        },
        workpacks_available: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Work Packs',
        }
    },
    computed: {
        Userhelper() {
            return Userhelper
        },
        Panelhelper() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
    },
}
</script>
