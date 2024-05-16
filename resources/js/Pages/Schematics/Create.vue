<template>

    <Head :title="`Create ${pageHeader}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('schematics.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="`Create ${pageHeader}`"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Add a {{ pageHeader }}</h1>
                </div>
            </div>

            <div class="my-6 p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form class="jw-form" @submit.prevent="submit">

                    <div class="block pt-4">
                        <label class="block w-full text-md font-bold" for="airframe_id">Aeroplane Type</label>
                        <select id="airframe_id" v-model="form.airframe_id" class="block w-full text-md">
                            <option disabled>Choose Aeroplane Type</option>
                            <option v-for="airframe in airframes.data" :key="airframe.id" :value="airframe.id">
                                {{ airframe.fullname }}
                            </option>
                        </select>
                        <div v-if="errors.airframe_id" class="text-red-500 text-xs pt-2">{{ errors.airframe_id }}</div>
                    </div>


                    <div v-if="schematicPanels.length == 0"
                         class="airframe-schematic w-full py-4">

                        <div class="w-full text-center text-sm text-gray-600 rounded-md border-2 border-dashed border-gray-100 px-6 pt-7 pb-8">
                            <span class="icon jw-svg-stroke__gray-light mx-auto mb-4  w-8 h-8"><IconSchematic/></span>
                            <label for="schematic-upload"
                                   class="relative cursor-pointer rounded-md font-medium text-gray-300">
                                <span class="">Add a new aeroplane schematic</span>
                                <br>
                                <br>
                                <span class="jw-btn jw-btn__blue">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                     stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                  <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                </svg>
                                Add Schematic
                            </span>
                            </label>
                            <input id="schematic-upload"
                                   @change="parseSvg"
                                   @input="form.image = $event.target.files[0]"
                                   class="sr-only"
                                   accept=".svg"
                                   type="file"/>
                        </div>

                    </div>

                    <div v-else
                         class="w-full mt-4 py-4">

                        <progress v-if="form.progress" :value="form.progress.percentage" max="100">
                            {{ form.progress.percentage }}%
                        </progress>

                        <div v-if="svgerrors" class="text-red-500 text-xs pt-2" v-html="svgerrors"/>
                        <div v-if="errors.image" class="text-red-500 text-xs pt-2">{{ errors.image }}</div>

                        <div v-if="svg" class="schematic" v-html="svg"></div>

                        <template v-if="schematicPanels.length > 0">
                            <div class="p-4 rounded-t bg-gray-100 border border-gray-300">
                                <h3 class="text-gray-400">{{ schematicPanels.length }} Panels</h3>
                            </div>

                            <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                                <li  v-for="schematicPanel in schematicPanels"
                                    :key="schematicPanel"
                                    class="flex flex-auto justify-between flex-a- p-3">
                                    <span class="schematic-details">
                                        <span class="icon icon__gray inline-block w-4 h-4 mr-2">
                                            <IconSchematic/>
                                        </span>
                                        <span class="schematic-name">
                                            {{ schematicPanel.name }}
                                        </span>
                                    </span>
                                    <span class="schematic-panel-details">
                                        <a @click.prevent="togglePanel(schematicPanel)" class="jw-text__red mr-4"><span v-if="schematicPanel.highlighted == undefined || schematicPanel.highlighted == false">Highlight</span><span v-else>Hide</span></a>
                                    </span>
                                </li>
                            </ul>

                            <div class="block my-4 text-center">
                                <a @click.prevent="resetSchematic" class="jw-btn jw-btn__gray">Choose different Schematic</a>
                            </div>
                        </template>
                    </div>


                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="name">Schematic Name</label>
                        <input id="name" v-model="form.name" class="block w-full" type="text"/>
                        <div v-if="!form.name" class="text-xs pt-2">If left blank, the schematic file name will be
                            used...
                        </div>
                        <div v-if="errors.name" class="text-red-500 text-xs pt-2">{{ errors.name }}</div>
                    </div>

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="description">Description</label>
                        <QuillEditor ref="quill" v-model:content="form.description" content="html" contentType="html"
                                     theme="snow" toolbar="essential"/>
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{ errors.description }}</div>
                    </div>

                    <div v-if="errors.create" class="text-red-500 text-sm pt-2">{{ errors.create }}</div>

                    <div class="flex pt-8 justify-end space-x-6">
                        <template v-if="airframe_id">
                            <a :href="route('airframes.edit', { 'airframe' : airframe_id })" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        </template>
                        <template v-else>
                            <a :href="route('schematics.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        </template>

                        <button class="jw-btn jw-btn__blue-dark" type="submit" :disabled="form.processing">Add Schematic
                        </button>
                    </div>

                </form>
            </div>

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
import {QuillEditor} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import IconSchematic from "@/Jetworks/Icons/Schematic.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

