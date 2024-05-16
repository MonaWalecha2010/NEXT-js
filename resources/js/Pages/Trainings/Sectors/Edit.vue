<template>
<Head :title="`Edit ${pageHeader}`" />

<AuthenticatedLayout :can="can">
    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :title="'Edit a Training Sector'" />
    </template>

    <template v-slot:default>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Edit a Training Sector</h1>
            </div>
        </div>        
        <div class="mt-6 bg-white overflow-hidden shadow-sm jw-form rounded-md">
            <div class="my-3 px-6 ">
                <div class="mb-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <div class="w-full">
                            <label for="title">Sector Title </label>
                            <input id="title" v-model="title" class="block w-full" type="text" />
                            <div v-if="errors.title" class="text-red-500 text-xs pt-2">{{ errors.title }}</div>
                        </div>
                        <div class="w-full pt-5">
                            <label for="order_number">Sector Order Number</label>
                            <input id="order_number" v-model="order_number" class="block w-full" type="text" />
                            <div v-if="errors.order_number" class="text-red-500 text-xs pt-2">{{ errors.order_number }}</div>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="description">Sector Description (Optional) </label>
                        <textarea id="description" v-model="description" rows="5" class="block w-full"></textarea>
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{ errors.description }}</div>
                    </div>
                </div>
                <div class="mb-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="fname_created_by">Created By</label>
                        <div class="flex space-x-3 w-full items-baseline">
                            <div class="w-[50%]">
                                <input id="fname_created_by" :readonly="isReadOnly" v-model="fname_created_by" class="block mr-1 w-full" type="text" />
                                <div v-if="errors.fname_created_by" class="text-red-500 text-xs pt-2">{{ errors.fname_created_by }}</div>
                            </div>
                            <div class="w-[50%]">
                                <input id="lname_created_by" :readonly="isReadOnly" v-model="lname_created_by" class="block ml-1 w-full" type="text" />
                                <div v-if="errors.lname_created_by" class="text-red-500 text-xs pt-2">{{ errors.lname_created_by }}</div>
                            </div>
                        </div>                        
                    </div>
                    <div class="sm:col-span-3">
                        <label for="created_at">Date </label>
                        <input id="created_at" :readonly="isReadOnly" v-model="created_at" class="block w-full bg-gray-100" type="text" />
                    </div>
                </div>
                <div class="mb-3 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="fname_modified_by">Modified By</label>
                        <div class="flex space-x-3 w-full items-baseline">
                            <div class="w-[50%]">
                                <input id="fname_modified_by" :readonly="isReadOnly" v-model="Userhelper.user.fname" class="block mr-1 w-full" type="text" />
                                <div v-if="errors.fname_modified_by" class="text-red-500 text-xs pt-2">{{ errors.fname_modified_by }}</div>
                            </div>
                            <div class="w-[50%]">
                                <input id="lname_modified_by" :readonly="isReadOnly" v-model="Userhelper.user.lname" class="block ml-1 w-full" type="text" />
                                <div v-if="errors.lname_modified_by" class="text-red-500 text-xs pt-2">{{ errors.lname_modified_by }}</div>
                            </div>
                        </div>                        
                    </div>
                    <div class="sm:col-span-3">
                        <label for="modified_by_date">Date </label>
                        <input id="modified_by_date" :readonly="isReadOnly" v-model="date" class="block w-full bg-gray-100" type="text" />
                    </div>
                </div>
            </div>
            <div class="sm:flex sm:items-center bg-gray-lightest p-4 overflow-hidden">
                <div class="sm:flex-auto">
                    <button @click="openCancelPopup()" type="button" class="jw-btn-md border-gray-300 text-gray-500 btn-cancel bg-white">Cancel</button>                    
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-5">
                    <button class="jw-btn-md jw-btn__red" type="button" @click="confirmDelete()">Delete</button>
                    <button class="jw-btn-md jw-btn__blue" type="button" @click="submit()">Update Training Sector</button>
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
                                <h3 id="modal-title" class="mb-2 text-2xl font-semibold text-gray-900 text-center">Delete Sector ?</h3>
                                <p class="mx-1 text-center text-gray-500 px-16 text-base">Please Confirm that You would like to delete this Sector</p>
                                <div class="mt-4">
                                    <p class="text-sm text-gray-500">
                                        <slot name="default" />
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-gray-lightest w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:flex-row-reverse sm:px-6">
                            <button @click="destroy()" class="w-full jw-btn jw-btn__red" type="button">
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

import {
    Inertia
} from '@inertiajs/inertia';

import {
    Userhelper
} from "@/Jetworks/Userhelper";

export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
    },
    data(props) {
        return {
            title: props.sector.title,
            order_number: props.sector.order_number,
            description: props.sector.description,
            fname_created_by: props.sector.fname_created_by,
            lname_created_by: props.sector.lname_created_by,
            created_at: props.sector.created_at,
            isReadOnly: true,
            cancelPopup: false,
            deletePopup: false,
        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        sector: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Sector',
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

        submit: function () {
            const modified_order_number = this.addLeadingZero(this.order_number);

            const form = {
                _method: 'put',
                title: this.title,
                order_number: modified_order_number,
                description: this.description,
                fname_created_by: this.fname_created_by,
                lname_created_by: this.lname_created_by,
                fname_modified_by: Userhelper.user.fname,
                lname_modified_by: Userhelper.user.lname,
                user_id_modified_by: Userhelper.user.id,
            }

            Inertia.post(route('sectors.update', {
                sector: this.sector.id
            }), form);

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

        destroy() {
            Inertia.delete(route('sectors.destroy', {
                sector: this.sector.id
            }));
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
}
</script>
