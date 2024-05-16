<template>
    <span class="jw-pill" :class="statCLasses">
        <span class="inline-block mr-1">Panels Completed:</span>
        <span class="value">
            {{ stat.panels_completed }}
            <span class="divider">/</span> {{ stat.panels_total }}
        </span>
    </span>
</template>

<script>
export default {
    name: "PanelStats",
    props: {
        stat : {
            type: Object,
            default: {},
        }
    },
    computed: {
        statCLasses(){
            let classes = "bg-gray-200 text-gray-400";
            if( this.stat.panels_completed > 0 ){
                classes = "jw-text__blue bg-blue-50";
            }
            return classes;
        }
    }
}
</script>
