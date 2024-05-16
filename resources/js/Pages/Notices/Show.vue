<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('notices.index')" :title="'Notices'" />
        <BreadcrumbItem :title="notice.title" />
    </template>

    <template v-slot:default>
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="inline-flex items-center text-3xl font-semibold text-gray-900">
                    <StatusDot :bgColor="notice.markReadStatus.length > 0 ?'bg-green-500':'bg-red-600'" />
                    {{notice.title}}
                </h1>
            </div>
            <div class="sm:flex text-gray-400 gap-x-7 text-end">
                <span v-if="notice.markReadStatus.length > 0" class="flex items-center border border-green-600 text-green-600 bg-green-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                    <StatusDot bgColor="bg-green-600" />
                    <span class=" text-green-500">
                        Read
                    </span>
                </span>
                <span v-else class="flex items-center border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
                    <StatusDot bgColor="bg-red-600" />
                    <span class=" text-red-500">
                        Unread
                    </span>
                </span>
            </div>
        </div>
        <div class="sm:flex sm:items-center mt-4">
            <div class="sm:flex items-center">
                <div class="mr-4 flex items-center">
                    <span class="mr-2">
                        <UserIcon svgFill="#d1d5db" />
                    </span>
                    <strong class="text-sm font-semibold">Created By : </strong>

                    <span class="text-sm">
                        {{Userhelper.user.lname}}, {{Userhelper.user.fname}}
                    </span>
                </div>
                <div class="mx-4 gap-x-2">
                    <strong class="text-sm font-semibold">Created On : </strong>
                    <span class="text-sm">
                        {{formatCreatedDate(notice.created_at)}}
                    </span>
                </div>
            </div>
        </div>

        <div class="border border-gray-300 rounded-lg overflow-hidden mt-5">
            <div class="p-6 bg-white overflow-hidden shadow-sm min-h-64">
                <label class="inline-block text-sm font-medium text-gray-400 mb-4 capitalize">
                    {{notice.notice_type.name}}
                </label>
                <h1 class="text-xl font-semibold text-gray-900 mb-6 capitalize">{{notice.title}}</h1>
                <div class=" text-base text-gray-500 mb-4" v-html="notice.comment" />
            </div>
            <!-- <div v-if="attachments.length > 0" class="px-6 bg-white overflow-hidden shadow-sm border-t">
                <div class="block airframe-files">
                    <ViewDocument :attachments="attachments" />
                </div>
            </div> -->

            <div class="px-6 bg-white overflow-hidden shadow-sm border-t">
                <div class="block airframe-files">
                    <div v-if="notice.notice_documents_history.length > 0" class="mb-3 overflow-hidden">
                        <div class="flex items-center justify-start space-x-4 my-2 w-full">
                            <h2 class="text-sm font-medium text-gray-500">Documents</h2>
                            <div class="w-full gap-y-2">
                                <div v-for="file in notice.notice_documents_history" :key="file.id" class="flex my-1 items-center flex-auto justify-between p-3 border rounded-md border-gray-300 overflow-hidden">

                                    <span class="attachment-details">
                                        <span class="attachment-icon inline-block w-4 h-4 mr-2">
                                            <IconPaperclip />
                                        </span>
                                        <span class="attachment-name text-sm text-gray-500">
                                            {{ file.notice_document_name }}
                                        </span>
                                    </span>
                                    <template v-if="file.status == 'Current'">
                                        <span class="document-actions">
                                            <a :href="file.notice_document" class="text-gray-900 mr-4 text-sm font-medium" target="_blank">View</a>
                                        </span>
                                    </template>
                                    <template v-else>
                                        <span class="document-actions">
                                            <a @click="openOldDocumentPopup(file.notice_document)" class="text-gray-900 mr-4 text-sm font-medium" target="_blank">View</a>
                                        </span>
                                    </template>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="sm:flex justify-end px-6 py-4">
                <div class="sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                    <template v-if="Userhelper.user.is_admin == true || Userhelper.user.is_training_admin == true">
                        <a :href="route('notices.trackReaders', {'id' : notice.id } )" class="jw-btn jw-btn__blue">Track Readers</a>
                    </template>
                    <template v-if="notice.markReadStatus.length > 0">
                        <button class="jw-btn-md jw-btn__blue disabled:opacity-50" type="button" disabled>Mark as Read</button>
                    </template>
                    <template v-else>
                        <button class="jw-btn-md jw-btn__blue" type="button" @click="markRead">Mark as Read</button>
                    </template>
                </div>
            </div>
        </div>

        <div v-if="oldDocumentPopup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">

            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

            <div class="fixed inset-0 z-10 overflow-y-auto">
                <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">

                    <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                        <div class="bg-white flex justify-center px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <div class="items-center">
                                <div class="flex justify-center">
                                    <div class="m-auto flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                                        <svg aria-hidden="true" class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>
                                </div>

                                <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">

                                    <h3 id="modal-title" class="text-lg font-medium leading-6 text-gray-900 text-center">
                                        <p class="mx-1 text-center text-2xl">A newer version of this document exists</p>
                                        <p class="mx-1 text-center text-gray-400">There is a newer version of this document. Do you wish to continue?</p>
                                    </h3>
                                    <div class="mt-2">
                                        <p class="text-sm text-gray-500">
                                            <slot name="default" />
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="bg-gray-50 w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:px-6">
                            <button @click="closeOldDocumentPopup()" class="w-full jw-btn border-gray-300 text-gray-500 btn-cancel" type="button">
                                Cancel
                            </button>
                            <a :href="popupFilepath" class="w-full jw-btn jw-btn__blue" target="_blank">Show me anyway</a>
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
            notice_id: props.notice.id,
            oldDocumentPopup: false,
            popupFilepath:'',
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

        openOldDocumentPopup: function (url) {
            this.oldDocumentPopup = true;
            this.popupFilepath = url;
        },

        closeOldDocumentPopup: function () {
            this.oldDocumentPopup = false;
        },

        markRead: function () {
            const form = {
                notice_id: this.notice_id,
            }
            Inertia.post(route('notices.markRead'), form);
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
