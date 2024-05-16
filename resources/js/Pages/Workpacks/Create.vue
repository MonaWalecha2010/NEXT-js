<template>

    <Head :title="`Create ${pageHeader}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('workpacks.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="`Create ${pageHeader}`"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Create a Workpack</h1>
                </div>
            </div>

            <nav class="my-6 p-5 rounded-lg border-2 border-gray-200" aria-label="Progress">
                <ol role="list" class="space-y-4 md:flex md:space-y-0 md:space-x-8">
                    <li class="md:flex-1">
                        <!-- Current Step -->
                        <a href="#"
                           class="flex flex-col border-l-4 jw-border__blue py-2 pl-4 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0"
                           aria-current="step">
                            <span class="text-sm font-medium jw-text__blue">Step 1</span>
                            <span class="text-sm font-medium text-black">Aeroplane / Details</span>
                        </a>
                    </li>

                    <li class="md:flex-1">
                        <!-- Upcoming Step -->
                        <a href="#"
                           class="group flex flex-col border-l-4 border-gray-200 py-2 pl-4 hover:border-gray-300 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0">
                            <span class="text-sm font-medium text-gray-400 group-hover:text-gray-700">Step 2</span>
                            <span class="text-sm font-medium text-black">Panels</span>
                        </a>
                    </li>
                </ol>
            </nav>

            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form class="jw-form" @submit.prevent="submit">

                    <div class="block pb-6">
                        <label class="block w-full text-md font-bold" for="aeroplane_id">Choose
                            Aeroplane</label>
                        <select id="aeroplane_id"
                                v-model="form.aeroplane_id"
                                @change="loadWorkpackTemplates"
                                class="block w-full">
                            <option disabled>Choose Aeroplane</option>
                            <option v-for="aeroplane in aeroplanes.data"
                                    :key="aeroplane.id"
                                    :value="aeroplane.id">
                                {{ aeroplane.name }}
                            </option>
                        </select>
                        <div v-if="errors.aeroplane" class="text-red-500 text-xs pt-2">{{ errors.aeroplane }}
                        </div>
                    </div>

                    <div v-if="form.aeroplane_id && airframe_workpacks.length > 0" class="block pb-0">
                        <span class="flex w-full">
                            <input id="use-workpack-template"
                                   v-model="use_template"
                                   true-value="1"
                                   false-value="0"
                                   type="checkbox"/>
                            <label class="flex-auto ml-4 text-md"
                                   for="use-workpack-template">Use a Work Pack Template</label>
                        </span>
                    </div>

                    <div v-if="(this.use_template == true) && (this.airframe_workpacks.length > 0)" class="block pb-6">
                        <label class="block w-full text-md font-bold" for="airframe_workpack_id">Choose Work Pack
                            Template</label>
                        <select id="airframe_workpack_id" v-model="form.airframe_workpack"
                                class="block w-full" @change.prevent="setNameAndDescriptionFromTemplate">
                            <option disabled>Choose Template</option>
                            <template v-for="airframe_workpack in this.airframe_workpacks"
                                      :key="airframe_workpack.id">
                                <option :value="airframe_workpack">
                                    {{ airframe_workpack.name }}
                                </option>
                            </template>
                        </select>

                        <div v-if="errors.airframe_workpack_id" class="text-red-500 text-xs pt-2">
                            {{ errors.airframe_workpack_id }}
                        </div>
                    </div>

                    <div class="block pb-6">
                        <label class="block w-full text-md font-bold" for="name">Workpack Name</label>
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

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="description">Description</label>
                        <QuillEditor ref="quill" v-model:content="form.description" content="html" contentType="html"
                                     theme="snow" toolbar="essential"/>
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{ errors.description }}
                        </div>
                    </div>

                    <div v-if="errors.create" class="text-red-500 text-sm pt-2">{{ errors.create }}</div>

                    <div v-if="errors.general" class="text-red-500 text-sm pt-2">{{ errors.general }}</div>

                    <div class="flex pt-8 justify-end space-x-6">
                        <a :href="route('workpacks.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        <button class="jw-btn jw-btn__blue-dark" type="submit">Next</button>
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
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import {Userhelper} from "@/Jetworks/Userhelper";

export default {
    components: {
        BreadcrumbItem,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
        QuillEditor
    },
    data() {
        return {
            quill             : null,
            use_template      : 0,
            airframe_workpacks: {
                type   : Object,
                default: {},
            },
        }
    },
    props: {
        can         : {
            type   : Object,
            default: {},
        },
        aeroplanes  : {
            type   : Object,
            default: {},
        },
        aeroplane_id: {
            type   : Number,
            default: null,
        },
        airframes   : {
            type   : Object,
            default: {},
        },
        errors      : {
            type   : Object,
            default: {},
        },
        pageHeader  : {
            type   : String,
            default: 'Work Packs',
        }
    },

    computed: {
        aeroplaneAirframeId: function () {
            let airframe_id = null;
            this.aeroplanes.data.every((aeroplane) => {
                if (aeroplane.id === this.form.aeroplane_id) {
                    airframe_id = aeroplane.airframe.id;
                    return false;
                }
                return true;
            });
            return airframe_id;
        }
    },

    methods: {

        loadWorkpackTemplates: function () {

            axios.get(route('aeroplane.workpacktemplates', {'aeroplane': this.form.aeroplane_id})).then((response) => {
                if (response.data.airframe_workpacks.length > 0) {
                    this.airframe_workpacks = response.data.airframe_workpacks;
                }
            }).catch(error => {
                this.airframe_workpacks = {};
                this.use_template = false;
            })
                .finally(() => {

                });

        },

        setNameAndDescriptionFromTemplate: function () {
            let date = new Date();
            this.form.name = `${this.form.airframe_workpack.name}-${date.getFullYear()}-${date.getMonth()}-${date.getDate()}` ;
            this.form.description = this.form.airframe_workpack.description;
        }

    },

    setup(props) {

        const form = useForm({
            name             : null,
            active           : false,
            description      : "",
            aeroplane_id     : props.aeroplane_id,
            airframe_workpack: null,
            airframe_workpack_id: null,
        });

        function submit() {
            if( form.airframe_workpack ){
                form.airframe_workpack_id = form.airframe_workpack.id;
            }
            Inertia.post('/workpacks', form);
        }

        return {
            form,
            submit,
        }
    }
}
</script>
