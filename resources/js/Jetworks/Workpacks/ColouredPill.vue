<template>
    <span
        :classes="classes"
        class="workpack-header-status-value inline-flex items-center px-3 py-1 rounded-full bg-gray-100 text-gray-700 border-gray-100">
        {{ text }}
   </span>
</template>

<script>
import {Panelhelper} from '@/Jetworks/Panelhelper.js';

export default {
    data() {

    },
    computed: {
        Panelhelper() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
    },
    props: {
        text: {
            type: Object,
            default: {},
        },
        classes: {
            type: String,
            default: null,
        },
    }
}
</script>
