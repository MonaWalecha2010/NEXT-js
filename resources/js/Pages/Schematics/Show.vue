<template>

    <Head :title="`${pageHeader} : ${schematic.name}`"/>

    <AuthenticatedLayout :can="can">

        <template v-slot:breadcrumbs>
            <BreadcrumbItem :url="route('schematics.index')" :title="pageHeader"/>
            <BreadcrumbItem :title="schematic.name"/>
        </template>

        <template v-slot:default>

            <div class="sm:flex sm:items-center">

                <div class="sm:flex-auto">
                    <h1 class="text-3xl font-semibold text-gray-900">{{ schematic.name }}</h1>
                </div>
                <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
                    <Link :href="route('schematics.create')" class="jw-btn jw-btn__blue-dark">Add Schematic</Link>
                </div>

            </div>

            <div class="p-6 mt-8 bg-white overflow-hidden shadow-sm sm:rounded-lg">

                <div class="p-6 bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <Schematic :schematic="schematic"/>
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
                        <a @click.prevent="destroy()" class="jw-btn jw-btn__red">Delete Schematic</a>
                    </div>
                    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                        <a :href="route('schematics.index')" class="jw-btn jw-btn__gray btn-cancel">Cancel</a>
                        <a :href="route('airframes.show', { 'airframe' : schematic.airframe.id })"
                           class="jw-btn jw-btn__blue">Show {{ schematic.airframe.fullname }}</a>
                    </div>
                </div>

            </div>

        </template>

    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Schematic from "@/Components/Schematic.vue";
import TextInput from "@/Components/InputLabel.vue";
import InputLabel from "@/Components/InputLabel.vue";
import InputError from "@/Components/InputError.vue";
import {QuillEditor} from "@vueup/vue-quill";
import {Inertia} from "@inertiajs/inertia";
import BreadcrumbItem from "@/Jetworks/Common/BreadcrumbItem.vue";

export default {

    components: {
        BreadcrumbItem,
        Schematic,
        AuthenticatedLayout,
        TextInput,
        InputLabel,
        InputError,
        QuillEditor
    },
    props: {
        can: {
            type: Object,
            default: {},
        },
        schematic: {
            type: Object,
            default: {},
        },
        schematic_panels: {
            type: Object,
            default: {},
        },
        errors: {
            type: Object,
            default: {},
        },
        pageHeader: {
            type: String,
            default: 'Schematics',
        }
    },
    methods: {
        destroy(event) {
            Inertia.delete(route('schematics.destroy', {schematic: this.schematic.id}));
        },
    }
}
</script>

