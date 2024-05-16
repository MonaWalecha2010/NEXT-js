<template>
<div class="border border-gray-300 rounded-md overflow-hidden">
    <div class="bg-gray-100 py-6 px-3 border-b border-gray-300">
        <div class="flex items-baseline mb-3">
            <div class=" inline-flex items-center min-w-44 ">
                <span class="inline-block text-base font-semibold text-gray-900 mr-4">Search</span>
                <span class="inline-block text-sm font-medium text-gray-500 mr-3">Active Filters:</span>
            </div>
            <div class="flex items-center space-x-3 flex-wrap">

                <template v-if="filterRoles" v-show="filterRoles.length > 0">
                    <span v-for="role in this.selected_roles" @click.prevent="removeRole( role )" class="          justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">
                        <span class="icon icon__white w-3 h-3">
                        </span>
                        <span class="ml-1">{{role.name}}</span>
                        <span class="ml-4 cursor-pointer">
                            <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.53494 3.99944L7.07994 1.45444C7.14516 1.40148 7.19853 1.33542 7.2366 1.26052C7.27466 1.18562 7.29658 1.10357 7.30091 1.01966C7.30525 0.935762 7.29193 0.851884 7.26179 0.77346C7.23166 0.695036 7.18539 0.623815 7.12598 0.564408C7.06657 0.505001 6.99535 0.458732 6.91693 0.428597C6.8385 0.398463 6.75463 0.385133 6.67072 0.389473C6.58682 0.393813 6.50477 0.415724 6.42987 0.45379C6.35497 0.491855 6.2889 0.545225 6.23594 0.610444L3.69194 3.15644L1.14194 0.610444C1.08898 0.545225 1.02292 0.491855 0.948019 0.45379C0.873123 0.415724 0.791067 0.393813 0.707165 0.389473C0.623262 0.385133 0.539383 0.398463 0.460959 0.428597C0.382535 0.458732 0.311315 0.505001 0.251907 0.564408C0.1925 0.623815 0.146231 0.695036 0.116096 0.77346C0.0859617 0.851884 0.0726332 0.935762 0.0769729 1.01966C0.0813127 1.10357 0.103225 1.18562 0.14129 1.26052C0.179355 1.33542 0.232725 1.40148 0.297944 1.45444L2.84294 3.99944L0.302944 6.54844C0.237725 6.6014 0.184354 6.66747 0.146289 6.74237C0.108224 6.81727 0.0863128 6.89932 0.0819731 6.98322C0.0776333 7.06713 0.0909618 7.151 0.121097 7.22943C0.151231 7.30785 0.1975 7.37907 0.256907 7.43848C0.316315 7.49789 0.387536 7.54416 0.46596 7.57429C0.544383 7.60442 0.628263 7.61776 0.712165 7.61342C0.796067 7.60908 0.878123 7.58716 0.953019 7.5491C1.02792 7.51103 1.09398 7.45766 1.14694 7.39244L3.69194 4.84744L6.24194 7.38844C6.2949 7.45366 6.36097 7.50703 6.43587 7.5451C6.51077 7.58316 6.59282 7.60508 6.67672 7.60942C6.76063 7.61376 6.8445 7.60043 6.92293 7.57029C7.00135 7.54016 7.07257 7.49389 7.13198 7.43448C7.19139 7.37507 7.23766 7.30385 7.26779 7.22543C7.29792 7.147 7.31125 7.06313 7.30692 6.97922C7.30258 6.89532 7.28066 6.81327 7.2426 6.73837C7.20453 6.66347 7.15116 6.5974 7.08594 6.54444L4.53494 3.99944Z" fill="white" />
                            </svg>
                        </span>
                    </span>
                </template>

                <template v-if="filterSectors" v-show="filterSectors.length > 0">
                    <span v-for="sector in this.selected_sectors" @click.prevent="removeSector( sector )" class="my-1 justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">
                        <span class="icon icon__white w-3 h-3">
                        </span>
                        <span class="ml-1">{{sector.title}}</span>
                        <span class="ml-4 cursor-pointer">
                            <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.53494 3.99944L7.07994 1.45444C7.14516 1.40148 7.19853 1.33542 7.2366 1.26052C7.27466 1.18562 7.29658 1.10357 7.30091 1.01966C7.30525 0.935762 7.29193 0.851884 7.26179 0.77346C7.23166 0.695036 7.18539 0.623815 7.12598 0.564408C7.06657 0.505001 6.99535 0.458732 6.91693 0.428597C6.8385 0.398463 6.75463 0.385133 6.67072 0.389473C6.58682 0.393813 6.50477 0.415724 6.42987 0.45379C6.35497 0.491855 6.2889 0.545225 6.23594 0.610444L3.69194 3.15644L1.14194 0.610444C1.08898 0.545225 1.02292 0.491855 0.948019 0.45379C0.873123 0.415724 0.791067 0.393813 0.707165 0.389473C0.623262 0.385133 0.539383 0.398463 0.460959 0.428597C0.382535 0.458732 0.311315 0.505001 0.251907 0.564408C0.1925 0.623815 0.146231 0.695036 0.116096 0.77346C0.0859617 0.851884 0.0726332 0.935762 0.0769729 1.01966C0.0813127 1.10357 0.103225 1.18562 0.14129 1.26052C0.179355 1.33542 0.232725 1.40148 0.297944 1.45444L2.84294 3.99944L0.302944 6.54844C0.237725 6.6014 0.184354 6.66747 0.146289 6.74237C0.108224 6.81727 0.0863128 6.89932 0.0819731 6.98322C0.0776333 7.06713 0.0909618 7.151 0.121097 7.22943C0.151231 7.30785 0.1975 7.37907 0.256907 7.43848C0.316315 7.49789 0.387536 7.54416 0.46596 7.57429C0.544383 7.60442 0.628263 7.61776 0.712165 7.61342C0.796067 7.60908 0.878123 7.58716 0.953019 7.5491C1.02792 7.51103 1.09398 7.45766 1.14694 7.39244L3.69194 4.84744L6.24194 7.38844C6.2949 7.45366 6.36097 7.50703 6.43587 7.5451C6.51077 7.58316 6.59282 7.60508 6.67672 7.60942C6.76063 7.61376 6.8445 7.60043 6.92293 7.57029C7.00135 7.54016 7.07257 7.49389 7.13198 7.43448C7.19139 7.37507 7.23766 7.30385 7.26779 7.22543C7.29792 7.147 7.31125 7.06313 7.30692 6.97922C7.30258 6.89532 7.28066 6.81327 7.2426 6.73837C7.20453 6.66347 7.15116 6.5974 7.08594 6.54444L4.53494 3.99944Z" fill="white" />
                            </svg>
                        </span>
                    </span>
                </template>

                <template v-if="filterStatuses" v-show="filterStatuses.length > 0">
                    <span v-for="status in this.selected_statuses" @click.prevent="removeStatus( status )" class="          justify-center jw-pill jw-bg__blue relative text-white text-sm text-xs">
                        <span class="icon icon__white w-3 h-3">
                        </span>
                        <span class="ml-1">{{status}}</span>
                        <span class="ml-4 cursor-pointer">
                            <svg width="9" height="9" viewBox="0 0 9 9" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M4.53494 3.99944L7.07994 1.45444C7.14516 1.40148 7.19853 1.33542 7.2366 1.26052C7.27466 1.18562 7.29658 1.10357 7.30091 1.01966C7.30525 0.935762 7.29193 0.851884 7.26179 0.77346C7.23166 0.695036 7.18539 0.623815 7.12598 0.564408C7.06657 0.505001 6.99535 0.458732 6.91693 0.428597C6.8385 0.398463 6.75463 0.385133 6.67072 0.389473C6.58682 0.393813 6.50477 0.415724 6.42987 0.45379C6.35497 0.491855 6.2889 0.545225 6.23594 0.610444L3.69194 3.15644L1.14194 0.610444C1.08898 0.545225 1.02292 0.491855 0.948019 0.45379C0.873123 0.415724 0.791067 0.393813 0.707165 0.389473C0.623262 0.385133 0.539383 0.398463 0.460959 0.428597C0.382535 0.458732 0.311315 0.505001 0.251907 0.564408C0.1925 0.623815 0.146231 0.695036 0.116096 0.77346C0.0859617 0.851884 0.0726332 0.935762 0.0769729 1.01966C0.0813127 1.10357 0.103225 1.18562 0.14129 1.26052C0.179355 1.33542 0.232725 1.40148 0.297944 1.45444L2.84294 3.99944L0.302944 6.54844C0.237725 6.6014 0.184354 6.66747 0.146289 6.74237C0.108224 6.81727 0.0863128 6.89932 0.0819731 6.98322C0.0776333 7.06713 0.0909618 7.151 0.121097 7.22943C0.151231 7.30785 0.1975 7.37907 0.256907 7.43848C0.316315 7.49789 0.387536 7.54416 0.46596 7.57429C0.544383 7.60442 0.628263 7.61776 0.712165 7.61342C0.796067 7.60908 0.878123 7.58716 0.953019 7.5491C1.02792 7.51103 1.09398 7.45766 1.14694 7.39244L3.69194 4.84744L6.24194 7.38844C6.2949 7.45366 6.36097 7.50703 6.43587 7.5451C6.51077 7.58316 6.59282 7.60508 6.67672 7.60942C6.76063 7.61376 6.8445 7.60043 6.92293 7.57029C7.00135 7.54016 7.07257 7.49389 7.13198 7.43448C7.19139 7.37507 7.23766 7.30385 7.26779 7.22543C7.29792 7.147 7.31125 7.06313 7.30692 6.97922C7.30258 6.89532 7.28066 6.81327 7.2426 6.73837C7.20453 6.66347 7.15116 6.5974 7.08594 6.54444L4.53494 3.99944Z" fill="white" />
                            </svg>
                        </span>
                    </span>
                </template>

            </div>
        </div>
        <div class="grid grid-cols-5 gap-x-3 ">
            <div class="col-span-1 border bg-white border-gray-300 rounded-md py-1.5 px-3 flex items-center justify-between" @click="openSelectRolePopup()">
                <span class="text-gray-900 font-medium text-sm">Roles:</span>
                <span>
                    <svg width="8" height="16" viewBox="0 0 8 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M6.66663 4L3.66663 1L0.666626 4" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M0.666626 12L3.66663 15L6.66663 12" stroke="#9CA3AF" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </div>
            <div v-if="selectRolePopup === true" aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
                <div class="fixed inset-0 z-10 overflow-y-auto">
                    <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
                        <div class="relative transform overflow-hidden rounded-lg bg-white transition-all sm:my-8 sm:w-full sm:max-w-lg">
                            <div class="p-5 flex items-center justify-between border-b border-gray-300">
                                <h3 class="inline-block text-start text-2xl text-gray-900 font-semibold">Choose Roles</h3>
                                <span class="inline-block ml-auto cursor-pointer" @click="closeSelectRolePopup()">
                                    <svg width="36" height="36" viewBox="0 0 36 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M18 18L10.5 10.5M18 18L25.5 25.5M18 18L25.5 10.5M18 18L10.5 25.5" stroke="#9CA3AF" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </span>
                            </div>
                            <div class="pl-0 ">
                                <label v-for="role in training_roles.data" :key="role.id" :for="'role'+role.id" class="flex items-center checked:text-blue-300 text-gray-900 text-lg font-medium p-5 checked:bg-blue-50 border-b border-gray-300 cursor-pointer">
                                    <input :name="role.id" v-model="filterRoles" type="checkbox" :id="'role'+role.id" :value="role.id" class="form-checkbox checked:bg-blue-300 border-gray-300 rounded-sm focus:outline-0 " />
                                    <span class="inline-block ml-3">{{ role.name }}</span>
                                </label>
                            </div>
                            <div class="bg-gray-lightest w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:px-6">
                                <button @click="closeSelectRolePopup()" class="w-full jw-btn border-gray-200 text-gray-500 bg-white" type="button">
                                    cancel
                                </button>
                                <button type="button" class="w-full jw-btn jw-btn__blue" @click="selectedRole()">Select</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- <select @change="selectedRole" v-model="filterRole" class="block w-full border border-gray-300 rounded">                    
                <option v-for="role in training_roles.data" :key="role.id" :value="role.id">
                    {{ role.name }}
                </option>
            </select> -->
            <div class="col-span-1 ">
                <select name="sector" id="sector" @change="selectedSector" class="custom-dropdown w-full border text-gray-400 font-medium border-gray-300 rounded-md text-sm" :data-te-select-icon="WarningIcon">
                    <option value="" :selected="defaultSelected" id="sector1">Sector</option>
                    <option v-for="sector in sectors" :key="sector.id" :value="sector.id" :id="sector.id">
                        {{ sector.title }}
                    </option>
                </select>

            </div>
            <div class="col-span-1">
                <select name="status" id="status" @change="selectedStatus" class="custom-dropdown w-full border text-gray-900 font-medium border-gray-300 rounded-md text-sm" :data-te-select-icon="WarningIcon">
                    <option disabled value="" :selected="defaultSelected" id="status1">Status</option>
                    <option v-for="(status,index) in statuses" :key="index" :value="status" :id="status">
                        {{ status }}
                    </option>
                </select>
            </div>
            <div class="col-span-2">
                <input type="text" v-model="searchUserInput" class="w-full border border-gray-300 rounded-md font-medium text-sm placeholder:text-sm" @input="searchUser()" placeholder="Search...">
            </div>
        </div>
    </div>
    <template v-if=" allUsers.data.length > 0">
        <div class="flex">
            <div class="w-1/4 bg-gray-100  border-r border-gray-300">
                <ul class="bg-white text-gray-300 w-full">
                    <li v-for="(user, index) in allUsers.data" :key="index" @click="selectItem(user)" :class="{ active: activeElement === user.id }" class="cursor-pointer py-6 px-3 text-sm text-gray-500 border-b border-gray-300 font-semibold">{{ user.fname }} {{ user.lname }}</li>
                </ul>
                <div class="text-center py-6 px-3 w-full">
                    <span @click="clearFilters()" class="text-blue-500 text-sm font-medium underline cursor-pointer hover:text-blue-300 ">Clear Filters</span>
                </div>
            </div>
            <!-- Details -->
            <div class="w-3/4 bg-gray-100">
                <div v-if="UserData !== null">
                    <div class="bg-white mb-3 py-7 px-6 shadow-b shadow-lg">
                        <h1 class="text-3xl font-semibold text-gray-900 mb-2.5">{{ UserData.lname +', '+UserData.fname}}</h1>
                        <p class="text-sm text-gray-500 mb-2.5"><span class="font-semibold mr-2 ">Licence No: </span>{{ UserData.license_number}}</p>
                        <p class="text-sm text-gray-500 mb-2.5"><span class="font-semibold mr-2 ">Role: </span>{{ UserData.training_role.name }}</p>
                    </div>
                    <div class="px-6 py-3">
                        <p class="text-sm text-gray-500 font-medium mb-3">Action Required</p>
                        <div v-for="task in UserData.training_role.tasks">
                            <template v-if="task.task_status.length == 0 ">

                                <div class="bg-white mb-3 rounded-md p-4 border border-gray-300">
                                    <div class="sm:flex sm:items-center justify-between space-x-3 items-center mb-2">
                                        <h1 class="text-sm text-gray-500">{{task.sector.title}}</h1>
                                        <RoundedTextPill actionStatus="required" actionValue="Action Required" />
                                    </div>
                                    <div class="flex items-center gap-x-5 mb-2">
                                        <span class="text-sm text-gray-900 font-semibold pb-1">{{task.title}}</span>
                                        <span class="flex gap-x-1">
                                            <span v-if="task.revalidation_type == 0">
                                                <DocumentIcon svgFill="#f79797" />
                                            </span>
                                            <span v-else>
                                                <ListIcon svgFill="#f79797" />
                                            </span>
                                            <span v-if="task.is_mandatory">
                                                <WarningIcon svgFill="#ef4444" />
                                            </span>
                                        </span>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        <span class=" font-semibold">Expires: </span>
                                        <span>--/--/--</span>
                                    </div>
                                </div>
                            </template>
                        </div>
                        <div v-for="task in UserData.training_role.tasks">
                            <template v-if="task.task_status.length > 0">

                                <template v-for="my_task in task.task_status">
                                    <template v-if="my_task.user_id == UserData.id">
                                        <template v-if="my_task.status == 0">
                                            <!-- <a :href="route('trainings.viewStaffTask', { 'taskRequest_id' : my_task.id } )"> -->
                                            <div class="bg-white mb-3 rounded-md p-4 border border-gray-300">
                                                <div class="sm:flex sm:items-center justify-between space-x-3 items-center mb-2">
                                                    <h1 class="text-sm text-gray-500">{{task.sector.title}}</h1>
                                                    <RoundedTextPill actionStatus="required" actionValue="Action Required" />
                                                </div>
                                                <div class="flex items-center gap-x-5 mb-2">
                                                    <span class="text-sm text-gray-900 font-semibold pb-1">{{task.title}}</span>
                                                    <span class="flex gap-x-1">
                                                        <span v-if="task.revalidation_type == 0">
                                                            <DocumentIcon svgFill="#f79797" />
                                                        </span>
                                                        <span v-else>
                                                            <ListIcon svgFill="#f79797" />
                                                        </span>
                                                        <span v-if="task.is_mandatory">
                                                            <WarningIcon svgFill="#ef4444" />
                                                        </span>
                                                    </span>
                                                </div>
                                                <div v-if="my_task.valid_upto!='undefind'" class="text-sm text-gray-500">
                                                    <span class=" font-semibold">Expires: </span>
                                                    <span>{{formatExpiry(my_task.valid_upto)}}</span>
                                                </div>
                                            </div>
                                            <!-- </a> -->
                                        </template>
                                    </template>
                                </template>
                            </template>
                        </div>
                        <p class="text-sm text-gray-500 font-medium mb-3 mt-4">Pending</p>
                        <div v-for="task in UserData.training_role.tasks">
                            <template v-if="task.task_status.length > 0">
                                <template v-for="my_task in task.task_status">
                                    <template v-if="my_task.user_id == UserData.id && my_task.request_status == 'Pending'">
                                        <!-- <a :href="route('trainings.viewStaffTask', { 'taskRequest_id' : my_task.id } )"> -->
                                            <div class="bg-white mb-3 rounded-md p-4 border border-gray-300">
                                                <div class="sm:flex sm:items-center justify-between space-x-3 items-center mb-2">
                                                    <h1 class="text-sm text-gray-500">{{task.sector.title}}</h1>
                                                    <RoundedTextPill actionStatus="pending" actionValue="Pending" />
                                                </div>
                                                <div class="flex items-center gap-x-5 mb-2">
                                                    <span class="text-sm text-gray-900 font-semibold pb-1">{{task.title}}</span>
                                                    <span class="flex gap-x-1">
                                                        <span v-if="task.revalidation_type == 0">
                                                            <DocumentIcon svgFill="#98D3FA" />
                                                        </span>
                                                        <span v-else>
                                                            <ListIcon svgFill="#98D3FA" />
                                                        </span>
                                                        <span v-if="task.is_mandatory">
                                                            <WarningIcon svgFill="#60a5fa" />
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <span class=" font-semibold">Expires: </span>
                                                    <span>{{formatExpiry(my_task.valid_upto)}}</span>
                                                </div>
                                            </div>
                                        <!-- </a> -->
                                    </template>
                                </template>
                            </template>
                        </div>

                        <p class="text-sm text-gray-500 font-medium mb-3 mt-4">Upcomming</p>
                        <div v-for="task in UserData.training_role.tasks">
                            <template v-if="task.task_status.length > 0">
                                <template v-for="my_task in task.task_status">
                                    <template v-if="my_task.user_id == UserData.id">
                                        <template v-if="my_task.status == 1 && my_task.display_status == 'Upcoming'">
                                            <div class="bg-white mb-3 rounded-md p-4 border border-gray-300">
                                                <div class="sm:flex sm:items-center justify-between space-x-3 items-center mb-2">
                                                    <h1 class="text-sm text-gray-500">{{task.sector.title}}</h1>
                                                    <RoundedTextPill actionStatus="upcoming" actionValue="Upcomming" />
                                                </div>
                                                <div class="flex items-center gap-x-5 mb-2">
                                                    <span class="text-sm text-gray-900 font-semibold pb-1">{{task.title}}</span>
                                                    <span class="flex gap-x-1">
                                                        <span v-if="task.revalidation_type == 0">
                                                            <DocumentIcon svgFill="#d1d5db" />
                                                        </span>
                                                        <span v-else>
                                                            <ListIcon svgFill="#d1d5db" />
                                                        </span>
                                                        <span v-if="task.is_mandatory">
                                                            <WarningIcon svgFill="#d1d5db" />
                                                        </span>
                                                    </span>
                                                </div>
                                                <div class="text-sm text-gray-500">
                                                    <span class=" font-semibold">Expires: </span>
                                                    <span>{{formatExpiry(my_task.valid_upto)}}</span>
                                                </div>
                                            </div>
                                        </template>
                                    </template>
                                </template>
                            </template>
                        </div>

                        <p class="text-sm text-gray-500 font-medium mb-3 mt-4">Active</p>
                        <div v-for="task in UserData.training_role.tasks">
                            <template v-if="task.task_status.length > 0">
                                <template v-for="my_task in task.task_status">
                                    <template v-if="my_task.user_id == UserData.id && my_task.status == 1 && my_task.display_status == 'Active'">
                                        <div class="bg-white mb-3 rounded-md p-4 border border-gray-300">
                                            <div class="sm:flex sm:items-center justify-between space-x-3 items-center mb-2">
                                                <h1 class="text-sm text-gray-500">{{task.sector.title}}</h1>
                                                <RoundedTextPill actionStatus="active" actionValue="Active" />
                                            </div>
                                            <div class="flex items-center gap-x-5 mb-2">
                                                <span class="text-sm text-gray-900 font-semibold pb-1">{{task.title}}</span>
                                                <span class="flex gap-x-1">
                                                    <span v-if="task.revalidation_type == 0">
                                                        <DocumentIcon svgFill="#d1d5db" />
                                                    </span>
                                                    <span v-else>
                                                        <ListIcon svgFill="#d1d5db" />
                                                    </span>
                                                    <span v-if="task.is_mandatory">
                                                        <WarningIcon svgFill="#d1d5db" />
                                                    </span>
                                                </span>
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                <span class=" font-semibold">Expires: </span>
                                                <span>{{formatExpiry(my_task.valid_upto)}}</span>
                                            </div>
                                        </div>

                                    </template>
                                </template>
                            </template>
                        </div>
                    </div>
                </div>
                <div v-else class="py-7 px-6 text-center">
                    <p>No item selected</p>
                </div>
                <!-- </div> -->
                <!-- </div> -->
            </div>
        </div>
    </template>
    <template v-else>
        <div class="bg-white  border-r border-gray-300 py-6 text-center">
            <h1>No Records Found</h1>
        </div>

    </template>

