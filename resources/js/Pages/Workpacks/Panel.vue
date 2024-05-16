<template>

    <Head :title="`Work Pack Panel : ${workpack_panel.panel.name}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('workpacks.index')" :title="pageHeader"/>
            <BreadcrumbItem :url="route('workpacks.show', { 'workpack' : workpack.id } )" :title="workpack.name"/>
            <BreadcrumbItem
                :url="route('workpacks.schematic', { 'workpack' : workpack.id, 'schematic' : schematic.id } )"
                :title="schematic.name"/>
            <BreadcrumbItem :title="workpack_panel.panel.name"/>
        </template>

        <template v-slot:default>

            <h2 class="block text-3xl mb-4 text-black font-semibold">{{ workpack_panel.panel.name }}</h2>

            <div class="workpack-aeroplane flex align-content-centered mb-4">
            <span class="workpack-number mr-4">
                <span class="inline-block label font-semibold mr-1">WPN:</span>
                <span class="inline-block value">{{ workpack.aeroplane.name }}</span>
            </span>
                <span class="workpack-aeroplane__tailnumber mr-4">
                <span class="inline-block icon icon__gray h-4 w-4 mr-1"><IconTail/></span>
                <span class="inline-block value">{{ workpack.aeroplane.name }}</span>
            </span>
                <span class="workpack-aeroplane__airframe_name mr-4">
                <span class="inline-block icon icon__gray h-4 w-4 mr-1"><IconPlane/></span>
                <span class="inline-block value">{{ airframe.name }}</span>
            </span>
                <span class="workpack-aeroplane__airframe_name mr-4">
                <span class="inline-block label font-semibold mr-1">Revision Number:</span>
                <span class="inline-block value">{{ airframe.revision }}</span>
            </span>
            </div>

            <PanelProgress :workpack_panel="workpack_panel"/>

            <div class="workpack-panel-status flex w-full my-8 justify-between align-content-center">
                <div class="workpack-panel-notes-summary flex align-content-center">
                    <span class="inline-block workpack-header-notes ml-1">
                        <h2 class="inline-block text-xl mr-4 text-black font-semibold">Activity</h2>
                        <span class="inline-block icon icon__notes icon__gray w-4 h-4"><IconNotes/></span>
                        <span class="after-icon text-gray-600">Notes</span>
                        <span v-if="tasks.length > 0" class="notes-count text-gray-600">({{ tasks.length }})</span>
                    </span>
                </div>
                <div class="workpack-header-status ">
                    <span class="workpack-header-status-label inline-block mr-4 font-semibold">Availability:</span>
                    <span class="workpack-header-status-step jw-pill mr-4"
                          :class="`jw-pill__${Panelhelper.getStepColor( this.workpack_panel.workpack_panel_step_id )}`">
                    {{ Panelhelper.getActionName(this.workpack_panel.workpack_panel_action_id) }}
                    for
                    {{ Panelhelper.getStepName(this.workpack_panel.workpack_panel_step_id) }}
                </span>

                    <template v-if="this.workpack_panel.user">
                        <UserInitials :name="this.workpack_panel.user.name" :id="this.workpack_panel.user.id"
                                      class="mr-2"/>
                        <span class="workpack-header-status-username text-black">{{
                                this.workpack_panel.user.name
                            }}</span>
                    </template>


                </div>
            </div>

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="workpack-panel-tasks">

                    <template v-if="tasks.length > 0">

                        <div v-for="task in tasks"
                             :key="task.id"
                             :data-workpack-panel-task-id="task.id"
                             class="workpack-panel-tasks-task mb-8 font-semibold">
                            <div class="workpack-panel-tasks-task-status-and-action block">
                                <UserInitials :id="task.user.id" :classes="'mr-3'" :name="task.user.name"/>
                                <span class="task-user-name inline-block mr-2 text-black">{{ task.user.name }}</span>
                                <span class="task-details">
                                <span class="task-action mr-2">{{
                                        Panelhelper.getActionName(task.workpack_panel_action_id).toLowerCase()
                                    }}</span>
                                <span :class="`jw-pill__${Panelhelper.getStepColor( task.workpack_panel_step_id )}`"
                                      class="task-status jw-pill mr-2">{{
                                        Panelhelper.getStepName(task.workpack_panel_step_id)
                                    }}</span>
                                <span class="task-date mr-2">
                                    <span class="mr-2">on</span>
                                    <span class="value text-black">{{ task.date }}</span>
                                </span>
                                <span class="task-has-note mr-2" v-if="task.notes.length > 0">
                                    <span class="mr-2">and</span>
                                    <span class="text-black">left a note</span>
                                </span>
                            </span>
                            </div>

                            <div v-if="task.notes.length > 0"
                                 class="workpack-panel-tasks-task-details relative mt-4 p-5 pr-10 border-2 border-gray-100 rounded-lg">
                                <span class="absolute top-2 right-2 icon icon__gray-light w-4 h-4"><IconNotes/></span>
                                <div v-for="task_note in task.notes"
                                     class="workpack-panel-tasks-task-details-note font-normal"
                                     v-html="task_note.note">
                                </div>
                            </div>

                        </div>

                    </template>

                    <template v-else>
                        <p>No work has been completed on this panel yet.</p>
                    </template>
                </div>


            </div>

            <template v-if="!Panelhelper.isWorkpackClosed( this.workpack )">
                <div class="workpack-panel-actions bg-gray-100 fixed bottom-0 left-0 w-full border-t">

                    <form class="jw-form max-w-7xl mx-auto sm:px-6 lg:px-8 my-5"
                          @submit.prevent="checkRequiresActionCodeConfirmation">

                        <div class="workpack-panel-note-add">

                            <div v-if="show_note" class="block pb-4">
                                <label class="block w-full text-md font-bold" for="note">Add Note</label>
                                <QuillEditor id="note" ref="quill" v-model:content="form.note" content="html"
                                             contentType="html" theme="snow" toolbar="essential"/>
                                <div v-if="errors.note" class="text-red-500 text-xs pt-2">{{ errors.note }}</div>
                            </div>

                            <input id="user_code" v-model="form.user_code" type="hidden"/>

                        </div>

                        <div class="workpack-panel-actions-buttons sm:flex sm:items-center">

                            <div class="sm:flex-auto">

                                <p v-if="Userhelper.isWorker() && !isWorkerAllowedToCompleteNextTask()"
                                   class="font-semibold">Please ask an <span class="text-black">Engineer</span> to now
                                    Inspect this panel</p>

                                <button v-if="Userhelper.isWorker() && isWorkerAllowedToCompleteNextTask()"
                                        class="jw-btn jw-btn__red workpack-panel-action__addnote"
                                        @click.prevent="toggleAddNote"><span v-if="!this.show_note">Add Note</span><span
                                    v-else>Hide Note</span>
                                </button>

                            </div>

                            <div>
                                <a :href="route('workpacks.schematic', { 'workpack' : workpack.id, 'schematic' : schematic.id } )"
                                   class="jw-btn jw-btn__gray btn-cancel workpack-panel-action__cancel mr-6">Back</a>
                                <template
                                    v-if="Userhelper.isWorker() && !isPanelCompleted && isWorkerAllowedToCompleteNextTask()">
                                    <button v-if="isPanelStarted && !isCurrentlyWorkingOnPanel"
                                            class="jw-btn jw-btn__blue workpack-panel-action__next">Take over working on
                                        panel
                                    </button>
                                    <button v-else
                                            v-show="Userhelper.isWorker()"
                                            class="jw-btn jw-btn__blue workpack-panel-action__next">
                                        <span v-if="form.note">Add Note and&nbsp;</span>
                                        {{
                                            Panelhelper.getNextActionFromId(this.workpack_panel.workpack_panel_action_id)
                                        }}
                                        {{
                                            Panelhelper.getNextStatusForPanel(this.workpack_panel)
                                        }}
                                    </button>
                                </template>

                                <template v-if="isPanelCompleted && Userhelper.isWorker()">
                                    <button class="jw-btn jw-btn__green workpack-panel-action__next"><span
                                        v-if="form.note">Add Note and&nbsp;</span>Re-Open Panel
                                    </button>
                                </template>

                            </div>

                        </div>

                        <div v-if="errors.general" class="w-full text-center  text-red-500 text-sm pt-2">
                            {{ errors.general }}
                        </div>
                        <div v-if="errors.user_code" class="w-full text-center text-red-500 text-sm pt-2">
                            {{ errors.user_code }}
                        </div>

                    </form>

                </div>

                <ActionCodeConfirmation v-if="this.show_code" :visible="this.show_code"
                                        @closeCodeConfirmation="listenForActionCodeConfirmation">
                    <template v-slot:details>
                        <PanelTaskModalSummary :workpack_panel="this.workpack_panel"/>
                    </template>
                </ActionCodeConfirmation>
            </template>

        </template>

    </AuthenticatedLayout>
