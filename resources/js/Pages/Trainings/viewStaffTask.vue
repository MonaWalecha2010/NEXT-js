<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :title="pageHeader" />
    </template>
    <template v-slot:default>
        {{taskRequest}}

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="inline-flex items-center text-3xl font-semibold text-gray-900">
                    <StatusDot :bgColor="taskRequest.task.status==1 && taskRequest.task.display_status=='Active' ?'bg-green-500':taskRequest.task.status==1 && taskRequest.task.display_status=='Upcoming'?'bg-yellow-500':'bg-red-600'" />
                    {{taskRequest.task.title}}
                </h1>
            </div>
            <div class="sm:flex text-gray-300 gap-x-7 text-end">
                <span class="flex gap-x-1">
                    <template v-if="taskRequest.task.revalidation_type == 0">
                        <template v-if="taskRequest.task.status==1 && taskRequest.task.display_status=='Active'">
                            <span class="flex items-center border border-green-500 text-green-500 bg-green-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <StatusDot bgColor="bg-green-600" />
                                <span class=" text-green-500">
                                    Awaiting Sign Off
                                </span>
                            </span>
                        </template>
                        <template v-else-if="taskRequest.task.status==1 && taskRequest.task.display_status=='Upcoming'">
                            <span class="flex items-center border border-yellow-500 text-yellow-600 bg-yellow-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <StatusDot bgColor="bg-yellow-500" />
                                <span class=" text-yellow-500">
                                    Awaiting Sign Off
                                </span>
                            </span>
                        </template>
                        <template v-else>
                            <span class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <StatusDot bgColor="bg-red-600" />
                                <span class=" text-red-500">
                                    Awaiting Sign Off
                                </span>
                            </span>
                        </template>
                    </template>
                    <template v-else>
                        <template v-if="taskRequest.task.status==1 && taskRequest.task.display_status=='Active'">
                            <span class="flex items-center border border-green-500 text-green-500 bg-green-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <StatusDot bgColor="bg-green-600" />
                                <span class=" text-green-500">
                                    Assessment Requested
                                </span>
                            </span>
                        </template>
                        <template v-else-if="taskRequest.task.status==1 && taskRequest.task.display_status=='Upcoming'">
                            <span class="flex items-center border border-yellow-500 text-yellow-600 bg-yellow-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <StatusDot bgColor="bg-yellow-500" />
                                <span class=" text-yellow-500">
                                    Assessment Requested
                                </span>
                            </span>
                        </template>
                        <template v-else>
                            <span class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                                <StatusDot bgColor="bg-red-600" />
                                <span class=" text-red-500">
                                    Assessment Requested
                                </span>
                            </span>
                        </template>
                    </template>
                </span>
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
                    <strong class="text-sm text-gray-400 font-bold">Licence Number: </strong>
                    <span class="text-sm text-gray-400 font-medium">
                        {{taskRequest.user.license_number}}
                    </span>
                </div>
                <div class="mx-4 gap-x-2">                   
                    <strong class="text-sm text-gray-400 font-bold">Role:</strong>                   
                    <span v-if="taskRequest.user.training_role_id == 1" class="text-sm text-gray-400 font-medium">
                        Training Admin
                    </span>
                    <span v-if="taskRequest.user.training_role_id == 2" class="text-sm text-gray-400 font-medium">
                        Training Officer
                    </span>
                    <span v-if="taskRequest.user.training_role_id == 3" class="text-sm text-gray-400 font-medium">
                        Training Manager
                    </span>
                    <span v-if="taskRequest.user.training_role_id == 4" class="text-sm text-gray-400 font-medium">
                        raining Engineeer
                    </span>
                    <span v-if="taskRequest.user.training_role_id == 5" class="text-sm text-gray-400 font-medium">
                        Training Mechanic
                    </span>
                </div>
            </div>
        </div>
        <!-- {{taskRequest}} -->
        <div class="my-6 bg-white overflow-hidden border border-gray-300 shadow-sm sm:rounded-lg">
            <div class="px-6 py-3 mt-3 flex gap-x-8">                        
                <span class="flex items-center gap-x-1" v-if="taskRequest.task.revalidation_type == 0">
                    <DocumentIcon svgFill="#d1d5db" />
                    <span class="text-sm text-gray-400 text-medium">
                        Document Task
                    </span>
                </span>
                <span class="flex items-center gap-x-1" v-if="taskRequest.task.revalidation_type == 1" >
                    <ListIcon svgFill="#d1d5db" />                    
                    <span class="text-sm text-gray-400 text-medium">
                        Assesment Task
                    </span>
                </span>
                <span class="flex items-center gap-x-1" v-if="taskRequest.task.revalidation_type == 2">                    
                    <ListIcon svgFill="#d1d5db" />                    
                    <span class="text-sm text-gray-400 text-medium">
                        Assesment Checklist Task
                    </span>
                </span>
                <span class="text-sm">{{taskRequest.task.sector.title}}</span> 
            </div>
            <div class="mb-3 mt-2 px-6 pb-6 w-full border-b border-gray-300">
                <h1 class="text-xl font-semibold text-gray-900">{{taskRequest.task.title}}</h1>
                <div class="mt-3">
                    <p class="text-base text-gray-500" v-html="taskRequest.task.description"></p>
                </div>
            </div> 
            <div v-if="attachments.length > 0" class="px-6 overflow-hidden sm:rounded-lg">
                <div class="block airframe-files">
                    <ViewDocument :attachments="attachments" />
                </div>
            </div>

            <template v-if="taskRequest.task.revalidation_type != 0">
                <div v-if="training_material.length > 0" class="px-6 bg-white overflow-hidden shadow-sm border-t">
                    <div class="block airframe-files">
                        <ViewDocument docType="Training Material" :attachments="training_material" />
                    </div>
                </div>
            </template>

            <div class="sm:flex justify-end px-6 py-4 bg-gray-lightest">
                <div class="sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                    <button v-if="taskRequest.task.revalidation_type == 0" @click="redirectToValidation(taskRequest.id)" class="jw-btn jw-btn__blue" type="button">Review Document</button>
                    <button v-else class="jw-btn jw-btn__blue" @click="redirectToAssesment(taskRequest.id)" type="button">Start Assesment</button>
                </div>
            </div>
        </div>
    </template>
</AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import EntityIsActive from '@/Jetworks/Common/EntityIsActive.vue';
import MyTrainings from '@/Jetworks/Training/MyTrainings/MyTrainings.vue';
import AwaitingSignOff from '@/Jetworks/Training/AwaitingSignOff/AwaitingSignOff.vue';
import StaffTraining from '@/Jetworks/Training/StaffTraining/StaffTraining.vue';
import AdminSettings from '@/Jetworks/Training/AdminSettings/AdminSettings.vue';
import DocumentIcon from '@/Jetworks/Icons/DocumentIcon.vue';
import ListIcon from '@/Jetworks/Icons/ListIcon.vue';
import WarningIcon from '@/Jetworks/Icons/WarningIcon.vue';
import IconPen from '@/Jetworks/Icons/Pen.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import RoundedTextPill from '@/Components/RoundedTextPill.vue'
import ViewDocument from "@/Jetworks/Common/ViewDocument.vue";

import {
    Userhelper
} from "@/Jetworks/Userhelper";

export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        MyTrainings,
        AwaitingSignOff,
        StaffTraining,
        AdminSettings,
        IconPen,
        DocumentIcon,
        ListIcon,
        WarningIcon,
        RoundedTextPill,
        ViewDocument,
    },
    data(props) {
        return {
            // my_tasks: props.tasks.slice(0, this.iter),
            // descending: true,
        }
    },
    setup(props) {
        let iter = 10;
        return {
            iter,
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
        training_material: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'View Staff Task',
        },
    },
    methods: {
        formatExpiry: function (date) {

            if (date == null) {
                const formattedDate = '--/--/--';
                return formattedDate
            } else {
                const parts = date.split('-');
                const formattedDate = `${parts[2]}/${parts[1]}/${parts[0].slice(-2)}`;
                return formattedDate
            }
        },

        // viewAllMyTasks: function () {
        //     this.iter = this.tasks.length;
        //     let descending = this.descending;
        //     let defaultDescending;

        //     if (descending == false) {
        //         defaultDescending = true;
        //     } else {
        //         defaultDescending = false;
        //     }

        //     axios.post(route('tasks.sortMyTasks', {
        //             'descending': defaultDescending,
        //         }))
        //         .then((response) => {
        //             if (response.data.success === true) {
        //                 this.my_tasks = response.data.data.tasks.slice(0, this.iter);
        //             }
        //         }).catch(error => {
        //             console.log(error);
        //         });
        // },

        // sort: function () {
        //     this.my_tasks = this.tasks;
        //     let descending = this.descending;

        //     if (descending == false) {
        //         this.descending = true;
        //     } else {
        //         this.descending = false;
        //     }

        //     axios.post(route('tasks.sortMyTasks', {
        //             'descending': descending,
        //         }))
        //         .then((response) => {
        //             if (response.data.success === true) {
        //                 this.my_tasks = response.data.data.tasks.slice(0, this.iter);
        //             }
        //         }).catch(error => {
        //             console.log(error);
        //         });
        // },

        truncateString: function (string, maxLength) {
            if (string.length > maxLength) {
                return string.substring(0, maxLength) + '.....';
            } else {
                return string;
            }
        }
    },

    // beforeMount() {
    //     this.sortTasks();
    // },

}
</script>
