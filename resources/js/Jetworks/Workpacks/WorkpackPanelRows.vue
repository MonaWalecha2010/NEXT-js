<template>

    <tr v-for="workpack_panel in workpack_panels"
        :key="workpack_panel.id">
        <td class="status"></td>
        <td class="title title__step font-semibold">
            <span class="-mt-1 mr-2 icon icon__l w-3 h-3 jw-text__gray">
                <IconL/>
            </span>
            <a :href="route('workpackpanels.show', workpack_panel.id)">{{ workpack_panel.panel.name }}</a>
            <span :class="`jw-schematic-pill__${Panelhelper.getStepColor(workpack_panel.workpack_panel_step_id )}`"
                class="ml-3 jw-schematic-pill">{{ Panelhelper.getStepName(workpack_panel.workpack_panel_step_id) }}</span>
        </td>
        <td class="progress"></td>
        <td class="team text-center">Me</td>
        <td class="created"></td>
        <td class="notes"></td>
        <td class="actions text-right">
            <Link :href="route('workpackpanels.show', workpack_panel.id)">
                <span class="inline-block icon icon__eye w-4 h-4"><IconPen/></span>
                <span class="sr-only">Edit</span>
            </Link>
        </td>
    </tr>


</template>

<script>
import IconPen from '@/Jetworks/Icons/Pen.vue';
import {Panelhelper} from "@/Jetworks/Panelhelper";
import UsersInitialsGroup from '@/Jetworks/Users/InitialsGroup.vue';
import StatusByUser from "@/Jetworks/Workpacks/StatusByUser.vue";
import IconEye from "@/Jetworks/Icons/Eye.vue";
import {Userhelper} from "@/Jetworks/Userhelper";
import IconL from "@/Jetworks/Icons/L.vue";

export default {
    name: "WorkpackPanelRows",
    components: {
        IconL,
        IconEye,
        IconPen,
        UsersInitialsGroup,
        StatusByUser
    },
    data() {
        return {}
    },
    props: {
        workpack_panels: {
            type: Object,
            default: {},
        }
    },
    computed: {
        Userhelper: function () {
            return Userhelper
        },
        Panelhelper: function (){
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
    }
}
</script>
