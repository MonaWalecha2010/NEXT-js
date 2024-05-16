<template>

    <Head :title="`${pageHeader} : ${workpack.name}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('workpacks.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="workpack.name"/>
        </template>

        <template v-slot:header>
            <Link v-if="!Panelhelper.isWorkpackClosed( this.workpack )"
                  :href="route('workpacks.edit.panels', { 'workpack' : this.workpack.id })"
                  class="jw-btn jw-btn__blue mr-4">Add Panel
            </Link>
        </template>

        <template v-slot:default>

            <div class="workpack-details mb-6">

                <div class="flex w-full justify-between justify-content-center mb-4">
                    <h1 class="font-semibold text-2xl text-gray-800">{{ workpack.name }}</h1>
                    <div class="workpack-stats flex flex-col justify-between">
                    <span class="workpack-progress">
                        <span class="workpack-progress-panels">
                            <PanelStats :stat="workpack.workpack_stats"/>
                        </span>

                        <span class="workpack-progress-step">
                            <span v-if="workpack.workpack_stats.completed"
                                  class="workpack-status workpack-status__completed bg-red-400 text-white border rounded-full px-4 py-1 ml-6 rounded-full">Completed</span>
                            <span v-else
                                  class="workpack-status workpack-status__inprogress jw-text__blue border jw-border__blue px-4 py-1 ml-6 rounded-full">In Progress</span>
                            <span class="icon icon__clipboard icon__blue inline-block w-5 h-5 ml-2 translate-y-1"><IconClipboard/></span>
                        </span>

                    </span>
                    </div>
                </div>

                <AirframeAeroplaneDetails :airframe="airframe" :workpack="workpack"/>

            </div>

            <DescriptionAndDocuments :model="workpack" :attachments="attachments"/>


            <template v-if="Panelhelper.isWorkpackClosed( workpack )">
                <div class="p-6 mt-4 mb-8 bg-white drop-shadow overflow-hidden drop-shadow sm:rounded-lg text-center">
                    <a :href="route('workpacks.report.pdf', { 'workpack' : workpack.id })" target="_blank"
                       class="jw-btn jw-btn__blue py-3 px-8 text-base">Download Report (PDF)</a>
                </div>
            </template>
            <template v-else-if="Panelhelper.isWorkpackCompleted( workpack )">
                <div class="p-6 mt-4 mb-8 bg-white drop-shadow overflow-hidden drop-shadow sm:rounded-lg text-center">
                    <template v-if="Userhelper.isSeniorEngineer()">
                        <a @click.prevent="showActionCodeConfirmation" class="jw-btn jw-btn__blue py-3 px-8 text-base">Sign
                            Off Work Pack</a>
                    </template>
                    <template v-else>
                        <p class="text-black">All tasks on this workpack have been completed. Please ask a <span
                            class="font-semibold">Senior Engineer</span>
                            to visit this page to sign it off.</p>
                    </template>
                </div>
            </template>

            <div class="workpack-schematics" v-if="workpack_schematics">

                <h2 class="my-6 text-xl text-black font-semibold">Schematics</h2>

                <div class="flex">

                    <ul role="list" class="w-full grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
                        <li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow"
                            v-for="workpack_schematic in workpack_schematics" :key="workpack_schematic.id">
                            <a :href="route('workpacks.schematic', { 'workpack' : workpack.id ,'schematic' : workpack_schematic.id })">
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
                                            <span class="inline-block icon icon icon__white w-4 h-4 ">
                                                <template v-if="Panelhelper.isWorkpackClosed( workpack )"><IconEye/></template>
                                                <template v-else><IconPen/></template>
                                            </span>
                                        </div>

                                    </div>
                                </div>

                                <div class="flex w-full items-center justify-between space-x-6 p-5">
                                    <div class="workpack-schematic-image-wrapper flex-1 truncate">
                                        <img :src="workpack_schematic.image"
                                             :alt="`${workpack_schematic.name} illustration`"
                                             class="w-full workpack-workpack_schematic-image"
                                             loading="lazy">
                                        <div class="workpack-schematic-workers">
                                            <UsersInitialsGroup v-if="hasSchematicUsers( workpack_schematic )"
                                                                :users="getSchematicUsers( workpack_schematic )"
                                                                :textposition="'left'"/>
                                        </div>

                                    </div>
                                </div>

                            </a>

                        </li>

                        <li class="col-span-1 divide-y divide-gray-200 rounded-lg bg-white shadow"
                            v-for="airframe_schematic in airframe_schematics" :key="airframe_schematic.id">

                            <a :href="route('workpacks.schematic', { 'workpack' : workpack.id ,'schematic' : airframe_schematic.id })">
                                <div class="-mt-px divide-x divide-gray-900">
                                    <div class="p-5 flex justify-between border-b">
                                        <div>
                                            <h3 class="workpack-schematic-name text-black font-semibold mb-2">
                                                {{ airframe_schematic.name }}</h3>

                                            <span class="jw-pill jw-pill__gray">
                                            No work required
                                        </span>
                                        </div>

                                        <div
                                            class="workpack-schematic-edit-icon rounded-lg w-10 h-10 p-2.5 bg-gray-400 flex justify-center items-center">
                                            <span class="inline-block icon icon icon__white w-4 h-4 ">
                                                <template v-if="Panelhelper.isWorkpackClosed( workpack )"><IconEye/></template>
                                                <template v-else><IconPen/></template>
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

            <ActionCodeConfirmation :visible="show_code" @closeCodeConfirmation="listenForActionCodeConfirmation">
                <template v-slot:details>
                    <div class="w-full">
                        Sign Work Pack <strong class="font-bold text-black">{{ workpack.name }}</strong> as completed.
                    </div>
                </template>
            </ActionCodeConfirmation>

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
import ActionCodeConfirmation from "@/Jetworks/Common/ActionCodeConfirmation.vue";
import {Inertia} from "@inertiajs/inertia";

export default {
    components: {
        ActionCodeConfirmation,
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
        return {
            show_code: false,
        }
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
        workpack: {
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
            default: 'Work Packs',
        }
    },
    methods: {
        getWorkpackSchematicStats: function (schematic) {
            const schematic_id = schematic.id;
            const stats = this.workpack.workpack_schematic_stats;
            return stats.find(stats => stats.id === schematic_id);
        },
        hasSchematicUsers: function (workpack_schematic) {
            let stats = this.getWorkpackSchematicStats(workpack_schematic);
            if (stats) {
                stats.users.length > 0;
            }
            return false;
        },
        getSchematicUsers: function (workpack_schematic) {
            let stats = this.getWorkpackSchematicStats(workpack_schematic);
            if (stats) {
                stats.users;
            }
            return [];
        },

        showActionCodeConfirmation: function () {
            this.show_code = true;
        },

        listenForActionCodeConfirmation: function (data) {
            console.log('data', data);
            if (data != false) {
                Inertia.post(route('workpacks.complete', {
                    'workpack': this.workpack.id
                }), {user_code: data});
            }
            this.show_code = false;
        }
    }
}
</script>

