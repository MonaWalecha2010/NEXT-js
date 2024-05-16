<template>

    <span v-if="Panelhelper.isWorkpackClosed(workpack)" :class="`jw-dot__green`" class="jw-dot"/>
    <span v-else-if="isWorkingOnWorkpack() && Userhelper.isSeniorEngineer()" :class="`icon__${Userhelper.user.color}`"
          class="icon icon__clipboard"><IconClipboard/></span>
    <span v-else-if="isWorkingOnWorkpack()" :class="`jw-dot__${Userhelper.user.color}`" class="jw-dot"></span>
    <span v-else :class="`jw-dot__gray`" class="jw-dot"></span>


</template>
<script>
import {Panelhelper} from "@/Jetworks/Panelhelper";
import {Userhelper} from "@/Jetworks/Userhelper";
import IconClipboard from "@/Jetworks/Icons/Clipboard.vue";

export default {
    name: "StatusByUser",
    components: {
        IconClipboard,
    },
    props: {
        workpack: {
            type: Object,
            default: {},
        },
    },
    computed: {
        Panelhelper: function() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
        Userhelper: function() {
            Userhelper.initialise(this.$page);
            return Userhelper;
        },
    },
    methods : {
        isSeniorEngineer: function() {
            return Panelhelper.user.is_cengineer;
        },
        isWorkingOnWorkpack: function() {
            return Panelhelper.isUserWorkingOnWorkpack( this.workpack.workpack_stats.users, this.Panelhelper.user );
        }
    }
}
</script>
