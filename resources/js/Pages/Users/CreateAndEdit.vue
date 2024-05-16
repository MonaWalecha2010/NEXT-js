<template>
<Head :title="`${this.actionPrefix} Team Member`" />

<AuthenticatedLayout :can="can">

    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('users.index')" :title="pageHeader" />
        <BreadcrumbItem v-if="this.actionPrefix == 'create'" :title="`${this.actionPrefix} ${pageHeader}`" />
        <BreadcrumbItem v-else :title="`${this.actionPrefix} ${user.fname} ${user.lname}`" />
    </template>

    <template v-slot:default>

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">{{ this.actionPrefix }} Team Member</h1>
            </div>
        </div>

        <div class="my-6 p-6  bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form class="jw-form" @submit.prevent="submit">

                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                    <div class="sm:col-span-2">
                        <label for="name">First Name</label>
                        <input id="fname" v-model="form.fname" class="block w-full" type="text" />
                        <div v-if="errors.fname" class="text-red-500 text-xs pt-2">{{ errors.fname }}</div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="name">Last Name</label>
                        <input id="lname" v-model="form.lname" class="block w-full" type="text" />
                        <div v-if="errors.lname" class="text-red-500 text-xs pt-2">{{ errors.lname }}</div>
                    </div>

                    <div class="sm:col-span-2">
                        <label for="name">Email Address</label>
                        <input id="email" v-model="form.email" class="block w-full" type="text" />
                        <div v-if="errors.email" class="text-red-500 text-xs pt-2">{{ errors.email }}</div>
                    </div>

                </div>

                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                    <div class="sm:col-span-2">

                        <label for="role_id">Role</label>

                        <select id="role_id" v-model="form.role_id" name="show">
                            <option disabled>Choose Role</option>
                            <option :selected="form.role_id == 3" value="3">Mechanic</option>
                            <option :selected="form.role_id == 4" value="4">Engineer</option>
                            <option :selected="form.role_id == 5" value="5">Certified Engineer</option>
                            <option :selected="form.role_id == 2" value="2">Administrator</option>
                        </select>
                    </div>

                    <div class="sm:col-span-2">

                        <label for="role_id">Training Role</label>
                        <select id="role_id" v-model="form.training_role_id" name="show">
                            <option disabled>Choose Training Role</option>
                            <option v-for="training_role in training_roles.data" :key="training_role.id" :value="training_role.id" :selected="form.training_role_id == training_role.id">{{training_role.name}}</option>
                        </select>

                    </div>

                    <div class="sm:col-span-2">
                        <label for="code">Worker Code -
                            <span class="text-xs text-red-600">Note: This cannot be changed</span>
                        </label>
                        <template v-if="user.id">
                            <input v-model="form.code" id="code" class="block w-full" type="text" maxlength="6" disabled />
                        </template>
                        <template v-else>
                            <input v-model="form.code" @keyup.prevent="truncateUserCode" id="code" class="block w-full" type="text" maxlength="6" />
                            <div v-if="errors.code" class="text-red-500 text-xs pt-2">{{ errors.code }}</div>
                        </template>
                    </div>

                </div>

                <div class="mt-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-12">

                    <div class="sm:col-span-4">

                        <div class="user-status">
                            <label for="active">Status</label>
                            <select id="active" v-model="form.active" class="" name="show">
                                <option :selected="form.active == 1" value="1">Active</option>
                                <option :selected="form.active == 0" value="0">Inactive</option>
                            </select>
                            <div v-if="errors.active" class="text-red-500 text-xs pt-2">{{ errors.active }}</div>
                        </div>

                        <div v-if="isCengineer" class="user-airframes mt-6">

                            <label for="airframe_id">Authorized Aeroplane Types</label>
                            <div class="p-4 border border-gray-200 rounded-md">
                                <select @change="userAirframeChanged" id="airframe_id" class="block w-full">
                                    <option selected="true" disabled>Choose Aeroplane Types
                                    </option>
                                    <option v-for="airframe in airframes" :key="airframe.id" :value="airframe.id">
                                        {{ airframe.fullname }}
                                    </option>
                                </select>
                                <div v-if="form.airframe_ids" v-show="form.airframe_ids.length > 0" class="flex grow-0 gap-2 mt-4 user-airframes selected">
                                    <span v-for="airframe in this.user_airframes" @click.prevent="removeFromUserAirframes( airframe )" class="justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">
                                        <span class="icon icon__white w-3 h-3">
                                            <IconPlane /></span>
                                        <span class="ml-1">{{ airframe.fullname }}</span>
                                        <span class="ml-4 cursor-pointer">x</span>
                                    </span>
                                </div>
                                <div v-if="errors.airframe_ids" class="text-red-500 text-xs pt-2">
                                    {{ errors.airframe_ids }}
                                </div>
                            </div>

                        </div>

                        <div v-if="isEngineer" class="mt-6 user-stamp">
                            <label for="name">Workpack Report Stamp</label>
                            <input id="fname" v-model="form.stamp" class="block w-full" type="text" />
                            <div v-if="errors.stamp" class="text-red-500 text-xs pt-2">{{ errors.stamp }}</div>
                        </div>

                    </div>

                    <div class="sm:col-span-3">
                        <label for="name">License Number</label>
                        <input id="license_number" v-model="form.license_number" class="block w-full" type="text" />
                        <div v-if="errors.license_number" class="text-red-500 text-xs pt-2">{{ errors.license_number }}</div>
                    </div>

                    <div class="sm:col-span-5 user-signature">

                        <div v-if="isWorker">

                            <label for="active">Worker Signature</label>

                            <template v-if="user.signature">
                                <div class="relative rounded-md border-2 border border-gray-100 bg-gray-50 px-6 py-4">
                                    <img :src="user.signature" class="min-w-full mix-blend-darken" alt="">
                                    <span class="user-signature-actions absolute top-1 right-1">
                                        <a @click.prevent="clearSignature" class="jw-text__black mr-2">Replace</a>
                                        <a @click.prevent="clearSignature" class="jw-text__red">Delete</a>
                                    </span>
                                </div>
                            </template>

                            <template v-else>

                                <div class="text-center text-sm text-gray-600 rounded-md border-2 border-dashed border-gray-100 px-6 pt-7 pb-8">

                                    <span class="inline-block mx-auto mb-4 icon jw-svg-stroke__gray-light w-8 h-8">
                                        <IconPaperclip /></span>
                                    <label for="document-upload" class="relative cursor-pointer rounded-md font-medium text-gray-300">
                                        <span class="">Add a signature</span>
                                        <br>
                                        <br>
                                        <span class="jw-btn jw-btn__blue">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m6-6H6" />
                                            </svg>
                                            Add Signature</span>
                                    </label>
                                    <input id="document-upload" name="files" @input="previewSignature" class="sr-only" type="file" />

                                </div>

                            </template>

                            <div v-if="errors.signature" class="text-red-500 text-xs pt-2">{{
                                        errors.signature
                                    }}
                            </div>
                        </div>

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

                <div class="flex pt-8 justify-end space-x-6">
                    <div v-if="user.id" class="sm:flex-auto">
                        <a class="jw-btn jw-btn__red" @click.prevent="showActionCodeConfirmation">Delete Team
                            Member</a>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                        <a :href="route('users.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        <button class="jw-btn jw-btn__blue" type="submit">{{ this.actionPrefix }} Team Member
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <ActionCodeConfirmation :visible="show_code" @closeCodeConfirmation="listenForActionCodeConfirmation" />

    </template>

