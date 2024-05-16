<template>
<Head :title="`Create ${pageHeader}`" />

<AuthenticatedLayout :can="can">
    <template v-slot:breadcrumbs>
        <BreadcrumbItem :url="route('trainings.index')" :title="'Training'" />
        <BreadcrumbItem :title="'Edit a Training Task'" />
    </template>

    <template v-slot:default>

        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <h1 class="text-3xl font-semibold text-gray-900">Edit a Training Task</h1>
            </div>
        </div>

        <div class="my-6 p-6  bg-white overflow-hidden shadow-sm sm:rounded-lg">
            <form class="jw-form" @submit.prevent="submit">
                <div class="mt-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                    <div class="sm:col-span-1 ">
                        <label for="name">Task Number </label>
                        <input id="task_number" v-model="form.task_number" class="block w-full" type="text" />
                        <div v-if="errors.task_number" class="text-red-500 text-xs pt-2">{{ errors.task_number }}</div>
                    </div>

                    <div class="sm:col-span-3 ">
                        <label for="name">Task Title </label>
                        <input id="title" v-model="form.title" class="block w-full" type="text" />
                        <div v-if="errors.title" class="text-red-500 text-xs pt-2">{{ errors.title }}</div>
                    </div>

                    <div class="sm:col-span-2 ">
                        <label for="name">Short Name </label>
                        <input id="short_name" v-model="form.short_name" class="block w-full" type="text" />
                        <div v-if="errors.short_name" class="text-red-500 text-xs pt-2">{{ errors.short_name }}</div>
                    </div>

                </div>
                <div class="mt-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                    <div class="sm:col-span-3 mb-5">
                        <div class="sm:col-span-3 ">
                            <label for="name">External ID (Optional)</label>
                            <input id="external_id" v-model="form.external_id" class="block w-full" type="text" />
                            <div v-if="errors.external_id" class="text-red-500 text-xs pt-2">{{ errors.external_id }}</div>
                        </div>
                        <div class="sm:col-span-3 pt-5">
                            <label for="name">Revalidation Type</label>
                            <select id="revalidation_type" v-model="form.revalidation_type" class="" name="show">
                                <option v-for="(revalidation_type,index) in revalidation_types" :key="index" :value="index">{{revalidation_type}}</option>
                            </select>
                            <div v-if="errors.revalidation_type" class="text-red-500 text-xs pt-2">{{ errors.revalidation_type }}</div>
                        </div>
                    </div>

                    <div class="sm:col-span-3">
                        <label for="name">Task Description (Optional) </label>
                        <textarea id="description" v-model="form.description" rows="6" class="block w-full"></textarea>
                        <!-- <input id="description" v-model="form.description" class="block w-full" type="text" /> -->
                        <div v-if="errors.description" class="text-red-500 text-xs pt-2">{{ errors.description }}</div>
                    </div>

                </div>

                <div class="mt-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                    <div class="sm:col-span-1 ">
                        <label for="name">Validity</label>
                        <input id="validity_number" v-model="form.validity_number" class="block w-full" type="text" />
                        <div v-if="errors.validity_number" class="text-red-500 text-xs pt-2">{{ errors.validity_number }}</div>
                    </div>

                    <div class="sm:col-span-2 ">
                        <label for="name">Validity</label>
                        <select id="validity_period" v-model="form.validity_period" class="" name="show">
                            <option v-for="(validity_period,index) in validity_periods" :key="index" :value="validity_period">{{validity_period}}</option>
                        </select>
                        <div v-if="errors.validity_period" class="text-red-500 text-xs pt-2">{{ errors.validity_period }}</div>
                    </div>

                    <div class="sm:col-span-3 ">
                        <label for="name">Training Sector</label>
                        <select id="sector_id" v-model="form.sector_id" class="" name="show">
                            <option v-for="(sector,index) in sectors.data" :key="index" :value="sector.id">{{sector.title}}</option>
                        </select>
                        <div v-if="errors.sector_id" class="text-red-500 text-xs pt-2">{{ errors.sector_id }}</div>
                    </div>
                </div>

                <div class="mt-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                    <div class="sm:col-span-6 flex">

                        <div v-for="(role,index) in training_roles.data" class="mx-2">
                            <label for="myCheckbox" class="block text-sm font-medium text-gray-700">{{role.name}}</label>
                            <input type="checkbox"  :value="role.id" :id="role.name" v-model="form.training_role" class="mt-1 form-checkbox h-5 w-5 text-indigo-600 transition duration-150 ease-in-out" />
                        </div>
                        <div v-if="errors.roles" class="text-red-500 text-xs pt-2">{{ errors.roles }}</div>
                    </div>

                </div>

                <div class="mt-2 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">

                    <div class="sm:col-span-6 ">
                        <div class="flex">
                            <input type="checkbox" id="is_mandatory" class="rounded mr-3" v-model="form.is_mandatory" />
                            <label for="is_mandatory">This Task is Mandatory</label>
                        </div>
                        <div v-if="errors.is_mandatory" class="text-red-500 text-xs pt-2">{{ errors.is_mandatory }}</div>
                    </div>

                </div>

                <div class="sm:flex sm:items-center mt-5">
                    <div class="sm:flex-auto">
                        <a :href="route('trainings.index')" class="jw-btn border-gray-300 text-gray-400 btn-cancel">Cancel</a>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                        <button class="jw-btn jw-btn__blue" type="submit">Create Training Task</button>
                    </div>
                </div>

            </form>
        </div>
    </template>

</AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";
import TextInput from '@/Components/InputLabel.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import {
    Inertia
} from '@inertiajs/inertia';
import {
    useForm
} from '@inertiajs/inertia-vue3';

import {
    Userhelper
} from "@/Jetworks/Userhelper";

export default {
    components: {
        AuthenticatedLayout,
        BreadcrumbItem,
        TextInput,
        InputLabel,
        InputError,
    },
    data() {
        return {
            isReadOnly: true,
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
        revalidation_types: {
            type: Object,
            default: {},
        },
        validity_periods: {
            type: Object,
            default: {},
        },
        sectors: {
            type: Object,
            default: {},
        },
        training_roles: {
            type: Object,
            default: {},
        },
        task: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Task',
        },
    },

    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper
        }
    },
    setup(props) {

        import("@/Jetworks/Userhelper").then(module => {
            const Userhelper = module.Userhelper;
            Userhelper.initialise(props.$page);
        });

        const form = useForm({
            _method: 'put',
            task_number: props.task.task_number,
            title: props.task.title,
            short_name: props.task.short_name,
            external_id: props.task.external_id,
            description: props.task.description,
            revalidation_type: props.task.revalidation_type,
            validity_number: props.task.validity_number,
            validity_period: props.task.validity_period,
            sector_id: props.task.sector_id.id,
            training_role: [],
            is_mandatory: (props.task.is_mandatory === 1) ? true : false,
            status: 1,
        });

        function getCurrentDate() {
            const now = new Date();
            const year = String(now.getFullYear()).slice(-2).padStart(2, '0');
            const month = String(now.getMonth() + 1).padStart(2, '0');
            const day = String(now.getDate()).padStart(2, '0');
            return `${day}/${month}/${year}`;
        }

        function submit() {
            Inertia.post(route('tasks.update', {
                task: props.task.id
            }), form);
        }

        return {
            form,
            submit,
        }
    },
}
</script>
