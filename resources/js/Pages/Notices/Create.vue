<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('notices.index')" :title="pageHeader" />
        <BreadcrumbItem :title="'Add a Notice'" />
    </template>
    <template v-slot:default>
        <div class="sm:flex justify-between sm:items-center traning_tabs">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Add a Notice</h1>
            </div>
        </div>
        <div class="jw-form border border-gray-300 rounded-lg overflow-hidden shadow-sm mt-6">
            <div class="p-6 bg-white overflow-hidden">
                <div class="mt-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="name">Notice Title </label>
                        <input id="title" v-model="title" class="block w-full" type="text" placeholder="Title" />
                        <div v-if="errors.title" class="text-red-500 text-xs pt-2">{{ errors.title }}</div>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="w-full mt-2 mb-2 text-end"><span class="text-xs text-end border text-gray-900 hover:text-blue-300 border-gray-300 font-medium hover:border-blue-300 rounded-full px-4 py-1 " @click="openNewTypePopup">Create New Notice Type </span></div>
                        <div class="flex flex-wrap items-center justify-between">
                            <div class="w-full sm:w-[16%] ">
                                <label for="number">Number </label>
                                <input id="title" v-model="number" class="block w-full" type="text" placeholder="0" />
                                <div v-if="errors.number" class="text-red-500 text-xs pt-2">{{ errors.number }}</div>
                            </div>
                            <div class="w-full sm:w-[83%]"> 
                                <label for="type">Type </label>                        
                                <select id="type" v-model="notice_type" class="text-sm w-full" name="type">
                                    <option disabled value="" :selected="defaultSelected">Select type</option>
                                    <option v-for="(notice_type,index) in all_notice_types" :key="index" :value="notice_type.id">{{notice_type.name}}</option>
                                </select>
                                <div v-if="errors.notice_type" class="text-red-500 text-xs pt-2">{{ errors.notice_type }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-6 exclude-toolbar">
                        <label for="comment">Comment (optional) </label>
                        <QuillEditor ref="quill" v-model:content="comment" content="html" contentType="html" theme="snow" toolbar="#exclude-toolbar" />
                        <span id="exclude-toolbar" class="hidden"></span>
                        <!-- <div v-if="errors.comment" class="text-red-500 text-xs pt-2">{{ errors.comment }}</div> -->
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-1 gap-y-1 gap-x-4 sm:grid-cols-6 mb-4">
                    <div class="sm:col-span-6">
                        <label for="name">Training Roles </label>
                        <select @change="roleChanged" id="airframe_id" class="block w-full">
                            <option disabled value="" :selected="defaultSelected">Choose Training Roles</option>
                            <option v-for="role in training_roles.data" :key="role.id" :value="role.id">
                                {{ role.name }}
                            </option>
                        </select>
                    </div>
                    <div class="sm:col-span-6 flex">
                        <div v-if="training_role_ids" v-show="training_role_ids.length > 0" class="flex grow-0 gap-2 mt-2 user-airframes selected">
                            <span v-for="role in this.selected_roles" @click.prevent="removeRole( role )" class="justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">
                                <span class="ml-1">{{role.name}}</span>
                                <span class="ml-4 cursor-pointer">
                                    <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.53494 3.99944L7.07994 1.45444C7.14516 1.40148 7.19853 1.33542 7.2366 1.26052C7.27466 1.18562 7.29658 1.10357 7.30091 1.01966C7.30525 0.935762 7.29193 0.851884 7.26179 0.77346C7.23166 0.695036 7.18539 0.623815 7.12598 0.564408C7.06657 0.505001 6.99535 0.458732 6.91693 0.428597C6.8385 0.398463 6.75463 0.385133 6.67072 0.389473C6.58682 0.393813 6.50477 0.415724 6.42987 0.45379C6.35497 0.491855 6.2889 0.545225 6.23594 0.610444L3.69194 3.15644L1.14194 0.610444C1.08898 0.545225 1.02292 0.491855 0.948019 0.45379C0.873123 0.415724 0.791067 0.393813 0.707165 0.389473C0.623262 0.385133 0.539383 0.398463 0.460959 0.428597C0.382535 0.458732 0.311315 0.505001 0.251907 0.564408C0.1925 0.623815 0.146231 0.695036 0.116096 0.77346C0.0859617 0.851884 0.0726332 0.935762 0.0769729 1.01966C0.0813127 1.10357 0.103225 1.18562 0.14129 1.26052C0.179355 1.33542 0.232725 1.40148 0.297944 1.45444L2.84294 3.99944L0.302944 6.54844C0.237725 6.6014 0.184354 6.66747 0.146289 6.74237C0.108224 6.81727 0.0863128 6.89932 0.0819731 6.98322C0.0776333 7.06713 0.0909618 7.151 0.121097 7.22943C0.151231 7.30785 0.1975 7.37907 0.256907 7.43848C0.316315 7.49789 0.387536 7.54416 0.46596 7.57429C0.544383 7.60442 0.628263 7.61776 0.712165 7.61342C0.796067 7.60908 0.878123 7.58716 0.953019 7.5491C1.02792 7.51103 1.09398 7.45766 1.14694 7.39244L3.69194 4.84744L6.24194 7.38844C6.2949 7.45366 6.36097 7.50703 6.43587 7.5451C6.51077 7.58316 6.59282 7.60508 6.67672 7.60942C6.76063 7.61376 6.8445 7.60043 6.92293 7.57029C7.00135 7.54016 7.07257 7.49389 7.13198 7.43448C7.19139 7.37507 7.23766 7.30385 7.26779 7.22543C7.29792 7.147 7.31125 7.06313 7.30692 6.97922C7.30258 6.89532 7.28066 6.81327 7.2426 6.73837C7.20453 6.66347 7.15116 6.5974 7.08594 6.54444L4.53494 3.99944Z" fill="white" />
                                    </svg>
                                </span>
                            </span>
                        </div>
                    </div>
                    <div v-if="errors.training_role_ids" class="text-red-500 text-xs pt-2">{{ errors.training_role_ids }}</div>
                </div>
                <div class="grid grid-cols-1 gap-y-5 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3 mb-5">
                        <div class="flex items-end gap-2 w-100">
                            <div class="sm:w-[50%]">
                                <label for="createdByFirstname">Created By </label>
                                <input id="createdByFirstname" readonly placeholder="firstname" v-model="Userhelper.user.fname" class="block w-full" type="text" />
                                <div v-if="errors.created_by_fname" class="text-red-500 text-xs pt-2">{{ errors.created_by_fname }}</div>
                            </div>
                            <div class="sm:w-[50%]">
                                <input id="createdByLastname" readonly placeholder="lastname" v-model="Userhelper.user.lname" class="block w-full" type="text" />
                                <div v-if="errors.created_by_lname" class="text-red-500 text-xs pt-2">{{ errors.created_by_lname }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-3 mb-5">
                        <label for="name">Date </label>
                        <input id="date" v-model="date" class="block w-full read-only:border-gray-300 read-only:bg-gray-100 read-only:text-gray-400 read-only:cursor-not-allowed" type="text" readonly />
                    </div>
                </div>

                <template v-if="files.length > 0">
                    <div class="flex items-center justify-start space-x-4 my-6 w-full">
                        <h2 class="text-sm text-gray-400 font-medium">New Document</h2>
                        <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                            <li v-for="file in files" :key="file.id" class="flex flex-auto justify-between flex-a- p-3">
                                <span class="attachment-details">
                                    <span class="attachment-icon inline-block w-4 h-4 mr-2">
                                        <IconPaperclip />
                                    </span>
                                    <span class="text-sm">
                                        {{ file.name }}
                                    </span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </template>

                <template v-if="files.length > 0">
                    <div class="block my-4 text-center">
                        <a @click.prevent="resetDocuments" class="jw-btn jw-btn__gray capitalize">Choose different document</a>
                    </div>
                </template>

                <template v-else>
                    <div class="text-center text-sm text-gray-600 mb-6 rounded-md border-2 border-dashed border-gray-300 px-6 pt-7 pb-8">
                        <span class="flex items-center justify-center mx-auto mb-1 icon jw-svg-stroke__gray-light w-8 h-8">
                            <IconPaperclip svgFill="#6B7280" /></span>
                        <label for="document-upload" class="relative cursor-pointer rounded-md font-sm text-gray-400">
                            <span class="text-gray-400 text-sm block text-center">Add a document</span>
                            <span class="jw-btn jw-btn__blue mt-4">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                </svg>
                                Add Document</span>
                        </label>
                        <input id="document-upload" name="files" @input="files = $event.target.files" class="sr-only" multiple="true" type="file" />
                    </div>
                    <div v-if="errors.files" class="text-red-500 text-xs pt-2">{{ errors.files }}</div>

                </template>
            </div>
            <div class="sm:flex sm:items-center alpha p-6 overflow-hidden bg-gray-lightest">
                <div class="sm:flex-auto">
                    <button type="button" @click="openCancelPopup()" class="jw-btn-md border-gray-300 text-gray-500 btn-cancel bg-white">Cancel</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                    <button class="jw-btn-md jw-btn__blue" type="button" @click="submit()">Create Notice</button>
                </div>
            </div>
        </div>
        <div v-if="cancelPopup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white flex justify-center px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="items-center">
                                <div class="flex justify-center">
                                    <div class="m-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg aria-hidden="true" class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">

                                    <h3 id="modal-title" class="text-lg font-medium leading-6 text-gray-900 text-center">
                                        <p class="mx-1 text-center text-2xl">Are you wish to cancel ?</p>
                                        <p class="mx-1 text-center text-gray-400">All inputted data will be lost</p>
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            <slot name="default" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:px-6">
                            <button @click="closeCancelPopup()" class="w-full jw-btn border-gray-300 text-gray-500 btn-cancel" type="button">
                                Back
                            </button>
                            <a :href="route('notices.index')" class="w-full jw-btn jw-btn__blue">Confirm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="newTypePoupup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white flex justify-center px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="items-center">
                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">

                                    <h3 id="modal-title" class="text-lg font-medium leading-6 text-gray-900 text-center">
                                        <p class="mx-1 text-center text-2xl">Create Notice Type</p>
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            <slot name="default" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="jw-form px-6 py-3">
                            <label for="name">Name </label>
                            <input id="name" placeholder="Notice Type" v-model="notice_type_name" class="block w-full" type="text" />
                            <p class="text-sm text-red-600 mt-1">{{newNoticeTypeError}}</p>
                        </div>
                        <div class="bg-gray-50 w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:flex-row-reverse sm:px-6">
                            <button class="w-full jw-btn jw-btn__blue" @click="saveNoticeType()" type="button">
                                Save Notice Type
                            </button>
                            <button @click="closeNewTypePopup()" class="w-full jw-btn border-gray-300 text-gray-500 btn-cancel" type="button">
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
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";

import {
    Inertia
} from '@inertiajs/inertia';
import {
    QuillEditor
} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import {
    Userhelper
} from "@/Jetworks/Userhelper";

export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        QuillEditor,
        IconPaperclip
    },
    data(props) {

        return {
            title: '',
            number: 0,
            notice_type: '',
            comment: '',
            quill: null,
            defaultSelected: true,
            files: [],
            // docFiles: [],
            training_role_ids: [],
            newTypePoupup: false,
            cancelPopup: false,
            notice_type_name: '',
            newNoticeTypeError: '',
            all_notice_types: props.notice_types.data,

        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        notice_types: {
            type: Object,
            default: {},
        },
        user: {
            type: Object,
            default: {},
        },
        training_roles: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Notices',
        },
        errors: {
            type: Object,
            default: {},
        },
    },
    setup(props) {
        let selected_roles = [];
        return {
            selected_roles,
        }
    },

    // watch: {
    //     notice_types: {
    //         handler(newVal) {
    //             this.all_notice_types = newVal;
    //         },
    //         deep: true,
    //     },
    // },    
    methods: {

        openNewTypePopup: function () {
            this.newTypePoupup = true;
        },

        closeNewTypePopup: function () {
            this.newTypePoupup = false;
        },

        openCancelPopup: function () {
            this.cancelPopup = true;
        },

        closeCancelPopup: function () {
            this.cancelPopup = false;
        },

        saveNoticeType: function () {

            if (!this.notice_type_name) {
                this.newNoticeTypeError = 'Please Enter a Notice Type Name';
            } else {
                this.newNoticeTypeError = '';
                const notice_type_name = this.notice_type_name;

                axios.post(route('notices.createNoticeType', {
                        'name': notice_type_name
                    }))
                    .then((response) => {
                        if (response.data.success == true) {
                            console.log(response.data.data);
                            const new_notice_types = response.data.data.notice_types;
                            this.all_notice_types = new_notice_types;
                            this.closeNewTypePopup();                           
                        } else {
                            this.newNoticeTypeError = response.data.errors.name[0];
                        }
                        console.log(this.newNoticeTypeError);
                    })
                    .catch(error => {
                        console.log(error);
                    });
            }
        },

        getCurrentDate: function () {
            const now = new Date();
            const year = String(now.getFullYear()).slice(-2).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            return `${day}/${month}/${year}`;
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

        submit: function () {
            const modified_order_number = this.addLeadingZero(this.number);
            const form = {
                title: this.title,
                number: modified_order_number,
                notice_type: this.notice_type,
                comment: this.comment,
                files: this.files,
                training_role_ids: this.training_role_ids,
                created_by_fname: Userhelper.user.fname,
                created_by_lname: Userhelper.user.lname,
            }
            Inertia.post(route('notices.store'), form);
            // console.log(form);
        },

        resetDocuments: function () {
            this.files = [];
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
    },
    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper
        },

        date() {
            return this.getCurrentDate();
        }
    },

}
</script>
