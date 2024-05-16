<template>

    <div class="mb-2 py-4 w-full">

        <h2 class="text-black text-2xl mb-6">Schematics</h2>

        <p class="mb-3">Uploaded Schematics</p>

            <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                <li v-for="schematic in schematics"
                    :key="schematic.id"
                    class="flex flex-auto justify-between flex-a- p-3">
                                <span class="schematic-details">
                                    <span class="icon icon__gray inline-block w-4 h-4 mr-2">
                                        <IconSchematic/>
                                    </span>
                                    <span class="schematic-name">
                                        {{ schematic.name }}
                                    </span>
                                </span>
                    <span class="schematic-details">
                        <span class="mr-8">{{ schematic.panels.length }} Panels</span>
                        <a @click.prevent="showSchematic(schematic)" :href="route('schematics.show', { 'schematic' : schematic.id } )" class="text-black mr-4">Show </a>
                        <a :href="route('schematics.destroy', { 'schematic' : schematic.id } )" class="jw-text__red">Delete</a>
                    </span>

                </li>
            </ul>
    </div>

    <SchematicModal v-if="schematic_for_model"
                    @closeSchematicModal="hideSchematic()"
                    :schematic="schematic_for_model"
                    ref="schematic-model"/>
</template>

<script>
import IconSchematic from "@/Jetworks/Icons/Schematic.vue";
import SchematicModal from "@/Jetworks/Schematic/Modal.vue";

export default {
    name: "Schematics",
    components: {
        SchematicModal,
        IconSchematic
    },
    data() {
        return {
            schematic_for_model: false,
        }
    },
    props: {
        schematics: {
            type: Object,
            default: null,
        },
    },
    methods: {
        showSchematic: function (schematic, event) {
            this.schematic_for_model = schematic;
        },

        hideSchematic: function () {
            this.schematic_for_model = null;
            this.panel_for_model = null;
        },
    }
}
</script>
