<template>

    <Head title="Edit Aeroplane Type"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Work Pack Template: {{
                    airframeworkpack.name
                }} </h2>
        </template>

        <template v-slot:default>

            <nav class="my-6 p-5 rounded-lg border-2 border-gray-200" aria-label="Progress">
                <ol role="list" class="space-y-4 md:flex md:space-y-0 md:space-x-8">
                    <li class="md:flex-1">
                        <!-- Current Step -->
                        <a href="#"
                           class="group flex flex-col border-l-4 border-gray-200 py-2 pl-4 hover:border-gray-300 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0
                           "
                           aria-current="step">
                            <span class="text-sm font-medium jw-text__blue">Step 1</span>
                            <span class="text-sm font-medium text-black">Aeroplane Type / Details</span>
                        </a>
                    </li>

                    <li class="md:flex-1">
                        <!-- Upcoming Step -->
                        <a href="#"
                           class="flex flex-col border-l-4 jw-border__blue py-2 pl-4 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0">
                            <span class="text-sm font-medium text-gray-400 group-hover:text-gray-700">Step 2</span>
                            <span class="text-sm font-medium text-black">Panels</span>
                        </a>
                    </li>
                </ol>
            </nav>

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form class="jw-form" @submit.prevent="submit">

                    <div class="flex pt-4">
                        {{ airframeworkpack.airframe.name }}
                    </div>

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="name">Name</label>
                        <input id="name" v-model="form.name" class="block w-full" type="text"/>
                        <div v-if="errors.name" class="text-red-500 text-xs pt-2">{{ errors.name }}</div>
                    </div>


                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="description">Description</label>
                        <QuillEditor ref="quill" v-model:content="form.description" content="html" contentType="html"
                                     theme="snow" toolbar="essential"/>
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{ errors.description }}</div>
                    </div>

                    <div v-if="panels.length > 0"
                         class="block pb-4 airframe-panels border border-gray-300 rounded-md mb-6">

                        <div class="flex items-center p-4 rounded-t-md bg-gray-100">
                        <span class="flex-auto  mr-8">
                            <input class="border jw-border-gray bg-white w-1/3"
                                   @keyup.prevent="filterPanels"
                                   type="text"
                                   placeholder="Search..."/>
                        </span>
                            <span class="flex-none space-x-4 justify-self-end justify-end content-center">
                            <span>{{ panels.length }} Panels ( {{ form.panels.length }} Selected )</span>
                            <a @click.prevent="checkAllPanels" class="jw-btn jw-btn__blue">Select All</a>
                            <a @click.prevent="uncheckAllPanels" class="jw-btn jw-btn__blue">Deselect All</a>
                        </span>
                        </div>

                        <div class="max-h-[300px] overflow-y-auto">
                            <table class="jw-table border-y border-gray-300">
                                <tbody class="mt-4 pt-4 border-t">
                                <template v-for="(panel, index ) in panels"
                                          v-show="panel.visible != false"
                                          :data-panel-name="panel.name"
                                          :key="panel.id">
                                    <tr class="panel-row" :class="panelSelectedRowClasses(panel)">
                                        <td class="w-8">
                                            <input v-model="form.panels"
                                                   :value="panel.id"
                                                   :id="`panel-${panel.id}`"
                                                   class="airframe-panel h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500"
                                                   type="checkbox">
                                        </td>
                                        <td>
                                            <label :for="`panel-${panel.id}`"
                                                   class="mb-0 font-medium text-gray-700">{{ panel.name }}</label>
                                        </td>
                                        <td class="text-right">
                                <span class="pointer" @click.prevent="togglePanelSchematics( panel )">
                                        <span v-if="panel.show_schematics">Hide </span>
                                        <span v-else>Show</span>
                                        <span class="pl-1 schematics-count">({{ panel.schematics.length }})</span>
                                    </span>
                                        </td>
                                    </tr>
                                    <tr v-if="panel.schematics.length > 0 && panel.show_schematics == true"
                                        class="panel-row mt-4 pt-4 border-t border-gray-200"
                                        :class="panelSelectedRowClasses(panel)">
                                        <td colspan="3">
                                            <ul>
                                                <li v-for="(schematic, index) in panel.schematics">
                                                    <a @click.prevent="showSchematic( schematic, panel, $event )"
                                                       :href="schematic.image"
                                                       class="jw-text__blue"
                                                       target="_blank">{{ schematic.name }}</a>
                                                </li>
                                            </ul>
                                        </td>
                                    </tr>
                                </template>
                                </tbody>

                            </table>
                        </div>

                    </div>

                    <div v-if="errors.create" class="text-red-500 text-sm pt-2">{{ errors.create }}</div>

                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <a class="jw-btn jw-btn__red" @click.prevent="showActionCodeConfirmation">Delete Work Pack Template</a>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                            <a :href="route('airframeworkpacks.index')"
                               class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                            <button class="jw-btn jw-btn__blue" type="submit">Update Work Pack Template</button>
                        </div>
                    </div>

                </form>

            </div>

            <SchematicModal v-if="schematic_for_model"
                            @closeSchematicModal="hideSchematic()"
                            :schematic="schematic_for_model"
                            :panel="panel_for_model"
                            ref="schematic-model"/>

            <ActionCodeConfirmation v-if="this.show_code" :visible="this.show_code"
                                    @closeCodeConfirmation="listenForActionCodeConfirmation"/>

        </template>

    </AuthenticatedLayout>

