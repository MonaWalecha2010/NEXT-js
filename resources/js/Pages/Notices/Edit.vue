<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('notices.index')" :title="pageHeader" />
        <BreadcrumbItem :title="'Edit a Notice'" />
    </template>

    <template v-slot:default>
        <div class="sm:flex justify-between sm:items-center traning_tabs">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Edit a Notice</h1>
            </div>
        </div>
        <div class="jw-form border border-gray-300 rounded-lg overflow-hidden shadow-sm mt-6">
            <div class="p-6 bg-white overflow-hidden">
                <div class="mt-2 grid grid-cols-1 gap-y-4 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6">
                        <label for="name">Notice Title </label>
                        <input id="title" v-model="title" class="block w-full" type="text" />
                        <div v-if="errors.title" class="text-red-500 text-xs pt-2">{{ errors.title }}</div>
                    </div>
                    <div class="sm:col-span-6">
                        <div class="w-full mt-2 mb-2 text-end"><span class="text-xs text-end border text-gray-900 hover:text-blue-300 border-gray-300 font-medium hover:border-blue-300 rounded-full px-4 py-1 " @click="openNewTypePopup">Create New Notice Type </span></div>
                        <div class="flex flex-wrap items-center justify-between">
                            <div class="w-full sm:w-[16%] ">
                                <label for="number">Number </label>
                                <input id="title" v-model="number" class="block w-full" type="text" />
                                <div v-if="errors.number" class="text-red-500 text-xs pt-2">{{ errors.number }}</div>
                            </div>
                            <div class="w-full sm:w-[83%]"> 
                                <label for="name">Type </label>
                                <select id="type" v-model="notice_type" class="text-sm" name="type">
                                    <option disabled value="" :selected="defaultSelected">Select type</option>
                                    <option v-for="(notice_type,index) in all_notice_types" :key="notice_type.id" :value="notice_type.id">{{notice_type.name}}</option>
                                </select>
                                <div v-if="errors.notice_type" class="text-red-500 text-xs pt-2">{{ errors.notice_type }}</div>
                            </div>
                        </div> 
                    </div>
                    <div class="sm:col-span-6 exclude-toolbar">
                        <label for="comment">Comment (optional) </label>
                        <QuillEditor ref="quill" v-model:content="comment" content="html" contentType="html" theme="snow" toolbar="#exclude-toolbar"  />
                        <span id="exclude-toolbar" class="hidden"></span>
                        <!-- <div v-if="errors.comment" class="text-red-500 text-xs pt-2">{{ errors.comment }}</div> -->
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-1 gap-y-1 gap-x-4 sm:grid-cols-6 mb-4">
                    <div class="sm:col-span-6 ">
                        <label for="notification_sectors">Training Roles</label>
                        <select @change="roleChanged" id="training_roles" class="" name="show">
                            <option disabled value="" :selected="defaultSelected">Choose Training Roles</option>
                            <option v-for="role in training_roles.data" :key="role.id" :value="role.id">
                                {{ role.name }}
                            </option>
                        </select>
                    </div>

                    <div class="sm:col-span-6 flex">
                        <div v-if="training_role_ids" v-show="training_role_ids.length > 0" class="flex grow-0 gap-2 mt-2 user-airframes selected">
                            <span v-for="role in this.selected_training_roles" @click.prevent="removeRole( role )" class="justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">

                                <span class="ml-1">{{role.name}}</span>
                                <span class="ml-4 cursor-pointer">
                                    <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.53494 3.99944L7.07994 1.45444C7.14516 1.40148 7.19853 1.33542 7.2366 1.26052C7.27466 1.18562 7.29658 1.10357 7.30091 1.01966C7.30525 0.935762 7.29193 0.851884 7.26179 0.77346C7.23166 0.695036 7.18539 0.623815 7.12598 0.564408C7.06657 0.505001 6.99535 0.458732 6.91693 0.428597C6.8385 0.398463 6.75463 0.385133 6.67072 0.389473C6.58682 0.393813 6.50477 0.415724 6.42987 0.45379C6.35497 0.491855 6.2889 0.545225 6.23594 0.610444L3.69194 3.15644L1.14194 0.610444C1.08898 0.545225 1.02292 0.491855 0.948019 0.45379C0.873123 0.415724 0.791067 0.393813 0.707165 0.389473C0.623262 0.385133 0.539383 0.398463 0.460959 0.428597C0.382535 0.458732 0.311315 0.505001 0.251907 0.564408C0.1925 0.623815 0.146231 0.695036 0.116096 0.77346C0.0859617 0.851884 0.0726332 0.935762 0.0769729 1.01966C0.0813127 1.10357 0.103225 1.18562 0.14129 1.26052C0.179355 1.33542 0.232725 1.40148 0.297944 1.45444L2.84294 3.99944L0.302944 6.54844C0.237725 6.6014 0.184354 6.66747 0.146289 6.74237C0.108224 6.81727 0.0863128 6.89932 0.0819731 6.98322C0.0776333 7.06713 0.0909618 7.151 0.121097 7.22943C0.151231 7.30785 0.1975 7.37907 0.256907 7.43848C0.316315 7.49789 0.387536 7.54416 0.46596 7.57429C0.544383 7.60442 0.628263 7.61776 0.712165 7.61342C0.796067 7.60908 0.878123 7.58716 0.953019 7.5491C1.02792 7.51103 1.09398 7.45766 1.14694 7.39244L3.69194 4.84744L6.24194 7.38844C6.2949 7.45366 6.36097 7.50703 6.43587 7.5451C6.51077 7.58316 6.59282 7.60508 6.67672 7.60942C6.76063 7.61376 6.8445 7.60043 6.92293 7.57029C7.00135 7.54016 7.07257 7.49389 7.13198 7.43448C7.19139 7.37507 7.23766 7.30385 7.26779 7.22543C7.29792 7.147 7.31125 7.06313 7.30692 6.97922C7.30258 6.89532 7.28066 6.81327 7.2426 6.73837C7.20453 6.66347 7.15116 6.5974 7.08594 6.54444L4.53494 3.99944Z" fill="white" />
                                    </svg>
                                </span>
                            </span>
                        </div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3 mb-5">
                        <div class="flex items-end gap-2 w-100">
                            <div class="sm:w-[50%]">
                                <label for="createdByFirstname">Created By </label>
                                <input id="createdByFirstname" readonly placeholder="firstname" v-model="created_by_fname" class="block w-full" type="text" />
                                <div v-if="errors.created_by_fname" class="text-red-500 text-xs pt-2">{{ errors.created_by_fname }}</div>
                            </div>
                            <div class="sm:w-[50%]">
                                <!-- <label for="number"> </label> -->
                                <input id="createdByLastname" readonly placeholder="lastname" v-model="created_by_lname" class="block w-full" type="text" />
                                <div v-if="errors.created_by_lname" class="text-red-500 text-xs pt-2">{{ errors.created_by_lname }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-3 mb-5">
                        <label for="name">Date </label>
                        <input id="date" v-model="created_date" class="block w-full read-only:border-gray-300 read-only:bg-gray-100 read-only:text-gray-400 read-only:cursor-not-allowed" type="text" readonly />
                        <!-- <div v-if="errors.created_date" class="text-red-500 text-xs pt-2">{{ errors.created_date }}</div> -->
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3 mb-5">
                        <div class="flex items-end gap-2 w-100">
                            <div class="sm:w-[50%]">
                                <label for="modifiedByFirstname">Modified By </label>
                                <input id="modifiedByFirstname" readonly placeholder="firstname" v-model="Userhelper.user.fname" class="block w-full" type="text" />
                                <div v-if="errors.modified_by_fname" class="text-red-500 text-xs pt-2">{{ errors.modified_by_fname }}</div>
                            </div>
                            <div class="sm:w-[50%]">
                                <!-- <label for="number"> </label> -->
                                <input id="modifiedByLastname" readonly placeholder="lastname" v-model="Userhelper.user.lname" class="block w-full" type="text" />
                                <div v-if="errors.modified_by_lname" class="text-red-500 text-xs pt-2">{{ errors.modified_by_lname }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-3 mb-5">
                        <label for="name">Date </label>
                        <input id="date" v-model="date" class="text-smblock w-full read-only:border-gray-300 read-only:bg-gray-100 read-only:text-gray-400 read-only:cursor-not-allowed" type="text" readonly />
                        <div v-if="errors.date" class="text-red-500 text-xs pt-2">{{ errors.date }}</div>
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6 bg-white overflow-hidden">
                        <!-- <ViewDocument :attachments="attachments" /> -->
                        <div v-if="notice.notice_documents_history.length > 0" class="mb-6 overflow-hidden">
                            <h2 class="text-sm font-medium text-gray-400">Document History</h2>
                            <div class="flex items-center justify-start space-x-6 mt-5 w-full">

                                <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                                    <li v-for="file in notice.notice_documents_history" :key="file.id" class="flex flex-auto justify-between p-3">
                                        <span class="">
                                            <span class="attachment-icon inline-block w-4 h-4 mr-2">
                                                <IconPaperclip />
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{ file.notice_document_name }}
                                            </span>
                                        </span>
                                        <span class="flex items-center justify-end gap-x-8">
                                            <i class="text-xs text-gray-400 font-medium">
                                                ({{file.status}})
                                            </i>
                                            <span class="text-sm text-gray-500">
                                                {{file.version}}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                {{formatDbdate(file.created_at)}}
                                            </span>
                                            <span class="text-sm text-gray-500">
                                                <a :href="file.notice_document" class="text-gray-900 font-medium" target="_blank">View</a>
                                            </span>
                                        </span>
                                    </li>
                                </ul>

                            </div>
                        </div>
                    </div>
                </div>
                <template v-if="files.length > 0">

                    <div class="mb-2">
                        <div class="flex justify-start items-center space-x-4 my-6 w-full">

                            <h2 class="text-sm font-medium text-gray-400">New Document</h2>

                            <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                                <li v-for="file in files" :key="file.id" class="flex flex-auto justify-between flex-a- p-3">
                                    <span class="attachment-details">
                                        <span class="attachment-icon inline-block w-4 h-4 mr-2">
                                            <IconPaperclip />
                                        </span>
                                        <span class="attachment-name">
                                            {{ file.name }}
                                        </span>
                                    </span>
                                    <span class="attachment-size">
                                        <span class="jw-text__red cursor-pointer" @click="resetDocuments(file)">Remove</span>
                                    </span>

                                </li>
                            </ul>
                        </div>
                    </div>
                </template>
                <input type="file" id="FileUpload1" class="hidden text-sm text-gray-400" ref="file" @input="files = $event.target.files" />
            </div>
            <div class="sm:flex sm:items-center alpha p-6 overflow-hidden bg-gray-lightest">
                <div class="sm:flex-auto">
                    <button type="button" @click="openCancelPopup()" class="jw-btn-md border-gray-300 text-gray-500 btn-cancel bg-white">Cancel</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-3 sm:flex-none ">
                    <button class="jw-btn-md bg-yellow-500 disabled:opacity-50" @click="openWithdarwPopup()" type="button">Withdraw</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-3 sm:flex-none ">
                    <button class="jw-btn-md jw-btn__green disabled:opacity-50" @click="$refs.file.click()" type="button">Issue New</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-3 sm:flex-none">
                    <button class="jw-btn-md jw-btn__blue disabled:opacity-50" type="button" @click="submit()">Save Notice</button>
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

        <div v-if="withdarwPopup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white flex justify-center px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="items-center">
                                <div class="flex justify-center">
                                    <div class="m-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-orange-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg aria-hidden="true" class="h-6 w-6 text-yellow-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="mt-4 text-center  ">
                                    <div id="modal-title" class="text-center w-72">
                                        <h3 class="text-center font-semibold leading-6 text-2xl text-gray-900">Withdraw Notice?</h3>
                                        <p class="mt-3 text-center text-[16px] font-normal  text-gray-500">Please confirm that you would like to withdraw this notice.</p>
                                    </div>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            <slot name="default" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-50 w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:flex-row-reverse sm:px-6">
                            <button class="w-full jw-btn bg-yellow-500" @click="withdrawNotice()" type="button">
                                Withdraw Notice
                            </button>
                            <button @click="closeWithdarwPopup()" class="w-full jw-btn border-gray-300 text-gray-500 btn-cancel" type="button">
                                Cancel
                            </button>
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
import DeleteDocuments from "@/Jetworks/Common/DeleteDocuments.vue";
import ViewDocument from "@/Jetworks/Common/ViewDocument.vue";
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
        DeleteDocuments,
        ViewDocument,
        IconPaperclip
    },
    data(props) {

        return {
            id: props.notice.id,
            title: props.notice.title,
            number: props.notice.number,
            notice_type: props.notice.notice_type.id,
            comment: props.notice.comment,
            created_date: props.notice.created_at,
            created_by_fname: props.notice.created_by_fname,
            created_by_lname: props.notice.created_by_lname,
            quill: null,
            training_role_ids: [],
            defaultSelected: true,
            files: [],
            cancelPopup: false,
            withdarwPopup: false,
            newTypePoupup: false,
            notice_type_name: '',
            newNoticeTypeError: '',
            all_notice_types: props.notice_types.data,



        }
    },
    setup(props) {
        let selected_training_roles = [];
        return {
            selected_training_roles,
        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Notices',
        },
        notice_types: {
            type: Object,
            default: {},
        },
        training_roles: {
            type: Object,
            default: {},
        },
        attachments: {
            type: Object,
            default: {},
        },
        notice: {
            type: String,
            default: 'Notices',
        },
        errors: {
            type: Object,
            default: {},
        },

    },
    methods: {
        openNewTypePopup: function () {
            this.newTypePoupup = true;
        },

        closeNewTypePopup: function () {
            this.newTypePoupup = false;
        },

        openWithdarwPopup: function () {
            this.withdarwPopup = true;
        },

        closeWithdarwPopup: function () {
            this.withdarwPopup = false;
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
            const modified_number = this.addLeadingZero(this.number);
            const form = {
                id: this.id,
                title: this.title,
                number: modified_number,
                notice_type: this.notice_type,
                comment: this.comment,
                modified_by_fname: Userhelper.user.fname,
                modified_by_lname: Userhelper.user.lname,
                training_role_ids: this.training_role_ids,
                files: this.files,
            }

            Inertia.post(route('notices.updateFile'), form);

        },

        withdrawNotice: function () {
            const form = {
                id: this.id,
            }
            Inertia.post(route('notices.withdrawNotice'), form);
        },

        handleFileChange(event) {
            const selectedFiles = event.target.files;
            this.files = selectedFiles;

        },

        resetDocuments: function () {
            this.files = [];
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
            let selectedRoleId = this.notice.roles;
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

        issueNew: function () {
            // files = $event.target.files
        },

        formatDbdate: function (data) {
            const date = new Date(data);

            const day = date.getUTCDate().toString().padStart(2, '0');
            const month = (date.getUTCMonth() + 1).toString().padStart(2, '0'); // Adding 1 because months are zero-indexed
            const year = date.getUTCFullYear();

            const formattedDate = `${day}/${month}/${year}`;
            return formattedDate

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
    beforeMount() {
        this.selectedTrainingRoles();
    },
}
</script>
