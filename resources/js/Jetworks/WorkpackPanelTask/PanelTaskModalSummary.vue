<template>

    <div class="w-full flex justify-center items-center gap-2">
        <span class="workpackpanel-task-action">{{ Panelhelper.getNextActionFromId( this.workpack_panel.workpack_panel_action_id ) }}</span>
        <span class="workpackpanel-task-step jw-pill" :class="`jw-pill__${Panelhelper.getStepColor( this.workpack_panel.workpack_panel_step_id )}`">
                        {{ Panelhelper.getNextStatusForPanel(this.workpack_panel) }}</span>
        <span class="workpackpanel-task-description">of panel</span>
        <span class="workpackpanel-task-panel-name jw-pill jw-pill__medium jw-pill__gray">{{ panelName }}</span>
    </div>

</template>
<script>
import {Panelhelper} from '@/Jetworks/Panelhelper.js';

export default {
    name: "PanelTaskModalSummary",
    props: {
        workpack_panel: {
            type: Object,
            default: {},
        }
    },
    computed: {
        Panelhelper() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },

        panelName() {
            let name = "";
            if( this.workpack_panel.name ){
                name = this.workpack_panel.name;
            }else if( this.workpack_panel.panel.name ) {
                name = this.workpack_panel.panel.name;
            }
            return name;
        }
    }
}
</script>
