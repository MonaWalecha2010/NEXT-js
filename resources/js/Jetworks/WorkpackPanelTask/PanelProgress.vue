<template>

    <div class="workpack-panel-steps-progress my-5 shadow-sm sm:rounded-lg bg-white">

        <nav class="" aria-label="Progress">
            <ol role="list"
                class="overflow-hidden rounded-md lg:flex">
                <template v-for="(step, index ) in Panelhelper.steps">
                    <li v-if="index > 0" class="relative overflow-hidden lg:flex-1">

                        <div class="border border-gray-200 overflow-hidden lg:border-0">
                            <!-- Current Step -->
                            <span class="px-5 py-3 flex justify-center items-center text-sm font-medium lg:pl-9"
                                  aria-current="step">
                                <span v-if="stepStatus( step ) == 'completed'"
                                      :class="`icon__${Panelhelper.getStepColor( step.id )}`"
                                      class="icon icon__tick w-8 h-8"><IconTick/></span>
                                <span v-else-if="stepStatus( step ) == 'inprogress'"
                                      :class="`icon__${Panelhelper.getStepColor( step.id )}`"
                                      class="icon icon__circle-dot w-8 h-8"><IconCircledot/></span>
                                <span v-else-if="stepStatus( step ) == 'waiting'"
                                      :class="`icon__${Panelhelper.getStepColor( step.id )}`"
                                      class="icon icon__circle w-8 h-8"><IconCircle/></span>
                                <span class="step-label inline-block ml-3.5 text-xs text-black">{{ step.name }}</span>
                            </span>

                            <!-- Separator -->
                            <div v-if="index != 1" class="absolute inset-0 top-0 left-0 hidden w-3 lg:block"
                                 aria-hidden="true">
                                <svg class="h-full w-full text-gray-300" viewBox="0 0 12 82" fill="none"
                                     preserveAspectRatio="none">
                                    <path d="M0.5 0V31L10.5 41L0.5 51V82" stroke="currentcolor"
                                          vector-effect="non-scaling-stroke"/>
                                </svg>
                            </div>

                        </div>

                    </li>
                </template>
            </ol>
        </nav>

    </div>

</template>

<script>
import {Panelhelper} from '@/Jetworks/Panelhelper.js';
import IconTick from "@/Jetworks/Icons/Tick.vue";
import IconCircledot from "@/Jetworks/Icons/Circledot.vue";
import IconCircle from "@/Jetworks/Icons/Circle.vue";

export default {
    components: {
        IconCircle,
        IconCircledot,
        IconTick
    },
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
        isPanelStarted() {
            if (this.workpack_panel.user_id == 0) {
                return false;
            }
            return true
        }
    },
    methods: {
        stepStatus(step) {
            if (step.id < this.workpack_panel.workpack_panel_step_id) {
                return "completed";
            }
            if (step.id > this.workpack_panel.workpack_panel_step_id) {
                return "waiting";
            }
            if (step.id == this.workpack_panel.workpack_panel_step_id) {
                switch (this.workpack_panel.workpack_panel_action_id) {
                    case 1 :
                        return "waiting";
                    case 2 :
                        return "inprogress";
                    case 3 :
                        return "completed";
                }
            }
            return null;
        }
    }
}
</script>


