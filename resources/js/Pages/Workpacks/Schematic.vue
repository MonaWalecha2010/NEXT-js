<template>

    <Head :title="`Work Pack Schematic : ${schematic.name}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:header>
            <Link v-if="Userhelper.isAdmin()" :href="route('workpacks.edit', { 'workpack' : workpack.id })"
                  class="jw-btn jw-btn__blue">Edit Work
                Pack
            </Link>
        </template>

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('workpacks.index')" :title="pageHeader"/>
            <BreadcrumbItem :url="route('workpacks.show', { 'workpack' : workpack.id } )" :title="workpack.name"/>
            <BreadcrumbItem :title="schematic.name"/>
        </template>

        <template v-slot:default>

            <div class="workpack-schematic-header my-6">
                <h1 class="font-semibold text-2xl text-gray-800 mb-4">{{ schematic.name }}</h1>
                <AirframeAeroplaneDetails :airframe="airframe" :workpack="workpack"/>
            </div>

            <div class="p-6 mb-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="workpack-schematic">
                    <WorkpackSchematic @showWorkpackPanelTask="showPanelsWorkpackPanelTask"
                                       @showAddWorkpackPanelConfirmation="showAddWorkpackPanelConfirmation"
                                       :schematic="schematic"
                                       :schematic_panels="schematicCombinedPanels"/>
                    <WorkpackSchematicKey/>
                </div>
            </div>

            <div class="workpack-schematic-panels">

                <div class="workpack-schematic-panels-header flex w-full mb-6">
                    <div class="flex-auto">
                        <h2 class="text-black text-2xl font-semibold">Panels</h2>
                        <p>Below is a list of panels that have work required</p>
                    </div>
                    <div class="flex-none">
                    <span class="inline-block mr-6">
                        <PanelStats :stat="workpack.workpack_stats"/>
                    </span>
                        <Link v-if="!Panelhelper.isWorkpackClosed( this.workpack )"
                              :href="route('workpacks.edit.panels', { 'workpack' : this.workpack.id, 'schematic' : this.schematic })"
                              class="jw-btn jw-btn__blue mr-4">Add
                            Panel
                        </Link>

                        <div v-if="!Panelhelper.isWorkpackClosed( this.workpack ) && Userhelper.isWorker()"
                             class="relative inline-block text-left">
                            <div>
                                <button @click.prevent="toggleMassEdit"
                                        type="button"
                                        class="inline-flex w-full justify-center rounded-md border border-gray-300 px-4 py-2 text-sm font-medium jw-btn jw-bg__gray text-black"
                                        id="menu-button" aria-expanded="true" aria-haspopup="true">
                                    Select
                                    <svg class="-mr-1 ml-2 h-5 w-5" viewBox="0 0 20 20" fill="currentColor"
                                         aria-hidden="true">
                                        <path fill-rule="evenodd"
                                              d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                              clip-rule="evenodd"/>
                                    </svg>
                                </button>
                            </div>
                            <div v-if="this.mass_edit"
                                 class="absolute right-0 z-20 mt-2 w-56 origin-top-right divide-y divide-gray-100 text-center font-medium rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                 role="menu" aria-orientation="vertical" aria-labelledby="menu-button" tabindex="-1">
                                <div class="py-1" role="none">
                                    <a @click.prevent="setMassAction"
                                       class="text-gray-700 block px-4 py-2 text-sm"
                                       data-mass-action-id="1"
                                       role="menuitem"
                                       tabindex="-1">Start</a>
                                </div>
                                <div class="py-1" role="none">
                                    <a @click.prevent="setMassAction"
                                       class="text-gray-700 block px-4 py-2 text-sm"
                                       data-mass-action-id="2"
                                       role="menuitem"
                                       tabindex="-1">Complete</a>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div
                    class="workpack-schematic-panels-table bg-white border border-gray-200 rounded-md overflow-hidden shadow-sm sm:rounded-lg ">

                    <div class="workpack-panels">

                        <div class="table-container inline-block min-w-full align-middle">
                            <table class="jw-table min-w-full">
                                <thead class="rounded-t-lg">
                                <th v-show="form.mass_update_action_id" scope="col"
                                    class="select rounded-tl-lg sticky w-6">
                                    <span class="sr-only">Select</span></th>
                                <th class="name sticky">Panel</th>
                                <th class="step step__current sticky">Current Step</th>
                                <th class="step step__availability sticky">Availability</th>
                                <th class="notes sticky w-16 text-center">Notes</th>
                                <th class="team sticky text-center">Team</th>
                                <th class="actions sticky rounded-tr-lg w-6"><span class="sr-only">Action</span></th>
                                </thead>
                                <tbody>
                                <template v-if="schematic_panels_user.length > 0">
                                    <tr class="jw-bg__gray-lightest border-t-2 border-t-gray-200">
                                        <td colspan="7"><span class="-ml-2 font-bold jw-text__gray">My Panels</span>
                                        </td>
                                    </tr>
                                </template>
                                <template v-for="schematic_panel in schematic_panels_user" :key="schematic_panel.id">
                                    <tr @click="showWorkpackPanelTask( schematic_panel.id, $event )"
                                        class="cursor-pointer"
                                        :class="Panelhelper.getStepRowbackgroundClasses( schematic_panel, false )">
                                        <td v-show="form.mass_update_action_id" class="massaction select w-6">
                                            <input
                                                v-if="Userhelper.isWorkerAllowedToStartTask( Panelhelper, schematic_panel.workpack_panel_step_id ) && (form.mass_update_action_id == schematic_panel.workpack_panel_action_id )"
                                                v-model="form.mass_update_panels"
                                                :value="schematic_panel"
                                                :id="`panel-${schematic_panel.id}`"
                                                class="massaction workpack-schematic-panel_checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                type="checkbox">
                                        </td>
                                        <td class="name font-bold">{{ schematic_panel.name }}</td>
                                        <td class="step step__current">
                                            <span
                                                :class="`jw-schematic-pill__${Panelhelper.getStepColor(schematic_panel.workpack_panel_step_id )}`"
                                                class="jw-schematic-pill">
                                                {{ Panelhelper.getStepName(schematic_panel.workpack_panel_step_id) }}
                                            </span>
                                        </td>
                                        <td class="step step__availability">
                                    <span class="jw-schematic-pill"
                                          :class="`jw-schematic-pill__${Panelhelper.getStepColor(schematic_panel.workpack_panel_step_id )}`">
                                        {{ Panelhelper.getStepAvailabilityLabel(schematic_panel) }}
                                    </span>
                                        </td>
                                        <td class="notes w-16 text-center">
                                    <span v-if="schematic_panel.note_count > 0" class="panel-note-count icon__gray"><IconNotes/><span
                                        class="panel-note-count-number">{{ schematic_panel.note_count }}</span></span>
                                        </td>
                                        <td class="team text-center">
                                            <UserInitials :id="schematic_panel.user_id"
                                                          :name="schematic_panel.user_name"/>
                                        </td>
                                        <td class="actions w-6">
                                            <Link
                                                :href="route('workpackpanels.schematic', { 'workpack' : workpack.id, 'schematic' : schematic.id, 'workpackpanel' : schematic_panel.id } )"
                                                class="icon icon__pen inline-block w-4 h-4">
                                                <template v-if="Panelhelper.isWorkpackClosed( workpack )">
                                                    <IconEye/>
                                                </template>
                                                <template v-else>
                                                    <IconPen/>
                                                </template>
                                                <span class="sr-only">Show Panel History</span>
                                            </Link>
                                        </td>
                                    </tr>
                                </template>
                                <template v-if="schematic_panels.length > 0">
                                    <tr class="jw-bg__gray-lightest border-t-2 border-t-gray-200">
                                        <td colspan="7"><span class="-ml-2 font-bold jw-text__gray">Panels</span></td>
                                    </tr>
                                </template>
                                <template v-for="schematic_panel in schematic_panels" :key="schematic_panel.id">
                                    <tr @click="showWorkpackPanelTask( schematic_panel.id, $event )"
                                        class="cursor-pointer"
                                        :class="Panelhelper.getStepRowbackgroundClasses( schematic_panel )">
                                        <td v-show="form.mass_update_action_id" class="massaction select w-6">
                                            <input
                                                v-if="Userhelper.isWorkerAllowedToStartTask( Panelhelper, schematic_panel.workpack_panel_step_id ) && (form.mass_update_action_id == schematic_panel.workpack_panel_action_id )"
                                                v-model="form.mass_update_panels"
                                                :value="schematic_panel"
                                                :id="`panel-${schematic_panel.id}`"
                                                class="massaction workpack-schematic-panel_checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                type="checkbox">
                                        </td>
                                        <td class="name font-bold">{{ schematic_panel.name }}</td>
                                        <td class="step step__current">
                                            <span
                                                :class="`jw-schematic-pill__${Panelhelper.getStepColor(schematic_panel.workpack_panel_step_id )}`"
                                                class="jw-schematic-pill">
                                                {{ Panelhelper.getStepName(schematic_panel.workpack_panel_step_id) }}
                                            </span>
                                        </td>
                                        <td class="step step__availability">
                                    <span class="jw-schematic-pill"
                                          :class="`jw-schematic-pill__${Panelhelper.getStepColor(schematic_panel.workpack_panel_step_id )}`">
                                        {{ Panelhelper.getStepAvailabilityLabel(schematic_panel) }}
                                    </span>
                                        </td>
                                        <td class="notes w-16 text-center">
                                    <span v-if="schematic_panel.note_count > 0" class="panel-note-count icon__gray"><IconNotes/><span
                                        class="panel-note-count-number">{{ schematic_panel.note_count }}</span></span>
                                        </td>
                                        <td class="team text-center">
                                            <UserInitials :id="schematic_panel.user_id"
                                                          :name="schematic_panel.user_name"/>
                                        </td>
                                        <td class="actions w-6">
                                            <Link
                                                :href="route('workpackpanels.schematic', { 'workpack' : workpack.id, 'schematic' : schematic.id, 'workpackpanel' : schematic_panel.id } )"
                                                class="icon icon__pen inline-block w-4 h-4">
                                                <template v-if="Panelhelper.isWorkpackClosed( workpack )">
                                                    <IconEye/>
                                                </template>
                                                <template v-else>
                                                    <IconPen/>
                                                </template>
                                                <span class="sr-only">Show Panel History</span>
                                            </Link>
                                        </td>
                                    </tr>
                                </template>

                                </tbody>
                            </table>

                        </div>

                        <div v-show="form.mass_update_action_id"
                             class="workpack-schematic-panels-table-mass-actions flex justify-between items-center p-4 bg-gray-200">
                            <div class="flex">
                                <p>Select the panels you wish to
                                    <span v-if="form.mass_update_action_id == 1">Start</span>
                                    <span v-if="form.mass_update_action_id == 2">Complete</span>
                                </p>
                            </div>
                            <div class="flex-none">
                                <a @click.prevent="cancelMassUpdate"
                                   class="jw-btn jw-btn__gray-light mr-4">Cancel</a>
                                <a v-show="form.mass_update_panels.length > 0"
                                   @click.prevent="showActionCodeConfirmation"
                                   class="jw-btn jw-btn__blue">
                                    <span v-if="form.mass_update_action_id == 1">Start</span>
                                    <span v-if="form.mass_update_action_id == 2">Complete</span>
                                    <span>( {{ form.mass_update_panels.length }} )</span>
                                </a>
                            </div>
                        </div>

                    </div>

                </div>

            </div>

            <ActionCodeConfirmation v-if="this.show_code"
                :visible="this.show_code"
                @closeCodeConfirmation="listenForActionCodeConfirmation">
                <template v-slot:details>
                    <div class="flex flex-wrap gap-4 justify-center items-center">
                        <template v-for="workpack_panel in form.mass_update_panels" :key="workpack_panel.id">
                            <PanelTaskModalSummary :workpack_panel="workpack_panel"/>
                        </template>
                    </div>
                </template>
            </ActionCodeConfirmation>

            <ModalConfirmation v-if="this.show_confirmation"
                               @confirmModalConfirmation="listenForPanelAddConfirmation"
                               action="Add"
                               title="Add Panel to Work Pack">
                Are you sure you want to add panel <span v-if="this.panel_to_add"
                                                         class="font-bold text-black">{{
                    this.panel_to_add.name
                }}</span> to
                this Work Pack?
            </ModalConfirmation>

        </template>

    </AuthenticatedLayout>

