<template>
<div class="flex items-center justify-between py-2 mb-5">
    <div class="flex-shrink-0 ">
        <input type="text" placeholder="Search..." v-model="searchNoticeInput" @input="searchNotice()" class="px-4 py-2 bg-white border border-gray-300 rounded-md focus:outline-none focus:border-indigo-500 max-w-64">
    </div>

    <div class="flex-shrink-0">
        <Link :href="route('notices.create')" class="jw-btn jw-btn__blue ml-4"> Add Notice</Link>
    </div>
</div>

<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table v-if="notices.data.length > 0" class="jw-table min-w-full">
        <thead class="bg-white">

            <div class="rounded-full ..."></div>

            <tr>
                <th scope="col" class="lg:w-[125px]">
                    Status
                </th>
                <th scope="col">
                    No.
                </th>
                <th scope="col" style="width:50%">
                    Title
                </th>
                <th scope="col">
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
                <td colspan="6"><span class="text-sm font-medium text-gray-400">Active</span></td>
            </tr>
            <tr v-for="(notice,index) in allnotices" class="border-t border-gray-300">
                <template v-if="notice.status == 1">
                    <td class="text-center">
                        <StatusDot bgColor="bg-blue-500" />
                    </td>
                    <td>
                        {{notice.number}}
                    </td>
                    <td>
                        <strong>{{notice.title}}</strong>
                    </td>
                    <td>
                        <RoundedTextPill actionStatus="pending" :actionValue="notice.notice_type.name" />
                    </td>

                    <td>
                        <span class="text-black">{{notice.created_at}}</span>
                    </td>
                    <td>
                        <Link :href="route('notices.edit', {'id' : notice.id } )">
                        <span class="inline-block icon icon__pen w-4 h-4">
                            <IconPen /></span>
                        <span class="sr-only">Edit</span>
                        </Link>
                    </td>
                </template>
            </tr>
            <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                <td colspan="6"><span class="text-sm font-medium text-gray-400">Withdrawn</span></td>
            </tr>
            <tr v-for="(notice,index) in allnotices" class="border-t border-gray-300">
                <template v-if="notice.status == 0">
                    <td class="text-center">
                        <StatusDot bgColor="bg-yellow-500" />
                    </td>
                    <td>
                        {{notice.number}}
                    </td>
                    <td>
                        <strong>{{notice.title}}</strong>
                    </td>
                    <td>
                        <RoundedTextPill actionStatus="pending" :actionValue="notice.notice_type.name" />
                    </td>

                    <td>
                        <span class="text-black">{{notice.created_at}}</span>
                    </td>
                    <td>
                        <!-- <Link :href="route('notices.edit', {'id' : notice.id } )"> -->
                        <span class="inline-block icon icon__pen w-4 h-4">
                            <IconPen /></span>
                        <span class="sr-only">Edit</span>
                        <!-- </Link> -->
                    </td>
                </template>
            </tr>
        </tbody>
    </table>
    <table v-else class="jw-table min-w-full bg-white  ">
        <div class="p-4 text-black">Notices Will Appear here.</div>
    </table>
</div>
<!-- {{notices.data.length}} -->
<div v-if="notices.data.length > 10 && allnotices.length == 10" class="flex w-full p-8 justify-center my-8">
    <button @click="viewAllNotices()" class="jw-btn jw-btn__blue" type="button">
        View All
    </button>
</div>
</template>

<script>
import IconPen from '@/Jetworks/Icons/Pen.vue';
import RoundedTextPill from '@/Components/RoundedTextPill.vue'
import StatusDot from '@/Components/StatusDot.vue'

export default {
    components: {
        IconPen,
        RoundedTextPill,
        StatusDot,

    },
    name: 'AllNotices',
    props: {
        notices: {
            type: Object,
            default: {},
        },
        notice_types: {
            type: Object,
            default: {},
        }
    },
    data(props) {
        return {
            searchNoticeInput: '',
            allnotices: props.notices.data.slice(0, this.iter),
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
        searchNotice: function () {
            const noticeValue = this.searchNoticeInput;

            // if (noticeValue != '') {
            //     this.noticeSearch = true;
            // } else {
            //     this.noticeSearch = false;
            // }

            axios.post(route('notices.search', {
                    'noticeValue': noticeValue,
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        const newResponseData = response.data.data.data;
                        this.allnotices = newResponseData;
                    }
                }).catch(error => {
                    console.log(error);
                })
        },
        viewAllNotices: function () {
            this.iter = this.notices.length;
            this.allnotices = this.notices.data.slice(0, this.iter);
        }
    }

}
</script>
