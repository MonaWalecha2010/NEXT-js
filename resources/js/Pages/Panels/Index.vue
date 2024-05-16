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
                    <Link :href="route('panels.create')" class="jw-btn jw-btn__blue-dark">Add Panel</Link>
                </div>
            </div>

            <div class="mt-8 flex flex-col">

                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">

                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">

                            <table v-if="panels.data.length > 0" class="jw-table">
                                <thead>
                                <tr>
                                    <th class="first">Panel Name</th>
                                    <th scope="col">Aeroplane Type</th>
                                    <th scope="col">Description</th>
                                    <th scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="panel in panels.data" :key="panel.id" class="align-text-top">
                                    <td>
                                        <span class="panel-name after-icon">{{ panel.name }}</span>
                                    </td>
                                    <td>
                                        <NameWithRevision :airframe="panel.airframe"/>
                                    </td>
                                    <td>{{ panel.description }}</td>

                                    <td>
                                        <Link :href="route('panels.edit', {'id' : panel.id } )">
                                                <span class="icon icon__pen w-8 h-8">
                                                    <span class="inline-block icon icon__pen w-4 h-4"><IconPen/></span>
                                                </span>
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
                        <Link :href="route('panels.create')" class="jw-btn jw-btn__blue-dark">Add Panel</Link>
                    </div>
                </div>

            </div>

            <Pagination :links="panels.meta.links"/>


        </template>

    </AuthenticatedLayout>

</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import NameWithRevision from '@/Jetworks/Airframe/NameWithRevision.vue';
import IconPen from '@/Jetworks/Icons/Pen.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";


defineProps({
    panels: {
        type: Object,
        default: {},
    },
    pageHeader: {
        type: String,
        default: 'Panels',
    }
});
</script>
