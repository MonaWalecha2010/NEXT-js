<template>
<Head :title="`Create ${pageHeader}`" />

<AuthenticatedLayout :can="can">
    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :title="'Add a Training Task'" />
    </template>

    <template v-slot:default>

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Add a Training Task</h1>
            </div>
        </div>

        <div class="mt-6 bg-white overflow-hidden shadow-sm jw-form rounded-md">
            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-1 ">
                    <label for="task_number">Task Number </label>
                    <input id="task_number" v-model="task_number" type="text" class="block w-full">
                    <div v-if="errors.task_number" class="text-red-600 text-xs pt-2">{{ errors.task_number }}</div>
                </div>
                <div class="sm:col-span-3 ">
                    <label for="title">Task Title </label>
                    <input id="title" v-model="title" class="block w-full" type="text" />
                    <div v-if="errors.title" class="text-red-600 text-xs pt-2">{{ errors.title }}</div>
                </div>
                <div class="sm:col-span-2 ">
                    <label for="short_name">Short Name </label>
                    <input id="short_name" v-model="short_name" class="block w-full" type="text" />
                    <div v-if="errors.short_name" class="text-red-600 text-xs pt-2">{{ errors.short_name }}</div>
                </div>

            </div>
            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3">
                    <div class="w-full ">
                        <label for="external_id">External ID (Optional)</label>
                        <input id="external_id" v-model="external_id" class="block w-full" type="text" />
                    </div>
                    <div class="w-full pt-5">
                        <label for="revalidation_type">Revalidation Type</label>
                        <select id="revalidation_type" @change="selectedRevalidation" v-model="revalidation_type" class="" name="show">
                            <option disabled value="" :selected="defaultSelected">Select a Revalidation Type</option>
                            <option v-for="(revalidation_type,index) in revalidation_types" :key="index" :value="index">{{revalidation_type}}</option>
                        </select>
                        <div v-if="errors.revalidation_type" class="text-red-600 text-xs pt-2">{{ errors.revalidation_type }}</div>

                    </div>

                </div>
                <div class="sm:col-span-3 exclude-toolbar small">
                    <label for="description">Task Description (Optional) </label>
                    <QuillEditor ref="quill" v-model:content="description" content="html" contentType="html" theme="snow" toolbar="#exclude-toolbar" />
                    <span id="exclude-toolbar" class="hidden"></span>
                </div>

            </div>
            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-2 place-self-end w-full flex items-end justify-between gap-x-3">
                    <div class="inline-block w-[30%]">
                        <label for="validity_number">Validity</label>
                        <input id="validity_number" v-model="validity_number" class="block w-full" type="text" />
                        <div v-if="errors.validity_number" class="text-red-600 text-xs pt-2">{{ errors.validity_number }}</div>
                    </div>
                    <div class="inline-block w-[70%] mx-auto">
                        <select id="validity_period" v-model="validity_period" class="" name="show">
                            <option disabled value="" :selected="defaultSelected">Select a Validity Period</option>
                            <option v-for="(validity_period,index) in validity_periods" :key="index" :value="validity_period">{{validity_period}}</option>
                        </select>
                        <div v-if="errors.validity_period" class="text-red-600 text-xs pt-2">{{ errors.validity_period }}</div>
                    </div>
                </div>

                <!-- <div class="sm:col-span-1 place-self-end w-full">
                    <label for="validity_period"></label>
                    <select id="validity_period" v-model="validity_period" class="" name="show">
                        <option disabled value="" :selected="defaultSelected">Select a Validity Period</option>
                        <option v-for="(validity_period,index) in validity_periods" :key="index" :value="validity_period">{{validity_period}}</option>
                    </select>
                    <div v-if="errors.validity_period" class="text-red-600 text-xs pt-2">{{ errors.validity_period }}</div>
                </div> -->

                <div class="sm:col-span-4 ">
                    <label for="sector_id">Training Sector</label>
                    <select id="sector_id" v-model="sector_id" class="" name="show">
                        <option disabled value="" :selected="defaultSelected">Select a Training Sector</option>
                        <option v-for="(sector,index) in sectors.data" :key="index" :value="sector.id">{{sector.title}}</option>
                    </select>
                    <div v-if="errors.sector_id" class="text-red-600 text-xs pt-2">{{ errors.sector_id }}</div>

                </div>
            </div>
            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-1 gap-x-4 sm:grid-cols-6">

                <div class="sm:col-span-6 flex">
                    <select @change="roleChanged" id="airframe_id" class="block w-full">
                        <option selected="true" disabled>Choose Training Roles
                        </option>
                        <option v-for="role in training_roles.data" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>
                    </select>
                </div>

                <div class="sm:col-span-6 flex">
                    <div v-if="training_role_ids" v-show="training_role_ids.length > 0" class="flex grow-0 gap-2 mt-4 user-airframes selected">
                        <span v-for="role in this.selected_roles" @click.prevent="removeRole( role )" class="justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">
                            <span class="icon icon__white w-3 h-3">
                                <IconPlane />
                            </span>
                            <span class="ml-1">{{role.name}}</span>
                            <span class="ml-4 cursor-pointer">
                                <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M4.53494 3.99944L7.07994 1.45444C7.14516 1.40148 7.19853 1.33542 7.2366 1.26052C7.27466 1.18562 7.29658 1.10357 7.30091 1.01966C7.30525 0.935762 7.29193 0.851884 7.26179 0.77346C7.23166 0.695036 7.18539 0.623815 7.12598 0.564408C7.06657 0.505001 6.99535 0.458732 6.91693 0.428597C6.8385 0.398463 6.75463 0.385133 6.67072 0.389473C6.58682 0.393813 6.50477 0.415724 6.42987 0.45379C6.35497 0.491855 6.2889 0.545225 6.23594 0.610444L3.69194 3.15644L1.14194 0.610444C1.08898 0.545225 1.02292 0.491855 0.948019 0.45379C0.873123 0.415724 0.791067 0.393813 0.707165 0.389473C0.623262 0.385133 0.539383 0.398463 0.460959 0.428597C0.382535 0.458732 0.311315 0.505001 0.251907 0.564408C0.1925 0.623815 0.146231 0.695036 0.116096 0.77346C0.0859617 0.851884 0.0726332 0.935762 0.0769729 1.01966C0.0813127 1.10357 0.103225 1.18562 0.14129 1.26052C0.179355 1.33542 0.232725 1.40148 0.297944 1.45444L2.84294 3.99944L0.302944 6.54844C0.237725 6.6014 0.184354 6.66747 0.146289 6.74237C0.108224 6.81727 0.0863128 6.89932 0.0819731 6.98322C0.0776333 7.06713 0.0909618 7.151 0.121097 7.22943C0.151231 7.30785 0.1975 7.37907 0.256907 7.43848C0.316315 7.49789 0.387536 7.54416 0.46596 7.57429C0.544383 7.60442 0.628263 7.61776 0.712165 7.61342C0.796067 7.60908 0.878123 7.58716 0.953019 7.5491C1.02792 7.51103 1.09398 7.45766 1.14694 7.39244L3.69194 4.84744L6.24194 7.38844C6.2949 7.45366 6.36097 7.50703 6.43587 7.5451C6.51077 7.58316 6.59282 7.60508 6.67672 7.60942C6.76063 7.61376 6.8445 7.60043 6.92293 7.57029C7.00135 7.54016 7.07257 7.49389 7.13198 7.43448C7.19139 7.37507 7.23766 7.30385 7.26779 7.22543C7.29792 7.147 7.31125 7.06313 7.30692 6.97922C7.30258 6.89532 7.28066 6.81327 7.2426 6.73837C7.20453 6.66347 7.15116 6.5974 7.08594 6.54444L4.53494 3.99944Z" fill="white" />
                                </svg>
                            </span>
                        </span>
                    </div>
                </div>
                <div v-if="errors.training_role_ids" class="text-red-600 text-xs pt-2">{{ errors.training_role_ids }}</div>
            </div>
            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                <div class="sm:col-span-6 ">
                    <div class="flex">
                        <input type="checkbox" id="is_mandatory" class="rounded mr-3" v-model="is_mandatory" />
                        <label for="is_mandatory">This Task is Mandatory</label>
                    </div>
                </div>

            </div>
            <div class="block px-6 py-3 pb-4 airframe-files">
                <template v-if="docFiles.length > 0">
                    <!-- <RemoveDocuments :attachments="files" /> -->
                    <div class="block my-4 text-center">
                        <!-- <a @click.prevent="resetDocuments" class="jw-btn jw-btn__gray">Choose different Documents</a> -->
                    </div>

                    <div class="mb-2">
                        <div class="flex justify-start space-x-4 my-6 w-full">

                            <h2 class="mt-3">New Documents</h2>

                            <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                                <li v-for="file in docFiles" :key="file.id" class="flex flex-auto justify-between flex-a- p-3">
                                    <span class="attachment-details">
                                        <span class="attachment-icon inline-block w-4 h-4 mr-2">
                                            <IconPaperclip />
                                        </span>
                                        <span class="attachment-name">
                                            {{ file.name }}
                                        </span>
                                    </span>
                                    <span class="attachment-size">
                                        <!-- <span class="text-black mr-4">Remove</span> -->
                                        <span class="jw-text__red cursor-pointer" @click="deleteFile(file)">Remove</span>
                                    </span>

                                </li>
                            </ul>
                        </div>
                    </div>
                </template>

                <div class="text-center text-sm text-gray-600 mb-6 rounded-md border-2 border-dashed border-gray-300 px-6 pt-7 pb-8">

                    <span class="inline-block mx-auto mb-4 icon jw-svg-stroke__gray-light w-8 h-8">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="26" viewBox="0 0 17.232 18.197">
                            <path id="Icon_feather-paperclip" data-name="Icon feather-paperclip" d="M7.954,19.68A5.549,5.549,0,0,1,4.029,10.2l7.581-7.581a3.9,3.9,0,0,1,5.517,5.517L9.537,15.722a2.251,2.251,0,0,1-3.183-3.183l7-7a.6.6,0,0,1,.848.849l-7,6.995a1.051,1.051,0,0,0,1.486,1.486l7.589-7.581a2.7,2.7,0,0,0-3.82-3.82L4.877,11.053a4.352,4.352,0,0,0,6.155,6.155l7.581-7.581a.6.6,0,1,1,.849.849L11.88,18.057A5.535,5.535,0,0,1,7.954,19.68Z" transform="translate(-2.405 -1.483)" stroke="#d1d5db"></path>
                        </svg>
                    </span>
                    <label for="document-upload" class="relative cursor-pointer rounded-md font-medium text-gray-300">
                        <span class="">Add a document</span>
                        <br>
                        <br>
                        <span class="jw-btn jw-btn__blue">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                            </svg>
                            Add Document</span>
                    </label>
                    <!-- <input id="document-upload" name="files" @input="files = $event.target.files" class="sr-only" multiple="true" type="file" /> -->
                    <input id="document-upload" name="files" @change="handleFileChange" class="sr-only" multiple="true" type="file" />

                </div>

            </div>
            <div v-if="showSection === true" class="px-6 py-3">
                <div class="sm:flex-auto">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-5">Create Assesment</h2>
                </div>
                <div v-if="hasSection==true" class="block pb-4 airframe-files border border-gray-300 rounded-md bg-gray-100 px-6 py-3 shadow-sm ">
                    <div v-for="(input, index) in inputs" :key="index" class="py-3 last:pb-0">
                        <div class="mb-3">
                            <div class="flex items-center space-x-6 my-3">
                                <input class="grow" v-model="input.value" type="text" placeholder="Section Title...">
                                <span class="cursor-pointer" @click="removeInput(index)">
                                    <IconCross />
                                </span>
                            </div>
                            <div class="text-red-600 text-xs mt-2 mb-3">{{ input.sectionError}}</div>
                        </div>
                        <div class="text-red-600 text-center text-xs mb-3">{{ input.childSectionItemError}}</div>

                        <div v-if="showSectionItem === true" class="jw-form " id="sectionItem">
                            <div v-for="(sectionItem, innerindex) in input.sectionItems" :key="innerindex" class="relative px-6 py-3 mb-3 border border-gray-300 bg-white rounded-md overflow-hidden">
                                <div class="flex items-center space-x-4 flex-warap my-3 md:pr-6">
                                    <div class="w-auto">
                                        <label class="text-sm font-medium text-gray-900">Number</label>
                                        <input v-model="sectionItem.number" type="text" placeholder="Item Number...">
                                        <span class="text-red-600 text-xs mt-2">{{sectionItem.errorNumber}}</span>
                                    </div>
                                    <div class="w-full">
                                        <label>Title</label>
                                        <input v-model="sectionItem.value" type="text" placeholder="Section Item...">
                                        <span class="text-red-600 text-xs mt-2">{{sectionItem.errorValue}}</span>
                                    </div>
                                </div>
                                <div v-if="showSectionItemDescription === true" class="md:pr-6 mb-2">
                                    <div class="w-auto">
                                        <label>Description</label>
                                        <textarea v-model="sectionItem.description" placeholder="Description"></textarea>
                                        <span class="text-red-600 text-xs pt-2">{{sectionItem.errorDescription}}</span>
                                    </div>
                                </div>
                                <div class="text-end absolute top-4 right-4">
                                    <span @click="removeSectionItem(index,innerindex)" class="cursor-pointer pt-3">
                                        <IconCross />
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="text-center text-sm mt-5 mb-2">
                            <button @click="addSectionItemField(index)" type="button" class="cursor-pointer jw-btn jw-btn__blue focus:outline-0 focus:ring-0">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Add Item
                            </button>
                        </div>
                    </div>
                </div>
                <div class="mt-6 text-center text-sm text-gray-600 mb-6 rounded-md border-2 border-dashed border-gray-300 px-9 py-4">
                    <button @click="addInputField" type="button" class="cursor-pointer jw-btn jw-btn__blue focus:outline-0 focus:ring-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                        </svg>
                        Add Section
                    </button>
                </div>
                <div v-if="errors.sections" class="text-red-600 text-xs my-2">{{ errors.sections }}</div>
                <div class="text-red-600 text-xs my-2">{{errorEmptySection}}</div>
            </div>
            <div class="sm:flex sm:items-center bg-gray-lightest  p-4 overflow-hidden">
                <div class="sm:flex-auto">
                    <button @click="openCancelPopup()" type="button" class="jw-btn-md border-gray-300 text-gray-500 btn-cancel bg-white">Cancel</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                    <button class="jw-btn jw-btn__blue" type="button" @click="submitForm">Create Training Task</button>
                </div>
            </div>
        </div>
        <div v-if="cancelPopup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white flex justify-center px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="text-center">
                                <div class="mb-4 mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100">
                                    <svg aria-hidden="true" class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <div class="mx-auto max-w-[309px]">
                                    <h3 id="modal-title" class="mb-2 text-2xl font-semibold text-gray-900 text-center">Are you sure you wish to cancel ?</h3>
                                    <p class="text-center text-gray-500 text-base">All inputted data will be lost</p>
                                </div>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500">
                                        <slot name="default" />
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-lightest w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:px-6">
                            <button @click="closeCancelPopup()" class="w-full jw-btn border-gray-200 text-gray-500 bg-white" type="button">
                                Back
                            </button>
                            <a :href="route('trainings.index')" class="w-full jw-btn jw-btn__blue">Confirm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </template>
</AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import TextInput from '@/Components/InputLabel.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import DeleteDocuments from "@/Jetworks/Common/DeleteDocuments.vue";
import RemoveDocuments from "@/Jetworks/Common/RemoveDocuments.vue";
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";
import IconCross from "@/Jetworks/Icons/Cross.vue";
import {
    QuillEditor
} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';

import {
    Inertia
} from '@inertiajs/inertia';
import {
    useForm
} from '@inertiajs/inertia-vue3';

import {
    Userhelper
} from "@/Jetworks/Userhelper";

export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        TextInput,
        InputLabel,
        InputError,
        DeleteDocuments,
        RemoveDocuments,
        IconPaperclip,
        IconCross,
        QuillEditor,
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        user: {
            type: Object,
            default: {},
        },
        revalidation_types: {
            type: Object,
            default: {},
        },
        attachments: {
            type: Object,
            default: {},
        },
        validity_periods: {
            type: Object,
            default: {},
        },
        sectors: {
            type: Object,
            default: {},
        },
        training_roles: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Task',
        },
    },
    data() {
        return {
            inputs: [], // Initialize inputs array
            task_number: 0, // Initialize static field value
            title: '',
            short_name: '',
            external_id: '',
            revalidation_type: '',
            description: '',
            validity_number: '',
            validity_period: '',
            sector_id: '',
            training_role_ids: [],
            is_mandatory: '',
            files: [],
            docFiles: [],

            quill: null,
            file_to_delete: null,
            defaultSelected: true,
            hasSection: false,
            showSection: false,
            showSectionItem: false,
            showSectionItemDescription: false,
            isReadOnly: true,
            cancelPopup: false,
            errorEmptySection: '',

        };
    },
    setup(props) {
        let selected_roles = [];
        return {
            selected_roles,
        }
    },
    mounted() {
        // Update files array when component is mounted
        this.updateFiles();
    },
    watch: {
        files: {
            handler(newVal) {
                // Update files array when attachments prop changes
                this.updateFiles();
            },
            immediate: true, // Trigger handler immediately to update files initially
        },
    },
    methods: {
        // Method to add a new dynamic input field
        addInputField() {
            const newIndex = this.inputs.length + 1;
            this.inputs.push({
                name: `section_${newIndex}`,
                parentSectionId: newIndex,
                value: '',
                sectionError: '',
                childSectionItemError: '',
                sectionItems: [],
            });
            if (this.inputs.length >= 1) {
                this.hasSection = true;
            }
        },

        addSectionItemField(inputIndex) {
            console.log(inputIndex);
            const newSubIndex = this.inputs[inputIndex].sectionItems.length + 1;
            this.inputs[inputIndex].sectionItems.push({
                name: `section_${inputIndex}_sectionItem__${newSubIndex}`,
                value: '',
                errorValue: '',
                number: '',
                errorNumber: '',
                description: '',
                errorDescription: '',

            });
        },

        // Method to remove a dynamic input field by index
        removeInput(index) {
            console.log(index);
            this.inputs.splice(index, 1);
            if (this.inputs.length < 1) {
                this.hasSection = false;
            }
        },

        removeSectionItem(index, inputIndex) {
            this.inputs[index].sectionItems.splice(inputIndex, 1);
            // this.inputs[inputIndex].sectionItems.splice({
            //     name: `input${inputIndex}_sub${newSubIndex}`,
            //     value: '',
            //     number: '',
            // });
        },

        // Method to submit the form
        submitForm() {
            const modified_task_number = this.addLeadingZero(this.task_number);
            // Combine static field value and dynamic input values into one object
            const formData = {
                task_number: modified_task_number,
                title: this.title,
                short_name: this.short_name,
                external_id: this.external_id,
                revalidation_type: this.revalidation_type,
                description: this.description,
                validity_number: this.validity_number,
                validity_period: this.validity_period,
                sector_id: this.sector_id,
                training_role_ids: this.training_role_ids,
                is_mandatory: this.is_mandatory,
                files: this.docFiles,
                sections: this.inputs.map(input => input.value),
                sectionItems: this.inputs.map(input => input),
            };

            let revalidation_type = this.revalidation_type;
            let submitStatus = true;

            if (revalidation_type == 1 || revalidation_type == 2) {

                const sectionItemsInputs = this.inputs;

                if (sectionItemsInputs.length != 0) {
                    this.errorEmptySection = '';

                    for (let index = 0; index < sectionItemsInputs.length; index++) {
                        const element = sectionItemsInputs[index];

                        if (element.value == '') {
                            element.sectionError = 'Please Enter Section Title';
                            submitStatus = false;
                        } else {
                            element.sectionError = '';
                        }

                        if (element.sectionItems.length == 0) {
                            element.childSectionItemError = 'Please Add Item to Section';
                            submitStatus = false;
                        } else {
                            element.childSectionItemError = '';
                            for (let innerIndex = 0; innerIndex < element.sectionItems.length; innerIndex++) {
                                const childElement = element.sectionItems[innerIndex];
                                if (childElement.number == '') {
                                    childElement.errorNumber = 'Please Enter  Item Number';
                                    submitStatus = false;
                                } else {
                                    childElement.errorNumber = '';
                                }

                                if (childElement.value == '') {
                                    childElement.errorValue = 'Please Enter  Item Title';
                                    submitStatus = false;
                                } else {
                                    childElement.errorValue = '';
                                }

                                if (revalidation_type == 1) {
                                    if (childElement.description == '') {
                                        childElement.errorDescription = 'Please Enter Item Description ';
                                        submitStatus = false;
                                    } else {
                                        childElement.errorDescription = '';
                                    }
                                }
                            }
                        }
                    }

                } else {
                    submitStatus = false;
                    this.errorEmptySection = 'Please Add a Section ';

                }

            }

            // const sectionItemsInputs = this.inputs;

            // for (let index = 0; index < sectionItemsInputs.length; index++) {
            //     const element = sectionItemsInputs[index];
            //     if (element.value == '') {
            //         element.sectionError = 'Please Enter Section Title';
            //         submitStatus = false;
            //     } else {
            //         element.sectionError = '';
            //     }

            //     if (element.sectionItems.length == 0) {
            //         element.childSectionItemError = 'Please Add Item to Section';
            //           submitStatus = false;
            //     } else {
            //         element.childSectionItemError = '';
            //         for (let innerIndex = 0; innerIndex < element.sectionItems.length; innerIndex++) {
            //             const childElement = element.sectionItems[innerIndex];
            //             if (childElement.number == '') {
            //                 childElement.errorNumber = 'Please Enter  Item Number';
            //                   submitStatus = false;
            //             } else {
            //                 childElement.errorNumber = '';
            //             }

            //             if (childElement.value == '') {
            //                 childElement.errorValue = 'Please Enter  Item Title';
            //                   submitStatus = false;
            //             } else {
            //                 childElement.errorValue = '';
            //             }

            //             if (childElement.description == '') {
            //                 childElement.errorDescription = 'Please Enter Item Description ';
            //                   submitStatus = false;
            //             } else {
            //                 childElement.errorDescription = '';
            //             }
            //         }
            //     }
            //     // console.log(element.sectionItems.length);

            //     //    childSectionItemError        
            // }
            //     // alert(submitStatus);
            if (submitStatus == true) {
                // alert('submitted');
                Inertia.post(route('tasks.store'), formData);
                // console.log(formData);

            }

            // this.appendData();
            // console.log(formData);
            // console.log('ok');

            // Inertia.post(route('tasks.store'), formData);
        },

        resetDocuments: function () {
            this.files = [];
        },

        //     appendData: function () {
        //     //     // console.log('test');
        // //    rawdata = [];

        //         this.inputs.forEach(input => {
        //             console.log(input);
        //             // input.sectionItems.forEach(sectionItem => {
        //             //     this.formData.sectionItem.push(sectionItem.value);
        //             // });
        //         });
        //     },

        selectedRevalidation() {
            let revalidation_type = this.revalidation_type;
            if (revalidation_type === 1 || revalidation_type === 2) {
                this.showSection = true;
            } else {
                this.showSection = false;
            }
            if (revalidation_type === 1 || revalidation_type === 2) {
                this.showSectionItem = true;
            } else {
                this.showSectionItem = false;
            }

            if (revalidation_type === 1) {
                this.showSectionItemDescription = true;
            } else {
                this.showSectionItemDescription = false;
            }
        },

        roleChanged: function (event) {
            let selectedroleId = parseInt(event.target.value);
            let already = this.selected_roles.find(element => element.id == selectedroleId);
            if (already === undefined) {
                for (let i = 0; i < this.training_roles.data.length; i++) {
                    if (selectedroleId == this.training_roles.data[i].id) {
                        this.selected_roles.push(this.training_roles.data[i]);
                        this.training_role_ids.push(selectedroleId);
                        break;
                    }
                }
            }
        },

        removeRole: function (role, event) {
            this.selected_roles = this.selected_roles.filter((element, index) => {
                return role.id != element.id;
            });
            this.training_role_ids = this.training_role_ids.filter(id => {
                return role.id != id;
            });
        },

        addLeadingZero(task) {
            task = task.toString();
            if (task.length < 2) {
                if (task == 0) {
                    task = '';
                } else {
                    task = '0' + task;
                }
            }
            return task;
        },
        openCancelPopup() {
            this.cancelPopup = true;
        },

        closeCancelPopup() {
            this.cancelPopup = false;
        },

        updateFiles() {
            // Convert attachments object values to array and assign to files
            this.docFiles = Object.values(this.files);
        },

        deleteFile(fileToDelete) {
            this.file_to_delete = fileToDelete;
            // console.log(this.attachments);
            console.log('before : ', this.docFiles);
            // Filter out the file to be deleted
            this.docFiles = this.docFiles.filter(file => file !== this.file_to_delete);
            console.log('after : ', this.docFiles);

        },

        handleFileChange(event) {
            // alert('f');
            // Get the selected files from the input element
            const files = event.target.files;
            // // Convert the FileList object to an array and assign it to 'files' data property
            for (let i = 0; i < files.length; i++) {
                this.docFiles.push(files[i]);
            }
        }

    },

};
</script>
