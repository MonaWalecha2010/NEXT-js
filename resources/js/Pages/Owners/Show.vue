<template>

    <Head :title="`${pageHeader} : ${owner.name}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Owner</h2>
        </template>

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('owners.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="owner.name"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">{{ owner.name }}</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link v-if="Userhelper.isAdmin()"
                          :data="{ owner_id: owner.id }" :href="route('owners.edit', { 'owner' : owner.id } )"
                          class="jw-btn jw-btn__blue-dark">Edit {{ owner.name }}
                    </Link>
                </div>
            </div>

            <div class="mt-8 flex flex-col">

                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">

                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">

                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">

                            <table v-if="owner.aeroplanes.length > 0" class="jw-table">
                                <thead>
                                <tr>
                                    <th>Name</th>
                                    <th scope="col" class="text-center">Status</th>
                                    <th scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr v-for="aeroplane in owner.aeroplanes" :key="aeroplane.id"
                                    class="align-text-top">
                                    <td class="">
                                        <Link :href="route('aeroplanes.show', aeroplane.id)">
                                            <span class="inline-block icon icon__plane w-4 h-4"><IconPlane/></span>
                                            <span class="airframe-name after-icon">{{
                                                    aeroplane.name
                                                }}</span>
                                        </Link>
                                    </td>
                                    <td class="text-center">
                                        <EntityIsActive :entity="aeroplane"/>
                                    </td>
                                    <td  class="text-right">
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

            </div>

        </template>

    </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import {Userhelper} from "@/Jetworks/Userhelper";
import IconPlane from "@/Jetworks/Icons/Aeroplane.vue";
import IconPen from "@/Jetworks/Icons/Pen.vue";
import EntityIsActive from "@/Jetworks/Common/EntityIsActive.vue";
import IconEye from "@/Jetworks/Icons/Eye.vue";

defineProps({
    can: {
        type: Object,
        default: {},
    },
    owner: {
        type: Object,
        default: {},
    },
    pageHeader: {
        type: String,
        default: 'Owners',
    }
});
</script>

