<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('notices.index')" :title="'Notices'" />
        <BreadcrumbItem :url="route('notices.show', {'id' : notice.id })" :title="notice.title" />
        <BreadcrumbItem :title="'Track Readers'" />
    </template>

    <template v-slot:default>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="inline-flex items-center text-3xl font-semibold text-gray-900">
                    {{notice.title}}
                </h1>
            </div>
        </div>
        <div class="sm:flex sm:items-center mt-5">
            <div class="sm:flex">
                <div class="flex items-center">
                    <UserIcon svgFill="#d1d5db" />
                    <strong class="text-sm text-gray-400 font-semibold">Created By : </strong>
                    <span class="text-sm text-gray-400">
                        {{Userhelper.user.lname}}, {{Userhelper.user.fname}}
                    </span>
                </div>
                <div class="mx-4 gap-x-2">
                    <strong class="text-sm text-gray-400 font-semibold">Created On: </strong>
                    <span class="text-sm text-gray-400">
                        {{formatCreatedDate(notice.created_at)}}
                    </span>
                </div>
            </div>
        </div>
        <div class="overflow-hidden  ring-1 ring-black ring-opacity-5 md:rounded-lg mt-6">
            <table class="jw-table min-w-full">
                <thead class="bg-white">
                    <div class="rounded-full ..."></div>
                    <tr>
                        <th scope="col">
                            <span class="text-sm text-gray-400 font-medium">Name</span>
                        </th>
                        <th scope="col"></th>
                        <th scope="col"> </th>
                        <th scope="col">
                            <span class="text-sm text-gray-400 font-medium">Read On</span>
                        </th>
                        <th scope="col" class="text-center" style="width:20%">
                            <span class="text-sm text-gray-400 font-medium">Actions</span>
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <template v-for="roles in notice.roles">
                        <tr class="border-t border-gray-300" v-for="user in roles.users">
                            <td colspan="3">
                                <span class="text-gray-900 text-sm font-semibold">{{user.fname}} {{user.lname}}</span>
                            </td>
                            <td>
                                <template v-if="user.read_status != null">
                                    <span class="text-gray-900 text-sm font-semibold">{{formatReadDate(user.read_status.created_at)}}</span>
                                </template>
                            </td>
                            <td class="text-center">
                                <template v-if="user.read_status == null">
                                    <button class="jw-btn-md jw-btn__blue" type="button" @click="remind(notice.id,user.id,)">Send Reminders</button>
                                </template>
                                <template v-else>
                                    <span class="block min-h-[50px]"></span>
                                </template>
                            </td>
                        </tr>
                    </template>
                </tbody>
            </table>
        </div>
        <!-- <div class="flex justify-end py-6">
            <button class="jw-btn jw-btn__blue" type="button" @click="remindall()">Remind All</button>
        </div> -->

    </template>
</AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ViewDocument from "@/Jetworks/Common/ViewDocument.vue";
import UserIcon from '@/Jetworks/Icons/UserIcon.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import StatusDot from '@/Components/StatusDot.vue'

import {
    Inertia
} from '@inertiajs/inertia';
import {
    Userhelper
} from "@/Jetworks/Userhelper";

export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        ViewDocument,
        UserIcon,
        StatusDot,

    },
    data(props) {
        return {
            notice_id: props.notice.id
        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        user: {
            type: Object,
            default: {},
        },
        notice: {
            type: Object,
            default: {},
        },
        notice_types: {
            type: Object,
            default: {},
        },
        attachments: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Notices',
        },
    },
    methods: {
        formatCreatedDate(created_at) {
            const date = new Date(created_at);
            const year = date.getFullYear().toString().substring(2);
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const day = ('0' + date.getDate()).slice(-2);
            const hours = ('0' + date.getHours()).slice(-2);
            const minutes = ('0' + date.getMinutes()).slice(-2);

            const formattedDateTime = `${day}/${month}/${year} | ${hours}:${minutes}`;
            return formattedDateTime;
        },

        formatReadDate(created_at) {
            const date = new Date(created_at);
            const year = date.getFullYear().toString().substring(2);
            const month = ('0' + (date.getMonth() + 1)).slice(-2);
            const day = ('0' + date.getDate()).slice(-2);

            const formattedDateTime = `${day}/${month}/${year}`;
            return formattedDateTime;
        },

        remind: function (notice_id, user_id) {

            axios.post(route('notices.remind', {
                    'notice_id': notice_id,
                    'user_id': user_id
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        console.log(response.data.users);
                        console.log(this.users.data);
                    }
                }).catch(error => {
                    console.log(error);
                });
        }

    },
    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper
        }
    },
}
</script>
