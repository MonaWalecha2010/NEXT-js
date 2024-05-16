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
                <h1 class="text-3xl font-semibold text-gray-900">Edit a Training Task</h1>
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
                </div>
                <div class="sm:col-span-2 ">
                    <label for="short_name">Short Name </label>
                    <input id="short_name" v-model="short_name" class="block w-full" type="text" />
                </div>
            </div>
            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-3 mb-5">
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
                    </div>
                </div>
                <div class="sm:col-span-3 exclude-toolbar small">
                    <label for="description">Task Description (Optional) </label>
                    <QuillEditor ref="quill" v-model:content="description" content="html" contentType="html" theme="snow" toolbar="#exclude-toolbar" />
                    <span id="exclude-toolbar" class="hidden"></span>
                    <!-- <textarea id="description" v-model="description" rows="5" class="block w-full"></textarea> -->
                </div>
            </div>
            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-2 place-self-end w-full flex items-end justify-between gap-x-3">
                    <div class="inline-block w-[30%]">
                        <label for="name">Validity</label>
                        <input id="validity_number" v-model="validity_number" class="block w-full" type="text" />
                    </div>
                    <div class="inline-block w-[70%] mx-auto">
                        <!-- <label for="name">Validity</label> -->
                        <select id="validity_period" v-model="validity_period" class="" name="show">
                            <option v-for="(validity_period,index) in validity_periods" :key="index" :value="validity_period">{{validity_period}}</option>
                        </select>
                    </div>
                </div>
                <div class="sm:col-span-4 ">
                    <label for="sector_id">Training Sector</label>
                    <select id="sector_id" v-model="sector_id" class="" name="show">
                        <option disabled value="" :selected="defaultSelected">Select a Training Sector</option>
                        <option v-for="(sector,index) in sectors.data" :key="index" :value="sector.id">{{sector.title}}</option>
                    </select>
                </div>
            </div>

            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-6 ">
                    <label for="notification_sectors">Training Roles</label>
                    <select @change="roleChanged" id="training_roles" class="" name="show">
                        <option selected="true" disabled>Choose Training Roles</option>

                        <option v-for="role in training_roles.data" :key="role.id" :value="role.id">
                            {{ role.name }}
                        </option>
                    </select>
                </div>
                <div class="sm:col-span-6 flex">
                    <div v-if="training_role_ids" v-show="training_role_ids.length > 0" class="flex grow-0 gap-2 mt-4 user-airframes selected">
                        <span v-for="role in this.selected_training_roles" @click.prevent="removeRole( role )" class="justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">
                            <span class="icon icon__white w-3 h-3">
                                <IconPlane /></span>
                            <span class="ml-1">{{role.name}}</span>
                            <span class="ml-4 cursor-pointer">x</span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="mt-2 px-6 py-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                <div class="sm:col-span-6 ">
                    <div class="flex">
                        <input type="checkbox" :checked="task.is_mandatory" class="rounded mr-3" v-model="is_mandatory" />
                        <label for="is_mandatory">This Task is Mandatory</label>
                    </div>
                </div>

            </div>

            <DeleteDocuments :attachments="attachments" modalText="Tasks" />

            <div class="px-6 pb-4 airframe-files">

                <template v-if="docFiles.length > 0">
                    <div class="block my-4 text-center">
                    </div>

                    <div class="mb-2">
                        <div class="flex justify-start space-x-4 mb-6 w-full">

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
                                        <span class="jw-text__red cursor-pointer" @click="deleteFile(file)">Remove</span>
                                    </span>

                                </li>
                            </ul>
                        </div>
                    </div>
                </template>

                <div class="text-center text-sm text-gray-600 mb-6 rounded-md border-2 border-dashed border-gray-300 px-6 pt-7 pb-8">

                    <span class="inline-block mx-auto mb-4 icon jw-svg-stroke__gray-light w-8 h-8">
                        <IconPaperclip /></span>
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
                    <input id="document-upload" name="files" @change="handleFileChange" class="sr-only" multiple="true" type="file" />

                </div>

                <!-- {{files}} -->
                <!-- <template v-if="files.length > 0">
                    <Documents :attachments="files" />
                    <div class="block my-4 text-center">
                        <a @click.prevent="resetDocuments" class="jw-btn jw-btn__gray">Choose different Documents</a>
                    </div>
                </template> -->

                <!-- <div v-else class="text-center text-sm text-gray-600 mb-6 rounded-md border-2 border-dashed border-gray-300 px-6 pt-7 pb-8">

                    <span class="inline-block mx-auto mb-4 icon jw-svg-stroke__gray-light w-8 h-8">
                        <IconPaperclip /></span>
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
                    <input id="document-upload" name="files" @input="files = $event.target.files" class="sr-only" multiple="true" type="file" />
                </div> -->

            </div>

            <div v-if="revalidation_type != 0" class="px-6 py-3">
                <div class="sm:flex-auto">
                    <h2 class="text-2xl font-semibold text-gray-900 mb-5">Create Assesment</h2>
                </div>
                <div v-if="hasSection==true" class="block pb-4 airframe-files border border-gray-300 rounded-md bg-gray-100 px-6 py-3 shadow-sm">
                    <div v-for="(input, index) in inputs" :key="index" class="py-3 last:pb-0">
                        <div class="mb-3">
                            <div class="flex items-center space-x-6 my-3">
                                <input v-model="input.value" type="text" placeholder="Section Title...">
                                <span class="cursor-pointer" @click="removeInput(index)">
                                    <IconCross />
                                </span>
                            </div>
                            <div class="text-red-600 text-xs mb-3">{{ input.sectionError}}</div>
                        </div>
                        <div class="text-red-600 text-center text-xs mb-3">{{ input.childSectionItemError}}</div>

                        <div v-if="showSectionItem === true" class="jw-form " id="sectionItem">
                            <div v-for="(sectionItem, innerindex) in input.sectionItems" :key="innerindex" class="relative px-6 py-3 mb-3 border border-gray-300 bg-white rounded-md overflow-hidden">
                                <div class="flex items-center space-x-4 flex-warap my-3 md:pr-6">
                                    <div class="w-auto">
                                        <label>Number</label>
                                        <input v-model="sectionItem.number" type="text" placeholder="Item Number...">
                                        <span class="text-red-600 text-xs pt-2">{{sectionItem.errorNumber}}</span>
                                    </div>
                                    <div class="w-full">
                                        <label>Title</label>
                                        <input v-model="sectionItem.value" type="text" placeholder="Section Item...">
                                        <span class="text-red-600 text-xs pt-2">{{sectionItem.errorValue}}</span>
                                    </div>
                                </div>
                                <div v-if="showSectionItemDescription === true" class="md:pr-6 mb-2">
                                    <span class="w-auto">
                                        <label>Description</label>
                                        <textarea v-model="sectionItem.description" placeholder="Description"></textarea>
                                        <span class="text-red-600 text-xs pt-2">{{sectionItem.errorDescription}}</span>
                                    </span>
                                </div>
                                <div class="text-end absolute top-4 right-4">
                                    <span @click="removeSectionItem(index,innerindex)" class="cursor-pointer">
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
            <div class="sm:flex sm:items-center bg-gray-lightest p-4 overflow-hidden">
                <div class="sm:flex-auto">
                    <a @click="openCancelPopup()" class="jw-btn-md border-gray-300 text-gray-500 btn-cancel bg-white">Cancel</a>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                    <!-- <a class="jw-btn jw-btn__red" @click="deleteTask">Delete Training Task</a> -->
                    <template v-if="task.task_status.length > 0">
                        <button class="jw-btn jw-btn__blue disabled:opacity-50" type="button" disabled>Save Training Task</button>
                        <button class="jw-btn jw-btn__red disabled:opacity-50" type="button" disabled>Delete Training Task</button>
                    </template>
                    <template v-else>
                        <button class="jw-btn jw-btn__blue" type="button" @click="submitForm">Save Training Task</button>
                        <a class="jw-btn jw-btn__red" @click="confirmDelete()">Delete Training Task</a>
                    </template>

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
                                <h3 id="modal-title" class="mb-2 text-2xl font-semibold text-gray-900 text-center">Are you sure you wish to cancel ?</h3>
                                <p class="text-center text-gray-500 text-base">All inputted data will be lost</p>
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

        <div v-if="deletePopup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white flex justify-center px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="text-center">
                                <div class="mb-4 mx-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-red-100 sm:h-10 sm:w-10">
                                    <svg aria-hidden="true" class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>
                                <h3 id="modal-title" class="mb-2 text-2xl font-semibold text-gray-900 text-center">Delete Training Task ?</h3>
                                <p class="mx-1 text-center text-gray-500 px-16 text-base">Please Confirm that You would like to delete this Training Task</p>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500">
                                        <slot name="default" />
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-lightest w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:flex-row-reverse sm:px-6">
                            <button @click="deleteTask()" class="w-full jw-btn jw-btn__red" type="button">
                                Delete
                            </button>
                            <button @click="closeDeletePopup()" class="w-full jw-btn border-gray-200 text-gray-500 bg-white" type="button">
                                Cancel
                            </button>
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
import Documents from "@/Jetworks/Common/Documents.vue";
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
        Documents,
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
        validity_periods: {
            type: Object,
            default: {},
        },
        sectors: {
            type: Object,
            default: {},
        },
        attachments: {
            type: Object,
            default: {},
        },
        training_roles: {
            type: Object,
            default: {},
        },
        task: {
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
    data(props) {
        return {
            inputs: [],
            id: props.task.id, // Initialize static field value
            task_number: props.task.task_number, // Initialize static field value
            title: props.task.title,
            short_name: props.task.short_name,
            external_id: props.task.external_id,
            revalidation_type: props.task.revalidation_type,
            description: props.task.description,
            validity_number: props.task.validity_number,
            validity_period: props.task.validity_period,
            sector_id: props.task.sector_id.id,
            training_role_ids: [],
            // is_mandatory: props.task.is_mandatory,
            files: [],
            docFiles: [],

            quill: null,
            file_to_delete: null,
            selectedroles: props.task.roles,
            defaultSelected: true,
            hasSection: false,
            showSection: false,
            showSectionItem: false,
            defaultCheked: false,
            showSectionItemDescription: false,
            deletePopup: false,
            cancelPopup: false,
            errorEmptySection: '',

        };
    },
    setup(props) {
        let selected_training_roles = [];
        return {
            selected_training_roles,
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
                name: `input${newIndex}`,
                value: '',
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
                name: `input${inputIndex}_sub${newSubIndex}`,
                value: '',
                number: '',
                description: '',
            });
        },

        appendSectionTitles() {
            let revalidation_type = this.task.revalidation_type;
            if (revalidation_type != 0) {
                this.hasSection = true;
                this.showSection = true;
                this.showSectionItem = true;

                if (revalidation_type == 1) {
                    this.showSectionItemDescription = true;
                } else {
                    this.showSectionItemDescription = false;
                }

                let assesments = this.task.assesments;
                if (assesments.length >= 1) {
                    for (let index = 0; index < assesments.length; index++) {
                        const element = assesments[index].title;
                        this.inputs.push({
                            section_id: assesments[index].id,
                            name: `input${index}`,
                            value: element,
                            sectionError: '',
                            childSectionItemError: '',
                            sectionItems: [],
                        });

                        let assesment_checklists = assesments[index].assesment_checklist;

                        if (assesment_checklists.length >= 1) {
                            for (let innerindex = 0; innerindex < assesment_checklists.length; innerindex++) {
                                const value = assesment_checklists[innerindex].check_value;
                                const number = assesment_checklists[innerindex].asigned_check_num;
                                const description = assesment_checklists[innerindex].check_description;

                                this.inputs[index].sectionItems.push({
                                    section_item_id: assesment_checklists[innerindex].id,
                                    name: `input${index}_sub${innerindex}`,
                                    value: value,
                                    errorValue: '',
                                    number: number,
                                    errorNumber: '',
                                    description: description,
                                    errorDescription: '',

                                });
                            }
                        }
                        // console.log(this.inputs[index]);
                    }
                }
            }
        },

        // appendSectionTitlesWithItems() {
        //     let revalidation_type = this.task.revalidation_type;
        //     if (revalidation_type == 2) {
        //         let assesments = this.task.assesments;

        //         if (assesments.length >= 1) {
        //             for (let index = 0; index < assesments.length; index++) {
        //                 const element = assesments[index].title;
        //                 this.inputs.push({
        //                     name: `input${index}`,
        //                     value: element
        //                 });

        //                 let assesments_checklist = this.task.assesments_checklist.filter(element => element.assesment_id == assesments[index].id);
        //                 console.log(assesments_checklist);
        //                 for (let innerindex = 0; innerindex < assesments_checklist.length; innerindex++) {
        //                     const asigned_check_num = assesments_checklist[innerindex].asigned_check_num;
        //                     const check_value = assesments_checklist[innerindex].check_value;

        //                     this.inputs.push({
        //                         name: `input${innerindex}`,
        //                         value: asigned_check_num
        //                     });
        //                     this.inputs.push({
        //                         name: `input${innerindex}`,
        //                         value: check_value
        //                     });
        //                 }
        //             }
        //         }
        //     }
        // },

        // Method to remove a dynamic input field by index
        removeInput(index) {
            this.inputs.splice(index, 1);
            if (this.inputs.length < 1) {
                this.hasSection = false;
            }
        },

        // confirmDelete() {
        //     this.deletePopup = true;
        //     console.log(this.deletePopup);
        // },

        // closeDeletePopup() {
        //     this.deletePopup = false;
        //     console.log(this.deletePopup);
        // },

        removeSectionItem(index, inputIndex) {
            this.inputs[index].sectionItems.splice(inputIndex, 1);
            // this.inputs[inputIndex].sectionItems.splice({
            //     name: `input${inputIndex}_sub${newSubIndex}`,
            //     value: '',
            //     number: '',
            // });
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
        },

        // Method to submit the form
        submitForm() {
            const modified_task_number = this.addLeadingZero(this.task_number);
            // Combine static field value and dynamic input values into one object
            const formData = {
                id: this.id,
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
            // Log form data to the console for debugging
            // console.log('Form submitted with data:', formData);
            // Inertia.put(route('tasks.update', {
            //     task: this.id
            // }), formData);

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
                    // this.errorEmptySection = 'Please Add a Section ';

                }

            }

            if (submitStatus == true) {
                //  alert('all done');

                // Inertia.put(route('tasks.update', {
                //     task: this.id
                // }), formData);

                Inertia.post(route('tasks.updateFile'), formData);

            }

        },

        roleChanged: function (event) {
            let selectedRoleId = parseInt(event.target.value);
            console.log(selectedRoleId);
            let already = this.selected_training_roles.find(element => element.id == selectedRoleId);
            if (already === undefined) {
                for (let i = 0; i < this.training_roles.data.length; i++) {
                    if (selectedRoleId == this.training_roles.data[i].id) {
                        this.selected_training_roles.push(this.training_roles.data[i]);
                        this.training_role_ids.push(selectedRoleId);
                        break;
                    }
                }
            }
        },

        selectedTrainingRoles: function () {
            let selectedRoleId = this.task.roles;
            const filteredArray = selectedRoleId.filter(item => item);

            let sel_training_role_ids = [];
            filteredArray.forEach(element => {
                sel_training_role_ids.push(element.id)
            });

            for (let index = 0; index < sel_training_role_ids.length; index++) {
                const selectedRoleId = sel_training_role_ids[index];
                for (let i = 0; i < this.training_roles.data.length; i++) {
                    if (selectedRoleId == this.training_roles.data[i].id) {
                        this.selected_training_roles.push(this.training_roles.data[i]);
                        this.training_role_ids.push(selectedRoleId);
                        break;
                    }
                }

            }
        },

        removeRole: function (role, event) {
            this.selected_training_roles = this.selected_training_roles.filter((element, index) => {
                return role.id != element.id;
            });
            this.training_role_ids = this.training_role_ids.filter(id => {
                return role.id != id;
            });
        },

        deleteTask: function () {
            // axios.delete(route('tasks.destroy', this.id))
            //     .then((response) => {
            //         if (response.data.success === true) {
            //             console.log('Task Deleted');
            //             Inertia.get(route('trainings.index'));
            //         }
            //     }).catch(error => {
            //         console.log(error.response.status);
            //         console.log(error.response.data.message);
            //     });

            Inertia.delete(route('tasks.destroy', {
                task: this.id
            }));
        },

        resetDocuments: function () {
            this.files = [];
        },

        selectedRevalidation() {
            let revalidation_type = this.revalidation_type;
            if (revalidation_type === 1 || revalidation_type === 2) {
                this.showSection = true;

            } else {
                this.showSection = false;
            }

            if (revalidation_type == 1) {
                this.showSectionItemDescription = true;
            } else {
                this.showSectionItemDescription = false;
            }

        },

        isRoleSelected(roleId) {
            const selectedRoleIds = this.selectedroles.map(role => role.id);
            const selectedR = selectedRoleIds.includes(roleId);
            if (selectedR == true) {
                return checked;
            }
        },
        addLeadingZero(order_number) {
            order_number = order_number.toString();
            if (order_number.length < 2) {
                if (order_number == 0) {
                    order_number = '';
                } else {
                    order_number = '0' + order_number;
                }
            }
            return order_number;
        },

        openCancelPopup() {
            this.cancelPopup = true;
        },

        closeCancelPopup() {
            this.cancelPopup = false;
        },

        confirmDelete() {
            this.deletePopup = true;
        },

        closeDeletePopup() {
            this.deletePopup = false;
        },
        updateFiles() {
            // Convert attachments object values to array and assign to files
            this.docFiles = Object.values(this.files);
        },

    },
    beforeMount() {
        this.appendSectionTitles();
        this.selectedTrainingRoles();
        // this.appendSectionTitlesWithItems();

    },
};
</script>
