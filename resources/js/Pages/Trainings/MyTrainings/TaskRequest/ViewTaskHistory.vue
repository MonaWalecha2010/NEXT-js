<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :url="route('taskrequests.edit', {'id' : task_history.task_req_id.task.id } )" :title="task_history.task_req_id.task.title" />
        <BreadcrumbItem :title="`${task_history.task_req_id.task.title} Assesment-Checklist`" />
    </template>

    <template v-slot:default>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">{{task_history.task_req_id.task.title}}</h1>
            </div>
            <div class="sm:flex text-gray-400 gap-x-7 text-end">
                <span class="flex gap-x-1">
                    <span v-if="task_history.task_req_result == 'Rejected'" class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                        <StatusDot bgColor="bg-red-600" />
                        <span class=" text-red-600">Failed</span>
                    </span>
                    <span v-else class="flex items-center border border-green-500 text-green-500 bg-green-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                        <StatusDot bgColor="bg-green-500" />
                        <span class=" text-green-500">Passed</span>
                    </span>
                </span>
            </div>
        </div>

        <div class="sm:flex sm:items-center my-5">
            <div class="sm:flex items-center">
                <div class="flex items-center">
                    <span class="mt-1"> 
                        <UserIcon svgFill="#9CA3AF" />
                    </span>                    
                    <span class="sm:ml-2 text-sm text-gray-400 font-medium">
                        {{task_history.task_req_id.user.lname}} , {{task_history.task_req_id.user.fname}}
                    </span>
                </div>                
                <div class="mx-4 gap-x-2">
                    <strong class="text-sm text-gray-400">Licence Number: </strong>
                    <span class="text-sm text-gray-400 font-medium">
                        {{task_history.task_req_id.user.license_number}}
                    </span>
                </div>
                <div class="mx-4 gap-x-2">
                    <strong class="text-sm text-gray-400 font-bold">Role:</strong>
                    <span v-if="task_history.task_req_id.user.training_role_id == 1" class="text-sm text-gray-400 font-medium">
                        Training Admin
                    </span>
                    <span v-if="task_history.task_req_id.user.training_role_id == 2" class="text-sm text-gray-400 font-medium">
                        Training Officer
                    </span>
                    <span v-if="task_history.task_req_id.user.training_role_id == 3" class="text-sm text-gray-400 font-medium">
                        Training Manager
                    </span>
                    <span v-if="task_history.task_req_id.user.training_role_id == 4" class="text-sm text-gray-400 font-medium">
                        raining Engineeer
                    </span>
                    <span v-if="task_history.task_req_id.user.training_role_id == 5" class="text-sm text-gray-400 font-medium">
                        Training Mechanic
                    </span>
                </div>
            </div>
        </div>
        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table v-if="task_history.task_req_id.task" class="jw-table min-w-full">
                <template v-if="task_history.task_req_id.task.revalidation_type == 1">
                    <thead class="bg-white">
                        <tr class="bg-white">
                            <th scope="col">
                                <span class="text-sm text-gray-400 font-medium">No.</span>
                            </th>
                            <th scope="col">
                                <span class="text-sm text-gray-400 font-medium">Topic</span>
                            </th>
                            <th class="text-center" scope="col">
                                <span class="text-sm text-gray-400 font-medium">Result</span>
                            </th>
                            <th scope="col" class="w-[30%]">
                                <span class="text-sm text-gray-400 font-medium">Comment</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <template v-for="(assesment , index) in task_history.task_req_id.task.assesments">
                            <tr class="border-t border-gray-300  text-gray-300 bg-gray-100 ">
                                <td class="text-gray-200" colspan="3">
                                    <div class="text-sm text-gray-400 font-medium divide-x">
                                        <span class="pr-5">Section {{index+1}}</span>
                                        <span class="px-5">{{assesment.title}}</span>
                                    </div>
                                </td>
                                <td class="text-end">
                                    <div class="min-w-[80px] inline-block mx-auto cursor-pointer border px-4 py-2 text-center rounded-full" :class="history_data[2][assesment.id] == 1?PassClass:history_data[2][assesment.id] == 0?FailClass:defaultClass">
                                        <span>{{history_data[2][assesment.id] == 1?'Pass':history_data[2][assesment.id]  == 0?'Fail':'Select'}}</span>
                                    </div>
                                </td>
                            </tr>
                            <tr v-for="(assesment_checklist , index) in assesment.assesment_checklist" class="border-t border-gray-300  text-gray-300 bg-white ">
                                <td class="text-gray-400 align-baseline">{{assesment_checklist.asigned_check_num}}</td>
                                <td class="align-baseline">
                                    <p class="text-sm text-gray-900 font-medium capitalize">{{assesment_checklist.check_value}}</p>
                                    <div class="mt-2 text-sm text-gray-500">{{assesment_checklist.check_description}}</div>
                                </td>
                                <td class="text-center">
                                    <div class="min-w-[80px] inline-block mx-auto cursor-pointer border px-4 py-2 text-center rounded-full" :class="history_data[0][assesment_checklist.id] == 1?PassClass:history_data[0][assesment_checklist.id] == 0?FailClass:defaultClass">
                                        <span>{{history_data[0][assesment_checklist.id] == 1?'Pass':history_data[0][assesment_checklist.id]  == 0?'Fail':'Select'}}</span>
                                    </div>
                                    <!-- <input class="hidden" v-model="assesmentSectionsResult[assesment_checklist.id]" type="text"> -->
                                </td>
                                <td class="text-right">
                                    <textarea rows="2" style="resize: none;" placeholder="..." class="text-sm block w-full rounded-md border border-gray-300 bg-gray-50 text-gray-500 placeholder:text-sm" disabled>{{history_data[1][assesment_checklist.id]}}</textarea>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </template>

                <template v-if="task_history.task_req_id.task.revalidation_type == 2">
                    <thead class="bg-white">
                        <tr class="bg-white">
                            <th scope="col">
                                No.
                            </th>
                            <th scope="col">
                                Skill
                            </th>
                            <th class="text-center" scope="col">

                            </th>
                            <th scope="col">

                            </th>
                            <th scope="col" class="text-center">
                                Check
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <template v-for="(assesment , index) in task_history.task_req_id.task.assesments">
                            <tr class="border-t border-gray-300  text-gray-300 bg-gray-100 ">
                                <td class="text-gray-200">Section {{index + 1}}</td>
                                <td>{{assesment.title}}</td>
                                <td class="flex justify-center items-center mr-7  mt-5">
                                </td>
                                <td class="text-end">

                                </td>
                                <td class="text-center">
                                    <div class="min-w-[80px] inline-block mx-auto cursor-pointer border px-4 py-2 text-center rounded-full" :class="history_data[2][assesment.id] == 1?PassClass:history_data[2][assesment.id] == 0?FailClass:defaultClass">
                                        <span>{{history_data[2][assesment.id] == 1?'Pass':history_data[2][assesment.id]  == 0?'Fail':'Select'}}</span>
                                    </div>
                                </td>
                            </tr>

                            <tr v-for="(assesment_checklist , index) in assesment.assesment_checklist" class="border-t border-gray-300  text-gray-300 bg-white ">
                                <td class="text-gray-200">{{assesment_checklist.asigned_check_num}}</td>
                                <td class="pt-5">
                                    <p class="">{{assesment_checklist.check_value}}</p>
                                </td>
                                <td class="text-center" scope="col">

                                </td>
                                <td scope="col">

                                </td>
                                <td scope="col" class="text-center">
                                    <input v-if="history_data[0][assesment_checklist.id] == 1" type="checkbox" checked disabled class="w-4 h-4 text-green-600 bg-gray-100 border-gray-300 rounded focus:ring-green-500 dark:focus:ring-green-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                    <input v-if="history_data[0][assesment_checklist.id] == 0" type="checkbox" checked disabled class="w-4 h-4 text-red-600 bg-gray-100 border-gray-300 rounded focus:ring-red-500 dark:focus:ring-red-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600">
                                </td>
                            </tr>
                        </template>

                    </tbody>
                </template>

            </table>
        </div>

        <!-- <div class="overflow-hidden shadow ring-1 mt-5 ring-black ring-opacity-5 md:rounded-lg bg-white py-7">
            <h1 class="text-3xl mx-8 my-3 text-black">Final Comments</h1>
            <div class="bg-gray-50 px-4 py-3 sm:px-6">
                <h2 class="text-sm my-3 text-black">Add any Comments below</h2>
                <textarea v-model="assesmentComment" class="rounded w-full" rows="4"></textarea>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6">
                <button class="inline-flex w-full justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-base font-medium text-gray-500 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-100 focus:ring-offset-2 sm:ml-3 sm:w-auto sm:text-sm" type="button">
                    Cancel
                </button>
                <button @click="showSignoff" class="mt-3 inline-flex w-full justify-center rounded-md border border-transparent bg-blue-600 px-4 py-2 text-base font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" type="button">
                    Sign Off
                </button>
            </div>
        </div> -->
    </template>
</AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ViewDocument from "@/Jetworks/Common/ViewDocument.vue";
import DocumentIcon from '@/Jetworks/Icons/DocumentIcon.vue';
import ListIcon from '@/Jetworks/Icons/ListIcon.vue';
import WarningIcon from '@/Jetworks/Icons/WarningIcon.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import SignOffAssesmentConfirmation from "@/Jetworks/Common/SignOffAssesmentConfirmation.vue";
import UserIcon from '@/Jetworks/Icons/UserIcon.vue';
import StatusDot from '@/Components/StatusDot.vue'

import {
    Inertia
} from '@inertiajs/inertia';
import {
    useForm
} from '@inertiajs/inertia-vue3';
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
        SignOffAssesmentConfirmation,
        UserIcon,
        StatusDot
    },

    data() {
        return {
            defaultSelected: true,
            signoff: false,
            taskRequestdata: this.taskRequest,
            showModal: false,
            assesmentSectionsResult: {},
            assesmentSectionsComment: {},
            selectedPopup: null,
            defaultClass: 'text-blue-700 border-blue-700 bg-blue-100',
            PassClass: 'text-green-700 border-green-700 bg-green-100',
            FailClass: 'text-red-700 border-red-700 bg-red-100',
            assesmentComment: null
        }
    },

    props: {
        task_history: {
            type: Object,
        },

        history_data: {
            type: Object,
        },

        pageHeader: {
            type: String,
            default: 'Task History',
        },
    },

    methods: {
        unserializeData() {
            let revalidation_type = this.history_data;
            console.log(revalidation_type);
        }
    },
    beforeMount() {
        this.unserializeData();
    },
}
</script>

<style>
.activeTab p {
    color: red;
}
</style>
