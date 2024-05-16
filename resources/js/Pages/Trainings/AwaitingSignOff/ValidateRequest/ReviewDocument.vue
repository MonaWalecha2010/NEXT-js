<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :title="taskRequest.task.title" />
    </template>

    <template v-slot:default>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">{{taskRequest.task.title}}</h1>
            </div>

            <div class="sm:flex text-gray-300 gap-x-7 text-end">
                <template v-if="taskRequest.request_status=='Pending'">
                    <span class="flex items-center border border-blue-300 text-blue-300 bg-blue-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                        <StatusDot bgColor="bg-blue-500" />
                        <span class="text-blue-500">
                            Pending
                        </span>
                    </span>
                </template>
                <template v-if="taskRequest.status==1 && taskRequest.display_status=='Upcoming'">
                    <span class="flex items-center border border-yellow-500 text-yellow-600 bg-yellow-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                        <StatusDot bgColor="bg-yellow-500" />
                        <span class=" text-yellow-500">
                            Upcoming
                        </span>
                    </span>
                </template>
                <template v-if="taskRequest.status==1 && taskRequest.display_status=='Active'">
                    <span class="flex items-center border border-green-500 text-green-600 bg-green-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                        <StatusDot bgColor="bg-green-500" />
                        <span class=" text-green-500">
                            Active
                        </span>
                    </span>
                </template>
                <template v-if="taskRequest.status==null && taskRequest.request_status!='Pending'">
                    <span class="flex items-center border border-red-500 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                        <StatusDot bgColor="bg-red-500" />
                        <span class="text-red-500">
                            Action Required
                        </span>
                    </span>
                </template>
            </div>
        </div>
        <div class="sm:flex sm:items-center mt-5">
            <div class="sm:flex items-center">
                <div class="flex items-center">
                    <span class="mt-1">
                        <UserIcon svgFill="#9CA3AF" />
                    </span>
                    <span class="sm:ml-2 text-sm text-gray-400 font-medium">
                        {{taskRequest.user.lname}}, {{taskRequest.user.fname}}
                    </span>
                </div>  
                <div class="mx-4 gap-x-2">
                    <strong class="text-sm text-gray-400 font-bold">Licence Number:</strong>
                    <span class="text-sm text-gray-400 font-medium">
                        {{taskRequest.user.license_number}}
                    </span>
                </div>           
                
                <div class="mx-4 gap-x-2">
                    <strong class="text-gray-400 text-sm font-bold">Role:</strong>
                    <span v-if="taskRequest.user.training_role_id == 1" class="text-gray-400 text-sm font-medium">
                        Training Admin
                    </span>
                    <span v-if="taskRequest.user.training_role_id == 2" class="text-gray-400 text-sm font-medium">
                        Training Officer
                    </span>
                    <span v-if="taskRequest.user.training_role_id == 3" class="text-gray-400 text-sm font-medium">
                        Training Manager
                    </span>
                    <span v-if="taskRequest.user.training_role_id == 4" class="text-gray-400 text-sm font-medium">
                        raining Engineeer
                    </span>
                    <span v-if="taskRequest.user.training_role_id == 5" class="text-gray-400 text-sm font-medium">
                        Training Mechanic
                    </span>
                </div>
            </div>
        </div>

        <div class="mt-5 bg-gray-300 border border-gray-300 overflow-hidden rounded-lg">
            <div class="my-8 mx-8 sm:mx-10 md:mx-16 lg:mx-24 overflow-hidden border bg-white border-gray-300 rounded-lg shadow-xl h-full">
                <iframe width="100%" height="600px" :src="attachments[0].filepath+'#toolbar=0&navpanes=0&view=FitH'" title="Embedded Content" class="w-full p-0 shadow-none bg-transparent" style="background-color: #FFFFFF" frameborder="0" />
                <!-- <embed :src="attachments[0].filepath" type="application/pdf" width="100%" height="800px" class="w-full p-0 m-0 bg-white shadow-none" /> -->
            </div>
            <div class="bg-[#f8f8f8] w-full px-4 gap-x-3 py-3 flex justify-around sm:px-6">
                <div class="mt-4 flex w-full">
                    <div class="flex w-full">
                        <button class="jw-btn-md border-gray-300 text-gray-500 btn-cancel w-44 mx-2" @click="openCancelPopup">Cancel</button>
                    </div>
                    <div class="flex w-full justify-end">
                        <button class="jw-btn-md jw-btn__red w-44 mx-2" @click="openRejectPopup" type="button">Reject</button>
                        <button class="jw-btn-md jw-btn__blue w-44 mx-2" @click="showSignoff" type="button">Sign Off</button>
                    </div>
                </div>
            </div>
        </div>

        <SignOffModalConfirmation v-if="signoff" :action="'Sign Off'" :model="signoff" :title="'Sign Off Document ?'" @confirmModalConfirmation="listenForModalConfirmation">
            Please Confirm that you would like to sign off this document .
        </SignOffModalConfirmation>

        <div v-if="cancelPopup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white flex justify-center px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="items-center">
                                <div class="flex justify-center">
                                    <div class="m-auto flex my-2 h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg aria-hidden="true" class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">

                                    <h3 id="modal-title" class="text-lg font-medium leading-6 text-gray-900 text-center">
                                        <p class="mx-1 text-center text-2xl">Are you wish to cancel ?</p>
                                        <!-- <p class="mx-1 text-center text-gray-400">All inputted data will be lost</p> -->
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
                            <a :href="route('trainings.index')" class="w-full jw-btn jw-btn__blue">Confirm</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div v-if="rejectPopup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white px-5 py-4 ">
                            <div class="items-center">                               
                                <div class="mx-auto mt-3 flex h-16 w-16 flex-shrink-0 items-center justify-center rounded-full bg-red-100">
                                    <svg aria-hidden="true" class="h-[30px] w-[30px] text-red-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </div>                               
                                <div class="mx-auto mt-5 text-center sm:w-72">
                                    <h3 id="modal-title" class="text-center font-semibold leading-6 text-2xl text-gray-900">Reject Document ?</h3>
                                    <div class="mt-3 text-cente"> 
                                        <p class="text-center text-base font-normal  text-gray-500"> Please confirm that you would like to reject this document.</p>                             
                                    </div>
                                </div>
                                <div class="mt-5 w-full text-center sm:text-left">
                                    <div class="">
                                        <label class="text-sm text-gray-900 font-medium"> Please give a reason for this rejection</label>
                                        <div class="mt-2 jw-form exclude-toolbar">
                                            <QuillEditor ref="quill" v-model:content="comment" content="html" contentType="html" theme="snow" toolbar="#exclude-toolbar" />
                                            <span id="exclude-toolbar" class="hidden"></span>
                                        </div>
                                        <p class="text-sm text-red-600">{{commentError}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-lightest px-5 py-3 sm:flex sm:px-6 space-x-3 mt-3">
                            <button @click="closeRejectPopup()" class="w-full jw-btn border-gray-300 text-gray-500 btn-cancel" type="button">
                                Cancel
                            </button>
                            <a @click="rejectDocument" class="w-full jw-btn jw-btn__red">Reject</a>
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
import ViewDocument from "@/Jetworks/Common/ViewDocument.vue";
import DocumentIcon from '@/Jetworks/Icons/DocumentIcon.vue';
import ListIcon from '@/Jetworks/Icons/ListIcon.vue';
import UserIcon from '@/Jetworks/Icons/UserIcon.vue';
import WarningIcon from '@/Jetworks/Icons/WarningIcon.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import SignOffModalConfirmation from "@/Jetworks/Common/SignOffModalConfirmation.vue";
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
import StatusDot from '@/Components/StatusDot.vue'
// import {
//     Userhelper
// } from "@/Jetworks/Userhelper";

export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        ViewDocument,
        DocumentIcon,
        ListIcon,
        WarningIcon,
        SignOffModalConfirmation,
        UserIcon,
        StatusDot,
        QuillEditor
    },

    data() {
        return {
            signoff: false,
            taskRequestdata: this.taskRequest,
            rejectPopup: false,
            cancelPopup: false,
            comment: '',
            commentError: '',
            quill: null
        }
    },

    props: {
        can: {
            type: Object,
            default: {},
        },
        taskRequest: {
            type: Object,
            default: {},
        },
        attachments: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Document Review',
        },
    },
    // setup(props) {

    // import("@/Jetworks/Userhelper").then(module => {
    //     const Userhelper = module.Userhelper;
    //     Userhelper.initialise(props.$page);
    // });
    // console.log('prop', Userhelper);
    // const form = useForm({
    //     _method: 'put',
    //     id:props.taskRequest.id,
    //     user: props.taskRequest.user,
    //     task: props.taskRequest.task,
    // });

    // function submit() {
    //     // Inertia.post(route('taskrequests.update', {
    //     //     taskRequest: props.taskRequest.id
    //     // }), form);
    //     console.log(form);
    // }

    // return {
    //     form,
    //     submit,
    // }
    // },
    methods: {
        showSignoff(data) {
            this.signoff = true;
        },

        listenForModalConfirmation(data) {
            if (data == "Sign Off") {
                const form = {
                    task_request: this.taskRequest,
                    status: 1,
                };
                Inertia.post(route('taskrequests.signOffDocument'), form);
            } else {
                this.signoff = false;
            }
        },

        rejectDocument() {
            const form = {
                task_request: this.taskRequest,
                status: 0,
                comment: this.comment,
            };

            if (!this.comment) {
                this.commentError = 'Please Enter a reason for Document rejection';
            } else {
                this.commentError = ''
                Inertia.post(route('taskrequests.signOffDocument'), form);
            }

        },

        openCancelPopup() {
            this.cancelPopup = true;
        },

        closeCancelPopup() {
            this.cancelPopup = false;
        },
        openRejectPopup() {
            this.rejectPopup = true;
        },

        closeRejectPopup() {
            this.rejectPopup = false;
        },
    },
    computed: {
        pdfFilename() {
            return this.attachments[0] ? this.attachments[0].filepath : '';
        },
        pdfUrl() {
            return `${this.baseUrl}${this.pdfFilename}`;
        }
    },
    mounted() {
        this.baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');
    }

}
</script>

<style>
.activeTab p {
    color: red;
}
</style>
