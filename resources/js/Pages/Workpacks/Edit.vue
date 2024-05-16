<template>

    <Head :title="`Edit ${form.name}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('workpacks.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="form.name"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Edit {{ form.name }}</h1>
                </div>
            </div>

            <nav class="my-6 p-5 rounded-lg border-2 border-gray-200" aria-label="Progress">
                <ol role="list" class="space-y-4 md:flex md:space-y-0 md:space-x-8">
                    <li class="md:flex-1">
                        <a href="#"
                           class="flex flex-col border-l-4 border-gray-200 py-2 pl-4 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0"
                           aria-current="step">
                            <span class="text-sm font-medium jw-text__blue">Step 1</span>
                            <span class="text-sm font-medium text-black">Aeroplane / Details</span>
                        </a>
                    </li>

                    <li class="md:flex-1">
                        <!-- Upcoming Step -->
                        <a href="#"
                           class="group flex flex-col border-l-4 jw-border__blue py-2 pl-4 hover:border-gray-300 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0">
                            <span class="text-sm font-medium text-gray-400 group-hover:text-gray-700">Step 2</span>
                            <span class="text-sm font-medium text-black">Panels</span>
                        </a>
                    </li>
                </ol>
            </nav>

            <form @submit.prevent class="jw-form">

                <div class="p-6 mb-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div class="block pb-6">
                        <label class="block w-full text-md font-bold" for="aeroplane_id">Aeroplane</label>
                        <span class="aeroplane">{{ workpack.aeroplane.name }}</span>
                        <input v-model="form.aeroplane_id" type="hidden"/>
                    </div>

                    <div class="block pb-6">
                        <label class="block w-full text-md font-bold" for="name">Name</label>
                        <input id="name" v-model="form.name" class="block w-full" type="text"/>
                        <div v-if="errors.name" class="text-red-500 text-xs pt-2">{{ errors.name }}</div>
                    </div>

                    <div class=" pb-6">
                        <label for="active">Status</label>
                        <select id="active" v-model="form.active" class="" name="show">
                            <option :selected="form.active == 1" value="1">Active</option>
                            <option :selected="form.active == 0" value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="block">
                        <label class="block w-full text-md font-bold" for="description">Description</label>
                        <QuillEditor ref="quill" v-model:content="form.description" content="html" contentType="html"
                                     theme="snow" toolbar="essential"/>
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{ errors.description }}
                        </div>
                    </div>

                    <div v-if="errors.edit" class="text-red-500 text-sm pt-2">{{ errors.create }}</div>

                </div>

                <div v-if="panels.length > 0"
                     class="block pb-6 airframe-panels border border-gray-300 rounded-md mb-6">

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
                                <tr v-show="panel.visible != false" class="panel-row" :class="panelSelectedRowClasses(panel)">
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

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                    <div v-if="errors.general" class="rounded-md bg-red-50 p-4 mb-6">
                        <div class="flex">
                            <div class="text-sm text-red-700">
                                {{ errors.general }}
                            </div>
                        </div>
                    </div>

                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <a class="jw-btn jw-btn__red" @click.prevent="showActionCodeConfirmation">Delete Work
                                Pack</a>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                            <a :href="route('workpacks.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                            <button @click.prevent="submit" class="jw-btn jw-btn__blue" type="button">Update Work Pack
                            </button>
                        </div>
                    </div>

                </div>

            </form>

            <SchematicModal v-if="schematic_for_model"
                            @closeSchematicModal="hideSchematic()"
                            :schematic="schematic_for_model"
                            :panel="panel_for_model"
                            ref="schematic-model"/>

            <WorkpackModalSaveTemplate v-if="show_save_as_template"
                                       @closeSaveTemplateModal="listenForTemplateName()"/>

            <ActionCodeConfirmation v-if="this.show_code" :visible="this.show_code" @closeCodeConfirmation="listenForActionCodeConfirmation"/>

        </template>

    </AuthenticatedLayout>

</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Inertia} from '@inertiajs/inertia';
import {useForm} from '@inertiajs/inertia-vue3';
import TextInput from '@/Components/InputLabel.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import {QuillEditor} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import SchematicModal from "@/Jetworks/Schematic/Modal.vue";
import ActionCodeConfirmation from "@/Jetworks/Common/ActionCodeConfirmation.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import WorkpackModalSaveTemplate from "@/Jetworks/Workpacks/ModalSaveTemplate.vue";

export default {
    components: {
        WorkpackModalSaveTemplate,
        BreadcrumbItem,
        ActionCodeConfirmation,
        SchematicModal,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
        QuillEditor
    },
    data() {
        return {
            quill: null,
            use_template: false,
            schematic_for_model: null,
            panel_for_model: null,
            show_save_as_template: false,
            show_code: false,
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
        workpackpanels: {
            type: Object,
            default: {},
        },
        panels: {
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
        schematic_model: function () {

        },
    },
    methods: {

        showSaveAsTemplate: function () {
            this.show_save_as_template = true;
        },

        listenForTemplateName: function (data) {
            if (data != false) {
                this.form.name = data;
                this.submitTemplate();
            }
            this.show_save_as_template = false;
        },

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

        filterPanels: function ( event ) {
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

        setNameAndDescriptionFromTemplate: function () {
            console.log('Should update Name and Description');
        },


        destroy: function (event) {
            Inertia.delete(route('workpacks.destroy', {workpack: this.workpack.id}));
        }
    },

    setup(props) {
        // We need to get and array of  only the related panel id to update the checkboxes
        let selectedPanels = props.workpackpanels.map(object => object.panel_id);

        const form = useForm({
            _method: 'put',
            id: props.workpack.id,
            active: props.workpack.active,
            name: props.workpack.name,
            description: props.workpack.description,
            aeroplane_id: props.workpack.aeroplane.id,
            panels: selectedPanels
        });

        const submit = function (event) {
            form.post(route('workpacks.update', {'workpack': form.id}), form);
        }

        const submitTemplate = function () {
            form._method = 'post';
            form.airframe_id = this.workpack.airframe_id;
            form.post(route('airframeworkpacks.store'));
        }

        return {
            form,
            submit,
            submitTemplate
        }
    }
}
</script>
