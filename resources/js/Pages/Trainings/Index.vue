<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :title="pageHeader" />
    </template>

    <template v-slot:default>

        <toaster ref="toaster"></toaster>

        <div class="sm:flex justify-between sm:items-center traning_tabs flex-wrap px-4 sm:px-0 space-y-3 lg:space-y-0">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Training</h1>
            </div>
            <div class="mt-4 sm:mt-0 lg:ml-16 sm:flex-none">
                <div class="flex rounded-md overflow-hidden shadow-sm shadow-[#0000001A] bg-white flex-wrap">
                    <div class="grow w-full sm:w-auto text-gray-500 text-sm font-medium bg-white px-5 md:px-7 lg:px-9 py-4 cursor-pointer border-b-2 sm:border-b-0 sm:border-r  border-gray-300 last:border-r-0" v-for="(tab,index) in tabs" :key="index" @click="selectedTabs = tab" :class="{'text-gray-900 border-b-2 sm:border-b-2 border-sky-600 ' : selectedTabs === tab}">
                        <template v-if="tab === 'Awaiting Sign Off'">
                            <p :class="{'text-gray-900 font-semibold':selectedTabs === tab}">{{tab}} <span v-if="count_signoff_tasks.length > 0" class="text-white text-xs bg-[#539FF4] p-2 rounded-full w-4 h-4 inline-flex items-center justify-center ml-3 font-medium">{{count_signoff_tasks.length}}</span></p>
                        </template>
                        <template v-else>
                            <p>{{tab}}</p>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <!-- <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg"> -->
                    <div class="tabs_content">
                        <div class="tab_content" v-show="selectedTabs === 'My Training'">
                            <MyTrainings :tasks="my_tasks" />
                        </div>
                        <div class="tab_content" v-show="selectedTabs === 'Awaiting Sign Off'">
                            <AwaitingSignOff :tasks="signoff_tasks" />
                        </div>
                        <div class="tab_content" v-show="selectedTabs === 'Staff Training'">
                            <StaffTraining :users="users" :sectors="allsectors" :training_roles="training_roles" />
                        </div>
                        <div class="tab_content" v-show="selectedTabs === 'Admin Settings'">
                            <AdminSettings :selectedInnerTabs="selectedInnerTabs" :sectors="sectors" :tasks="tasks" :notifications="notifications" :toast="toast" />
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
import Pagination from '@/Components/Pagination.vue';
import EntityIsActive from '@/Jetworks/Common/EntityIsActive.vue';
import MyTrainings from '@/Jetworks/Training/MyTrainings/MyTrainings.vue';
import AwaitingSignOff from '@/Jetworks/Training/AwaitingSignOff/AwaitingSignOff.vue';
import StaffTraining from '@/Jetworks/Training/StaffTraining/StaffTraining.vue';
import AdminSettings from '@/Jetworks/Training/AdminSettings/AdminSettings.vue';

import IconPen from '@/Jetworks/Icons/Pen.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

import Toaster from '@/Jetworks/Common/Toaster.vue';

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
        Toaster

    },
    data(props) {
        return {
            tabs: ['My Training', 'Awaiting Sign Off', 'Staff Training', 'Admin Settings'],
            selectedTabs: props.selected_tab,
            selectedInnerTabs: props.selected_inner_tab,

        }

    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        selected_tab: {
            type: Object,
            default: {},
        },
        selected_inner_tab: {
            type: Object,
            default: {},
        },
        sectors: {
            type: Object,
            default: {},
        },
        allsectors: {
            type: Object,
            default: {},
        },
        tasks: {
            type: Object,
            default: {},
        },
        my_tasks: {
            type: Object,
            default: {},
        },
        users: {

        },
        training_roles: {
            type: Object,
            default: {},
        },
        count_signoff_tasks: {
            type: Object,
            default: {},
        },
        signoff_tasks: {
            type: Object,
            default: {},
        },
        notifications: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Training',
        },
        toast: {
            type: Object,
            default: {},
        }
    },
    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper
        }
    },
    methods: {
        openPop() {
            const toast = this.toast;
            if (toast != null) {
                const message = toast.message;
                const status = toast.status;
                console.log(message, status);
                this.$refs.toaster.showMessage(message, status);
            }
        }
    },
    mounted() {
        this.$nextTick(() => {
            this.openPop();
        });
    }

}
</script>

<style>
.activeTab p {
    color: red;
}
</style>
