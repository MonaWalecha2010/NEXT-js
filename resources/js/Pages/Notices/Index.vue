<template>
<Head :title="`${pageHeader}`" />
<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :title="pageHeader" />
    </template>

    <template v-slot:default>

        <toaster ref="toaster"></toaster>

        <div class="sm:flex justify-between sm:items-center traning_tabs">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Notices</h1>
            </div>
            <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                <div class="flex rounded-md overflow-hidden shadow-sm shadow-[#0000001A] bg-white flex-wrap">
                    <div class="text-sm bg-white px-6 py-4 cursor-pointer text-gray-600 lg:min-w-[220px] text-center font-semibold  border-b-2 sm:border-b-0 sm:border-r  border-gray-300 last:border-r-0" v-for="(tab,index) in tabs" :key="index" @click="selectedTabs = tab" :class="{'text-gray-900 border-b-2 sm:border-b-2 border-sky-600' : selectedTabs === tab}">
                        {{tab}}
                    </div>
                </div>
            </div>
        </div>

        <div class="mt-8 flex flex-col">
            <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
                <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
                    <div class="tabs_content">
                        <div class="tab_content" v-show="selectedTabs === 'My Notices'">
                            <MyNotices :my_notices="my_notices" :notice_types="notice_types" />
                        </div>
                        <div class="tab_content" v-show="selectedTabs === 'All Notices'">
                            <AllNotices :notices="notices" :notice_types="notice_types" />
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
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import MyNotices from '@/Jetworks/Notices/MyNotices.vue';
import AllNotices from '@/Jetworks/Notices/AllNotices.vue';
import Toaster from '@/Jetworks/Common/Toaster.vue';


export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        MyNotices,
        AllNotices,
        Toaster,
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        notices: {
            type: String,
            default: 'Notices',
        },
        my_notices: {
            type: String,
            default: 'Notices',
        },
        notice_types: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Notices',
        },
        toast: {
            type: Object,
            default: {},
        }
    },
    data() {
        return {
            tabs: ['My Notices', 'All Notices'],
            selectedTabs: 'All Notices',
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
