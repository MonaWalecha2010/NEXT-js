<template>
    <Head title="Profile"/>

    <AuthenticatedLayout>

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :title="Userhelper.getFullName()"/>
        </template>

        <div class="p-4 mb-6 sm:p-8 bg-white shadow sm:rounded-lg">
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
                class="max-w-xl"
            />
        </div>

        <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
            <UpdatePasswordForm class="max-w-xl"/>
        </div>

    </AuthenticatedLayout>
</template>
<script>

import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import {Userhelper} from "@/Jetworks/Userhelper";
import UpdateProfileInformationForm from "@/Pages/Profile/Partials/UpdateProfileInformationForm.vue";
import UpdatePasswordForm from "@/Pages/Profile/Partials/UpdatePasswordForm.vue";

export default {
    components: {UpdatePasswordForm,
        UpdateProfileInformationForm,
        BreadcrumbItem,
        AuthenticatedLayout
    },
    props: {
        mustVerifyEmail: Boolean,
        status: Boolean,
        pageHeader: {
            type: String,
            default: 'Users',
        }
    },
    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper;
        }
    }
}
</script>
