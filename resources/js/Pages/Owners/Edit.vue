<template>

    <Head :title="`Create ${pageHeader}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Owner</h2>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Edit {{ form.name }}</h1>
                </div>
            </div>

            <div class="my-6 p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form class="jw-form" @submit.prevent="submit">

                    <div class="block pb-6">
                        <label for="name">Name</label>
                        <input id="name" v-model="form.name" type="text"/>
                        <div v-if="errors.name" class="text-red-500 text-xs pt-2">{{ errors.name }}</div>
                    </div>

                    <div class="block pb-6">
                        <label for="email">Email</label>
                        <input id="email" v-model="form.email" type="email"/>
                        <div v-if="errors.email" class="text-red-500 text-xs pt-2">{{ errors.email }}</div>
                    </div>

                    <div class="block pb-6">
                        <label for="phone">Phone</label>
                        <input id="phone" v-model="form.phone" type="tel"/>
                        <div v-if="errors.phone" class="text-red-500 text-xs pt-2">{{ errors.phone }}</div>
                    </div>

                    <div class="pb-6">
                        <label for="active">Status</label>
                        <select id="active" v-model="form.active" class="" name="show">
                            <option :selected="form.active == 1" value="1">Active</option>
                            <option :selected="form.active == 0" value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="block pb-6">
                        <label for="description">Description</label>
                        <QuillEditor ref="quill" v-model:content="form.description" content="html" contentType="html"
                                     theme="snow" toolbar="essential"/>
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{
                                errors.description
                            }}
                        </div>
                    </div>

                    <div v-if="aeroplanes.length > 0" class="block pb-6">
                        <p class="label" for="description">Owner's Aeroplanes</p>
                        <a v-for="aeroplane in aeroplanes"
                           :href="route('aeroplanes.show', { 'aeroplane' : aeroplane.id })"
                           class="aeroplanes inline-block mr-2 text-xs ">
                            {{ aeroplane.name }}
                        </a>
                    </div>

                    <div v-if="errors.general" class="rounded-md bg-red-50 p-4 mb-6">
                        <div class="flex">
                            <div class="text-sm text-red-700">
                                {{ errors.general }}
                            </div>
                        </div>
                    </div>

                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <a class="jw-btn jw-btn__red" @click.prevent="showActionCodeConfirmation">Delete Owner</a>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                            <a :href="route('owners.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                            <button class="jw-btn jw-btn__blue" type="submit">Update Owner</button>
                        </div>
                    </div>

                </form>

            </div>

            <ActionCodeConfirmation v-if="this.show_code" :visible="this.show_code" @closeCodeConfirmation="listenForActionCodeConfirmation"/>

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
import ActionCodeConfirmation from "@/Jetworks/Common/ActionCodeConfirmation.vue";

export default {
    components: {
        ActionCodeConfirmation,
        BreadcrumbItem,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
        QuillEditor,
    },
    data() {
        return {
            quill: null,
            show_code: false,
        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        owner: {
            type: Object,
            default: {},
        },
        aeroplanes: {
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
    methods: {
        showActionCodeConfirmation: function () {
            this.show_code = true;
        },

        listenForActionCodeConfirmation: function (data) {
            if (data != false) {
                this.destroy();
            }
            this.show_code = false;
        },

        destroy(event) {
            Inertia.delete(route('owners.destroy', {owner: this.owner.id}));
        }
    },

    setup(props) {
        const form = useForm({
            _method: 'put',
            name: props.owner.name,
            email: props.owner.email,
            phone: props.owner.phone,
            description: props.owner.description ?? "",
            active: props.owner.active
        });

        function submit() {
            Inertia.post(route('owners.update', {owner: props.owner.id}), form);
        }

        return {
            form,
            submit,
        }
    }
}
</script>
