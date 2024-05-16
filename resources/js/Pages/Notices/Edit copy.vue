<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :title="pageHeader" />
        <BreadcrumbItem :title="'Edit a Notice'" />
    </template>

    <template v-slot:default>
        <div class="sm:flex justify-between sm:items-center traning_tabs">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Edit a Notice</h1>
            </div>
        </div>
        <div class="jw-form">
            <div class="my-6 p-6  bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6 mb-5">
                        <label for="name">Notice Title </label>
                        <input id="title" v-model="title" class="block w-full" type="text" />
                        <div v-if="errors.title" class="text-red-500 text-xs pt-2">{{ errors.title }}</div>
                    </div>
                    <div class="sm:col-span-1 mb-5">
                        <label for="number">Number </label>
                        <input id="title" v-model="number" class="block w-full" type="text" />
                        <div v-if="errors.number" class="text-red-500 text-xs pt-2">{{ errors.number }}</div>
                    </div>
                    <div class="sm:col-span-5 mb-5">
                        <label for="name">Type </label>
                        <select id="type" v-model="notice_type" class="" name="type">
                            <option disabled value="" :selected="defaultSelected">Select type</option>

                            <option v-for="(notice_type,index) in notice_types" :key="index" :value="index">{{notice_type}}</option>
                        </select>
                        <div v-if="errors.notice_type" class="text-red-500 text-xs pt-2">{{ errors.notice_type }}</div>

                    </div>
                    <div class="sm:col-span-6 mb-5 exclude-toolbar">
                        <label for="comment">Comment (optional) </label>
                        <QuillEditor ref="quill" v-model:content="comment" content="html" contentType="html" theme="snow" toolbar="#exclude-toolbar" />
                        <span id="exclude-toolbar" class="hidden"></span>
                        <!-- <div v-if="errors.comment" class="text-red-500 text-xs pt-2">{{ errors.comment }}</div> -->
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3 mb-5">
                        <div class="flex items-end gap-2 w-100">
                            <div class="sm:w-50">
                                <label for="createdByFirstname">Created By </label>
                                <input id="createdByFirstname" readonly placeholder="firstname" v-model="created_by_fname" class="block w-full" type="text" />
                                <div v-if="errors.created_by_fname" class="text-red-500 text-xs pt-2">{{ errors.created_by_fname }}</div>
                            </div>
                            <div class="sm:w-50">
                                <!-- <label for="number"> </label> -->
                                <input id="createdByLastname" readonly placeholder="lastname" v-model="created_by_lname" class="block w-full" type="text" />
                                <div v-if="errors.created_by_lname" class="text-red-500 text-xs pt-2">{{ errors.created_by_lname }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-3 mb-5">
                        <label for="name">Date </label>
                        <input id="date" v-model="created_date" class="block w-full" type="text" readonly />
                        <!-- <div v-if="errors.created_date" class="text-red-500 text-xs pt-2">{{ errors.created_date }}</div> -->
                    </div>
                </div>
                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3 mb-5">
                        <div class="flex items-end gap-2 w-100">
                            <div class="sm:w-50">
                                <label for="modifiedByFirstname">Modified By </label>
                                <input id="modifiedByFirstname" readonly placeholder="firstname" v-model="Userhelper.user.fname" class="block w-full" type="text" />
                                <div v-if="errors.modified_by_fname" class="text-red-500 text-xs pt-2">{{ errors.modified_by_fname }}</div>
                            </div>
                            <div class="sm:w-50">
                                <!-- <label for="number"> </label> -->
                                <input id="modifiedByLastname" readonly placeholder="lastname" v-model="Userhelper.user.lname" class="block w-full" type="text" />
                                <div v-if="errors.modified_by_lname" class="text-red-500 text-xs pt-2">{{ errors.modified_by_lname }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-3 mb-5">
                        <label for="name">Date </label>
                        <input id="date" v-model="date" class="block w-full" type="text" readonly />
                        <div v-if="errors.date" class="text-red-500 text-xs pt-2">{{ errors.date }}</div>
                    </div>
                </div>

                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
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
                        <div v-if="training_role_ids" v-show="training_role_ids.length > 0" class="flex grow-0 gap-2 mt-4 user-airframes selected">
                            <span v-for="role in this.selected_training_roles" @click.prevent="removeRole( role )" class="justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">

                                <span class="ml-1">{{role.name}}</span>
                                <span class="ml-4 cursor-pointer">x</span>
                            </span>
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-6 bg-white overflow-hidden">
                        <ViewDocument :attachments="attachments" />
                    </div>
                </div>

                <template v-if="files.length > 0">

                    <div class="mb-2">
                        <div class="flex justify-start space-x-4 my-6 w-full">

                            <h2 class="mt-3">New Document</h2>

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

                <input type="file" id="FileUpload1" class="hidden" ref="file" @input="files = $event.target.files" />

            </div>
            <div class="sm:flex sm:items-center alpha p-6 overflow-hidden shadow-sm">
                <div class="sm:flex-auto">
                    <button type="button" @click="submit" class="jw-btn-md border-gray-300 text-gray-400 btn-cancel bg-white">Cancel</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-3 sm:flex-none ">
                    <button class="jw-btn-md jw-btn__orange disabled:opacity-50" type="button">Withdraw</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-3 sm:flex-none ">
                    <button class="jw-btn-md jw-btn__green disabled:opacity-50" @click="$refs.file.click()" type="button">Issue New</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-3 sm:flex-none">
                    <button class="jw-btn-md jw-btn__blue disabled:opacity-50" ype="button" @click="submit()">Save Notice</button>
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
            notice_type: props.notice.notice_type,
            comment: props.notice.comment,
            created_date: props.notice.created_at,
            created_by_fname: props.notice.created_by_fname,
            created_by_lname: props.notice.created_by_lname,
            quill: null,
            training_role_ids: [],
            defaultSelected: true,
            files: []

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
                title: this.title,
                number: modified_number,
                notice_type: this.notice_type,
                comment: this.comment,
                modified_by_fname: Userhelper.user.fname,
                modified_by_lname: Userhelper.user.lname,
                training_role_ids: this.training_role_ids,
                files: this.files,
            }
            Inertia.put(route('notices.update', {
                notice: this.id
            }), form);

            // console.log(form);
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
        }
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
