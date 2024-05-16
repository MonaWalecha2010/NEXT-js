<template>
    <span v-if="name.length > 0"
          :class="user_classes"
          :data-user-id="id"
          class="jw-users-group-user">
        <span class="text-xs font-medium leading-none text-white">{{ Panelhelper.getInitials(name) }}</span>
        <span class="sr-only user-name">{{ name }}</span>

    </span>
</template>
<script>
import {Panelhelper} from '@/Jetworks/Panelhelper.js';

export default {
    name: "UserInitials",
    props: {
        name: {
            type: String,
            default: '',
        },
        id: {
            type: Number,
            default: null,
        },
        classes: {
            type: String,
            default: '',
        }
    },
    computed: {
        Panelhelper() {
            Panelhelper.initialise(this.$page);
            return Panelhelper;
        },
        user_classes() {
            return `jw-users-group-user__${Panelhelper.getWorkerColorById(this.id)} ${this.classes}`;
        }
    }
}
</script>
