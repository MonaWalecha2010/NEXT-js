<template>
<div class="flex items-center px-4 md:px-0 justify-center sm:justify-between py-2 mb-5 flex-wrap space-x-2 md:space-x-0 sm:flex-nowrap">
    <!-- Left Side: Search Field -->
    <div class="max-w-full md:max-w-[26%] lg:max-w-[30%] xl:max-w-[42%] w-full">
        <input v-show="selectedTabs === 'Tasks'" v-model="searchTaskInput" @input="searchTask()" type="text" placeholder="Search..." class="px-4 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 w-full">
        <input v-show="selectedTabs === 'Sectors'" v-model="searchSectorInput" @input="searchSector()" type="text" placeholder="Search..." class="px-4 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 w-full">
        <input v-show="selectedTabs === 'Notifications'" v-model="searchNotificationInput" @input="searchNotification()" type="text" placeholder="Search..." class=" px-4 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 w-full">
    </div>
    <!-- Center: Three Tabs Joined Together -->
    <div class="sm:flex sm:justify-center sm:flex-1 area_btn mt-3 sm:mt-0">
        <button class="px-6 md:px-9 mx-1 py-3 text-xs text-gray-500 rounded-md focus:outline-none bg-gray-200 btn_sizes min-w-[16] md:min-w-24 lg:min-w-40 xl:min-w-[180px]" v-for="(tab,index) in tabs" :key="index" @click="selectedTabs = tab" :class="{'bg-sky-600 text-white' : selectedTabs === tab}">{{tab}}</button>
    </div>
    <!-- Right Side: Button -->
    <div class="sm:flex-shrink-0 mt-3 sm:mt-0">
        <Link v-show="selectedTabs === 'Tasks'" :href="route('tasks.create')" class="jw-btn-md jw-btn__blue md:min-w-[135px] text-base text-medium h-[38px] "> Add Task</Link>
        <Link v-show="selectedTabs === 'Sectors'" :href="route('sectors.create')" class="jw-btn-md jw-btn__blue md:min-w-[135px] text-base text-medium h-[38px]"> Add Sector</Link>
        <Link v-show="selectedTabs === 'Notifications'" :href="route('notifications.create')" class="jw-btn-md jw-btn__blue md:min-w-[135px] text-base text-medium h-[38px]"> Add</Link>
    </div>
</div>

<div class="setting_tabs_content_sections">
    <div class="content_section" v-show="selectedTabs === 'Tasks'">
        <Tasks :tasks="allTasks" :searchStatus="taskSearch" />
    </div>
    <div class="content_section" v-show="selectedTabs === 'Sectors'">
        <Sectors :sectors="allSectors" :searchStatus="sectorSearch" />

    </div>
    <div class="content_section" v-show="selectedTabs === 'Notifications'">
        <Notifications :notifications="allNotifications" :searchStatus="notificationSearch" />
    </div>

</div>

<!-- <pre>
{{tst}}
</pre>
<pre>
{{tasks}}
</pre> -->
</template>

<script>
import Tasks from '@/Jetworks/Training/AdminSettings/Tasks.vue';
import Sectors from '@/Jetworks/Training/AdminSettings/Sectors.vue';
import Notifications from '@/Jetworks/Training/AdminSettings/Notifications.vue';

export default {
    components: {
        Tasks,
        Sectors,
        Notifications,
    },
    name: 'AdminSettings',
    props: {
        sectors: {
            type: Object,
            default: {},
        },
        tasks: {
            type: Object,
            default: {},
        },
        notifications: {
            type: Object,
            default: {},
        },
        selectedInnerTabs: {
            type: Object,
            default: {},
        }
    },
    data(props) {
        return {
            tabs: ['Tasks', 'Sectors', 'Notifications'],
            selectedTabs: props.selectedInnerTabs,
            searchTaskInput: '',
            searchSectorInput: '',
            searchNotificationInput: '',
            allTasks: props.tasks,
            allSectors: props.sectors,
            allNotifications: props.notifications,
            taskSearch: false,
            sectorSearch: false,
            notificationSearch: false,
            tst: ''

        }
    },
    methods: {
        searchTask: function () {
            const taskValue = this.searchTaskInput;

            if (taskValue != '') {
                this.taskSearch = true;
            } else {
                this.taskSearch = false;
            }

            axios.post(route('tasks.search', {
                    'taskValue': taskValue,
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        const newResponseData = response.data.data;
                        this.allTasks = newResponseData;
                    }
                }).catch(error => {
                    console.log(error);
                })
        },

        searchSector: function () {
            const sectorValue = this.searchSectorInput;
            if (sectorValue != '') {
                this.sectorSearch = true;
            } else {
                this.sectorSearch = false;
            }

            axios.post(route('sectors.search', {
                    'sectorValue': sectorValue,
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        const newResponseData = response.data.data;
                        this.allSectors = newResponseData;
                    }
                }).catch(error => {
                    console.log(error);
                })
        },

        searchNotification: function () {
            const notificationValue = this.searchNotificationInput;
            if (notificationValue != '') {
                this.notificationSearch = true;
            } else {
                this.notificationSearch = false;
            }
            axios.post(route('notifications.search', {
                    'notificationValue': notificationValue,
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        const newResponseData = response.data.data.data;
                        this.allNotifications = newResponseData;
                    }
                }).catch(error => {
                    console.log(error);
                })
        },
    }
}
</script>
