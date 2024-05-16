<template>

    <div v-if="users.length > 0">
        <div class="jw-users-group flex justify-center -space-x-2">

            <span v-if="textposition == 'left'">
                <span v-if="users.length >= max_displayed"
                      class="jw-users-group-number-not-displayed inline-flex pr-4 h-8 items-center justify-start">+{{
                        getNumberNotShownUsers
                    }}</span>
            </span>

            <UserInitials v-for="(user, index) in users"
                          :id="user.id"
                          :key="index"
                          :classes="this.getUserClasses( index )"
                          :name="user.name"/>

            <span v-if="textposition == 'right'">
                <span v-if="users.length >= max_displayed"
                      class="jw-users-group-number-not-displayed inline-flex pl-4 h-8 items-center justify-end">+{{
                        getNumberNotShownUsers
                    }}</span>
            </span>
        </div>
    </div>

</template>

<script>
import {Panelhelper} from '@/Jetworks/Panelhelper.js';
import UserInitials from "@/Jetworks/Users/Initials.vue";

export default {
    components: {UserInitials},
    data() {
        return {
            max_displayed: 5,
        }
    },
    props: {
        users: {
            type: Object,
            default: {},
        },
        textposition: {
            type: String,
            default: "right"
        },
        textcolor: {
            type: String,
            default: null
        },
    },
    computed: {
        Panelhelper() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
        getNumberNotShownUsers() {
            return this.users.length - this.max_displayed;
        },
    },
    methods: {
        getUserClasses(index) {
            let userClasses = "";
            if (this.max_displayed <= index) {
                userClasses = userClasses + " hidden";
            }
            return userClasses;
        }
    }
}
</script>
