// RoundedTextPill.vue

<template>
    <template v-if="actionStatus=='required'">
        <span class=" border border-red-600 text-red-600 bg-red-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
            {{actionValue}}
        </span>
    </template>
    <template v-if="actionStatus=='pending'">
        <span class=" border border-blue-500 text-blue-500 bg-blue-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
            {{actionValue}}
        </span>
    </template>
    <template v-if="actionStatus=='upcoming'">
        <span class=" border border-yellow-500 text-yellow-600 bg-yellow-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
            {{actionValue}}
        </span>
    </template>
    <template v-if="actionStatus=='active'">
        <span class=" border border-green-500 text-green-500 bg-green-100 px-[16px] text-xsm leading-4 py-[4px] rounded-full ... ">
            {{actionValue}}
        </span>
    </template>
</template>
 
<script>
export default {
    name: 'RoundedTextPill',
    props: {
        actionStatus:{
            type: String,
        },
        actionValue:{
            type: String,
        },
       

    }
}
</script>
