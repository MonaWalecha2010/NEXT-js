<template>

    <Head :title="`${pageHeader} : ${airframeworkpack.name}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('airframeworkpacks.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="airframeworkpack.name"/>
        </template>

        <template v-slot:default>

            <DescriptionAndDocuments :model="airframeworkpack" :attachments="attachments"/>

            <div class="workpack-schematics" v-if="workpack_schematics">

                <h2 class="my-6 text-xl text-black font-semibold">Schematics</h2>

                <div class="flex">

                    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow"
                            v-for="workpack_schematic in workpack_schematics" :key="workpack_schematic.id">
                            <a :href="route('schematics.show', { 'schematic' : workpack_schematic.id })">
                                <div class="-mt-px divide-x divide-gray-900">
                                    <div class="p-5 flex justify-between border-b">
                                        <div>
                                            <h3 class="workpack-schematic-name text-black font-semibold mb-2">
                                                {{ workpack_schematic.name }}</h3>

                                            <div class="workpack-schematic-stats">
                                                <PanelStats :stat="getWorkpackSchematicStats( workpack_schematic )"/>
                                            </div>
                                        </div>

                                        <div
                                            class="workpack-schematic-edit-icon rounded-lg w-10 h-10 p-2.5 bg-gray-400 flex justify-center items-center">
                                            <span class="inline-block icon icon icon__white w-4 h-4 "><IconPen/></span>
                                        </div>

                                    </div>
                                </div>

                                <div class="flex w-full items-center justify-between space-x-6 p-5">
                                    <div class="workpack-schematic-image-wrapper flex-1 truncate">
                                        <img :src="workpack_schematic.image"
                                             :alt="`${workpack_schematic.name} illustration`"
                                             class="w-full workpack-workpack_schematic-image"
                                             loading="lazy">
                                    </div>
                                </div>

                            </a>

                        </li>

                        <li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow"
                            v-for="airframe_schematic in airframe_schematics" :key="airframe_schematic.id">

                            <a :href="route('schematics.show', { 'schematic' : airframe_schematic.id })">
                                <div class="-mt-px divide-x divide-gray-900">
                                    <div class="p-5 flex justify-between border-b">
                                        <div>
                                            <h3 class="workpack-schematic-name text-black font-semibold mb-2">
                                                {{ airframe_schematic.name }}</h3>

                                            <span class="jw-pill jw-pill__gray">
                                            No work required
                                        </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="flex w-full items-center justify-between space-x-6 p-5">
                                    <div class="workpack-schematic-image-wrapper flex-1 truncate">
                                        <img :src="airframe_schematic.image"
                                             :alt="`${airframe_schematic.name} illustration`"
                                             class="w-full workpack-workpack_schematic-image"
                                             loading="lazy">
                                    </div>
                                </div>

                            </a>

                        </li>

                    </ul>

                </div>

            </div>

        </template>

    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Panelhelper} from '@/Jetworks/Panelhelper.js';
import {Userhelper} from "@/Jetworks/Userhelper";
import UsersInitialsGroup from '@/Jetworks/Users/InitialsGroup.vue';
import AirframeAeroplaneDetails from '@/Jetworks/Workpacks/AirframeAeroplaneDetails.vue';
import IconPen from "@/Jetworks/Icons/Pen.vue";
import IconEye from "@/Jetworks/Icons/Eye.vue";
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";
import IconClipboard from "@/Jetworks/Icons/Clipboard.vue";
import PanelStats from "@/Jetworks/Workpacks/PanelStats.vue";
import DescriptionAndDocuments from "@/Jetworks/Common/DescriptionAndDocuments.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

export default {
    components: {
        BreadcrumbItem,
        DescriptionAndDocuments,
        IconClipboard,
        AuthenticatedLayout,
        UsersInitialsGroup,
        AirframeAeroplaneDetails,
        PanelStats,
        IconEye,
        IconPen,
        IconPaperclip,
    },
    data() {
        return {}
    },
    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper;
        },
        Panelhelper() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        airframeworkpack: {
            type: Object,
            default: {},
        },
        workpack_panels: {
            type: Object,
            default: {},
        },
        attachments: {
            type: Object,
            default: {},
        },
        airframe: {
            type: Object,
            default: {},
        },
        workpack_schematics: {
            type: Object,
            default: {},
        },
        airframe_schematics: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Work Pack Templatae',
        }
    },
    methods: {
        getWorkpackSchematicStats(schematic) {
            let value = null;
            const schematic_id = schematic.id;
            //const stats = this.workpack.workpack_schematic_stats;
            //return stats.find(stats => stats.id === schematic_id);
        },
        hasSchematicUsers(workpack_schematic) {
            return this.getWorkpackSchematicStats(workpack_schematic).users.length > 0;
        },
        getSchematicUsers(workpack_schematic) {
            return this.getWorkpackSchematicStats(workpack_schematic).users;
        },
    }
}
</script>

