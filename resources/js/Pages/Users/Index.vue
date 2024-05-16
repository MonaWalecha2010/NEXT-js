<template>

    <Head title="Users"/>

    <AuthenticatedLayout>

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :title="pageHeader" />
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">{{ pageHeader }}</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link :href="route('users.create')"
                          class="jw-btn jw-btn__blue">
                        Add User
                    </Link>
                </div>
            </div>
            <div class="mt-8 flex flex-col">
                <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                    <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                        <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                            <table class="jw-table min-w-full">
                                <thead class="bg-white">

                                <div class="rounded-full ..."></div>

                                <tr>
                                    <th scope="col">
                                        <span class="sr-only">Role</span>
                                    </th>
                                    <th scope="col">
                                        Name
                                    </th>
                                    <th scope="col"
                                    >
                                        Role
                                    </th>
                                    <th scope="col"
                                    >
                                        Code
                                    </th>
                                    <th scope="col"
                                    >
                                        Signature
                                    </th>
                                    <th scope="col"
                                    >
                                        Status
                                    </th>
                                    <th class="relative py-3.5 pl-3 pr-4 sm:pr-6" scope="col">
                                        <span class="sr-only">Edit</span>
                                    </th>
                                </tr>
                                </thead>
                                <tbody class="bg-white">

                                <tr v-for="user in users.data" :key="user.id"
                                    class="border-t border-gray-300">
                                    <td>
                                        <span :class="`jw-dot__${user.color}`" class="jw-dot"></span>
                                    </td>
                                    <td>
                                        {{ user.fname }} {{ user.lname }}
                                    </td>
                                    <td>
                                        {{ Userhelper.getRoleNameFromUser( user ) }}
                                    </td>
                                    <td>
                                        {{ user.code }}
                                    </td>
                                    <td>
                                        <img class="w-48" v-if="user.signature" :src="user.signature" alt="">
                                    </td>
                                    <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                        <EntityIsActive :entity="user"/>
                                    </td>
                                    <td>
                                        <Link :href="route('users.edit', user.id)">
                                            <span class="inline-block icon icon__pen w-4 h-4"><IconPen/></span>
                                            <span class="sr-only">Edit</span>
                                        </Link>
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <Pagination :links="users.meta.links"/>

        </template>

    </AuthenticatedLayout>

</template>


<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';

import Pagination from '@/Components/Pagination.vue';
import EntityIsActive from '@/Jetworks/Common/EntityIsActive.vue';
import IconPen from '@/Jetworks/Icons/Pen.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import {Userhelper} from "@/Jetworks/Userhelper";

defineProps({
    users: {
        type: Object,
        default: {},
    },
    pageHeader: {
        type: String,
        default: 'Users',
    }
});
</script>
