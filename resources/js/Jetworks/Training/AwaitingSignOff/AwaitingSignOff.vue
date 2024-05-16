<template>
<div class="sm:flex sm:items-center mb-7">
    <div class="sm:flex-auto">
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
    <table v-if="signoff_tasks.length > 0" class="jw-table min-w-full">
        <thead class="bg-white">
            <!-- <div class="rounded-full ..."></div> -->
            <tr>
                <th scope="col">
                    <span class="text-gray-400">Title</span>
                </th>
                <th class="text-center" scope="col">
                    <span class="text-gray-400">Sectors</span>
                </th>
                <th class="text-center" scope="col">
                    <span class="text-gray-400">User</span>
                </th>
                <th scope="col">
                    <span class="text-gray-400">Expires <span class="cursor-pointer" @click="sort()">^</span></span>
                </th>
                <th scope="col">

                </th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <template v-for="task in signoff_tasks" :key="task.id">
                <tr class="border-t border-gray-300" v-bind:class="task.status==null?'bg-red-50': task.status==1 && task.display_status=='upcoming' ?'bg-yellow-50':''">
                    <!-- <template v-if="task.length > 0 "> -->
                    <!-- {{ task }} -->
                    <template v-if="task.request_status == 'Pending'">
                        <td class="flex gap-x-4">
                            <strong class="text-sm">{{task.task.title}}</strong>
                            <span class="flex gap-x-1">
                                <span v-if="task.task.revalidation_type == 0">
                                    <DocumentIcon :svgFill="task.status==null?'#d76363':'#9CA3AF'" />
                                </span>
                                <span v-else>
                                    <ListIcon :svgFill="task.status==null?'#d76363':'#9CA3AF'" />
                                </span>
                                <span v-if="task.task.is_mandatory">
                                    <WarningIcon :svgFill="task.status==null?'#d76363':'#9CA3AF'" />
                                </span>
                            </span>

                        </td>
                        <td class="text-center">
                            <template v-if="task.status==null ">
                                <RoundedTextPill actionStatus="required" :actionValue="truncateString(task.task.sector.title,35)" />
                            </template>
                            <template v-if="task.status==1 && task.display_status=='upcoming' ">
                                <RoundedTextPill actionStatus="upcoming" :actionValue="truncateString(task.task.sector.title,35)" />
                            </template>
                            <!-- <RoundedTextPill actionStatus="pending" :actionValue="truncateString(task.task.sector.title,35)" />  -->
                            <!-- <span class=" border-2 border-blue-500 px-4 py-2 rounded-full ... ">
                            {{truncateString(task.task.sector.title,35)}}
                        </span> -->
                        </td>
                        <td class="text-center">
                            <strong class="text-gray-400 text-sm font-medium">{{task.user.lname}}, {{task.user.fname}}</strong>
                        </td>
                        <td class="text-gray-400">
                            <span class="text-sm" v-bind:class="task.status==null?'text-red-600':'text-gray-900'">
                                {{formatExpiry(task.valid_upto)}}
                            </span>
                        </td>
                        <!-- <td v-else class="">
                        <span class="text-gray-400"> --/--/-- </span>                        
                    </td> -->
                        <td>
                            <Link :href="route('taskrequests.validates', {'taskRequest' : task.id } )">
                            <span class="inline-block icon icon__pen w-4 h-4">
                                <IconPen /></span>
                            <span class="sr-only">Edit</span>
                            </Link>
                        </td>
                    </template>
                    <!-- </template> -->
                </tr>
            </template>
            <!-- <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                <td class=""><span class="text-sm font-medium text-gray-400">Upcoming</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> -->
            <!-- <tr class="border-t border-gray-300  text-gray-400 bg-white ">
                <td class=""><span class="text-sm font-medium text-gray-400">Active</span></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
            </tr> -->
        </tbody>

    </table>
    <table v-else class="jw-table min-w-full bg-white  ">
        <!-- Rendered when aeroplanes.data.length is not greater than 0 -->
        <div class="p-4 text-gray">You don't have any training task awaiting sign off</div>
    </table>
</div>

<div v-if="signoff_tasks.length == 10 && tasks.length > 10" class="flex w-full p-8 justify-center my-8">
    <button @click="viewAllSignOffTasks()" class="jw-btn jw-btn__blue" type="button">
        View All
    </button>
</div>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Pagination from '@/Components/Pagination.vue';
import EntityIsActive from '@/Jetworks/Common/EntityIsActive.vue';
import StaffTraining from '@/Jetworks/Training/StaffTraining/StaffTraining.vue';
import AdminSettings from '@/Jetworks/Training/AdminSettings/AdminSettings.vue';
import DocumentIcon from '@/Jetworks/Icons/DocumentIcon.vue';
import ListIcon from '@/Jetworks/Icons/ListIcon.vue';
import WarningIcon from '@/Jetworks/Icons/WarningIcon.vue';
import IconPen from '@/Jetworks/Icons/Pen.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import RoundedTextPill from '@/Components/RoundedTextPill.vue'
import WarningIconSm from '@/Jetworks/Icons/WarningIconSm.vue'
import {
    Userhelper
} from "@/Jetworks/Userhelper";

export default {
    name: 'AwaitingSignoff',
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        StaffTraining,
        AdminSettings,
        IconPen,
        DocumentIcon,
        ListIcon,
        WarningIcon,
        WarningIconSm,
        RoundedTextPill,
    },
    data(props) {
        return {
            signoff_tasks: props.tasks.slice(0, this.iter),
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
        tasks: {
            type: Object,
            default: {},
        },
    },
    methods: {
        formatExpiry: function (date) {

            if (date == null) {
                const formattedDate = '--/--/--';
                return formattedDate
            } else {
                const parts = date.split('-');
                const formattedDate = `${parts[1]}/${parts[2]}/${parts[0].slice(-2)}`;
                return formattedDate
            }

        },

        viewAllSignOffTasks: function () {
            this.iter = this.tasks.length;
            // this.signoff_tasks = this.tasks.slice(0, this.iter);
            let descending = this.descending;
            let defaultDescending;

            if (descending == false) {
                defaultDescending = true;
            } else {
                defaultDescending = false;
            }

            axios.post(route('trainings.sortSignOffTasks', {
                    'descending': defaultDescending,
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        // this.iter = this.tasks.length;
                        this.signoff_tasks = response.data.data.data.slice(0, this.iter);
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        sort: function () {
            this.signoff_tasks = this.tasks;
            let descending = this.descending;

            if (descending == false) {
                this.descending = true;
            } else {
                this.descending = false;
            }

            axios.post(route('trainings.sortSignOffTasks', {
                    'descending': descending,
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        // this.iter = this.tasks.length;
                        this.signoff_tasks = response.data.data.data.slice(0, this.iter);
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
