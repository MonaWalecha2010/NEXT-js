<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :url="route('taskrequests.validates',{'taskRequest' : taskRequest.id })" :title="taskRequest.task.title" />
        <BreadcrumbItem :title="`${taskRequest.task.title} Assesment-Checklist`" />
    </template>

    <template v-slot:default>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">{{taskRequest.task.title}}</h1>
            </div>
            <div class="sm:flex text-gray-300 gap-x-7 text-end">
                <span class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                    <StatusDot bgColor="bg-red-600" />
                    <span class=" text-red-500">Awaiting Sign Off</span>
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
                        {{taskRequest.user.lname}}, {{taskRequest.user.fname}}
                    </span>
                </div> 
                <div class="mx-4 gap-x-2">
                    <strong class="text-sm text-gray-400 font-bold">Licence Number:</strong>
                    <span class="text-sm text-gray-400 font-medium">
                        {{taskRequest.user.license_number}}
                    </span>
                </div> 
                <div class="mx-4 gap-x-2 flex items-center">
                    <strong class="text-gray-400 text-sm font-bold">Role:</strong>
                    <span class="text-gray-400 text-sm font-medium" v-if="taskRequest.user.training_role_id == 1">
                        Training Admin
                    </span>
                    <span class="text-gray-400 text-sm font-medium" v-if="taskRequest.user.training_role_id == 2">
                        Training Officer
                    </span>
                    <span class="text-gray-400 text-sm font-medium" v-if="taskRequest.user.training_role_id == 3">
                        Training Manager
                    </span>
                    <span class="text-gray-400 text-sm font-medium" v-if="taskRequest.user.training_role_id == 4">
                        raining Engineeer
                    </span>
                    <span class="text-gray-400 text-sm font-medium" v-if="taskRequest.user.training_role_id == 5">
                        Training Mechanic
                    </span>
                </div>
            </div>
        </div>

        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table v-if="taskRequest.task" class="jw-table min-w-full">
                <template v-if="taskRequest.task.revalidation_type == 1">
                    <thead class="bg-white">
                        <tr class="bg-white">
                            <th scope="col" class="max-w-8">
                                <span class="text-gray-400 text-medium">No.</span>
                            </th>
                            <th scope="col">
                                <span class="text-gray-400 text-medium">Topic</span>
                            </th>
                            <th class="text-center" scope="col">
                                <span class="text-gray-400 text-medium">Result</span>
                            </th>
                            <th scope="col " class="w-[30%]">
                                <span class="text-gray-400 text-medium">Comment</span>
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <template v-for="(assesment , index) in taskRequest.task.assesments">
                            <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                                <td class="text-gray-400" colspan="3">
                                    <div class="text-sm text-gray-400 font-medium divide-x">
                                        <span class="pr-5">Section {{index+1}}</span>
                                        <span class="px-5">{{assesment.title}}</span>
                                    </div>
                                </td>

                                <td class="text-end">
                                    <div class="min-w-20 mx-auto cursor-pointer border px-4 py-2  inline-flex items-center space-between rounded-full" :class="sectionsResult[assesment.id] == 1?PassClass:sectionsResult[assesment.id] == 0?FailClass:defaultClass" @click="selectSectionResult(assesment.id)">
                                        <span>{{sectionsResult[assesment.id] == 1?'Pass':sectionsResult[assesment.id] == 0?'Fail':'Select'}}</span>
                                        <span class="ml-1.5">
                                            <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.5 4L4.5 1L1.5 4" :stroke="sectionsResult[assesment.id] == 1?'#10B981':sectionsResult[assesment.id] == 0?'#DC2626':'#1989E9'" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M1.5 8L4.5 11L7.5 8" :stroke="sectionsResult[assesment.id] == 1?'#10B981':sectionsResult[assesment.id] == 0?'#DC2626':'#1989E9'" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                    <input class="hidden" v-model="sectionsResult[assesment.id]" type="text">
                                </td>

                                <div v-if="selectedSectionPopup === assesment.id" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                    <div class="fixed inset-0 z-10 overflow-y-auto">
                                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start justify-center">
                                                        <div class="w-full relative text-center">
                                                            <h3 id="modal-title" class="z-30 text-2xl font-semibold leading-6 text-gray-900">Choose a Result</h3>
                                                            <span @click="closeModal()" class="absolute z-40 -top-2 right-0 cursor-pointer">
                                                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M18 18L10.5 10.5M18 18L25.5 25.5M18 18L25.5 10.5M18 18L10.5 25.5" stroke="#9CA3AF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>

                                                            </span>
                                                        </div>

                                                        <div class="my-4">
                                                            <p class="text-sm text-gray-400">
                                                                <slot name="default" />
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 w-full px-4 py-3 flex gap-3 ">
                                                    <button @click="sectionFail(assesment.id)" class="grow jw-btn-md jw-btn__red" type="button">
                                                        Fail
                                                    </button>
                                                    <button @click="sectionPass(assesment.id)" class="grow jw-btn-md jw-btn__green" type="button">
                                                        Pass
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>

                            <tr v-for="(assesment_checklist , index) in assesment.assesment_checklist" class="border-t border-gray-300  text-gray-400 bg-white ">
                                <td class="text-gray-400 align-baseline">{{assesment_checklist.asigned_check_num}}</td>
                                <td class="align-baseline">
                                    <p class="text-sm text-gray-900 font-medium capitalize">{{assesment_checklist.check_value}}</p>
                                    <div class="mt-2 text-sm text-gray-500">{{assesment_checklist.check_description}}</div>
                                </td>
                                <td class="text-center">
                                    <div class="min-w-20 mx-auto cursor-pointer border px-4 py-2  inline-flex items-center space-between rounded-full" :class="assesmentSectionsResult[assesment_checklist.id] == 1?PassClass:assesmentSectionsResult[assesment_checklist.id] == 0?FailClass:defaultClass" @click="selectResult(assesment_checklist.id)">
                                        <span>{{assesmentSectionsResult[assesment_checklist.id] == 1?'Pass':assesmentSectionsResult[assesment_checklist.id] == 0?'Fail':'Select'}}</span>
                                        <span class="ml-1.5">
                                            <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.5 4L4.5 1L1.5 4" :stroke="assesmentSectionsResult[assesment_checklist.id] == 1?'#10B981':assesmentSectionsResult[assesment_checklist.id] == 0?'#DC2626':'#1989E9'" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M1.5 8L4.5 11L7.5 8" :stroke="assesmentSectionsResult[assesment_checklist.id] == 1?'#10B981':assesmentSectionsResult[assesment_checklist.id] == 0?'#DC2626':'#1989E9'" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                    <input class="hidden" v-model="assesmentSectionsResult[assesment_checklist.id]" type="text">
                                </td>
                                <td class="text-right">
                                    <textarea rows="2" placeholder="Add a Comment..." class="block w-full placeholder:text-sm text-sm text-gray-900 rounded-md border border-gray-300 bg-white" v-model="assesmentSectionsComment[assesment_checklist.id]" style="resize: none;"></textarea>
                                </td>
                                <!-- ----------------------------Popup------------------------------ -->
                                <div v-if="selectedPopup === assesment_checklist.id" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                    <div class="fixed inset-0 z-10 overflow-y-auto">
                                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start justify-center">
                                                        <div class="w-full relative text-center">
                                                            <h3 id="modal-title" class="z-30 text-2xl font-semibold leading-6 text-gray-900">Choose a Result</h3>
                                                            <span @click="closeModal()" class="absolute z-40 -top-2 right-0 cursor-pointer">
                                                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M18 18L10.5 10.5M18 18L25.5 25.5M18 18L25.5 10.5M18 18L10.5 25.5" stroke="#9CA3AF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>

                                                            </span>
                                                        </div>

                                                        <div class="my-4">
                                                            <p class="text-sm text-gray-400">
                                                                <slot name="default" />
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 w-full px-4 py-3 flex gap-3 ">
                                                    <button @click="assesmentFail(assesment_checklist.id)" class="grow jw-btn-md jw-btn__red" type="button">
                                                        Fail
                                                    </button>
                                                    <button @click="assesmentPass(assesment_checklist.id)" class="grow jw-btn-md jw-btn__green" type="button">
                                                        Pass
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                        </template>
                    </tbody>
                </template>

                <template v-if="taskRequest.task.revalidation_type == 2">
                    <thead class="bg-white">
                        <tr class="bg-white">
                            <th scope="col " style="width:7%"> <span class="text-gray-400 text-medium">No.</span></th>
                            <th scope="col"> <span class="text-gray-400 text-medium">Skill</span> </th>
                            <th class="text-center" scope="col"></th>
                            <th scope="col"> </th>
                            <th scope="col" style="width:10%" class="text-end"><span class="text-gray-400 text-medium sm:mr-3">Check</span></th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <template v-for="(assesment , index) in taskRequest.task.assesments">
                            <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                                <td colspan="4">
                                    <div class="divide-x divide-gray-300 text-gray-400  font-medium">
                                        <span class="inline-block pr-2">Section {{index + 1}}</span> <span class="inline-block pl-3">{{assesment.title}}</span> </div>
                                </td>
                                <td class="text-end">
                                    <div class="min-w-20 mx-auto cursor-pointer border px-4 py-2  inline-flex items-center space-between rounded-full sm: mr-3" :class="sectionsResult[assesment.id] == 1?PassClass:sectionsResult[assesment.id] == 0?FailClass:defaultClass" @click="selectSectionResult(assesment.id)">
                                        <span>{{sectionsResult[assesment.id] == 1?'Pass':sectionsResult[assesment.id] == 0?'Fail':'Select'}}</span>
                                        <span class="ml-1.5">
                                            <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.5 4L4.5 1L1.5 4" :stroke="sectionsResult[assesment.id] == 1?'#10B981':sectionsResult[assesment.id] == 0?'#DC2626':'#1989E9'" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M1.5 8L4.5 11L7.5 8" :stroke="sectionsResult[assesment.id] == 1?'#10B981':sectionsResult[assesment.id] == 0?'#DC2626':'#1989E9'" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                    <input class="hidden" type="text" v-model="sectionsResult[assesment.id]">
                                </td>

                                <div v-if="selectedSectionPopup === assesment.id" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
                                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                                    <div class="fixed inset-0 z-10 overflow-y-auto">
                                        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                                            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                    <div class="sm:flex sm:items-start justify-center">
                                                        <div class="w-full relative text-center">
                                                            <h3 id="modal-title" class="z-30 text-2xl font-semibold leading-6 text-gray-900">Choose a Result</h3>
                                                            <span @click="closeModal()" class="absolute z-40 -top-2 right-0 cursor-pointer">
                                                                <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M18 18L10.5 10.5M18 18L25.5 25.5M18 18L25.5 10.5M18 18L10.5 25.5" stroke="#9CA3AF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>

                                                            </span>
                                                        </div>

                                                        <div class="my-4">
                                                            <p class="text-sm text-gray-400">
                                                                <slot name="default" />
                                                            </p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="bg-gray-50 w-full px-4 py-3 flex gap-3 ">
                                                    <button @click="sectionFail(assesment.id)" class="grow jw-btn-md jw-btn__red" type="button">
                                                        Fail
                                                    </button>
                                                    <button @click="sectionPass(assesment.id)" class="grow jw-btn-md jw-btn__green" type="button">
                                                        Pass
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </tr>
                            <tr v-for="(assesment_checklist , index) in assesment.assesment_checklist" class="border-t border-gray-300  text-gray-300 bg-white ">
                                <td class="text-sm text-gray-900 font-medium">{{assesment_checklist.asigned_check_num}}</td>
                                <td class="" colspan="3">
                                    <p class="text-sm text-gray-900 font-medium">{{assesment_checklist.check_value}}</p>
                                </td>
                                <td class="text-end">
                                    <input type="checkbox" class="border-gray-300 rounded-lg w-8 h-8 focus:outline-none focus:ring-0 sm: mr-3 " v-model="assesmentSectionsResult[assesment_checklist.id]">
                                </td>
                            </tr>
                        </template>

                    </tbody>
                </template>

            </table>

            <SignOffAssesmentConfirmation v-if="signoff" :action="'Pass'" :model="signoff" :title="'Sign Off Assesment '" @confirmModalConfirmation="listenForModalConfirmation">
                Please confirm that you would like to sign off this assesment
            </SignOffAssesmentConfirmation>
        </div>

        <div class="overflow-hidden shadow ring-1 mt-6 ring-black ring-opacity-5 md:rounded-lg bg-gray-50 pt-5">
            <h1 class="text-2xl font-semibold mx-8 mb-4 text-gray-900">Final Comments</h1>
            <div class="bg-white py-3 px-4 sm:px-6 exclude-toolbar ">
                <h2 class="text-sm my-3 font-medium text-gray-900 ">Add any Comments below</h2>
                <!-- <div class="sm:col-span-3 exclude-toolbar small"> -->
                <QuillEditor ref="quill" v-model:content="assesmentComment" content="html" contentType="html" theme="snow" toolbar="#exclude-toolbar" />
                <span id="exclude-toolbar" class="hidden"></span>
                <!-- <textarea v-model="assesmentComment" class="border-gray-300 rounded w-full text-gray-500 font-normal text-sm" rows="4"></textarea> -->
            </div>
            <div class=" px-4 py-4 space-x-4 text-center sm:justify-end sm:flex sm:px-6 bg-gray-lightest w-full">
                <a class="jw-btn-md border border-gray-300 text-gray-500 btn-cancel bg-white text-base sm:w-24 " :href="route('taskrequests.validates', {'taskRequest' : taskRequest.id } )">
                    Cancel
                </a>
                <button @click="showSignoff" class="jw-btn jw-btn__blue text-base sm:w-44 " type="button">
                    Sign Off
                </button>
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
import WarningIcon from '@/Jetworks/Icons/WarningIcon.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import SignOffAssesmentConfirmation from "@/Jetworks/Common/SignOffAssesmentConfirmation.vue";
import UserIcon from '@/Jetworks/Icons/UserIcon.vue';
import StatusDot from '@/Components/StatusDot.vue'
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
        StatusDot,
        QuillEditor
    },

    data() {
        return {
            defaultSelected: true,
            signoff: false,
            taskRequestdata: this.taskRequest,
            showModal: false,
            sectionsResult: {},
            assesmentSectionsResult: {},
            assesmentSectionsComment: {},
            selectedPopup: null,
            selectedSectionPopup: null,
            defaultClass: 'text-blue-300 border-blue-300 bg-blue-100',
            PassClass: 'text-green-500 border-green-500 bg-green-100',
            FailClass: 'text-red-600 border-red-600 bg-red-100',
            assesmentComment: null,
            quill: null
        }
    },

    props: {
        // can: {
        //     type: Object,
        //     default: {},
        // },
        taskRequest: {
            type: Object,
            default: {},
        },
        // attachments: {
        //     type: Object,
        //     default: {},
        // },
        pageHeader: {
            type: String,
            default: 'Assesment Review',
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

        selectResult(data) {
            this.selectedPopup = data;
        },

        selectSectionResult(data) {
            this.selectedSectionPopup = data;
            // alert(data);
        },

        assesmentPass(id) {
            this.assesmentSectionsResult[id] = 1;
            this.closeModal();
        },
        assesmentFail(id) {
            this.assesmentSectionsResult[id] = 0;
            this.closeModal();
        },

        sectionPass(id) {
            this.sectionsResult[id] = 1;
            this.closeModal();
        },
        sectionFail(id) {
            this.sectionsResult[id] = 0;
            this.closeModal();
        },

        addClassToElement(id, result) {
            const element = document.getElementById(id.toString());
            if (element) {
                if (result == 1) {
                    element.classList.add('text-green-700', 'border-green-700', 'bg-green-100');
                    // element.innerHTML('Pass');
                } else {
                    element.classList.add('text-red-700', 'border-red-700', 'bg-red-100');
                    // element.innerHTML('Fail');
                }
            }
        },

        closeModal() {
            // this.showModal = false;
            // this.selectedItem = null;
            // this.selectedIndex = null;
            this.selectedPopup = null;
            this.selectedSectionPopup = null;

        },

        listenForModalConfirmation(data) {

            if (data == "Pass") {
                const form = useForm({
                    taskrequest: this.taskRequest,
                    taskrequestresult: 1,
                    sectionsResult: this.sectionsResult,
                    assesmentresults: this.assesmentSectionsResult,
                    assesmentComment: this.assesmentSectionsComment,
                    comment: this.assesmentComment,
                });
                // console.log(form);

                Inertia.post(route('taskrequests.signOffAssesment'), form);

            }

            if (data == "Fail") {
                const form = useForm({
                    taskrequest: this.taskRequest,
                    taskrequestresult: 0,
                    sectionsResult: this.sectionsResult,
                    assesmentresults: this.assesmentSectionsResult,
                    assesmentComment: this.assesmentSectionsComment,
                    comment: this.assesmentComment,
                });

                // console.log(form);
                Inertia.post(route('taskrequests.signOffAssesment'), form);

                //     const form = this.taskRequest
            }

            if (data == "Close") {
                this.signoff = false;
            }
        },
    },
    // computed: {
    //     pdfFilename() {
    //         return this.attachments[0] ? this.attachments[0].filepath : '';
    //     },
    //     pdfUrl() {
    //         return `${this.baseUrl}${this.pdfFilename}`;
    //     }
    // },
    // mounted() {
    //     this.baseUrl = document.querySelector('meta[name="base-url"]').getAttribute('content');
    // }

}
</script>

<style>
.activeTab p {
    color: red;
}
</style>
