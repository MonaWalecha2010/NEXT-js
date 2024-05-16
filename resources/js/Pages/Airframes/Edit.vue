<template>

    <Head :title="`Edit ${ form.name }`"/>

    <AuthenticatedLayout :can="can">


        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('airframes.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="`Edit ${ form.name }`"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Edit {{ form.name }} {{ form.revision }}</h1>
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
                        <label class="block w-full text-md font-bold" for="revision">Revision Number</label>
                        <input id="revision" v-model="form.revision" class="block w-full" type="text"/>
                        <div v-if="errors.revision" class="text-red-500 text-xs pt-2">{{ errors.revision }}</div>
                    </div>

                    <div class="pb-6">
                        <label for="cengineers">Authorised C Level Engineers</label>
                        <div class="p-4 border border-gray-200 rounded-md">
                            <select @change="airframeCEngineerChanged"
                                    id="cengineers" class="block w-full">
                                <option selected="true">Choose Authorized Senior Engineers</option>
                                <option v-for="user in users"
                                        :value="user.id"
                                        :key="user.id">{{ user.name }}</option>
                            </select>

                            <div class="flex grow-0 gap-2 mt-4 airframe-cengineers selected"
                                 v-show="form.cengineer_ids.length > 0">
                                    <span v-for="user in this.airframe_users"
                                          @click.prevent="removeFromAirframeUsers( user )"
                                          class="justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">
                                        <span class="ml-1">{{ user.name }}</span>
                                        <span class="ml-4 cursor-pointer">x</span>
                                    </span>
                            </div>
                            <div v-if="errors.cengineers" class="text-red-500 text-xs pt-2">{{ errors.cengineers }}</div>

                        </div>

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
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{
                                errors.description
                            }}
                        </div>
                    </div>

                    <div class="block pb-4 airframe-files">

                        <DeleteDocuments :attachments="attachments"/>

                        <template  v-if="form.files.length > 0" >
                            <Documents :attachments="form.files"/>
                            <div class="block my-4 text-center">
                                <a @click.prevent="resetDocuments" class="jw-btn jw-btn__gray">Choose different Documents</a>
                            </div>
                        </template>

                        <div v-else
                             class="text-center text-sm text-gray-600 rounded-md border-2 border-dashed border-gray-100 px-6 pt-7 pb-8">

                            <span class="inline-block mx-auto mb-4 icon jw-svg-stroke__gray-light w-8 h-8"><IconPaperclip/></span>
                            <label for="document-upload"
                                   class="relative cursor-pointer rounded-md font-medium text-gray-300">
                                <span class="">Add a supporting document</span>
                                <br>
                                <br>
                                <span class="jw-btn jw-btn__blue">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                            </svg>
                                            Add Document</span>
                            </label>
                            <input id="document-upload"
                                   name="files"
                                   @input="form.files = $event.target.files"
                                   class="sr-only"
                                   multiple="true"
                                   type="file"/>
                        </div>

                    </div>

                    <div class="block pb-4 airframe-schematics">

                        <Schematics :schematics="schematics"/>

                        <Schematics v-if="form.schematics.length > 0" :schematics="form.schematics"/>

                        <div class="text-center text-sm text-gray-600 rounded-md border-2 border-dashed border-gray-100 px-6 pt-7 pb-8">
                            <Link :href="route('schematics.create', { 'airframe_id' : airframe.id } )"
                                class="w-full relative cursor-pointer rounded-md font-medium text-gray-300">
                                <span class="">Add a new Aeroplane schematic</span>
                                <br>
                                <br>
                                <span class="jw-btn jw-btn__blue">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                 stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                              <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6"/>
                                            </svg>
                                            Add Schematic</span>
                            </Link>
                        </div>
                    </div>


                    <div v-if="errors.create" class="text-red-500 text-sm pt-2">{{ errors.create }}</div>

                    <div v-if="errors.general" class="rounded-md bg-red-50 p-4 mb-6">
                        <div class="flex">
                            <div class="text-sm text-red-700">
                                {{ errors.general }}
                            </div>
                        </div>
                    </div>

                    <div class="sm:flex sm:items-center">
                        <div class="sm:flex-auto">
                            <a class="jw-btn jw-btn__red" @click.prevent="showActionCodeConfirmation">Delete Aeroplane
                                Type</a>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                            <a :href="route('airframes.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                            <button class="jw-btn jw-btn__blue" type="submit">Save Aeroplane Type</button>
                        </div>
                    </div>

                </form>
            </div>

            <ActionCodeConfirmation v-if="this.show_code" :visible="this.show_code" @closeCodeConfirmation="listenForActionCodeConfirmation"/>

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
import DeleteDocuments from "@/Jetworks/Common/DeleteDocuments.vue";
import Documents from "@/Jetworks/Common/Documents.vue";
import Schematics from "@/Jetworks/Common/Schematics.vue";
import IconSchematic from "@/Jetworks/Icons/Schematic.vue";
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import ActionCodeConfirmation from "@/Jetworks/Common/ActionCodeConfirmation.vue";

export default {
    components: {
        ActionCodeConfirmation,
        BreadcrumbItem,
        IconSchematic,
        Schematics,
        Documents,
        IconPaperclip,
        DeleteDocuments,
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
        airframe: {
            type: Object,
            default: {},
        },
        attachments: {
            type: Object,
            default: {},
        },
        schematics: {
            type: Object,
            default: {},
        },
        users: {
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
        }
    },
    methods: {

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

        resetDocuments: function() {
            this.form.files = [];
        },

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
            Inertia.delete(route('airframes.destroy', {airframe: this.airframe.id}));
        }
    },

    setup(props) {

        const form = useForm({
            _method: 'put',
            name: props.airframe.name,
            revision: props.airframe.revision,
            description: props.airframe.description,
            active: props.airframe.active,
            files: [],
            schematics: [],
            cengineer_ids: props.airframe.cengineer_ids,
        });

        function submit() {
            Inertia.post(route('airframes.update', {airframe: props.airframe.id}), form);
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