</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import AirframeAeroplaneDetails from '@/Jetworks/Workpacks/AirframeAeroplaneDetails.vue';
import IconPen from "@/Jetworks/Icons/Pen.vue";
import {Panelhelper} from "@/Jetworks/Panelhelper";
import IconNotes from "@/Jetworks/Icons/Notes.vue";
import WorkpackSchematicKey from "@/Jetworks/Workpacks/SchematicKey.vue";
import WorkpackSchematic from "@/Jetworks/Workpacks/Schematic.vue";
import ActionCodeConfirmation from "@/Jetworks/Common/ActionCodeConfirmation.vue";
import PanelStats from "@/Jetworks/Workpacks/PanelStats.vue";
import {useForm, usePage} from "@inertiajs/inertia-vue3";
import {Inertia} from "@inertiajs/inertia";
import UserInitials from "@/Jetworks/Users/Initials.vue";
import {Userhelper} from "@/Jetworks/Userhelper";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import PanelTaskModalSummary from "@/Jetworks/WorkpackPanelTask/PanelTaskModalSummary.vue";
import IconEye from "@/Jetworks/Icons/Eye.vue";
import ModalConfirmation from "@/Jetworks/Common/ModalConfirmation.vue";

export default {
    components: {
        ModalConfirmation,
        IconEye,
        PanelTaskModalSummary,
        BreadcrumbItem,
        UserInitials,
        PanelStats,
        ActionCodeConfirmation,
        WorkpackSchematic,
        IconNotes,
        IconPen,
        WorkpackSchematicKey,
        AuthenticatedLayout,
        AirframeAeroplaneDetails
    },
    data() {
        return {
            quill: null,
            use_template: false,
            show_code: false,
            show_confirmation: false,
            panel_to_add: null,
            mass_edit: false,
            mass_update_action_id: null,
        }
    },
    computed: {
        Userhelper: function () {
            Userhelper.initialise(this.$page);
            return Userhelper
        },

        Panelhelper: function () {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },

        schematicPanelRowClasses: function (schematic_panel) {
            return Panelhelper.getStepRowbackgroundClasses(schematic_panel);
        },

        schematicCombinedPanels : function() {
            return this.schematic_panels.concat( this.schematic_panels_user );
        }

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
        aeroplane: {
            type: Object,
            default: {},
        },
        schematic_panels: {
            type: Object,
            default: {},
        },
        schematic_panels_user: {
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
        schematic: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Work Packs',
        }
    },
    setup(props) {
        const form = useForm({
            mass_update_action_id: null,
            mass_update_panels: [],
            user_code: '',
        });

        function submit() {
            form.post(route('workpackpaneltasks.multistore'), form);
        }

        return {
            form,
            submit,
        }
    },
    methods: {

        toggleMassEdit: function () {
            if (!Panelhelper.isWorkpackClosed(this.workpack)) {
                this.mass_edit = !this.mass_edit;
            }
        },

        setMassAction: function (event) {
            let action = event.target.dataset.massActionId;
            this.form.mass_update_action_id = action;
            this.form.mass_update_panels = [];
            this.mass_edit = null;
        },

        togglePanelCheckbox: function (workpack_panel, event) {
            let checkbox = event.target;
            checkbox.closest('tr').classList.toggle('bg-blue-100');
        },

        showActionCodeConfirmation: function () {
            this.show_code = true;
        },

        showAddWorkpackPanelConfirmation: function (data) {
            if( this.workpack.completed == false ){
                this.show_confirmation = true;
                this.panel_to_add = data.name;
            }
        },

        cancelMassUpdate: function () {
            this.mass_edit = false;
            this.mass_update_panels = [];
            this.form.reset();
        },

        listenForActionCodeConfirmation: function (data) {
            if (data != false) {
                this.form.user_code = data;
                this.form.post(route('workpackpaneltasks.storemultiple', {
                    'workpack': this.workpack,
                    'schematic': this.schematic
                }), {
                    onSuccess: () => {
                        this.form.reset('mass_update_panels', 'user_code', 'mass_update_action_id');
                    }
                });
            }
            this.show_code = false;
        },

        listenForPanelAddConfirmation: function (data) {
            if ( data == "Add" ) {
                this.form.get(route('workpackpanels.storebyName', {
                    'workpack': this.workpack,
                    'schematic': this.schematic,
                    'panel_name': this.panel_to_add
                }));
            }
            this.show_confirmation = false;
            this.panel_to_add = null;
        },

        showPanelsWorkpackPanelTask: function (data) {
            let schematic_panel = Panelhelper.getObjectByPropertyFromObjectArray(this.schematicCombinedPanels, data.panel_id, 'panel_id')
            //Now we have to find a task associated with this panel id...
            let taskRoute = route('workpackpanels.schematic', {
                'workpack': this.workpack.id,
                'schematic': this.schematic.id,
                'workpackpanel': schematic_panel.id
            });
            Inertia.visit(taskRoute, {method: 'get'});
        },

        showWorkpackPanelTask: function (workpackpanel_id, event) {
            let target = event.target;
            //We don't want to run this if someone is trying to click on the <input> massaction...
            if (!event.target.classList.contains('massaction') ) {
                let taskRoute = route('workpackpanels.schematic', {
                    'workpack': this.workpack.id,
                    'schematic': this.schematic.id,
                    'workpackpanel': workpackpanel_id
                });
                Inertia.visit(taskRoute, {method: 'get'});
            }
        }
    }

}
</script>

<style>
svg {
    width: 100%;
    height: auto;
}
</style>

