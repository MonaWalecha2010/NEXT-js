<template>

    <Head title="Work Packs Templates"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ pageHeader }}</h2>
        </template>

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('airframeworkpacks.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="`Create ${pageHeader}`"/>
        </template>

        <template v-slot:default>

            <nav class="my-6 p-5 rounded-lg border-2 border-gray-200" aria-label="Progress">
                <ol role="list" class="space-y-4 md:flex md:space-y-0 md:space-x-8">
                    <li class="md:flex-1">
                        <!-- Current Step -->
                        <a href="#"
                           class="flex flex-col border-l-4 jw-border__blue py-2 pl-4 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0"
                           aria-current="step">
                            <span class="text-sm font-medium jw-text__blue">Step 1</span>
                            <span class="text-sm font-medium text-black">Aeroplane Type / Details</span>
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

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="airframe_id">Choose Aeroplane
                            Type</label>
                        <select id="airframe_id" v-model="form.airframe_id" class="block w-full">
                            <option disabled>Choose Aeroplane Type</option>
                            <option v-for="airframe in airframes.data" :key="airframe.id" :value="airframe.id">
                                {{ airframe.fullname }}
                            </option>
                        </select>
                        <div v-if="errors.airframe_id" class="text-red-500 text-xs pt-2">{{ errors.airframe_id }}
                        </div>
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

                    <div v-if="errors.create" class="text-red-500 text-sm pt-2">{{ errors.create }}</div>

                    <div class="flex pt-8 justify-end space-x-6">
                        <a :href="route('airframeworkpacks.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        <button class="jw-btn jw-btn__blue-dark" type="submit">Add Work Pack Template</button>
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

export default {
    components: {
        BreadcrumbItem,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
        QuillEditor

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
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Work Pack Templates',
        }

    },

    methods: {

        checkAllPanels(event) {
            let panelValues = [];
            if (event.target.checked) {
                let panels = document.getElementsByClassName('airframe-panel');
                for (let i = 0; i < panels.length; i++) {
                    panelValues.push(panels[i].value);
                }
            }
            this.form.panels = panelValues;
        }
    },

    setup(props) {
        const form = useForm({
            airframe_id: null,
            name: null,
            description: null,
            panels: [],
        });

        function submit() {
            Inertia.post('/airframeworkpacks', form);
        }

        return {
            form,
            submit,
        }
    },
}
</script>
