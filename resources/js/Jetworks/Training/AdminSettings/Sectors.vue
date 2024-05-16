<template>
<template v-if="searchStatus == true">

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table v-if="sectors.data.length > 0" class="jw-table min-w-full">
            <thead class="bg-white">

                <div class="rounded-full ..."></div>

                <tr>
                    <th scope="col">
                        Title
                    </th>
                    <th scope="col">
                        Created
                    </th>
                    <th scope="col">
                        Last Edited
                    </th>
                    <th scope="col">

                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">                
                <tr v-for="sector in sectors.data" :key="sector.id" class="border-t border-gray-300">
                    <td>
                        <strong>{{truncateString(sector.title,35)}}</strong>
                    </td>
                    <td>
                        <template v-if="sector.user_id_created_by">
                            <UserInitials :name="sector.fname_created_by+' '+sector.lname_created_by" :id="sector.user_id_created_by" /> <strong>{{sector.created_at}}</strong>
                        </template>
                    </td>
                    <td>
                        <template v-if="sector.user_id_modified_by">
                            <UserInitials :name="sector.fname_modified_by+' '+sector.lname_modified_by" :id="sector.user_id_modified_by" /> <strong>{{sector.updated_at}}</strong>
                        </template>

                    </td>
                    <td class="text-end">
                        <Link :href="route('sectors.edit', {'id' : sector.id } )" class="pr-2">
                        <span class="inline-block icon icon__pen w-4 h-4">
                            <IconPen /></span>
                        <span class="sr-only">Edit</span>
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
        <table v-else class="jw-table min-w-full bg-white  ">
            <div class="p-4 text-black">No Sectors Found</div>
        </table>
    </div>
</template>
<template v-else>

    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table v-if="allSectors.data.length > 0" class="jw-table min-w-full">
            <thead class="bg-white">

                <div class="rounded-full ..."></div>

                <tr>
                    <th scope="col">
                        Title
                    </th>
                    <th scope="col">
                        Created
                    </th>
                    <th scope="col">
                        Last Edited
                    </th>
                    <th scope="col">

                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <template v-if="fullDataTable == true">
                    <tr v-for="sector in allSectors.data" :key="sector.id" class="border-t border-gray-300">
                        <td>
                            <strong>{{truncateString(sector.title,35)}}</strong>
                        </td>
                        <td>
                            <UserInitials :name="sector.fname_created_by+' '+sector.lname_created_by" :id="sector.user_id_created_by" /> <strong>{{sector.created_at}}</strong>

                        </td>
                        <td>
                            <template v-if="sector.user_id_modified_by">
                                <UserInitials :name="sector.fname_modified_by+' '+sector.lname_modified_by" :id="sector.user_id_modified_by" /> <strong>{{sector.updated_at}}</strong>
                            </template>

                        </td>
                        <td class="text-end">
                            <Link :href="route('sectors.edit', {'id' : sector.id } )" class="pr-2">
                            <span class="inline-block icon icon__pen w-4 h-4">
                                <IconPen /></span>
                            <span class="sr-only">Edit</span>
                            </Link>
                        </td>
                    </tr>
                </template>

                <template v-else>
                    <tr v-for="sector in allSectors.data" :key="sector.id" class="border-t border-gray-300">
                        <td>
                            <strong>{{truncateString(sector.title,35)}}</strong>
                        </td>
                        <td>
                            <template v-if="sector.user_id_created_by">
                                <UserInitials :name="sector.fname_created_by+' '+sector.lname_created_by" :id="sector.user_id_created_by" /> <strong>{{sector.created_at}}</strong>
                            </template>
                        </td>
                        <td>
                            <template v-if="sector.user_id_modified_by">
                                <UserInitials :name="sector.fname_modified_by+' '+sector.lname_modified_by" :id="sector.user_id_modified_by" /> <strong>{{sector.updated_at}}</strong>
                            </template>

                        </td>
                        <td class="text-end">
                            <Link :href="route('sectors.edit', {'id' : sector.id } )" class="pr-2">
                            <span class="inline-block icon icon__pen w-4 h-4">
                                <IconPen /></span>
                            <span class="sr-only">Edit</span>
                            </Link>
                        </td>
                    </tr>
                </template>

            </tbody>
        </table>
        <table v-else class="jw-table min-w-full bg-white  ">
            <div class="p-4 text-black">Training Sectors Will Appear here.</div>
        </table>

    </div>

    <template v-if="fullDataTable != true">
        <div v-if="allSectors.data.length == 10" class="flex w-full p-8 justify-center my-8">
            <button @click="viewAllSectors()" class="jw-btn jw-btn__blue" type="button">
                View All
            </button>
        </div>
    </template>
</template>
</template>

<script>
import IconPen from '@/Jetworks/Icons/Pen.vue';

import {
    Userhelper
} from "@/Jetworks/Userhelper";
import UserInitials from "@/Jetworks/Users/Initials.vue";
import Toaster from '@/Jetworks/Common/Toaster.vue';

export default {
    components: {
        UserInitials,
        IconPen,
        Toaster,
    },
    name: 'Sectors',
    props: {
        sectors: {
            type: Object,
        },
        searchStatus: {
            type: Object,
        }
    },
    data(props) {
        return {
            fullDataTable: false,
            allSectors: props.sectors,

        }
    },
    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper
        }
    },
    methods: {
        showToastMessage(message) {
            this.$refs.toaster.showMessage(this.toast.message);
        },
        viewAllSectors() {
            axios.get(route('sectors.viewAllData'))
                .then((response) => {
                    if (response.data.success === true) {
                        this.fullDataTable = true;
                        this.allSectors = response.data.data;
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
    }
}
</script>
