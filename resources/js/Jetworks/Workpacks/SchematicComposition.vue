<template>
    <div v-html="schematic.svg"></div>

</template>

<script setup>
import {computed, onMounted} from 'vue';

const props = defineProps({
    schematic: {
        type: Object,
        default: {},
    },
    schematic_panels: {
        type: Object,
        default: {},
    }
});

const panels = computed(() => {
    return this.schematic_panels;
})

onMounted(() => {

    let panelsContainer = document.getElementById('panels');
    panelsContainer.classList.add('panel-detected');

    let panels = document.querySelectorAll('[id^="panel--"]');
    panels.forEach(function (panel) {
        panel.classList.add('panel')
    });

    let labels = document.querySelectorAll('[id^="label--"]');
    labels.forEach(function (label) {
        label.classList.add('label')
    });

    let arrows = document.querySelectorAll('[id^="arrow--"]');
    arrows.forEach(function (arrow) {
        arrow.classList.add('arrow')
    });

    let outlines = document.querySelectorAll('[id^="outline--"]');
    outlines.forEach(function (outline) {
        outline.classList.add('border')
    });
});

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
        &:hover,
        &.active {
            fill-opacity: 1;
        }

        &.red {
            fill: red !important;
        }

        &.blue {
            fill: blue !important;
        }

        &.green {
            fill: green !important;
        }

        &.yellow {
            fill: yellow !important;
        }

        &.brown {
            fill: brown !important;
        }

        &.black {
            fill: black !important;
        }

        &.white {
            fill: white !important;
        }
    }
}
</style>
