<template>

    <div class="workpack-aeroplane-details text-gray-400 flex">

        <span class="workpack-aeroplane__wpm inline-block mr-7">
            <span class="inline-block mr-2 font-semibold">WPN:</span>
            <span>{{ workpack.name }}</span>
        </span>

        <span class="workpack-aeroplane__tailnumber flex items-center mr-7">
            <span class="inline-block w-4 h-4 icon icon__grey-dark">
                <IconTail/>
            </span>
            <span class="airplane-name after-icon">{{ workpack.aeroplane.name }}</span>
        </span>

        <span class="workpack-aeroplane__airframe flex items-center mr-7">
            <span class="inline-block w-4 h-4">
                <IconPlane/>
            </span>
            <span class="after-icon">{{ airframe.name }}</span>
        </span>

        <span v-if="airframe.revision" class="workpack-aeroplane__revision inline-block">
            <span class="font-semibold mr-2">Revision Number:</span>
            <span>{{ airframe.revision }}</span>
        </span>

    </div>

</template>

<script>
import IconPlane from "@/Jetworks/Icons/Aeroplane.vue";
import IconTail from "@/Jetworks/Icons/Tail.vue";
import IconPen from "@/Jetworks/Icons/Pen.vue";
import IconEye from "@/Jetworks/Icons/Eye.vue";

export default {
    components: {
        IconPlane,
        IconTail,
        IconPen,
        IconEye,
    },
    props: {
        airframe: {
            type: Object,
            default: {},
        },
        workpack: {
            type: Object,
            default: {},
        },
    }
}
</script>
