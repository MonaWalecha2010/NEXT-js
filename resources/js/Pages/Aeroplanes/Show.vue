<template>

    <Head title="Aeroplane"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('aeroplanes.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="aeroplane.name"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">{{ aeroplane.name }} ({{
                            aeroplane.airframe.name
                        }})</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <span
                        class="jw-pill jw-pill__green mr-5">{{ Panelhelper.getBooleanActiveLabel(aeroplane.active) }}</span>
                    <Link :href="route('aeroplanes.edit', { 'aeroplane' : aeroplane.id})"
                          class="jw-btn jw-btn__blue-dark">Edit
                    </Link>
                </div>
            </div>

            <div class="my-8 flex flex-col p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="aeroplane-details text-gray-400 flex">

                <span class="aeroplane__tailnumber flex items-center mr-7">
                    <span class="inline-block w-4 h-4 icon icon__grey-dark">
                        <IconTail/>
                    </span>
                    <span class="airplane-name after-icon font-semi-bold">{{ aeroplane.name }}</span>
                </span>

                    <span class="aeroplane__airframe flex items-center mr-7">
                    <span class="inline-block w-4 h-4">
                        <IconPlane/>
                    </span>
                    <span class="after-icon">{{ aeroplane.airframe.name }}</span>
                </span>

                    <span v-if="aeroplane.airframe.revision" class="aeroplane__revision inline-block">
                    <span class="font-semibold mr-2">Revision Number:</span>
                    <span>{{ aeroplane.airframe.revision }}</span>
                </span>

                </div>

            </div>

            <div class="my-8 sm:flex sm:items-center">
                <div class="sm:flex-auto text-gray-400">
                <span class="aeroplane-workpacks aeroplane-workpacks__current mr-5">
                    <span class="jw-dot__text bg-gray-400 text-white mr-1.5">{{ activeWorkPacks.length }}</span>
                    <span>Assigned Work Packs</span>
                </span>
                    <span class="aeroplane-workpacks aeroplane-workpacks__total">
                    <span class="jw-dot__text bg-gray-400 text-white mr-1.5">{{ aeroplane.workpacks.length }}</span>
                    <span>Total Work Packs</span>
                </span>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link :href="route('workpacks.create', { 'aeroplane_id' : aeroplane.id })"
                          class="jw-btn jw-btn__blue-dark">Create Workpack
                    </Link>
                </div>
            </div>

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="airframe-name">
                    <span class="label">Aeroplane Type</span><span class="value">{{ aeroplane.airframe.name }}</span>
                </div>

                <div class="airframe-revision">
                    <span class="label">Revision</span><span class="value">{{ aeroplane.airframe.revision }}</span>
                </div>

                <div class="aeroplane-description">
                    <span class="label">Description</span>
                    <span class="value" v-html="aeroplane.description"/>
                </div>

                <div class="aeroplane-owner">
                    <span class="label">Owner</span><span class="value">{{ aeroplane.owner.name }}</span><br>
                    <span class="label">Phone</span><span class="value">{{ aeroplane.owner.phone }}</span><br>
                    <span class="label">Email</span><span class="value">{{ aeroplane.owner.email }}</span>
                </div>

                <div class="aeroplane-maintenance-history">
                    <h2>Maintenance history</h2>
                    <p>Plane's maintenance history should go in here...</p>
                </div>

            </div>

        </template>

    </AuthenticatedLayout>
</template>

<script>
import {Panelhelper} from '@/Jetworks/Panelhelper.js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import IconTail from "@/Jetworks/Icons/Tail.vue";
import IconPlane from "@/Jetworks/Icons/Aeroplane.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

export default {
    components: {
        BreadcrumbItem,
        AuthenticatedLayout,
        IconTail,
        IconPlane,
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        aeroplane: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Aeroplanes',
        }
    },
    computed: {
        Userhelper: function () {
            Userhelper.initialise(this.$page);
            return Userhelper;
        },

        Panelhelper: function () {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },

        activeWorkPacks: function () {
            let activeWorkpacks = this.aeroplane.workpacks.filter(workpack => {
                return (!workpack.completed && workpack.active) ? true : false;
            });
            return activeWorkpacks;
        }
    },
}
</script>

<style>
svg {
    width: 100%;
    height: auto;
}
</style>

