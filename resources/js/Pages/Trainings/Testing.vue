<template>

<template v-if="searchStatus == true">
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table v-if="notifications.data.length > 0" class="jw-table min-w-full">
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
                <tr v-for="notification in notifications.data" :key="notification.id" class="border-t border-gray-300">
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
            <!-- Rendered when aeroplanes.data.length is not greater than 0 -->
            <div class="p-4 text-black">No Notifications Found</div>
        </table>
    </div>
</template>
<template v-else>
    <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
        <table v-if="allNotifications.data.length > 0" class="jw-table min-w-full">
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
                <template v-if="fullDataTable == true">
                    <tr v-for="notification in allNotifications.data" :key="notification.id" class="border-t border-gray-300">
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

                <template v-else>
                    <tr v-for="notification in allNotifications.data" :key="notification.id" class="border-t border-gray-300">
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
                </template>

            </tbody>
        </table>
        <table v-else class="jw-table min-w-full bg-white  ">
            <div class="p-4 text-black">Training Notifications Will Appear here.</div>
        </table>
    </div>
    <template v-if="fullDataTable != true">
        <div v-if="allNotifications.data.length = 10" class="flex w-full p-8 justify-center my-8">
            <button @click="viewAllNotifications()" class="jw-btn jw-btn__blue" type="button">
                View All
            </button>
        </div>
    </template>
</template>











<p>Tasks : </p>
<pre class="text-green-500">
{{tasks}}
</pre>
<p>Sectors : </p>
<pre class="text-blue-500">
{{sectors}}
</pre>
<p>Notifications : </p>
<pre class="text-red-500">
{{notifications}}
</pre>
<p>My Tasks : </p>
<pre class="text-purple-500">
{{my_tasks}}
</pre>
<p>Sign Off Taskss : </p>
<pre class="text-pink-500">
{{signoff_tasks}}
</pre>

</template>

<script>
export default {
    props: {
        tasks: {
            type: Object,
            default: {}
        },
        sectors: {
            type: Object,
            default: {}
        },
        notifications: {
            type: Object,
            default: {}
        },
        my_tasks: {
            type: Object,
            default: {}
        },
        signoff_tasks: {
            type: Object,
            default: {}
        },
    }
}
</script>
