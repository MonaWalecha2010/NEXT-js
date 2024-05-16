<template>

    <Head :title="`Show ${airframe.fullname} `"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('airframes.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="airframe.fullname"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">{{ airframe.name }} ( {{ airframe.revision }} )</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link v-if="Userhelper.isAdmin()"
                          :data="{ airframe_id: airframe.id }" :href="route('airframes.edit', { 'airframe' : airframe.id } )"
                          class="jw-btn jw-btn__blue-dark">Edit {{ airframe.name }}
                    </Link>
                </div>
            </div>

            <div class="p-6 my-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <DescriptionAndDocuments :attachments="attachments" :model="airframe"/>
            </div>

            <div class="mt-8 sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h2 class="text-2xl font-semibold text-gray-900">Aeroplanes of this type in fleet</h2>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link :href="route('aeroplanes.create')" class="jw-btn jw-btn__blue-dark">Add Aeroplane of this Type</Link>
                </div>
            </div>

            <div class="p-6 my-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="airframe-aeroplanes">

                    <div v-if="airframe.aeroplanes.length > 0" class="block my-6 border rounded-md border-gray-200">
                        <table class="jw-table">
                            <thead>
                            <tr>
                                <th>Aeroplane Name</th>
                                <th>Created</th>
                                <th>Assigned Workpacks</th>
                                <th scope="col">
                                    <span class="sr-only">Edit</span>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            <tr v-for="aeroplane in airframe.aeroplanes" :key="aeroplane.id">
                                <td class="title whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-6">
                                    <a :href="route('aeroplanes.show', {'id' : aeroplane.id } )">
                                        <span class="inline-block icon icon__tail w-4 h-4"><IconTail/></span>
                                        <span class="airplane-name after-icon">{{ aeroplane.name }}</span>
                                    </a>
                                </td>
                                <td>{{ aeroplane.date }}</td>
                                <td>{{ aeroplane.workpacks.length }}</td>
                                <td>
                                    <Link :href="route('aeroplanes.edit', {'id' : aeroplane.id } )">
                                        <span class="inline-block icon icon__pen w-4 h-4"><IconPen/></span>
                                        <span class="sr-only">Edit</span>
                                    </Link>
                                </td>
                            </tr>
                            </tbody>

                        </table>
                    </div>

                    <div v-else class="block mt-6">
                        <p class="mb-4">No aeroplanes of this type are currently in your fleet.</p>
                    </div>
                </div>

            </div>

            <div class="mt-8 sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h2 class="text-2xl font-semibold text-gray-900">Schematics</h2>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link :href="route('schematics.create', { 'airframe_id' : airframe.id })" class="mt-4 jw-btn jw-btn__blue-dark">Add Schematic</Link>
                </div>
            </div>

            <div class="airframe-schematics p-6 my-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div v-if="airframe.schematics.length > 0"
                     class="airframe-schematic-grid width-full grid grid-cols-3 gap-3 mb-6">
                    <div v-for="schematic in airframe.schematics" :key="schematic.id">
                        <Link :href="route('schematics.show', schematic.id )" class="">
                            <div v-if="schematic.svg" v-html="schematic.svg"/>
                            <img v-else :src="schematic.image" alt="" loading="lazy"/>
                            <h3>{{ schematic.name }}</h3>
                        </Link>
                    </div>
                </div>

                <div v-else>
                    <p>No schematics have been added for this Aeroplane Type</p>
                </div>

            </div>

        </template>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import DescriptionAndDocuments from "@/Jetworks/Common/DescriptionAndDocuments.vue";
import Schematic from "@/Pages/Workpacks/Schematic.vue";
import IconTail from "@/Jetworks/Icons/Tail.vue";
import IconPen from "@/Jetworks/Icons/Pen.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import {Userhelper} from "@/Jetworks/Userhelper";

defineProps({
    can: {
        type: Object,
        default: {},
    },
    airframe: {
        type: Object,
        default: {},
    },
    attachments: {
        type: Object,
        default: {},
    },
    pageHeader: {
        type: String,
        default: 'Aeroplane Type',
    }
});
</script>

<style>
svg {
    width: 100%;
    height: auto;
    overflow: hidden;
}
</style>

