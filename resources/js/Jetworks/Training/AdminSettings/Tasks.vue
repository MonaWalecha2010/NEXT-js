<template>
<template v-if="searchStatus == true">
    <div class=" overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table v-if="tasks.data.length > 0" class="jw-table min-w-full">
            <thead class="bg-white">

                <div class="rounded-full ..."></div>

                <tr>
                    <th scope="col">
                        Task
                    </th>
                    <th scope="col">
                        Title
                    </th>
                    <th scope="col">
                        Roles
                    </th>
                    <th scope="col">
                        Validity
                    </th>
                    <th scope="col">

                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">

                <tr v-for="task in tasks.data" :key="task.id" class="border-t border-gray-300">
                    <td>
                        {{task.task_number}}
                    </td>
                    <td>
                        <strong>{{task.title}}</strong>
                    </td>
                    <td>
                        <span class="text-gray-500"><strong>{{roleslist(task.roles)}}</strong></span>
                    </td>
                    <td>
                        <strong>{{task.validity_number}} {{task.validity_period}}</strong>
                    </td>
                    <td class="text-end">
                        <Link :href="route('tasks.edit', {'id' : task.id })" class="pr-2">
                        <span class="inline-block icon icon__pen w-4 h-4">
                            <IconPen /></span>
                        <span class="sr-only">Edit</span>
                        </Link>
                    </td>

                </tr>

            </tbody>
        </table>
        <table v-else class="jw-table min-w-full bg-white  ">
            <!-- Rendered when aeroplanes.data.length is not greater than 0 -->
            <div class="p-4 text-black">No Tasks Found.</div>
        </table>
    </div>
</template>
<template v-else>
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table v-if="allTasks.data.length > 0" class="jw-table min-w-full">
            <thead class="bg-white">

                <div class="rounded-full ..."></div>

                <tr>
                    <th scope="col">
                        Task
                    </th>
                    <th scope="col">
                        Title
                    </th>
                    <th scope="col">
                        Roles
                    </th>
                    <th scope="col">
                        Validity
                    </th>
                    <th scope="col">
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <template v-if="fullDataTable == true">
                    <tr v-for="task in allTasks.data" :key="task.id" class="border-t border-gray-300">
                        <td>
                            {{task.task_number}}
                        </td>
                        <td>
                            <strong>{{task.title}}</strong>
                        </td>
                        <td>
                            <span class="text-gray-500"><strong>{{roleslist(task.roles)}}</strong></span>
                        </td>
                        <td>
                            <strong>{{task.validity_number}} {{task.validity_period}}</strong>
                        </td>
                        <td class="text-end">
                            <Link :href="route('tasks.edit', {'id' : task.id })" class="pr-2">
                            <span class="inline-block icon icon__pen w-4 h-4">
                                <IconPen /></span>
                            <span class="sr-only">Edit</span>
                            </Link>
                        </td>

                    </tr>
                </template>

                <template v-else>
                    <tr v-for="task in allTasks.data" :key="task.id" class="border-t border-gray-300">
                        <td>
                            {{task.task_number}}
                        </td>
                        <td>
                            <strong>{{task.title}}</strong>
                        </td>
                        <td>
                            <span class="text-gray-500"><strong>{{roleslist(task.roles)}}</strong></span>
                        </td>
                        <td>
                            <strong>{{task.validity_number}} {{task.validity_period}}</strong>
                        </td>
                        <td class="text-end">
                            <Link :href="route('tasks.edit', {'id' : task.id })" class="pr-2">
                            <span class="inline-block icon icon__pen w-4 h-4">
                                <IconPen /></span>
                            <span class="sr-only">Edit</span>
                            </Link>
                        </td>

                    </tr>
                </template>

            </tbody>
        </table>
        <table v-else class="jw-table min-w-full bg-white  ">
            <!-- Rendered when aeroplanes.data.length is not greater than 0 -->
            <div class="p-4 text-black">Training Tasks Will Appear here.</div>
        </table>
    </div>

    <template v-if="fullDataTable != true">
        <div v-if="allTasks.data.length == 10" class="flex w-full justify-center my-9">
            <button @click="viewAllTasks()" class="jw-btn jw-btn__blue" type="button">
                View All
            </button>
        </div>
    </template>
</template>
</template>

<script>
import IconPen from '@/Jetworks/Icons/Pen.vue';

export default {
    components: {
        IconPen,
    },
    name: 'Tasks',
    props: {
        tasks: {
            type: Object,
        },
        searchStatus: {
            type: Object,
        }
    },
    data(props) {
        return {
            fullDataTable: false,
            allTasks: props.tasks
        }
    },
    computed: {
        Userhelper() {
            Userhelper.initialise(this.$page);
            return Userhelper
        }
    },
    methods: {
        roleslist(roles) {
            let role_array = [];
            for (let index = 0; index < roles.length; index++) {
                role_array.push(roles[index].name);
            }

            const maxLength = 40;
            let roleString = role_array.join();
            if (roleString.length >= maxLength) {
                roleString = roleString.slice(0, maxLength - 3) + '...';
            }

            return roleString;
        },
        viewAllTasks() {
            axios.get(route('tasks.viewAllData'))
                .then((response) => {
                    if (response.data.success === true) {
                        this.fullDataTable = true;
                        this.allTasks = response.data.data;
                    }
                }).catch(error => {
                    console.log(error);
                });
        }
    }
}
</script>