</AuthenticatedLayout>
</template>

<script>
import ActionCodeConfirmation from "@/Jetworks/Common/ActionCodeConfirmation.vue";
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import {
    useForm
} from '@inertiajs/inertia-vue3';
import TextInput from '@/Components/InputLabel.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import IconPlane from "@/Jetworks/Icons/Aeroplane.vue";
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

export default {
    components: {
        BreadcrumbItem,
        IconPaperclip,
        IconPlane,
        ActionCodeConfirmation,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
    },
    data() {
        return {
            show_code: false,
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
        roles: {
            type: Object,
            default: {},
        },
        training_roles: {
            type: Object,
            default: {},
        },
        airframes: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Users',
        }
    },
    computed: {
        actionPrefix() {
            return (this.$props.user.id) ? "Update" : "Add";
        },
        isWorker() {
            let role_id = parseInt(this.form.role_id);
            let workerRoles = [3, 4, 5];
            return workerRoles.includes(role_id);
        },
        isEngineer() {
            let role_id = parseInt(this.form.role_id);
            let workerRoles = [4, 5];
            return workerRoles.includes(role_id);
        },
        isCengineer() {
            let role_id = parseInt(this.form.role_id);
            let workerRoles = [5];
            return workerRoles.includes(role_id);
        }
    },

    methods: {
        clearSignature: function () {
            this.user.signature = null;
            this.form.signature = null;
        },

        previewSignature: function (event) {
            if (event.target.files.length > 0) {
                let src = URL.createObjectURL(event.target.files[0]);
                this.user.signature = src;
                this.form.signature = event.target.files[0];
            }
        },

        removeFromUserAirframes: function (airframe, event) {
            this.user_airframes = this.user_airframes.filter((element, index) => {
                return airframe.id != element.id;
            });
            this.form.airframe_ids = this.form.airframe_ids.filter(id => {
                return airframe.id != id;
            });
        },

        userAirframeChanged: function (event) {
            let selectedAirframeId = parseInt(event.target.value);
            let already = this.user_airframes.find(element => element.id == selectedAirframeId);
            if (already === undefined) {
                for (let i = 0; i < this.airframes.length; i++) {
                    if (selectedAirframeId == this.airframes[i].id) {
                        this.user_airframes.push(this.airframes[i]);
                        this.form.airframe_ids.push(selectedAirframeId);
                        break;
                    }
                }
            }
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

        checkUserCode: function (code) {
            return (code.length == 6) ? true : false;
        },

        truncateUserCode: function (event) {
            let target = event.target;
            let code = target.value;
            if (this.checkUserCode(code)) {
                this.errors.code = null;
                target.value = code.slice(0, 6);
            } else {
                this.form.code = code.slice(0, 6);
            }
        },

        destroy(event) {
            this.form.delete(route('users.destroy', {
                user: this.user.id
            }));
        },

    },

    setup(props) {
        const formMethod = (props.user.id) ? "put" : "post";
        const form = useForm({
            _method: formMethod,
            fname: props.user.fname,
            lname: props.user.lname,
            email: props.user.email,
            code: props.user.code,
            stamp: props.user.stamp,
            role_id: props.user.role_id,
            training_role_id: props.user.training_role_id,
            license_number: props.user.license_number,
            active: props.user.active,
            airframe_ids: props.user.airframe_ids,
            signature: props.user.signature,
        });

        let user_airframes = [];
        if (props.airframes.length > 0) {
            user_airframes = props.airframes.filter((element, index) => {
                return form.airframe_ids.includes(element.id);
            });
        }

        function submit() {
            if (props.user.id == null) {
                form.post(route('users.store'));

                // console.log(form);
            } else {
                form.post(route('users.update', {
                    user: props.user.id
                }));
            }
        }

        return {
            form,
            submit,
            user_airframes: user_airframes,
        }
    },

}
</script>
