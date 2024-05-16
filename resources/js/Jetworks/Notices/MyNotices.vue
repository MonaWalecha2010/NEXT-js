<template>
<div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
    <table v-if="my_notices.data.length > 0" class="jw-table min-w-full traning_tabel">
        <thead class="bg-white">
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
                <th class="text-center" scope="col">
                    Type
                </th>
                <th scope="col">
                    Date
                </th>
                <th scope="col" ></th>
            </tr>
        </thead>
        <tbody class="bg-white">
            <tr class="border-t border-gray-300  text-gray-400 bg-gray-50 ">
                <td colspan="6"><span class="text-sm font-medium text-gray-400">Unread</span></td>              
            </tr>
            <tr v-for="(notice,index) in my_notices.data" class="border-t border-gray-300 bg-red-50">
                <template v-if="notice.roles.length > 0">
                    <template v-if="notice.markReadStatus.length == 0">
                        <td class="text-center">
                            <StatusDot bgColor="bg-red-600" />
                        </td>
                        <td>
                            {{notice.number}}
                        </td>
                        <td>
                            <strong>{{notice.title}}</strong>
                        </td>
                        <td class="text-center">
                            <RoundedTextPill actionStatus="required" :actionValue="notice.notice_type.name" />
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
                <td colspan="6"><span class="text-sm font-medium text-gray-400">Read</span></td>
            </tr>
            <tr v-for="(notice,index) in my_notices.data" class="border-t border-gray-300 bg-white">
                <template v-if="notice.roles.length > 0">
                    <template v-if="notice.markReadStatus.length > 0">
                        <td class="text-center">
                            <StatusDot bgColor="bg-green-500" />                            
                        </td>
                        <td class="text-gray-400">
                            {{notice.number}}
                        </td>
                        <td class="text-gray-400">
                            <strong>{{notice.title}}</strong>
                        </td>
                        <td class="text-center">
                            <RoundedTextPill actionStatus="active" :actionValue="notice.notice_type.name" />
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

</template>

<script>
import RoundedTextPill from '@/Components/RoundedTextPill.vue'
import IconPen from '@/Jetworks/Icons/Pen.vue';
import StatusDot from '@/Components/StatusDot.vue'
export default {
    name: 'MyNotices',
    components: {
        RoundedTextPill,
        IconPen,
        StatusDot
    },
    props: {
        my_notices: {
            type: Object,
            default: {},
        },
        notice_types: {
            type: Object,
            default: {},
        }
    }
}
</script>
