<template>

    <!-- Navigation Links -->
    <div v-if="$page.props.auth.user.is_admin" class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
        <NavLink :active="route().current('airframes.index')" :href="route('airframes.index')">
            Aeroplane Types
        </NavLink>
        <NavLink :active="route().current('aeroplanes.index')" :href="route('aeroplanes.index')">
            Aeroplanes
        </NavLink>
        <NavLink :active="route().current('schematics.index')" :href="route('schematics.index')">
            Schematics
        </NavLink>
        <NavLink :active="route().current('workpacks.index')" :href="route('workpacks.index')">
            Work Packs
        </NavLink>
        <NavLink :active="route().current('airframeworkpacks.index')" :href="route('airframeworkpacks.index')">
            Work Pack Templates
        </NavLink>
        <NavLink :active="route().current('owners.index')" :href="route('owners.index')">
            Owners
        </NavLink>
        <NavLink :active="route().current('users.index')" :href="route('users.index')">
            Users
        </NavLink>
    </div>

</template>

<script>
export default {
    name: "Navigation"
}
</script>

<script setup>
import NavLink from '@/Components/NavLink.vue';

defineProps({
    can: {
        type: Object,
        default: {},
    },
});
</script>
