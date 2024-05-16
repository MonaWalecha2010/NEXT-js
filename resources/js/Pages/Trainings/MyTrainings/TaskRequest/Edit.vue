<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :title="task.title" />
    </template>

    <template v-slot:default>
        <pre>
        <!-- {{task}} -->
        </pre>

        <div class="sm:flex justify-between sm:items-center traning_tabs">
            <div class="sm:flex-auto">
                <h1 class="inline-flex items-center text-3xl font-semibold text-gray-900">
                    <StatusDot :bgColor="task.task_status.length == 0 ?'bg-red-600':task.task_status[0].status==1 && task.task_status[0].display_status=='Upcoming'?'bg-yellow-500':task.task_status[0].status==null && task.task_status[0].request_status=='Pending'?'bg-blue-500':task.task_status[0].status==1 && task.task_status[0].display_status=='Active'?'bg-green-500':'bg-red-600'" />
                    {{task.title}}
                </h1>
            </div>
            <div class="sm:flex text-gray-400 gap-x-3 text-end">
                <template v-if="task.task_status.length == 0">
                    <span v-if="task.is_mandatory" class="flex gap-x-1">
                        <span class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                            <span class="mr-[8px]">
                                <WarningIconSm svgFill="#ef4444" />
                            </span>
                            <span class=" text-red-500 text-xsm">
                                Mandatory
                            </span>
                        </span>
                    </span>
                    <span class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                        <StatusDot bgColor="bg-red-600" />
                        <span class=" text-red-500">
                            Action Required
                        </span>
                    </span>
                </template>
                <template v-else>
                    <template v-if="task.task_status[0].status==1 && task.task_status[0].display_status=='Upcoming'">
                        <span v-if="task.is_mandatory" class="flex gap-x-1">
                            <span class="flex items-center border border-yellow-500 text-yellow-500 bg-yellow-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <span class="mr-[8px]">
                                    <WarningIcon svgFill="#d1d5db" />
                                </span>
                                <span class=" text-yellow-500 text-xsm">
                                    Mandatory
                                </span>
                            </span>
                        </span>
                        <span class="flex items-center border border-yellow-500 text-yellow-500 bg-yellow-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                            <StatusDot bgColor="bg-yellow-500" />
                            <span class=" text-yellow-500">
                                Upcoming
                            </span>
                        </span>
                    </template>
                    <template v-if="task.task_status[0].status==1 && task.task_status[0].display_status=='Active'">
                        <span v-if="task.is_mandatory" class="flex gap-x-1">
                            <span class="flex items-center border border-green-500 text-green-500 bg-green-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <span class="mr-[8px]">
                                    <WarningIconSm svgFill="#d1d5db" />
                                </span>
                                <span class=" text-green-500 text-xsm">
                                    Mandatory
                                </span>
                            </span>
                        </span>
                        <span class="flex items-center border border-green-500 text-green-500 bg-green-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                            <StatusDot bgColor="bg-green-500" />
                            <span class=" text-green-500">
                                Active
                            </span>
                        </span>
                    </template>
                    <template v-if="task.task_status[0].status==null && task.task_status[0].request_status=='Pending'">
                        <span v-if="task.is_mandatory" class="flex gap-x-1">
                            <span class="flex items-center border border-blue-500 text-blue-500 bg-blue-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <span class="mr-[8px]">
                                    <WarningIconSm svgFill="#60a5fa" />
                                </span>
                                <span class=" text-blue-500 text-xsm">
                                    Mandatory
                                </span>
                            </span>
                        </span>
                        <span class="flex items-center border border-blue-500 text-blue-500 bg-blue-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                            <StatusDot bgColor="bg-blue-500" />
                            <span class=" text-blue-500">
                                Pending
                            </span>
                        </span>
                    </template>
                    <template v-if="task.task_status[0].status==0">
                        <span v-if="task.is_mandatory" class="flex gap-x-1">
                            <span class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <span class="mr-[8px]">
                                    <WarningIconSm svgFill="#ef4444" />
                                </span>
                                <span class=" text-red-500 text-xsm">
                                    Mandatory
                                </span>
                            </span>
                        </span>
                        <span class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                            <StatusDot bgColor="bg-red-600" />
                            <span class=" text-red-500">
                                Action Required
                            </span>
                        </span>
                    </template>
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
                        {{Userhelper.user.lname}}, {{Userhelper.user.fname}}
                    </span>
                </div>
                <div class="mx-4 gap-x-2">
                    <strong class="text-sm text-gray-400 font-bold">Licence Number:</strong>
                    <span class="text-sm text-gray-400 font-medium">
                        {{Userhelper.user.license_number}}
                    </span>
                </div>
                <div class="mx-4 gap-x-2">
                    <strong class="text-sm text-gray-400 font-bold">Role:</strong>
                    <span v-if="Userhelper.user.is_training_admin === true" class="text-sm text-gray-400 font-medium">
                        Training Admin
                    </span>
                    <span v-if="Userhelper.user.is_training_officer === true" class="text-sm text-gray-400 font-medium">
                        Training Officer
                    </span>
                    <span v-if="Userhelper.user.is_training_manager === true" class="text-sm text-gray-400 font-medium">
                        Training Manager
                    </span>
                    <span v-if="Userhelper.user.is_training_engineer === true" class="text-sm text-gray-400 font-medium">
                        raining Engineeer
                    </span>
                    <span v-if="Userhelper.user.is_training_mechanic === true" class="text-sm text-gray-400 font-medium">
                        Training Mechanic
                    </span>
                </div>
            </div>
        </div>

        <div class="my-6 bg-white overflow-hidden border border-gray-300 shadow-sm sm:rounded-lg">
            <div class="px-6 py-3 mt-3 flex gap-x-8">
                <span class="flex items-center gap-x-1" v-if="task.revalidation_type == 0">
                    <DocumentIcon svgFill="#d1d5db" />
                    <span class="text-sm text-gray-400 text-medium">
                        Document Task
                    </span>
                </span>
                <span class="flex items-center gap-x-1" v-if="task.revalidation_type == 1">
                    <ListIcon svgFill="#d1d5db" />
                    <span class="text-sm text-gray-400 text-medium mb-1">
                        Assesment Task
                    </span>
                </span>
                <span class="flex items-center gap-x-1" v-if="task.revalidation_type == 2">
                    <ListIcon svgFill="#d1d5db" />
                    <span class="text-sm text-gray-400 text-medium mb-1">
                        Assesment Checklist Task
                    </span>
                </span>
                <span class="text-sm">{{task.sector_id.title}}</span>
            </div>
            <div class="mb-3 mt-2 px-6 pb-6 w-full border-b border-gray-300">
                <h1 class="text-xl font-semibold text-gray-900">{{task.title}}</h1>
                <div class="mt-3">
                    <p class="text-base text-gray-500" v-html="task.description"></p>
                </div>
            </div>
            <div v-if="attachments.length > 0" class="px-6 overflow-hidden sm:rounded-lg">
                <div class="block airframe-files">
                    <ViewDocument docType="Training Material" :attachments="attachments" />
                </div>
            </div>

            <template v-if="task.revalidation_type == 0">

                <template v-if="task.task_status.length > 0">
                    <div v-if="taskRequest_attachments.length > 0" class="px-6 overflow-hidden sm:rounded-lg">
                        <div class="block airframe-files">
                            <ViewDocument :attachments="taskRequest_attachments" />
                        </div>
                    </div>
                </template>

                <!-- <div class="my-6 p-6  bg-white overflow-hidden shadow-sm sm:rounded-lg"> -->
                <div v-if="files.length > 0" class="px-6 my-3 overflow-hidden sm:rounded-lg">
                    <div class="flex items-start justify-start space-x-4 my-3 w-full">
                        <h2 class="text-sm font-medium text-gray-500">New Documents</h2>
                        <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                            <li v-for="file in files" :key="file.id" class="flex flex-auto justify-between flex-a- p-3">
                                <span class="attachment-details">
                                    <span class="attachment-icon inline-block w-4 h-4 mr-2">
                                        <IconPaperclip />
                                    </span>
                                    <span class="attachment-name text-sm text-gray-500">
                                        {{ file.name }}
                                    </span>
                                </span>
                                <span class="attachment-size">
                                    <span class="jw-text__red cursor-pointer text-sm font-medium" @click="deleteFile(file)">Remove</span>
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
                <div v-else>
                    <template v-if="my_task.data.length > 0">
                        <template v-if="my_task.data[0].status == 0">
                            <div class="my-6 p-6  bg-white overflow-hidden shadow-sm sm:rounded-lg">
                                <div class="text-center text-sm text-gray-600 mb-6 rounded-md border-2 border-dashed border-gray-300 px-6 pt-7 pb-8">
                                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6 ">
                                        <span class="flex items-center justify-center mx-auto mb-1 icon jw-svg-stroke__gray-light w-8 h-8">
                                            <IconPaperclip svgFill="#6B7280" />
                                        </span>
                                        <label for="document-upload" class="relative cursor-pointer rounded-md font-sm text-gray-400">
                                            <span class="text-gray-400 text-sm block text-center font-medium">Add a document</span>
                                            <span class="jw-btn jw-btn__blue mt-4 ">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                                </svg>
                                                Add Document</span>
                                        </label>
                                        <input id="document-upload" name="files" @input="files = $event.target.files" class="sr-only" multiple="true" type="file" />
                                    </div>
                                </div>
                            </div>
                        </template>
                    </template>

                    <template v-else>
                        <div class="my-6 p-6  bg-white overflow-hidden shadow-sm sm:rounded-lg">
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
                        </div>
                    </template>
                </div>
                <div v-if="errors.files" class="text-red-500 text-xs pt-2">{{ errors.files }}</div>
            </template>

            <div class="sm:flex px-6 py-4 mt-5 justify-end bg-gray-lightest">
                <template v-if="my_task.data.length > 0">
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6" v-if="my_task.data[0].request_status == 'Pending'">
                        <button v-if="task.revalidation_type == 0" disabled class="jw-btn jw-btn__blue disabled:opacity-50" type="button">Submited</button>
                        <button v-else class="jw-btn jw-btn__blue disabled:opacity-50" disabled type="button">Assesment Requested</button>
                    </div>
                </template>
                <template v-if="my_task.data.length > 0">
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6 " v-if="my_task.data[0].status == 0">
                        <template v-if="task.revalidation_type == 0">
                            <button v-if="files.length > 0" class="jw-btn jw-btn__blue" type="button" @click="submitForm">Submit</button>
                            <button v-else class="jw-btn jw-btn__blue disabled:opacity-50" disabled type="button">Submit</button>
                        </template>
                        <button v-else class="jw-btn jw-btn__blue" type="button" @click="submitForm">Request Assesment</button>
                    </div>
                </template>
                <template v-else>
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6 ">
                        <template v-if="task.revalidation_type == 0">
                            <button v-if="files.length > 0" class="jw-btn jw-btn__blue" type="button" @click="submitForm">Submit</button>
                            <button v-else class="jw-btn jw-btn__blue disabled:opacity-50" disabled type="button">Submit</button>
                        </template>
                        <button v-else class="jw-btn jw-btn__blue" type="button" @click="submitForm">Request Assesment</button>
                    </div>
                </template>
            </div>
        </div>
        <template v-if="task.task_status[0] && task.task_status[0].task_request_history && task.task_status[0].task_request_history.length > 0">
            <div class="sm:flex-auto my-7">
                <h1 class="text-2xl font-semibold text-gray-900">History</h1>
            </div>
            <div class="bg-white border border-gray-300 overflow-hidden shadow-sm sm:rounded-lg">
                <div v-for="history in task.task_status[0].task_request_history" class="p-6 border-b last:border-b-0">
                    <div class="mt-2 mb-4 grid grid-cols-1 gap-y-6 sm:grid-cols-6">
                        <div class="col-span-12">
                            <div class="flex justify-between">
                                <h1 class="text-sm font-semibold text-gray-900">{{task.title}}</h1>
                                <div class="flex gap-x-8">
                                    <span v-if="history.previous_expiry" class="text-sm text-gray-400"> Previous Expiry : {{formatdate(history.previous_expiry)}}</span>
                                    <span class="text-gray-900 text-sm font-semibold"> {{history.task_req_result}} : {{formatdate(history.completed_date)}}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="flex  divide-x mb-8 text-gray-400 text-sm">
                        <div class="inline-flex items-center pr-8"><span class="pr-[.2rem] font-normal"> Trainee : </span><span class="font-semibold">{{task.task_status[0].user.lname}}, {{task.task_status[0].user.fname}}</span></div>
                        <div class="inline-flex items-center px-8"><span class="pr-[.2rem] font-normal"> Role: </span><span class="font-semibold">{{task.task_status[0].user.training_role.name}}</span></div>
                        <div class="inline-flex items-center px-8"><span class="pr-[.2rem] font-normal"> Completed by : </span><span class="font-semibold">{{history.completed_by}}</span></div>
                        <div class="inline-flex items-center px-8"><span class="pr-[.2rem] font-normal"> Modified : </span><span class="font-semibold">{{formatDbdate(history.created_at)}}</span></div>
                    </div>

                    <template v-if="task.revalidation_type == 0">

                        <ViewHistoryDocument :fileName="history.task_req_document_name" :filePath="history.task_req_document" />
                        <div v-if="history.task_req_comment" class="mt-2 mb-4 flex items-center space-y-2 flex-wrap sm:flex-nowrap">
                            <div class="min-w-[75px] mr-3">
                                <h1 class="text-sm text-gray-500 font-medium">Comments</h1>
                            </div>
                            <div v-html="history.task_req_comment" class="grow border w-full border-gray-300 p-3 rounded-md text-sm text-gray-500"></div>
                        </div>
                    </template>
                    <template v-else>
                        <div class="mt-2 flex items-center space-y-2 flex-wrap sm:flex-nowrap w-full">
                            <div class="min-w-[75px] mr-3">
                                <h1 class="text-xs gray-400 font-medium">Comments</h1>
                            </div>
                            <div  v-html="history.task_req_comment" class="grow border w-full border-gray-300 p-3 rounded-md text-sm text-gray-500">
                                
                            </div>
                        </div>
                        <div class="mt-2 flex items-center w-full space-y-2 flex-wrap sm:flex-nowrap">
                            <div class="min-w-[75px] mr-3">
                                <h1 class="text-xs gray-400 font-medium">Results</h1>
                            </div>
                            <div class="grow flex items-center border justify-between w-full space-x-4 border-gray-300 p-3 rounded-md">
                                <div class="flex items-center space-x-2">
                                    <ListIcon svgFill="#d1d5db" />
                                    <span class="text-gray-500 text-sm mb-1">
                                        View Results
                                    </span>
                                </div>
                                <span class="pointer">
                                    <a class="text-sm font-medium text-gray-900 pointer" :href="route('taskrequests.assesmentHistory', {'historyId' : history.id } )"> View</a>
                                </span>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </template>

    </template>
</AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import ViewDocument from "@/Jetworks/Common/ViewDocument.vue";
import ViewHistoryDocument from "@/Jetworks/Common/ViewHistoryDocument.vue";
import EntityIsActive from '@/Jetworks/Common/EntityIsActive.vue';
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";
import DocumentIcon from '@/Jetworks/Icons/DocumentIcon.vue';
import ListIcon from '@/Jetworks/Icons/ListIcon.vue';
import UserIcon from '@/Jetworks/Icons/UserIcon.vue';
import WarningIcon from '@/Jetworks/Icons/WarningIcon.vue';
import WarningIconSm from '@/Jetworks/Icons/WarningIconSm.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import Documents from "@/Jetworks/Common/Documents.vue";
import StatusDot from '@/Components/StatusDot.vue'

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
        ViewDocument,
        IconPaperclip,
        DocumentIcon,
        ListIcon,
        Documents,
        WarningIcon,
        WarningIconSm,
        UserIcon,
        ViewHistoryDocument,
        StatusDot,

    },
    data() {
        return {
            //         tabs: ['My Training', 'Awaiting Sign Off', 'Staff Training', 'Admin Settings'],
            //         selectedTabs: 'My Training',
            files: [],
            file_to_delete: null,

        }

    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        training_roles: {
            type: Object,
            default: {},
        },
        user: {
            type: Object,
            default: {},
        },
        task: {
            type: Object,
            default: {},
        },
        my_task: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        attachments: {
            type: Object,
            default: {},
        },
        taskRequest_attachments: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Training',
        },
    },
    methods: {

        formatdate: function (date) {
            // console.log(date);
            if (date == null) {
                const formattedDate = ' ';
                return formattedDate
            } else {
                const parts = date.split(/[- :]/);
                const formattedDate = `${parts[2]}/${parts[1]}/${parts[0].slice(-2)}`;
                return formattedDate
            }
        },

        formatDbdate: function (data) {
            const date = new Date(data);

            const day = date.getUTCDate().toString().padStart(2, '0');
            const month = (date.getUTCMonth() + 1).toString().padStart(2, '0'); // Adding 1 because months are zero-indexed
            const year = date.getUTCFullYear().toString().substr(-2);

            const formattedDate = `${day}/${month}/${year}`;
            return formattedDate

        },

        submitForm() {
            // Combine static field value and dynamic input values into one object
            const formData = {
                files: this.files,
                task_id: this.task.id,
                task_request: this.task.task_status,
                revalidation_type: this.task.revalidation_type,
            };
            // this.appendData();
            // console.log(formData);
            Inertia.post(route('taskrequests.store'), formData);
        },

        resetDocuments: function () {
            this.files = [];
        },

        deleteFile(fileToDelete) {
            this.files = [];
        },
    },
    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper
        }
    },

}
</script>

<style>
.activeTab p {
    color: red;
}
</style>
