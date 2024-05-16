<template>

    <tr class="border-t border-gray-200 bg-gray-100">
        <th colspan="7" scope="colgroup">
            {{ title }}
        </th>
    </tr>

    <template v-for="workpack in workpacks.data"
              :key="workpack.id">
        <tr :class="getRowbackground( workpack )">
            <td class="status">
            <span class="inline-block w-4 h-4 ml-4">
                <StatusByUser :workpack="workpack"/>
            </span>
            </td>
            <td class="title font-semibold" :class="getTextColor( workpack )">
                <a :href="route('workpacks.show', workpack.id)">{{ workpack.name }}</a>
            </td>
            <td class="progress whitespace-nowrap px-3 py-4 text-sm text-center" :class="getTextColor( workpack )">
            <span class="workpack-panels-completed font-semibold">{{
                    workpack.workpack_stats.panels_completed
                }}</span>
                /
                <span class="workpack-panels-total" :class="getTextColor( workpack, 'text-gray-300' )">{{
                        workpack.workpack_stats.panels_total
                    }}</span>
            </td>
            <td class="team">
                <UsersInitialsGroup v-if="workpack.workpack_stats.users"
                                    :users="workpack.workpack_stats.users"/>
            </td>
            <td class="created" :class="getTextColor( workpack )">
                {{ workpack.date }}
            </td>
            <td class="notes" :class="getTextColor( workpack )"></td>
            <td class="actions text-right">
                <Link v-if="!Panelhelper.isWorkpackClosed( workpack  ) && Userhelper.isAdmin()"
                      :href="route('workpacks.edit', workpack.id)"
                      class="mr-4">
                    <span class="inline-block icon icon__pen w-4 h-4"><IconPen/></span>
                    <span class="sr-only">Edit</span>
                </Link>
                <Link :href="route('workpacks.show', workpack.id)">
                    <span class="inline-block icon icon__eye w-4 h-4"><IconEye/></span>
                    <span class="sr-only">View</span>
                </Link>
            </td>
        </tr>

        <WorkpackPanelRows v-if="workpack.workpack_panels" :workpack_panels="workpack.workpack_panels"/>

    </template>

</template>

<script>
import IconPen from '@/Jetworks/Icons/Pen.vue';
import {Panelhelper} from "@/Jetworks/Panelhelper";
import UsersInitialsGroup from '@/Jetworks/Users/InitialsGroup.vue';
import StatusByUser from "@/Jetworks/Workpacks/StatusByUser.vue";
import IconEye from "@/Jetworks/Icons/Eye.vue";
import {Userhelper} from "@/Jetworks/Userhelper";
import WorkpackPanelRows from "@/Jetworks/Workpacks/WorkpackPanelRows.vue";

export default {
    name: "WorkpackRows",
    components: {
        WorkpackPanelRows,
        IconEye,
        IconPen,
        UsersInitialsGroup,
        StatusByUser
    },
    data() {
        return {}
    },
    props: {
        workpacks: {
            type: Object,
            default: {},
        },
        title: {
            type: String,
            default: '',
        }
    },
    computed: {
        Userhelper: function () {
            return Userhelper
        },
        Panelhelper: function (){
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
    },
    methods : {
        getRowbackground: function( workpack ) {
            if( workpack.completed == true ){
                return "jw-bg__green-lightest";
            }
        },
        getTextColor: function( workpack, text_color = 'text-black' ) {
            if( workpack.completed == true ){
                text_color = "jw-text__green";
            }
            return text_color;
        }
    }
}
</script>
