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
                <div class="flex items-center mt-4 sm:mt-0 sm:ml-16 sm:flex-none">

<!--                    <span class="">Workpacks</span>-->

<!--                    <span class="group relative inline-flex h-5 w-10 flex-shrink-0 cursor-pointer items-center justify-center rounded-full focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 mx-4" role="switch" aria-checked="false">-->
<!--                        <span class="sr-only">Use setting</span>-->
<!--                        <span aria-hidden="true" class="pointer-events-none absolute h-full w-full rounded-md bg-white"></span>-->
<!--                        <Link :href="route('workpacks.index')" aria-hidden="true" class="jw-bg__blue pointer-events-none absolute mx-auto h-4 w-9 rounded-full transition-colors duration-200 ease-in-out"></Link>-->
<!--                        <Link :href="route('airframeworkpacks.index')" aria-hidden="true" class="translate-x-0 pointer-events-none absolute left-0 inline-block h-5 w-5 transform rounded-full border border-gray-200 bg-white shadow ring-0 transition-transform duration-200 ease-in-out"></Link>-->
<!--                    </span>-->

<!--                    <span class="">Templates</span>-->


                    <Link :href="route('workpacks.create')"
                          class="jw-btn jw-btn__blue ml-4">
                        Create a Work Pack
                    </Link>
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
                                <WorkpackRows :workpacks="workpacks_active" title="Active / Available" />
                                <WorkpackRows :workpacks="workpacks_inactive" title="Inactive" />
                                <WorkpackRows :workpacks="workpacks_completed" title="Completed" />
                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            </div>

            <temmplate v-if="workpacks_completed.meta">
                <Pagination :links="workpacks_completed.meta.links"/>
            </temmplate>

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
        workpacks_active: {
            type: Object,
            default: {},
        },
        workpacks_inactive: {
            type: Object,
            default: {},
        },
        workpacks_completed: {
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
