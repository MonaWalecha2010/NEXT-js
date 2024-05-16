<template>
    <div v-html="schematic.svg"></div>
</template>

<script>
import {Panelhelper} from "@/Jetworks/Panelhelper";

export default {
    name: "WorkpackSchematic",
    props: {
        schematic: {
            type: Object,
            default: {},
        },
        schematic_panels: {
            type: Object,
            default: {},
        }
    },
    computed: {
        Panelhelper: function () {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
    },
    methods: {

        showAddWorkpackPanelConfirmation: function (data) {
            this.$emit('showAddWorkpackPanelConfirmation', data );
        },

        showWorkpackPanelTask: function (schematic_panel) {
            this.$emit('showWorkpackPanelTask', schematic_panel);
        }
    },
    mounted() {

        let panelsContainer = document.getElementById('panels');
        panelsContainer.classList.add('jw-svg-panels');

        let panels = document.querySelectorAll('[id^="panel--"]');

        let eventPanelAddHandlingFunction = (event) => {
            let paneltarget = event.currentTarget;
            if (paneltarget.classList.contains('panel__white')) {
                this.showAddWorkpackPanelConfirmation( { name: paneltarget.dataset.panelId } );
            }
        };

        panels.forEach((panel) => {
            let panel_id = panel.id.replace('panel--', '');
            panel.dataset.panelId = panel_id;
            panel.classList.add('panel', 'panel__white');
            panel.addEventListener('click', (event) => {
                eventPanelAddHandlingFunction(event)
            });
        });

        let labels = document.querySelectorAll('[id^="label--"]');
        labels.forEach(function (label) {
            label.classList.add('label');
        });

        let arrows = document.querySelectorAll('[id^="arrow--"]');
        arrows.forEach(function (arrow) {
            arrow.classList.add('arrow');
        });

        let outlines = document.querySelectorAll('[id^="outline--"]');
        outlines.forEach(function (outline) {
            outline.classList.add('border');
        });

        //Paint panels...
        for (const schematic_panel of this.schematic_panels) {
            let panel_id = schematic_panel.name;
            let panel_color = Panelhelper.getStepColor(schematic_panel.workpack_panel_step_id);
            let svgpanel = document.getElementById(`panel--${panel_id}`);

            if (svgpanel !== null) {
                // We have to bind the schematic_panel value into this function so that it's not assessed when the click even occurs...
                let eventHandlingFunction = (event) => {
                    this.showWorkpackPanelTask(schematic_panel);
                };
                svgpanel.classList.remove(`panel__white`);
                svgpanel.classList.add(`panel__${panel_color}`);
                svgpanel.addEventListener('click', (event) => {
                    eventHandlingFunction(event);
                });

            }
        }
    },
}

</script>

<style lang="scss">
svg {
    overflow: hidden;
}
</style>
