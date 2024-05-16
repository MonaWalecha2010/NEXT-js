<template>
<div class="mb-12">
    <div class="min-h-screen">
        <nav class="sticky top-0 z-40 bg-white border-b border-gray-100">

            <!-- Primary Navigation Menu -->
            <div class="jw-header max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center h-16">
                    <div class="flex-auto">
                        <Link @click.prevent="back"><span class="inline-block w-4 h-4 mr-1">
                            <IconArrowLeft class="rotate-180" /></span>Back</Link>
                    </div>

                    <div class="flex">
                        <!-- Logo -->
                        <div class="shrink-0 mr-6">
                            <Link :href="route('dashboard')">
                            <ApplicationLogo class="block h-9 w-auto fill-current text-gray-800" />
                            </Link>
                        </div>
                        <!-- Navigation Dropdown -->
                        <div v-if="Userhelper.isTrainingAdmin() || Userhelper.isTrainingOfficer() || Userhelper.isTrainingManager()" class="flex items-center mr-2 ">
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }" d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>
                                        <DropdownLink :active="route().current('airframes.index')" :href="route('airframes.index')">
                                            Aeroplane Types
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('aeroplanes.index')" :href="route('aeroplanes.index')">
                                            Aeroplanes
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('schematics.index')" :href="route('schematics.index')">
                                            Schematics
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('workpacks.index')" :href="route('workpacks.index')">
                                            Work Packs
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('airframeworkpacks.index')" :href="route('airframeworkpacks.index')">
                                            Work Pack Templates
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('owners.index')" :href="route('owners.index')">
                                            Owners
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('users.index')" :href="route('users.index')">
                                            Users
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('trainings.index')" :href="route('trainings.index')">
                                            Trainings
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('notices.index')" :href="route('notices.index')">
                                            Notices
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <div v-else class="flex items-center mr-2 ">
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <button @click="showingNavigationDropdown = !showingNavigationDropdown" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }" d="M4 6h16M4 12h16M4 18h16" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                                <path :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }" d="M6 18L18 6M6 6l12 12" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" />
                                            </svg>
                                        </button>
                                    </template>

                                    <template #content>                                       
                                        <DropdownLink :active="route().current('trainings.index')" :href="route('trainings.index')">
                                            Trainings
                                        </DropdownLink>
                                        <DropdownLink :active="route().current('notices.index')" :href="route('notices.index')">
                                            Notices
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Profile Dropdown -->
                        <div class="flex items-center">
                            <div class="relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex pointer">
                                            <UserInitials :name="Userhelper.user.name" :id="Userhelper.user.id" />
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile</DropdownLink>
                                        <DropdownLink :href="route('logout')" as="button" method="post">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                    </div>

                </div>
            </div>

            <!-- Responsive Navigation Menu -->
            <div :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }" class="sm:hidden">

                <!-- Responsive Settings Options -->
                <div class="pt-4 pb-1 border-t border-gray-200">
                    <div class="px-4">
                        <div class="font-medium text-base text-gray-800">
                            <span class="sr-only user-name">{{ Userhelper.user.name }}</span>
                            <span class="user-initials">{{ Userhelper.user.initials }}</span>
                        </div>
                        <div class="font-medium text-sm text-gray-500">{{ Userhelper.user.email }}</div>
                    </div>

                    <div class="mt-3 space-y-1">
                        <ResponsiveNavLink :href="route('profile.edit')"> Profile</ResponsiveNavLink>
                        <ResponsiveNavLink :href="route('logout')" as="button" method="post">
                            Log Out
                        </ResponsiveNavLink>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Heading -->
        <header class="jw-header-below bg-white shadow">
            <div class="jw-header-below-container max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <slot name="header" />
            </div>
        </header>

        <!-- Breadcrumbs -->
        <header class="nav-breadcrumbs jw-breadcrumbs">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <nav class="flex" aria-label="Breadcrumb">
                    <ol role="list" class="flex items-center">
                        <li>
                            <div class="flex items-center">
                                <a :href="route('dashboard')" class="px-3 py-1.5 rounded-md bg-gray-300 text-gray-500">
                                    <span>Dashboard</span>
                                </a>
                            </div>
                        </li>
                        <slot name="breadcrumbs" />
                    </ol>
                </nav>
            </div>
        </header>

        <!-- Page Content -->
        <main class="relative z-10">
            <div class="py-1.5">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <slot />
                </div>
            </div>
        </main>
    </div>

</div>
</template>

<script>
import {
    ref
} from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Navigation from "@/Components/Navigation.vue";
import {
    Panelhelper
} from "@/Jetworks/Panelhelper";
import {
    Userhelper
} from "@/Jetworks/Userhelper";
import UserInitials from "@/Jetworks/Users/Initials.vue";
import IconArrowLeft from "@/Jetworks/Icons/ArrowLeft.vue";

const showingNavigationDropdown = ref(false);

export default {
    components: {
        IconArrowLeft,
        UserInitials,
        ApplicationLogo,
        Dropdown,
        DropdownLink,
        NavLink,
        ResponsiveNavLink,
        Navigation,
    },
    data() {
        return {
            showingNavigationDropdown: false
        }
    },
    props: {
        can: {
            type: Object,
            default: {},
        }
    },
    computed: {
        Panelhelper() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper
        }
    },
    methods: {
        back() {
            window.history.back();
        },
    }
}
</script>

<style>
.jw-header-below-container:empty {
    display: none;
}
</style>
