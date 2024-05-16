<template>

    <Head :title="`Create ${pageHeader}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('airframes.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="`Create ${pageHeader}`"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Add an Aeroplane Type</h1>
                </div>
            </div>

            <nav class="my-6 p-5 rounded-lg border-2 border-gray-200" aria-label="Progress">
                <ol role="list" class="space-y-4 md:flex md:space-y-0 md:space-x-8">
                    <li class="md:flex-1">
                        <!-- Current Step -->
                        <a href="#"
                           class="flex flex-col border-l-4 jw-border__blue py-2 pl-4 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0"
                           aria-current="step">
                            <span class="text-sm font-medium jw-text__blue">Step 1</span>
                            <span class="text-sm font-medium text-black">Details</span>
                        </a>
                    </li>

                    <li class="md:flex-1">
                        <!-- Upcoming Step -->
                        <a href="#"
                           class="group flex flex-col border-l-4 border-gray-200 py-2 pl-4 hover:border-gray-300 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0">
                            <span class="text-sm font-medium text-gray-400 group-hover:text-gray-700">Step 2</span>
                            <span class="text-sm font-medium text-black">Notes</span>
                        </a>
                    </li>

                    <li class="md:flex-1">
                        <!-- Upcoming Step -->
                        <a href="#"
                           class="group flex flex-col border-l-4 border-gray-200 py-2 pl-4 hover:border-gray-300 md:border-l-0 md:border-t-4 md:pl-0 md:pt-4 md:pb-0">
                            <span class="text-sm font-medium text-gray-400 group-hover:text-gray-700">Step 2</span>
                            <span class="text-sm font-medium text-black">Schematics</span>
                        </a>
                    </li>
                </ol>
            </nav>


            <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <form class="jw-form" @submit.prevent="submit">

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="name">Name</label>
                        <input id="name" v-model="form.name" class="block w-full" type="text"/>
                        <div v-if="errors.name" class="text-red-500 text-xs pt-2">{{ errors.name }}</div>
                    </div>

                    <div class="block pb-4">
                        <label class="block w-full text-md font-bold" for="revision">Revision Number</label>
                        <input id="revision" v-model="form.revision" class="block w-full" type="text"/>
                        <div v-if="errors.revision" class="text-red-500 text-xs pt-2">{{ errors.revision }}</div>
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
                        <a :href="route('airframes.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        <button class="jw-btn jw-btn__blue-dark" type="submit">Add Aeroplane Type</button>
                    </div>

                </form>

            </div>

        </template>

    </AuthenticatedLayout>

</template>

<script>
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
            default: 'Aeroplane Types',
        },
        users: {
            type: Object,
            default: {},
        },
    },
    methods : {

        removeFromAirframeUsers: function (user, event) {
            this.airframe_users = this.airframe_users.filter((element, index) => {
                return user.id != element.id;
            });
            this.form.cengineer_ids = this.form.cengineer_ids.filter(id => {
                return user.id != id;
            });
        },

        airframeCEngineerChanged: function (event) {
            let selectedUserId = parseInt(event.target.value);
            let already = this.airframe_users.find(element => element.id == selectedUserId);
            if (already === undefined) {
                for (let i = 0; i < this.users.length; i++) {
                    if (selectedUserId == this.users[i].id) {
                        this.airframe_users.push(this.users[i]);
                        this.form.cengineer_ids.push(selectedUserId);
                        break;
                    }
                }
            }
        },
    },

    setup(props) {

        const form = useForm({
            name: null,
            revision: "",
            description: "",
            active: 1,
            cengineer_ids: [],
        });

        function submit() {
            Inertia.post('/airframes', form);
        }

        function cancel() {
            Inertia.post('/airframes', form);
        }

        let airframe_users = props.users.filter((element, index) => {
            return form.cengineer_ids.includes(element.id);
        });

        return {
            form,
            submit,
            airframe_users,
        }
    }
}
</script>
