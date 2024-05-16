<template>
<template v-if="searchStatus == true">
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table v-if="notifications.length > 0" class="jw-table min-w-full">
            <thead class="bg-white">
                <div class="rounded-full ..."></div>
                <tr>
                    <th scope="col">
                        Title
                    </th>
                    <th scope="col">
                        Active
                    </th>
                    <th scope="col">

                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <tr v-for="notification in notifications" :key="notification.id" class="border-t border-gray-300">
                    <td>
                        <strong>{{notification.title}}</strong>
                    </td>
                    <td>
                        <label class="inline-flex items-center mb-5 cursor-pointer">
                            <input type="checkbox" @click="changeStatus(notification.id,$event.target.checked)" v-model="checkboxChecked" :checked="notification.status === 1" id="active" class="sr-only peer">
                            <div class="relative w-10 h-[.875rem] bg-gray-300 peer-focus:outline-none peer-focus:ring-0 peer-focus:ring-blue-100 dark:peer-focus:ring-blue-200 rounded-full peer dark:bg-gray-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-blue-500 after:content-[''] after:absolute after:top-[-3px] after:left-0 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[1.25rem] after:w-[1.25rem] after:transition-all dark:border-gray-600 peer-checked:bg-blue-500"></div>
                        </label>
                    </td>
                    <td class="text-end">
                        <Link :href="route('notifications.edit', {'id' : notification.id } )" class="pr-2">
                        <span class="inline-block icon icon__pen w-4 h-4">
                            <IconPen />
                        </span>
                        <span class="sr-only">Edit</span>
                        </Link>
                    </td>
                </tr>
            </tbody>
        </table>
        <table v-else class="jw-table min-w-full bg-white  ">
            <div class="p-4 text-black">No Notifications Found</div>
        </table>
    </div>
</template>
<template v-else>
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table v-if="allNotifications.length > 0" class="jw-table min-w-full">
            <thead class="bg-white">

                <div class="rounded-full ..."></div>

                <tr>
                    <th scope="col">
                        Title
                    </th>
                    <th scope="col">
                        Active
                    </th>
                    <th scope="col">

                    </th>
                </tr>
            </thead>
            <tbody class="bg-white">
                <!-- <template v-if="fullDataTable == true">
                    <tr v-for="notification in allNotifications" :key="notification.id" class="border-t border-gray-300">
                        <td>
                            <strong>{{notification.title}}</strong>
                        </td>
                        <td>
                            <label class="inline-flex items-center mb-5 cursor-pointer">
                                <input type="checkbox" @click="changeStatus(notification.id,$event.target.checked)" v-model="checkboxChecked" :checked="notification.status === 1" id="active" class="sr-only peer">
                                <div class="relative w-10 h-[.875rem] bg-gray-300 peer-focus:outline-none peer-focus:ring-0 peer-focus:ring-blue-100 dark:peer-focus:ring-blue-200 rounded-full peer dark:bg-gray-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-blue-500 after:content-[''] after:absolute after:top-[-3px] after:left-0 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[1.25rem] after:w-[1.25rem] after:transition-all dark:border-gray-600 peer-checked:bg-blue-500"></div>
                            </label>

                        </td>
                        <td class="text-end">
                            <Link :href="route('notifications.edit', {'id' : notification.id } )" class="pr-2">
                            <span class="inline-block icon icon__pen w-4 h-4">
                                <IconPen /></span>
                            <span class="sr-only">Edit</span>
                            </Link>
                        </td>
                    </tr>
                </template>

                <template v-else> -->
                <tr v-for="notification in allNotifications" :key="notification.id" class="border-t border-gray-300">
                    <td>
                        <strong>{{notification.title}}</strong>
                    </td>
                    <td>
                        <label class="inline-flex items-center mb-5 cursor-pointer">
                            <input type="checkbox" @click="changeStatus(notification.id,$event.target.checked)" v-model="checkboxChecked" :checked="notification.status === 1" id="active" class="sr-only peer">
                            <div class="relative w-10 h-[.875rem] bg-gray-300 peer-focus:outline-none peer-focus:ring-0 peer-focus:ring-blue-100 dark:peer-focus:ring-blue-200 rounded-full peer dark:bg-gray-300 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-blue-500 after:content-[''] after:absolute after:top-[-3px] after:left-0 after:bg-white after:border-gray-300 after:border after:rounded-full after:h-[1.25rem] after:w-[1.25rem] after:transition-all dark:border-gray-600 peer-checked:bg-blue-500"></div>
                        </label>

                    </td>
                    <td class="text-end">
                        <Link :href="route('notifications.edit', {'id' : notification.id } )" class="pr-2">
                        <span class="inline-block icon icon__pen w-4 h-4">
                            <IconPen />
                        </span>
                        <span class="sr-only">Edit</span>
                        </Link>
                    </td>
                </tr>
                <!-- </template> -->

            </tbody>
        </table>
        <table v-else class="jw-table min-w-full bg-white  ">
            <div class="p-4 text-black">Training Notifications Will Appear here.</div>
        </table>
    </div>

    <div v-if="allNotifications.length == 10 && notifications.length > 10" class="flex w-full p-8 justify-center my-8">
        <button @click="viewAllNotifications()" class="jw-btn jw-btn__blue" type="button">
            View All
        </button>
    </div>

</template>
</template>

<script>
import IconPen from '@/Jetworks/Icons/Pen.vue';

export default {
    components: {
        IconPen,
    },

    name: 'Notifications',
    props: {
        notifications: {
            type: Object,
        },
        searchStatus: {
            type: Object,
        }
    },
    data(props) {
        return {
            fullDataTable: false,
            allNotifications: props.notifications.slice(0, this.iter),

        }
    },
    setup(props) {
        let iter = 10;
        return {
            iter,
        }
    },
    methods: {
        changeStatus(notificationId, isChecked) {

            axios.post(route('notifications.updateStatus', {
                    'notificationId': notificationId,
                    'status': isChecked
                }))
                .then((response) => {
                    if (response.data.success === true) {
                        console.log(response.data.users);
                        console.log(this.users.data);
                    }
                }).catch(error => {
                    console.log(error);
                }); // Inertia.post(route('notifications.updateStatus'), formData);
        },
        viewAllNotifications() {
            this.iter = this.notifications.length;
            this.allNotifications = this.notifications.slice(0, this.iter);

            // axios.get(route('notifications.viewAllData'))
            //     .then((response) => {
            //         if (response.data.success === true) {
            //             this.fullDataTable = true;
            //             this.allNotifications = response.data.data;
            //         }
            //     }).catch(error => {
            //         console.log(error);
            //     });
        }
    }
}
</script>
