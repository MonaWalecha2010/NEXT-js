<template>

    <div class="workpack-schematic-key flex justify-start space-x-4 my-6">
        <span class="jw-schematic-pill jw-schematic-pill__gray">Pending Removal</span>
        <span class="jw-schematic-pill jw-schematic-pill__red">Removal</span>
        <span class="jw-schematic-pill jw-schematic-pill__orange">Inspection <span
            class="sr-only">( Removal )</span></span>
        <span class="jw-schematic-pill jw-schematic-pill__cyan">Installation</span>
        <span class="jw-schematic-pill jw-schematic-pill__blue">Inspection <span
            class="sr-only">( Removal )</span></span>
        <span class="jw-schematic-pill jw-schematic-pill__green">Sealed</span>
        <span class="jw-schematic-pill bg-white text-black border border-gray-200">No Work Required</span>
    </div>

</template>

<script>
export default {
    name: "WorkpackSchematicKey",
}
</script>
