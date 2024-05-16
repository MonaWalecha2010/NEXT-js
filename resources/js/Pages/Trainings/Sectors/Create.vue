<template>
<Head :title="`Create ${pageHeader}`" />

<AuthenticatedLayout :can="can">
    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :title="'Add a Training Sector'" />
    </template>

    <template v-slot:default>        
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Add a Training Sector</h1>
            </div>
        </div>
        <div class="mt-6 bg-white overflow-hidden shadow-sm jw-form rounded-md">
            <div class="my-3 px-6 ">
                <div class="mb-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6"> 
                    <div class="sm:col-span-3">
                        <div class="w-full ">
                            <label for="title">Sector Title </label>
                            <input id="title" v-model="title" class="block w-full" type="text" />
                            <div v-if="errors.title" class="text-red-600 text-xs pt-2">{{ errors.title }}</div>
                        </div>
                        <div class="w-full pt-5">
                            <label for="order_number">Sector Order Number</label>
                            <input id="order_number" v-model="order_number" class="block w-full" type="text" />
                            <div v-if="errors.order_number" class="text-red-600 text-xs pt-2">{{ errors.order_number }}</div>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="description">Sector Description (Optional) </label>
                        <textarea id="description" v-model="description" rows="5" class="block w-full"></textarea>
                        <div v-if="errors.description" class="text-red-600 text-xs pt-2">{{ errors.description }}</div>
                    </div>
                </div>
                <div class="mb-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-3">
                        <label for="fname_created_by">Created By</label>
                        <div class="flex space-x-3 w-full items-baseline">
                            <div class="w-[50%]">
                                <input id="fname_created_by" :readonly="isReadOnly" v-model="Userhelper.user.fname" class="w-full" type="text" />
                                <div v-if="errors.fname_created_by" class="text-red-600 text-xs pt-2">{{ errors.fname_created_by }}</div>
                            </div>
                            <div class="w-[50%]">
                                <input id="lname_created_by" :readonly="isReadOnly" v-model="Userhelper.user.lname" class="w-full" type="text" />
                                <div v-if="errors.lname_created_by" class="text-red-600 text-xs pt-2">{{ errors.lname_created_by }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="sm:col-span-3">
                        <label for="date">Date </label>
                        <input id="date" :readonly="isReadOnly" v-model="date" class="block w-full bg-gray-100" type="text" />
                    </div>
                </div> 
            </div>           
            <div class="sm:flex sm:items-center bg-gray-lightest  p-4 overflow-hidden">
                <div class="sm:flex-auto">
                    <button @click="openCancelPopup()" type="button" class="jw-btn-md border-gray-300 text-gray-500 btn-cancel bg-white">Cancel</button>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                    <button class="jw-btn-md jw-btn__blue" type="button" @click="submit()">Create Training Sector</button>
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
    data() {
        return {
            title: '',
            order_number: 0,
            description: '',
            isReadOnly: true,
            cancelPopup: false,
        }
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
                title: this.title,
                order_number: modified_order_number,
                description: this.description,
                fname_created_by: Userhelper.user.fname,
                lname_created_by: Userhelper.user.lname,
                user_id_created_by: Userhelper.user.id,
            }

            Inertia.post(route('sectors.store'), form);
        },

        openCancelPopup() {
            this.cancelPopup = true;
        },

        closeCancelPopup() {
            this.cancelPopup = false;
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