export default {

    components: {
        BreadcrumbItem,
        IconSchematic,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
        QuillEditor
    },

    data() {
        return {
            svg: null,
        }
    },

    props: {
        can: {
            type: Object,
            default: {},
        },
        airframes: {
            type: Object,
            default: {},
        },
        airframe_id: {
            default: '',
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Schematic',
        }
    },

    setup(props) {
        const schematicPanels = ref([]);
        const svgerrors = ref("");

        const form = useForm({
            airframe_id: props.airframe_id,
            name: null,
            description: null,
            image: null,
            panels: [],
        });

        function submit() {
            //Serialise the panels...
            let schematicPanelNames = [];
            for (let i = 0; i < this.schematicPanels.length ; i++) {
                schematicPanelNames.push( this.schematicPanels[i].name );
            }
            form.panels = schematicPanelNames.join(',');

            form.post('/schematics', {
                onError: () => form.reset('name')
            });
        };

        return {
            schematicPanels,
            svgerrors,
            form,
            submit,
        };
    },

    methods: {

        resetSchematic(){
            this.form.image = null;
            this.form.panels = [];
            this.schematicPanels = [];
        },

        togglePanel( panel) {
            if( panel.highlighted === undefined ){
                panel.highlighted = false;
            }
            panel.highlighted = !panel.highlighted;
            let method = (panel.highlighted) ? "add" : "remove";
            let scannedPanel = this.scanPanel( panel );

            for (const layer of Object.entries(scannedPanel)) {
                if( layer[1] ){
                    layer[1].classList[method]('jw-svg__red');
                }
            }
        },

        scanPanel( panel ) {
            let scannedPanel = {};
            scannedPanel.outline = document.getElementById( `outline--${panel.name}` );
            scannedPanel.label= document.getElementById( `label--${panel.name}` );
            scannedPanel.arrow = document.getElementById( `arrow--${panel.name}` );
            return scannedPanel;
        },

        parseSvg(event) {
            const schematicInput = event.target.files;
            const schematicFile = schematicInput[0];
            this.schematicPanels = [];
            this.svgerrors = '';
            this.errors.create = null;

            if (this.isSchematicFileSVG(schematicFile)) {
                if (!this.form.name) {
                    let filename = schematicFile.name;
                    //Remove the file extension
                    this.form.name = filename.split('.').slice(0, -1).join('.');
                }
                this.processSchematicFile(schematicFile);


            } else {
                this.svgerrors = "Schematic does not appear to be a SVG";
            }

        },

        isSchematicFileSVG(file) {
            // Not supported in Safari for iOS.
            const name = file.name ? file.name : 'NOT SUPPORTED';
            //// Not supported in Firefox for Android or Opera for Android?
            const type = file.type ? file.type : 'NOT SUPPORTED';
            //// Unknown cross-browser support?
            const size = file.size ? file.size : 'NOT SUPPORTED';

            if (file.type == "image/svg+xml") {
                return true;
            }
            return false;
        },

        processSchematicFile(file) {
            const reader = new FileReader();
            reader.readAsText(file);

            reader.addEventListener('load', (event) => {
                let svgContents = reader.result.trim();
                let parser = new DOMParser();
                let doc = parser.parseFromString(svgContents, 'text/html');
                if (this.checkSchematicHasMinimalDOMStructure(doc)) {
                    let panelIDPrefix = 'panel--';
                    let panels = doc.querySelectorAll(`[id^="${panelIDPrefix}"]`);
                    for (let i = 0; i < panels.length; i++) {
                        let panelLabel = panels[i].id.replace(panelIDPrefix, '');
                        if (this.checkSchematicPanelHasSensibleName(panelLabel)) {
                            this.schematicPanels.push( {'name' : panelLabel } );
                        }
                    }
                    //Clear any existing errors
                    delete this.errors.image;
                    this.svg = svgContents;
                } else {
                    this.svgerrors = '<b>Schematic SVG structure is incorrect</b>. Please ensure that the necessary layers are present in the SVG file.';
                }
            });
        },

        checkSchematicHasMinimalDOMStructure(doc) {
            let schematicContainer = doc.getElementById('schematic');
            let panelsContainer = doc.getElementById('panels');
            let aircraftContainer = doc.getElementById('aircraft');
            let backgroundContainer = doc.getElementById('background');
            if (schematicContainer && panelsContainer && aircraftContainer && backgroundContainer) {
                return true;
            }
            return false;
        },

        checkSchematicPanelHasSensibleName(name) {
            if (name.length > 20) {
                this.svgerrors += this.addMessage('Layer with name <strong>' + name + '</strong> appears problematic. Check the source svg for other layers starting with the same "base" name.', 'danger');
                return false;
            }
            return true;
        },

        addMessage(message, label) {
            return '<p class="mb-2 p-1 alert alert-' + label + '">' + message + '</p>';
        }

    }
}
</script>
