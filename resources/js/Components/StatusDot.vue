// StatusDot.vue

<template>
    <span class="inline-block w-2 h-2 rounded-full mr-2 ... " :class="bgColor"></span>
</template>
 
<script>
export default {
    name: 'StatusDot',
    props: {
        bgColor:{
            type: String,
            default: 'bg-gray-300'
        },
    }
}
</script>