</template>

<script>
import {ref, onMounted} from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Inertia} from '@inertiajs/inertia';
import {useForm} from '@inertiajs/inertia-vue3';
import TextInput from '@/Components/InputLabel.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import VueUploadComponent from 'vue-upload-component';
import {QuillEditor} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import SchematicModal from "@/Jetworks/Schematic/Modal.vue";
import ActionCodeConfirmation from "@/Jetworks/Common/ActionCodeConfirmation.vue";

export default {
    components: {
        ActionCodeConfirmation,
        SchematicModal,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
        QuillEditor,
        VueUploadComponent
    },
    data() {
        return {
            schematic_for_model: null,
            panel_for_model    : null,
            show_code          : false,
        }
    },
    props: {
        can             : {
            type   : Object,
            default: {},
        },
        airframeworkpack: {
            type   : Object,
            default: {},
        },
        panels          : {
            type   : Object,
            default: {},
        },
        errors          : {
            type   : Object,
            default: {},
        },
    },

    methods: {

        showActionCodeConfirmation: function () {
            this.show_code = true;
        },

        listenForActionCodeConfirmation: function (data) {
            if (data != false) {
                this.destroy();
            }
            this.show_code = false;
        },

        panelSelectedRowClasses: function (panel) {
            let classes = "";
            let selected = this.form.panels.includes(panel.id);
            if (selected) {
                classes = "bg-blue-50";
            }
            return classes;
        },

        togglePanelSchematics: function (panel) {
            if (panel.show_schematics === undefined) {
                return panel.show_schematics = true;
            }
            panel.show_schematics = !panel.show_schematics;
        },

        showSchematic: function (schematic, panel, event) {
            this.schematic_for_model = schematic;
            this.panel_for_model = panel;
        },

        hideSchematic: function () {
            this.schematic_for_model = null;
            this.panel_for_model = null;
        },

        checkAllPanels: function () {
            let panelValues = [];
            let panels = document.getElementsByClassName('airframe-panel');
            for (let i = 0; i < panels.length; i++) {
                panelValues.push(panels[i].value);
            }
            this.form.panels = panelValues;
        },

        uncheckAllPanels: function () {
            this.form.panels = [];
        },

        filterPanels: function () {
            let filterText = event.target.value.toLowerCase();
            for (let i = 0; i < this.panels.length; i++) {
                let panel = this.panels[i];
                let panelName = panel.name.toLowerCase();
                if (panelName.indexOf(filterText) == -1) {
                    panel.visible = false;
                } else {
                    panel.visible = true;
                }
            }
        },

        destroy(event) {
            Inertia.delete(route('airframeworkpacks.destroy', {airframeworkpack: this.airframeworkpack.id}));
        },
    },

    setup(props) {
        // We need to get and array of  only the related panel id to update the checkboxes
        let selectedPanels = props.airframeworkpack.airframe_workpack_panels.map(object => object.panel_id);

        const form = useForm({
            _method    : 'put',
            id         : props.airframeworkpack.id,
            airframe_id: props.airframeworkpack.airframe.id,
            name       : props.airframeworkpack.name,
            description: props.airframeworkpack.description,
            panels     : selectedPanels,
            files      : [],
        });

        function submit() {
            Inertia.post(route('airframeworkpacks.update', {airframeworkpack: props.airframeworkpack.id}), form);
        }

        return {
            form,
            submit,
        }
    },
}
</script>

<style lang="scss" scoped>
.workpack-files {

    label.btn {
        margin-bottom: 0;
        margin-right: 1rem;
    }

    .drop-active {
        top: 0;
        bottom: 0;
        right: 0;
        left: 0;
        position: fixed;
        z-index: 9999;
        opacity: .6;
        text-align: center;
        background: #000;

        h3 {
            margin: -.5em 0 0;
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            -webkit-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            transform: translateY(-50%);
            font-size: 40px;
            color: #fff;
            padding: 0;
        }
    }
}

</style>
