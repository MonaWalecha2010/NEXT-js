<template>

    <Head :title="`Create ${pageHeader}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('aeroplanes.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="`Create ${pageHeader}`"/>
        </template>

        <template v-slot:default>


            <div class="sm:flex sm:items-center">
                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">Add an {{ pageHeader }}</h1>
                </div>
            </div>

            <div class="my-6 p-5 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <form class="jw-form" @submit.prevent="submit">

                    <div class="pb-6">
                        <label for="email">Aeroplane Name( i.e. the Aircraft Registration ID )</label>
                        <div class="mt-1">
                            <input id="name" v-model="form.name" aria-invalid="true" class="" type="text">
                        </div>
                        <div v-if="errors.name" id="email-error" class="mt-2 text-sm text-red-600">{{
                                errors.name
                            }}
                        </div>
                    </div>

                    <div class="pb-6">
                        <label for="active">Status</label>
                        <select id="active" v-model="form.active" class="" name="show">
                            <option :selected="form.active == 1" value="1">Active</option>
                            <option :selected="form.active == 0" value="0">Inactive</option>
                        </select>
                    </div>

                    <div class="pb-6">

                        <div class="flex justify-between">
                            <label class="w-auto" for="airframe_id">Aeroplane Type</label>
                            <Link :href='route("airframes.index")' class="link flex-none">Edit Aeroplane Types</Link>
                        </div>

                        <select id="airframe_id" v-model="form.airframe_id" name="airframe_id">
                            <option disabled>Choose Aeroplane Type</option>
                            <option v-for="airframe in airframes" :key="airframe.id" :value="airframe.id">
                                {{ airframe.fullname }}
                            </option>
                        </select>
                        <div v-if="errors.airframe_id" class="mt-2 text-sm text-red-600">{{ errors.airframe_id }}</div>

                    </div>

                    <div class="pb-6">

                        <div class="flex justify-between">
                            <label class="w-auto" for="airframe_id">Owner</label>
                            <Link :href='route("owners.index")' class="link flex-none">Edit Owners</Link>
                        </div>
                        <select id="owner_id" v-model="form.owner_id">
                            <option disabled>Choose Owner</option>
                            <option v-for="owner in owners" :key="owner.id" :value="owner.id">
                                {{ owner.name }}
                            </option>
                        </select>
                        <div v-if="errors.owner_id" class="mt-2 text-sm text-red-600">{{ errors.owner_id }}
                        </div>
                    </div>

                    <div class="pb-6">
                        <label for="description">Description</label>
                        <QuillEditor ref="quill" v-model:content="form.description" content="html" contentType="html"
                                     theme="snow" toolbar="essential"/>
                    </div>

                    <div v-if="errors.create" class="text-red-500 text-sm pt-2">{{ errors.create }}</div>

                    <div class="flex pt-8 justify-end space-x-6">
                        <a :href="route('aeroplanes.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        <button class="jw-btn jw-btn__blue-dark" type="submit">Add to Fleet</button>
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
            'activeBool': {
                active: [1, 'active'],
                inactive: [0, 'active'],
            }
        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        airframes: {
            type: Object,
            default: {},
        },
        owners: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Aeroplane',
        }
    },

    setup(props) {

        const form = useForm({
            airframe_id: null,
            owner_id: null,
            name: null,
            active: 1,
            description: null,
        });

        function submit() {
            Inertia.post('/aeroplanes', form);
        }

        return {
            form,
            submit,
        }
    },
}
</script>
