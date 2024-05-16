<template>

    <Head :title="`Create ${pageHeader}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('owners.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="`Create ${pageHeader}`"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Add a {{ pageHeader }}</h1>
                </div>
            </div>

            <div class="my-6 p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form class="jw-form" @submit.prevent="submit">

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="name">Name</label>
                        <input id="name" v-model="form.name" class="block w-full" type="text"/>
                        <div v-if="errors.name" class="text-red-500 text-xs pt-2">{{ errors.name }}</div>
                    </div>

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="email">Email</label>
                        <input id="email" v-model="form.email" class="block w-full" type="email"/>
                        <div v-if="errors.email" class="text-red-500 text-xs pt-2">{{ errors.email }}</div>
                    </div>

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="phone">Phone</label>
                        <input id="phone" v-model="form.phone" class="block w-full" type="tel"/>
                        <div v-if="errors.phone" class="text-red-500 text-xs pt-2">{{ errors.phone }}</div>
                    </div>

                    <div class="pb-6">
                        <label for="active">Status</label>
                        <select id="active" v-model="form.active" class="" name="show">
                            <option :selected="form.active == 1" value="1">Active</option>
                            <option :selected="form.active == 0" value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="description">Description</label>
                        <QuillEditor ref="quill" v-model:content="form.description" content="html" contentType="html"
                                     theme="snow" toolbar="essential"/>
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{ errors.description }}</div>
                    </div>

                    <div v-if="errors.create" class="text-red-500 text-sm pt-2">{{ errors.create }}</div>

                    <div class="flex pt-8 justify-end space-x-6">
                        <a :href="route('owners.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        <button class="jw-btn jw-btn__blue-dark" type="submit">Add Owner</button>
                    </div>

                </form>

            </div>

        </template>

    </AuthenticatedLayout>

</template>

<script>
import {ref, onMounted} from 'vue'
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {Inertia} from '@inertiajs/inertia';
import {useForm} from '@inertiajs/inertia-vue3';
import TextInput from '@/Components/InputLabel.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import {QuillEditor} from '@vueup/vue-quill';
import '@vueup/vue-quill/dist/vue-quill.snow.css';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

export default {
    components: {
        BreadcrumbItem,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
        QuillEditor
    },
    data() {
        return {
            quill: null,
        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Owners',
        }
    },

    setup(props) {

        const form = useForm({
            name: null,
            email: "",
            phone: "",
            description: "",
            active: 1,
        });

        function submit() {
            Inertia.post('/owners', form);
        }

        return {
            form,
            submit,
        }
    }
}
</script>
