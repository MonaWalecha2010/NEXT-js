<template>

    <div :id="`schematic-${schematic.id}`" v-html="schematic.svg"></div>

    <div class="my-4 text-center ">
        <a @click.prevent="validateSchematic()" class="jw-btn jw-btn__blue mb-4">Validate Schematic</a>

        <div v-if="schematicErrors.length > 0">
            <p class="text-black mb-4">The following layers were expected to be in this svg, but cannot be found.<br>This may mean that some panels cannot be highlighted or clicked.</p>
            <span class="block p-4 rounded-md border border-red-600 text-left bg-red-50">
                <span v-for="schematicError in schematicErrors" class="mr-4 jw-text__red">{{schematicError}}, </span>
            </span>
        </div>
        <div v-if="schematicValidated && (schematicErrors.length == 0)">
            <p class="text-black mb-4">Schematic appears good</p>
        </div>
    </div>

    <div class="schematic-panels mb-2 py-4 w-full">

        <h2 class="text-black text-2xl mb-6">Schematic Panels</h2>

        <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
            <li v-for="panel in schematic.panels"
                :key="schematic.id"
                class="schematic-panel flex flex-auto justify-between flex-a- p-3">
                                <span class="schematic-details">
                                    <span class="icon icon__gray inline-block w-4 h-4 mr-2">
                                        <IconSchematic/>
                                    </span>
                                    <span class="schematic-name">
                                        {{ panel.name }}
                                    </span>
                                </span>
                <span class="schematic-panel-details">
                    <a :href="`#schematic-${schematic.id}`" @click="togglePanel(panel)" class="jw-text__blue mr-4">
                        <span v-if="panel.highlighted == undefined || panel.highlighted == false">Highlight</span><span v-else>Hide</span>
                    </a>
                </span>
            </li>
        </ul>

    </div>

</template>

<script setup>
import {onMounted, ref, reactive } from 'vue';
import IconSchematic from "@/Jetworks/Icons/Schematic.vue";

const name = "Schematic";
const schematicErrors = reactive([]);
const schematicValidated = ref(false);

const props = defineProps({
    schematic: {
        type: Object,
        default: {},
    },
});

const validateSchematic = function( panels ) {
    props.schematic.panels.forEach( panel =>{
        let scannedPanel = scanPanel( panel );
        for (const layer of Object.entries(scannedPanel)) {
            if( !layer[1] ){
                schematicErrors.push( `${layer[0]}--${panel.name}` );
            }
        }
        schematicValidated.value = true;
    });
}

const togglePanel = function( panel) {
    if( panel.highlighted === undefined ){
        panel.highlighted = false;
    }
    panel.highlighted = !panel.highlighted;
    let method = (panel.highlighted) ? "add" : "remove";
    let scannedPanel = scanPanel( panel );

    for (const layer of Object.entries(scannedPanel)) {
        if( layer[1] ){
            layer[1].classList[method]('jw-svg__red','jw-animate__pulse');
        }
    }
}

const scanPanel = function( panel) {
    let scannedPanel = {};
    scannedPanel.outline = document.getElementById( `outline--${panel.name}` );
    scannedPanel.label= document.getElementById( `label--${panel.name}` );
    scannedPanel.arrow = document.getElementById( `arrow--${panel.name}` );
    return scannedPanel;
}

</script>

<style lang="scss">
.schematic-name {
    font-weight: 600;
}

svg {
    overflow: hidden;
}

#panels {

    g {
        &:hover {
            cursor: pointer;
        }

        rect,
        path {
            transform-origin: center;
        }

        &:hover rect,
        &:hover path {
            fill: grey;
        }
    }

    .border {
        fill: transparent;
        &:hover,
        &.active {
            fill-opacity: 1;
        }
    }
}
</style>
