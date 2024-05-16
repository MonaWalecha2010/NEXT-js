<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :title="pageHeader" />
    </template>

    <template v-slot:default>

        <div class="sm:flex sm:items-center mb-7">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Training</h1>
            </div>
            <div class="sm:flex text-gray-400 gap-x-7 text-end">
                <span class="flex gap-x-1 text-xs">
                    <span>
                        <WarningIcon svgFill="#9CA3AF" />
                    </span>
                    <span>
                        Mandatory
                    </span>
                </span>
                <span class="flex gap-x-1 text-xs">
                    <span>
                        <DocumentIcon svgFill="#9CA3AF" />
                    </span>
                    <span>
                        Document Task
                    </span>
                </span>
                <span class="flex gap-x-1 text-xs">
                    <span>
                        <ListIcon svgFill="#9CA3AF" />
                    </span>
                    <span>
                        Assesment Task
                    </span>
                </span>
            </div>
        </div>

        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table v-if="my_tasks.length > 0" class="jw-table min-w-full traning_tabel">
                <thead class="bg-white">
                    <!-- <div class="rounded-full ..."></div> -->
                    <tr>
                        <th scope="col" class="lg:w-[145px]">
                            Status
                        </th>
                        <th scope="col">
                            Title
                        </th>
                        <th class="text-center" scope="col">
                            Sectors
                        </th>
                        <th scope="col">
                            Expires <span class="cursor-pointer" @click="sort()">^</span>
                        </th>
                        <th scope="col">

                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                        <td class=""><span class="text-sm font-medium text-gray-400">Action Required</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr v-for="task in my_tasks" :key="task.id" class="border-t border-gray-300 bg-red-50">
                        <template v-if="task.task_status.length == 0 ">
                            <td>
                                <ul class="list-disc text-2xl text-red-500 pl-12">
                                    <li></li>
                                    <!-- ... -->
                                </ul>
                            </td>
                            <td class="flex gap-x-4">
                                <strong>{{task.title}}</strong>
                                <span class="flex gap-x-1">
                                    <span v-if="task.revalidation_type == 0">
                                        <DocumentIcon svgFill="#f79797" />
                                    </span>
                                    <span v-else>
                                        <ListIcon svgFill="#f79797" />
                                    </span>
                                    <span v-if="task.is_mandatory">
                                        <WarningIcon svgFill="#ef4444" />
                                    </span>
                                </span>

                            </td>
                            <td class="text-center sector_name">
                                <RoundedTextPill actionStatus="required" :actionValue="truncateString(task.sector_id.title,35)" />
                            </td>
                            <td>
                                <span class="text-red-600 text-sm">
                                    --/--/--
                                </span>
                            </td>
                            <td>
                                <Link :href="route('taskrequests.edit', {'id' : task.id } )">
                                <span class="inline-block icon icon__pen w-4 h-4">
                                    <IconPen /></span>
                                <span class="sr-only">Edit</span>
                                </Link>
                            </td>
                        </template>
                    </tr>

                    <tr v-for="task in my_tasks" :key="task.id" class="border-t border-gray-300 bg-red-50">
                        <template v-if="task.task_status.length > 0 ">
                            <template v-if="task.task_status[0].status == 0 && task.task_status[0].request_status != 'Pending'">

                                <td>
                                    <ul class="list-disc text-2xl text-red-500 pl-12">
                                        <li></li>
                                        <!-- ... -->
                                    </ul>
                                </td>
                                <td class="flex gap-x-4">
                                    <strong>{{task.title}}</strong>
                                    <span class="flex gap-x-1">
                                        <span v-if="task.revalidation_type == 0">
                                            <DocumentIcon svgFill="#f79797" />
                                        </span>
                                        <span v-else>
                                            <ListIcon svgFill="#f79797" />
                                        </span>
                                        <span v-if="task.is_mandatory">
                                            <WarningIcon svgFill="#ef4444" />
                                        </span>
                                    </span>

                                </td>
                                <td class="text-center">
                                    <RoundedTextPill actionStatus="required" :actionValue="truncateString(task.sector_id.title,35)" />
                                </td>
                                <td>
                                    <span class="text-red-600 text-sm">
                                        {{formatExpiry(task.task_status[0].valid_upto)}}
                                    </span>
                                </td>
                                <td>
                                    <Link :href="route('taskrequests.edit', {'id' : task.id } )">
                                    <span class="inline-block icon icon__pen w-4 h-4">
                                        <IconPen /></span>
                                    <span class="sr-only">Edit</span>
                                    </Link>
                                </td>
                            </template>
                        </template>

                    </tr>

                    <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                        <td class=""><span class="text-sm font-medium text-gray-400">Pending</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr v-for="task in my_tasks" :key="task.id" class="border-t border-gray-300 bg-blue-50">
                        <template v-if="task.task_status.length > 0 ">
                            <template v-if="task.task_status[0].request_status == 'Pending'">
                                <td>
                                    <ul class="list-disc text-2xl text-blue-500 pl-12">
                                        <li></li>
                                    </ul>
                                </td>
                                <td class="flex gap-x-4">
                                    <strong>{{task.title}}</strong>
                                    <span class="flex gap-x-1">
                                        <span v-if="task.revalidation_type == 0">
                                            <DocumentIcon svgFill="#98D3FA" />
                                        </span>
                                        <span v-else>
                                            <ListIcon svgFill="#98D3FA" />
                                        </span>
                                        <span v-if="task.is_mandatory">
                                            <WarningIcon svgFill="#60a5fa" />
                                        </span>
                                    </span>
                                </td>
                                <td class="text-center">
                                    <RoundedTextPill actionStatus="pending" :actionValue="truncateString(task.sector_id.title,35)" />
                                </td>
                                <td v-if="task.task_status.length > 0" class="text-gray-400">
                                    <span class="text-blue-500 text-sm">
                                        {{formatExpiry(task.task_status[0].valid_upto)}}
                                    </span>
                                </td>
                                <td v-else class="text-blue text-sm">
                                    --/--/--
                                </td>
                                <td>
                                    <Link :href="route('taskrequests.edit', {'id' : task.id } )">
                                    <span class="inline-block icon icon__pen w-4 h-4">
                                        <IconPen /></span>
                                    <span class="sr-only">Edit</span>
                                    </Link>
                                </td>
                            </template>
                        </template>
                    </tr>
                    <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                        <td class=""><span class="text-sm font-medium text-gray-400">Upcoming</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr v-for="task in my_tasks" :key="task.id" class="border-t border-gray-300 bg-yellow-50">
                        <template v-if="task.task_status.length > 0 ">
                            <template v-if="task.task_status[0].status == 1 && task.task_status[0].display_status == 'Upcoming' ">
                                <td>
                                    <ul class="list-disc text-2xl text-yellow-300 pl-12">
                                        <li></li>
                                    </ul>
                                </td>
                                <td class="flex gap-x-4">
                                    <strong>{{task.title}}</strong>
                                    <span class="flex gap-x-1">
                                        <span v-if="task.revalidation_type == 0">
                                            <DocumentIcon svgFill="#d1d5db" />
                                        </span>
                                        <span v-else>
                                            <ListIcon svgFill="#d1d5db" />
                                        </span>
                                        <span v-if="task.is_mandatory">
                                            <WarningIcon svgFill="#d1d5db" />
                                        </span>
                                    </span>

                                </td>
                                <td class="text-center">
                                    <RoundedTextPill actionStatus="upcoming" :actionValue="truncateString(task.sector_id.title,35)" />
                                </td>
                                <td v-if="task.task_status.length > 0" class="text-gray-400">
                                    <span class=" text-gray-400 text-sm">
                                        {{formatExpiry(task.task_status[0].valid_upto)}}
                                    </span>
                                </td>
                                <td v-else class="text-yellow">
                                    Expired
                                </td>
                                <td>
                                    <Link :href="route('taskrequests.edit', {'id' : task.id } )">
                                    <span class="inline-block icon icon__pen w-4 h-4">
                                        <IconPen /></span>
                                    <span class="sr-only">Edit</span>
                                    </Link>
                                </td>
                            </template>
                        </template>
                    </tr>
                    <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                        <td class=""><span class="text-sm font-medium text-gray-400">Active</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                    <tr v-for="task in my_tasks" :key="task.id" class="border-t border-gray-300 bg-white">
                        <template v-if="task.task_status.length > 0 ">
                            <template v-if="task.task_status[0].status == 1 && task.task_status[0].display_status == 'Active' ">
                                <td>
                                    <ul class="list-disc text-2xl text-green-600 pl-12">
                                        <li></li>
                                    </ul>
                                </td>
                                <td class="flex gap-x-4">
                                    <strong class="text-gray-400">{{task.title}}</strong>
                                    <span class="flex gap-x-1">
                                        <span v-if="task.revalidation_type == 0">
                                            <DocumentIcon svgFill="#d1d5db" />
                                        </span>
                                        <span v-else>
                                            <ListIcon svgFill="#d1d5db" />
                                        </span>
                                        <span v-if="task.is_mandatory">
                                            <WarningIcon svgFill="#d1d5db" />
                                        </span>
                                    </span>

                                </td>
                                <td class="text-center">
                                    <RoundedTextPill actionStatus="active" :actionValue="truncateString(task.sector_id.title,35)" />
                                </td>
                                <td v-if="task.task_status.length > 0" class="text-gray-400">
                                    <span class=" text-gray-400 text-sm">
                                        {{formatExpiry(task.task_status[0].valid_upto)}}
                                    </span>
                                </td>
                                <td v-else class="text-green">
                                    Expired
                                </td>
                                <td>
                                    <Link :href="route('taskrequests.edit', {'id' : task.id } )">
                                    <span class="inline-block icon icon__pen w-4 h-4">
                                        <IconPen /></span>
                                    <span class="sr-only">Edit</span>
                                    </Link>
                                </td>
                            </template>
                        </template>
                    </tr>
                </tbody>

            </table>
            <table v-else class="jw-table min-w-full bg-white  ">
                <div class="p-4 text-black">Training tasks Will Appear here.</div>
            </table>
        </div>

        <div v-if="my_tasks.length == 10 && tasks.length > 10" class="flex w-full p-8 justify-center my-8">
            <button @click="viewAllMyTasks()" class="jw-btn jw-btn__blue" type="button">
                View All
            </button>
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
    },
    data(props) {
        return {
            my_tasks: props.tasks.slice(0, this.iter),
            descending: true,
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
        tasks: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Training',
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

        viewAllMyTasks: function () {
            this.iter = this.tasks.length;
            let descending = this.descending;
            let defaultDescending;

            if (descending == false) {
                defaultDescending = true;
            } else {
                defaultDescending = false;
            }


            axios.post(route('tasks.sortMyTasks', {
                    'descending': defaultDescending,
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        this.my_tasks = response.data.data.tasks.slice(0, this.iter);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        sort: function () {
            this.my_tasks = this.tasks;
            let descending = this.descending;

            if (descending == false) {
                this.descending = true;
            } else {
                this.descending = false;
            }

            axios.post(route('tasks.sortMyTasks', {
                    'descending': descending,
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        this.my_tasks = response.data.data.tasks.slice(0, this.iter);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

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

<style>
.activeTab p {
    color: red;
}
</style>