</div>

<!-- <pre>
{{users.data}}
</pre> -->
</template>

<script>
import DocumentIcon from '@/Jetworks/Icons/DocumentIcon.vue';
import ListIcon from '@/Jetworks/Icons/ListIcon.vue';
import WarningIcon from '@/Jetworks/Icons/WarningIcon.vue';
import RoundedTextPill from '@/Components/RoundedTextPill.vue';

export default {
    name: 'StaffTraining',
    components: {
        DocumentIcon,
        ListIcon,
        WarningIcon,
        RoundedTextPill
    },
    props: {
        users: {
            type: Object,
            default: {},
        },
        sectors: {
            type: Object,
            default: {},
        },
        training_roles: {
            type: Object,
            default: {},
        },
    },
    data(props) {
        return {
            // sidebarItems: ['Item 1', 'Item 2', 'Item 3', 'Item 4'], // Example sidebar items
            UserData: null, // Initially no item is selected
            selectedItem: '',
            filterRoles: [],
            filterSectors: [],
            filterStatuses: [],
            selectRolePopup: false,
            defaultSelected: true,
            allUsers: props.users,
            searchUserInput: '',
            activeElement: null,
            statuses: ['Active', 'Upcoming', 'Pending', 'Action Required'],

        }
    },
    setup(props) {
        let selected_sectors = [];
        let selected_roles = [];
        let selected_statuses = [];

        // let checkactiveElement = props.users.data[0];

        return {
            selected_sectors,
            selected_roles,
            selected_statuses,
            // checkactiveElement,
        }

    },
    methods: {
        selectItem: function (userData) {
            this.UserData = userData;
            this.activeElement = userData.id;

        },
        defaultSelectedInnerItem: function () {
            this.activeElement = this.allUsers.data[0].id;
            this.UserData = this.allUsers.data[0];
        },
        formatExpiry: function (date) {
            if (date == null) {
                const formattedDate = '--/--/--';
                return formattedDate
            } else {
                const parts = date.split('-');
                const formattedDate = `${parts[2]}/${parts[1]}/${parts[0]}`;
                return formattedDate
            }

        },
        defaultSelectedItem() {
            // Return the first item if none is selected
            return this.selectedItem || (this.users.length > 0 ? this.users[0] : '');
        },

        searchUser: function () {

            const filterParameters = {
                roles: this.filterRoles,
                sectors: this.filterSectors,
                status: this.filterStatuses,
                user: this.searchUserInput
            };

            axios.post(route('trainings.userSearch'), filterParameters)
                .then((response) => {
                    if (response.data.success === true) {
                        this.allUsers = response.data.data;
                        this.activeElement = this.allUsers.data[0].id;
                        this.UserData = this.allUsers.data[0];
                    }
                }).catch(error => {
                    console.log(error);
                });
        },

        selectedSector: function (data) {
            const selectedSectorId = event.target.value;

            let already = this.selected_sectors.find(element => element.id == selectedSectorId);
            if (already === undefined) {
                for (let i = 0; i < this.sectors.length; i++) {
                    if (selectedSectorId == this.sectors[i].id) {
                        this.selected_sectors.push(this.sectors[i]);
                        this.filterSectors.push(selectedSectorId);
                        break;
                    }
                }
            }

            // this.filterSectors.push(sectorId);

            const filterRoles = this.filterRoles;
            const filterSectors = this.filterSectors;
            const filterStatuses = this.filterStatuses;
            this.filterUsers(filterRoles, filterSectors, filterStatuses);
        },

        removeRole: function (role, event) {
            this.selected_roles = this.selected_roles.filter((element, index) => {
                return role.id != element.id;
            });
            this.filterRoles = this.filterRoles.filter(id => {
                return role.id != id;
            });

            const filterRoles = this.filterRoles;
            const filterSectors = this.filterSectors;
            const filterStatuses = this.filterStatuses;
            this.filterUsers(filterRoles, filterSectors, filterStatuses);
        },

        removeSector: function (sector, event) {
            this.selected_sectors = this.selected_sectors.filter((element, index) => {
                return sector.id != element.id;
            });
            this.filterSectors = this.filterSectors.filter(id => {
                return sector.id != id;
            });

            const filterRoles = this.filterRoles;
            const filterSectors = this.filterSectors;
            const filterStatuses = this.filterStatuses;
            this.filterUsers(filterRoles, filterSectors, filterStatuses);
        },

        selectedRole: function (data) {

            const filterRoles = this.filterRoles;
            let training_roles = this.training_roles;

            training_roles.data.forEach(role => {
                // Check if the role ID is not already in selected_roles and is in filterRoles
                if (!this.selected_roles.some(selectedRole => selectedRole.id === role.id) && filterRoles.includes(role.id)) {
                    this.selected_roles.push(role);
                }
            });

            this.selected_roles = this.selected_roles.filter(selectedRole => filterRoles.includes(selectedRole.id));

            this.closeSelectRolePopup();
            const filterSectors = this.filterSectors;
            const filterStatuses = this.filterStatuses;
            this.filterUsers(filterRoles, filterSectors, filterStatuses);

        },

        selectedStatus: function (data) {
            const statusId = event.target.value;

            let already = this.selected_statuses.find(element => element.id == statusId);
            if (already === undefined) {
                for (let i = 0; i < this.statuses.length; i++) {
                    if (statusId == this.statuses[i]) {
                        this.selected_statuses.push(this.statuses[i]);
                        this.filterStatuses.push(statusId);
                        break;
                    }
                }
            }

            const filterRoles = this.filterRoles;
            const filterSectors = this.filterSectors;
            const filterStatuses = this.filterStatuses;
            this.filterUsers(filterRoles, filterSectors, filterStatuses);
        },

        removeStatus: function (status, event) {
            this.selected_statuses = this.selected_statuses.filter((element, index) => {
                return status != element;
            });

            this.filterStatuses = this.filterStatuses.filter((element, index) => {
                return status != element;
            });

            const filterRoles = this.filterRoles;
            const filterSectors = this.filterSectors;
            const filterStatuses = this.filterStatuses;
            this.filterUsers(filterRoles, filterSectors, filterStatuses);
        },

        filterUsers: function (roles, sectors, status) {
            const filterParameters = {
                roles: roles,
                sectors: sectors,
                status: status
            };
            // console.log(filterParameters);

            axios.post(route('trainings.filter'), filterParameters)
                .then((response) => {
                    if (response.data.success === true) {
                        console.log(response.data.data);
                        // console.log(this.users.data);
                        // alert('jj');
                        this.allUsers = response.data.data;
                        this.activeElement = this.allUsers.data[0].id;
                        this.UserData = this.allUsers.data[0];

                        // Inertia.get(route('trainings.index'));
                    }
                }).catch(error => {
                    // console.log(error.response.status);
                    console.log(error);
                });

        },

        openSelectRolePopup() {
            this.selectRolePopup = true;
        },

        closeSelectRolePopup() {
            this.selectRolePopup = false;
        },
        clearFilters() {
            this.filterRoles = [];
            this.filterSectors = [];
            this.filterStatuses = [];
            this.selected_sectors = [];
            this.selected_roles = [];

            const filterRoles = this.filterRoles;
            const filterSectors = this.filterSectors;
            const filterStatuses = this.filterStatuses;
            this.filterUsers(filterRoles, filterSectors, filterStatuses);
        }
    },
    beforeMount() {
        this.defaultSelectedItem();
        this.defaultSelectedInnerItem();
    }
}
</script>

<style>
.active {
    background-color: #3B82F6;
    color: white;
}
</style>
