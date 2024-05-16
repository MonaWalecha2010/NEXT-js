<template>
<Head :title="`Create ${pageHeader}`" />

<AuthenticatedLayout :can="can">
    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :title="'Add a Notification'" />
    </template>

    <template v-slot:default>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Add a Notification</h1>
            </div>
        </div>
        <div class="mt-6 bg-white overflow-hidden shadow-sm jw-form rounded-md">
            <div class="my-6 px-6 py-3 ">
                <div class="mb-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-5 ">
                        <label for="title">Notification Title </label>
                        <input id="title" v-model="form.title" class="block w-full" type="text" />
                        <div v-if="errors.title" class="text-red-500 text-xs pt-2">{{ errors.title }}</div>
                    </div>
                    <div class="sm:col-span-1">
                        <label class="sm:text-center" for="active">Active</label>
                        <div class="w-full flex items-center">
                            <label class="jw-toggle justify-center items-center mt-3 cursor-pointer">
                                <input type="checkbox" id="active" v-model="form.active" value="" class="sr-only peer">
                                <div class="relative w-10 h-[.875rem] bg-gray-300 peer-focus:outline-none peer-focus:ring-0 peer-focus:ring-blue-100 dark:peer-focus:ring-blue-200 rounded-full peer dark:bg-gray-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-blue-500 after:content-[''] after:absolute after:top-[-3px] after:left-0 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[1.25rem] after:w-[1.25rem] after:transition-all dark:border-gray-600 peer-checked:bg-blue-500"></div>
                            </label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 grid grid-cols-1 gap-y-3 gap-x-4 sm:grid-cols-6">

                    <div class="sm:col-span-6 ">
                        <label for="notification_sectors">Sectors</label>
                        <select @change="sectorChanged" id="validity_period" class="" name="show">
                            <option disabled value="" :selected="defaultSelected">Please select...</option>
                            <option v-for="(sector,index) in sectors.data" :key="index" :value="sector.id">{{sector.title}}</option>
                        </select>
                        <!-- <div v-if="errors.title" class="text-red-500 text-xs pt-2">{{ errors.title }}</div> -->

                    </div>

                    <div class="sm:col-span-6 flex">
                        <div v-if="notification_sectors" v-show="notification_sectors.length > 0" class="flex grow-0 gap-2 mt-4 user-airframes selected">
                            <span v-for="sector in this.selected_sectors" @click.prevent="removeSector( sector )" class="justify-center jw-pill jw-bg__blue relative text-white text-xs font-medium	">                            
                                <span class="ml-1">{{sector.title}}</span>
                                <span class="ml-4 cursor-pointer">x</span>
                            </span>
                        </div>
                    </div>

                </div>
                <div class="mb-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6 ">
                        <label for="content">Notification Content </label>
                        <QuillEditor ref="quill" v-model:content="form.content" content="html" contentType="html" theme="snow" />
                    </div>
                    <!-- <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="description">Description</label>
                        <QuillEditor ref="quill" v-model:content="form.content" content="html" contentType="html" theme="snow" toolbar="essential" /> -->
                    <!-- <div v-if="errors.title" class="text-red-500 text-xs pt-2">{{ errors.title }}</div> -->
                    <!-- </div> -->
                    <div v-if="errors.content" class="text-red-500 text-xs pt-2">{{ errors.content }}</div>
                </div>
                <div class="flex sm:items-center mt-6 mb-4">
                    <div class="sm:flex-auto">
                        <h4 class="text-xl font-semibold text-gray-900">Notification Settings</h4>
                    </div>
                </div>
                <div class="mb-3 grid grid-cols-1 gap-y-3 gap-x-2 sm:grid-cols-6">                   
                    <div class="sm:col-span-6 flex items-center flex-wrap space-x-2">
                        <div class="sm:w-[12%]">
                            <label for="name">Start Notifying</label>
                        </div>
                        <div class="sm:w-[8%]">
                            <input id="validity_number" v-model="form.start_number" class="w-full" type="text" />
                            <div v-if="errors.start_number" class="text-red-500 text-xs pt-2">{{ errors.start_number }}</div>
                        </div>
                        <div class="sm:w-[30%]">
                            <select id="validity_period" v-model="form.start_period" class="w-full" name="show">
                                <option disabled value="" :selected="defaultSelected">Select Start Period</option>
                                <option v-for="(period,index) in periods" :key="index" :value="period">{{period}}</option>
                            </select>
                            <div v-if="errors.start_period" class="text-red-500 text-xs pt-2">{{ errors.start_period }}</div>
                        </div>
                        <div class="sm:w-[30%]">
                            <select id="validity_period" v-model="form.start_scope" class="w-full" name="show">
                                <option disabled value="" :selected="defaultSelected">Select Start Time</option>
                                <option v-for="(scope,index) in scopes" :key="index" :value="scope">{{scope}}</option>
                            </select>
                            <div v-if="errors.start_scope" class="text-red-500 text-xs pt-2">{{ errors.start_scope }}</div>
                        </div>
                        <div class="sm:w-[16%]">
                            <label class="pl-2 py-2" for="name">the task expires</label>
                        </div>
                    </div>                    
                </div>
                <div class="mb-3 grid grid-cols-1 gap-y-6 gap-x-2 sm:grid-cols-6">
                    <div class="sm:col-span-6 flex items-center flex-wrap space-x-2"> 
                        <div class="sm:w-[12%]">
                            <label class="py-2" for="name">Stop Notifying</label>
                        </div>
                        <div class="sm:w-[8%]">
                            <input id="validity_number" v-model="form.stop_number" class="w-full" type="text" />
                            <div v-if="errors.stop_number" class="text-red-500 text-xs pt-2">{{ errors.stop_number }}</div>
                        </div>
                        <div class="sm:w-[30%]">
                            <select id="validity_period" v-model="form.stop_period" class="w-full" name="show">
                                <option disabled value="" :selected="defaultSelected">Select Stop Period</option>
                                <option v-for="(period,index) in periods" :key="index" :value="period">{{period}}</option>
                            </select>
                            <div v-if="errors.stop_period" class="text-red-500 text-xs pt-2">{{ errors.stop_period }}</div>

                        </div>
                        <div class="sm:w-[30%]">
                            <select id="stop_scope" v-model="form.stop_scope" class="w-full" name="show">
                                <option disabled value="" :selected="defaultSelected">Select Stop Scope</option>
                                <option v-for="(scope,index) in scopes" :key="index" :value="scope">{{scope}}</option>
                            </select>
                            <div v-if="errors.stop_scope" class="text-red-500 text-xs pt-2">{{ errors.stop_scope }}</div>
                        </div>
                        <div class="sm:w-[16%]">
                            <label class="pl-2 py-2" for="name">the task expires</label>
                        </div>
                    </div>
                </div>
                <div class="mb-3 grid grid-cols-1 gap-y-6 gap-x-2 sm:grid-cols-6">
                    <div class="sm:col-span-6 flex items-center flex-wrap space-x-2"> 
                        <div class="sm:w-[12%]">
                            <label class="py-2" for="name">Repeat Every</label>
                        </div>
                        <div class="sm:w-[8%]">
                            <input id="validity_number" v-model="form.repeat_number" class="w-full" type="text" />
                            <div v-if="errors.repeat_number" class="text-red-500 text-xs pt-2">{{ errors.repeat_number }}</div>
                        </div>
                        <div class="sm:w-[60.5%]">
                            <select id="validity_period" v-model="form.repeat_period" class="w-full" name="show">
                                <option disabled value="" :selected="defaultSelected">Select Repeat Period</option>
                                <option v-for="(period,index) in periods" :key="index" :value="period">{{period}}</option>
                            </select>
                            <div v-if="errors.repeat_period" class="text-red-500 text-xs pt-2">{{ errors.repeat_period }}</div>
                        </div>
                        <div class="sm:w-[16.5%]">
                            <label class="pl-2 py-2" for="name">until the stop date</label>
                        </div>
                    </div>
                </div>
                <div class="my-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6 ">
                        <div class="flex">
                            <input type="checkbox" id="for_mandatory" class="rounded mr-3" v-model="form.for_mandatory" />
                            <label for="for_mandatory">Mandatory Tasks Only</label>
                        </div>
                    </div>
                </div>
            </div>
            <div class="sm:flex sm:items-center bg-gray-lightest  p-4 overflow-hidden">
                <div class="sm:flex-auto">
                    <button @click="openCancelPopup()" type="submit" class="jw-btn-md border-gray-300 text-gray-500 btn-cancel bg-white">Cancel</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                    <button class="jw-btn-md jw-btn__blue" type="button" @click="submitForm">Create Notification</button>
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
import { ref } from 'vue';


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
        sectors: {
            type: Object,
            default: {},
        },
        periods: {
            type: Object,
            default: {},
        },
        scopes: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Notifcation',
        },
    },
    data() {
        return {
            // Form Fields ---------
            // title: '',
            // active: '',
            // notification_sectors: [],
            // content: '',
            // start_number: '',
            // start_period: '',
            // start_scope: '',
            // stop_number: '',
            // stop_period: '',
            // stop_scope: '',
            // repeat_number: '',
            // repeat_period: '',
            // for_mandatory: '',
            // Form Fields ---------

            defaultSelected: true,
            cancelPopup: false,
            quill: null,
        };
    },
    setup(props) {

        let selected_sectors = [];
        const notification_sectors = ref([]); 
        const form = useForm({
            title: '',
            active: '',
            content: '',
            notification_sectors: notification_sectors.value,
            start_number: '',
            start_period: '',
            start_scope: '',
            stop_number: '',
            stop_period: '',
            stop_scope: '',
            repeat_number: '',
            repeat_period: '',
            for_mandatory: '',
        });

        function submitForm() {
           Inertia.post(route('notifications.store'), form);
            // console.log(notification_sectors);
        }

        return {
            form,
            submitForm,
            selected_sectors,
            notification_sectors,
        }
    },
    methods: {
        // submitForm() {

        //     const formData = {
        //         title: this.title,
        //         active: this.active,
        //         notification_sectors: this.notification_sectors,
        //         content: this.content,
        //         start_number: this.start_number,
        //         start_period: this.start_period,
        //         start_scope: this.start_scope,
        //         stop_number: this.stop_number,
        //         stop_period: this.stop_period,
        //         stop_scope: this.stop_scope,
        //         repeat_number: this.repeat_number,
        //         repeat_period: this.repeat_period,
        //         for_mandatory: this.for_mandatory,
        //     };

        //     // console.log(formData);
        //     Inertia.post(route('notifications.store'), formData);
        // },

        sectorChanged: function (event) {
            let selectedSectorId = parseInt(event.target.value);
            // console.log(this.selected_sectors);
            let already = this.selected_sectors.find(element => element.id == selectedSectorId);
            if (already === undefined) {
                for (let i = 0; i < this.sectors.data.length; i++) {
                    if (selectedSectorId == this.sectors.data[i].id) {
                        this.selected_sectors.push(this.sectors.data[i]);
                        this.notification_sectors.push(selectedSectorId);
                        break;
                    }
                }
            }
        },

        removeSector: function (sector, event) {
            this.selected_sectors = this.selected_sectors.filter((element, index) => {
                return sector.id != element.id;
            });
            this.notification_sectors = this.notification_sectors.filter(id => {
                return sector.id != id;
            });
        },

        openCancelPopup() {
            this.cancelPopup = true;
            // console.log(this.cancelPopup);
        },

        closeCancelPopup() {
            this.cancelPopup = false;
            // console.log(this.deletePopup);
        },

    },

};
</script>
