<template>
<div class="sm:flex justify-between sm:items-center">
    <div class="sm:flex-auto">
        <h1 class="text-3xl font-semibold text-gray-900">Training</h1>
    </div>
    <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none">
        <div class="tabs_name flex">
            
        </div>
    </div>
</div>
<div class="mt-8 flex flex-col">
    <div class="-my-2 -mx-4 overflow-x-auto sm:-mx-6 lg:-mx-8">
        <div class="inline-block min-w-full py-2 align-middle md:px-6 lg:px-8">
            <div class="overflow-hidden shadow ring-1 ring-black ring-opacity-5 md:rounded-lg">
                <div class="tabs_content">
                    <div class="tab_content" v-show="selectedTabs === 'My Training'">
                        <table class="jw-table min-w-full">
                            <thead class="bg-white">

                                <div class="rounded-full ..."></div>

                                <tr>
                                    <th scope="col">
                                        Status
                                    </th>
                                    <th scope="col">
                                        Title
                                    </th>
                                    <th scope="col">
                                        Sector
                                    </th>
                                    <th scope="col">
                                        Expires
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">

                                <tr class="border-t border-gray-300">
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        4
                                    </td>
                                </tr>
                                <tr class="border-t border-gray-300">
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        4
                                    </td>
                                </tr><tr class="border-t border-gray-300">
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        4
                                    </td>
                                </tr><tr class="border-t border-gray-300">
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        4
                                    </td>
                                </tr><tr class="border-t border-gray-300">
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        4
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="tab_content" v-show="selectedTabs === 'Awaiting Sign Off'">
                        <table class="jw-table min-w-full">
                            <thead class="bg-white">

                                <div class="rounded-full ..."></div>

                                <tr>
                                   
                                    <th scope="col">
                                        Title
                                    </th>
                                    <th scope="col">
                                        Sector
                                    </th>
                                     <th scope="col">
                                        User
                                    </th>
                                    <th scope="col">
                                        Expires
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white">

                                <tr class="border-t border-gray-300">
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        4
                                    </td>                                    
                                </tr>
                                <tr class="border-t border-gray-300">
                                    <td>
                                        1
                                    </td>
                                    <td>
                                        2
                                    </td>
                                    <td>
                                        3
                                    </td>
                                    <td>
                                        4
                                    </td>                                    
                                </tr>

                            </tbody>
                        </table>
                    </div>
                    <div class="tab_content" v-show="selectedTabs === 'Staff Training'">
                        <h1>Staff Training</h1>
                    </div>
                    <div class="tab_content" v-show="selectedTabs === 'Admin Settings'">
                        <p>Tab Content 4</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    name: 'TabsNav',
    data() {
        return {
            tabs: ['My Training', 'Awaiting Sign Off', 'Staff Training', 'Admin Settings'],
            selectedTabs: 'My Training',
        }
    },
}
</script>

<style>
.activeTab p {
    color: red;
}
</style>