</template>

<script>
import {Panelhelper} from '@/Jetworks/Panelhelper.js';
import {Userhelper} from '@/Jetworks/Userhelper.js';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {useForm} from '@inertiajs/inertia-vue3';
import {QuillEditor} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import {Inertia} from "@inertiajs/inertia";
import IconNotes from "@/Jetworks/Icons/Notes.vue";
import IconPlane from "@/Jetworks/Icons/Aeroplane.vue";
import IconTail from "@/Jetworks/Icons/Tail.vue";
import IconTick from "@/Jetworks/Icons/Tick.vue";
import PanelProgress from "@/Jetworks/WorkpackPanelTask/PanelProgress.vue";
import ActionCodeConfirmation from "@/Jetworks/Common/ActionCodeConfirmation.vue";
import UserInitials from "@/Jetworks/Users/Initials.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import PanelTaskModalSummary from "@/Jetworks/WorkpackPanelTask/PanelTaskModalSummary.vue";

export default {
    components: {
        PanelTaskModalSummary,
        BreadcrumbItem,
        UserInitials,
        ActionCodeConfirmation,
        PanelProgress,
        IconTick,
        AuthenticatedLayout,
        QuillEditor,
        IconTail,
        IconPlane,
        IconNotes,
    },
    data() {
        return {
            quill: null,
            use_template: false,
            show_note: false,
            show_code: false,
        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        workpack_panel: {
            type: Object,
            default: {},
        },
        workpack: {
            type: Object,
            default: {},
        },
        schematic: {
            type: Object,
            default: {},
        },
        airframe: {
            type: Object,
            default: {},
        },
        aeroplane: {
            type: Object,
            default: {},
        },
        tasks: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Work Packs',
        }
    },
    computed: {
        Panelhelper: function () {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
        Userhelper: function () {
            Userhelper.initialise(this.$page);
            return Userhelper;
        },
        isPanelStarted: function () {
            if (this.workpack_panel.user_id == 0) {
                return false;
            }
            return true
        },
        isPanelCompleted: function () {
            return this.workpack_panel.completed;
        },
        isCurrentlyWorkingOnPanel: function () {
            return this.workpack_panel.user_id == Userhelper.user.id;
        },
    },
    methods: {
        isWorkerAllowedToCompleteNextTask: function () {
            return Userhelper.isWorkerAllowedToStartTask(Panelhelper, this.workpack_panel.workpack_panel_step_id)
        },

        checkRequiresActionCodeConfirmation: function () {
            if (Panelhelper.isPanelCompleted(this.workpack_panel)) {
                this.show_code = true;
            } else if (Panelhelper.isPanelBeingWorkedOn(this.workpack_panel)) {
                this.show_code = true;
            } else {
                this.submit();
            }
        },
        toggleAddNote() {
            this.show_note = !this.show_note;
        },
        listenForActionCodeConfirmation: function (data) {
            if (data != false) {
                this.form.user_code = data;
                this.submit();
            }
            this.show_code = false;
        }
    },
    setup(props) {
        const form = useForm({
            workpack_panel_id: props.workpack_panel.id,
            schematic_id: props.schematic.id,
            user_code: '',
            note: '',
        });

        const submit = function () {
            form.post(route('workpackpaneltask.store'), {
                onSuccess: () => {
                    form.reset('note', 'user_code');
                }
            });
        }

        return {
            form,
            submit,
        }
    }
}
</script>
