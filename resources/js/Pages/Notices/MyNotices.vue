<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :title="pageHeader" />
    </template>

    <template v-slot:default>
        <div class="sm:flex justify-between sm:items-center traning_tabs">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Notices</h1>
            </div>
        </div>

        <div class="mt-8 overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
            <table v-if="my_notices.length > 0" class="jw-table min-w-full traning_tabel">
                <thead class="bg-white">
                    <tr>
                        <th scope="col" class="lg:w-[145px]">
                            Status
                        </th>
                        <th scope="col">
                            No.
                        </th>
                        <th scope="col" style="width:50%">
                            Title
                        </th>
                        <th class="text-center" scope="col">
                            Type
                        </th>
                        <th scope="col">
                            Date
                        </th>
                        <th scope="col">

                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white">
                    <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                        <td class="" colspan="6"><span class="text-sm font-medium text-gray-400">Unread</span></td>
                        <!-- <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td> -->
                    </tr>
                    <tr v-for="(notice,index) in notices" class="border-t border-gray-300 bg-red-50">
                        <template v-if="notice.roles.length > 0">
                            <template v-if="notice.markReadStatus.length == 0">
                                <td>
                                    <ul class="list-disc text-2xl text-red-500 pl-12">
                                        <li></li>
                                        <!-- ... -->
                                    </ul>
                                </td>
                                <td>
                                    {{notice.number}}
                                </td>
                                <td>
                                    <strong>{{notice.title}}</strong>
                                </td>
                                <td class="text-center">
                                <td>
                                    <RoundedTextPill actionStatus="required" :actionValue="notice.notice_type.name" />
                                </td>
                                <!-- <template v-for="(type,index) in notice_types">
                                        <template v-if="index == notice.notice_type">
                                            <RoundedTextPill actionStatus="required" :actionValue="type" />
                                        </template>
                                    </template> -->
                                </td>

                                <td>
                                    <span class="text-black">{{notice.created_at}}</span>
                                </td>
                                <td>
                                    <Link :href="route('notices.show', {'id' : notice.id } )">
                                    <span class="inline-block icon icon__pen w-4 h-4">
                                        <IconPen /></span>
                                    <span class="sr-only">Edit</span>
                                    </Link>
                                </td>
                            </template>
                        </template>
                    </tr>
                    <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                        <td class=""><span class="text-sm font-medium text-gray-400">Read</span></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>

                    </tr>
                    <tr v-for="(notice,index) in notices" class="border-t border-gray-300 bg-white">
                        <template v-if="notice.roles.length > 0">
                            <template v-if="notice.markReadStatus.length > 0">
                                <td>
                                    <ul class="list-disc text-2xl text-green-600 pl-12">
                                        <li></li>
                                    </ul>
                                </td>
                                <td class="text-gray-400">
                                    {{notice.number}}
                                </td>
                                <td class="text-gray-400">
                                    <strong>{{notice.title}}</strong>
                                </td>
                                <td class="text-center">
                                    <RoundedTextPill actionStatus="active" :actionValue="notice.notice_type.name" />

                                    <!-- <template v-for="(type,index) in notice_types">
                                        <template v-if="index == notice.notice_type">
                                            <RoundedTextPill actionStatus="active" :actionValue="type" />
                                        </template>
                                    </template> -->
                                </td>

                                <td>
                                    <span class="text-gray-400">{{notice.created_at}}</span>
                                </td>
                                <td>
                                    <Link :href="route('notices.show', {'id' : notice.id } )">
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
                <div class="p-4 text-black">Notices Will Appear here.</div>
            </table>
        </div>

        <div v-if="my_notices.length > 10 && notices.length == 10" class="flex w-full p-8 justify-center my-8">
            <button @click="viewAllNotices()" class="jw-btn jw-btn__blue" type="button">
                View All
            </button>
        </div>

    </template>

</AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import RoundedTextPill from '@/Components/RoundedTextPill.vue'
import IconPen from '@/Jetworks/Icons/Pen.vue';

export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        RoundedTextPill,
        IconPen
    },
    props: {
        my_notices: {
            type: Object,
            default: {},
        },
        notice_types: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'My Notices',
        },
    },
    data(props) {
        return {
            notices: props.my_notices.slice(0, this.iter),
            descending: true,

        }
    },
    setup(props) {
        let iter = 10;
        return {
            iter,
        }
    },
    methods: {
        viewAllNotices: function () {
            this.iter = this.my_notices.length;
            this.notices = this.my_notices.slice(0, this.iter);
        },
    }

}
</script>
