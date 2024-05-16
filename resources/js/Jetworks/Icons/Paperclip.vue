<template>
    <svg xmlns="http://www.w3.org/2000/svg" width="17.232" height="18.197" viewBox="0 0 17.232 18.197">
        <path id="Icon_feather-paperclip" data-name="Icon feather-paperclip" d="M7.954,19.68A5.549,5.549,0,0,1,4.029,10.2l7.581-7.581a3.9,3.9,0,0,1,5.517,5.517L9.537,15.722a2.251,2.251,0,0,1-3.183-3.183l7-7a.6.6,0,0,1,.848.849l-7,6.995a1.051,1.051,0,0,0,1.486,1.486l7.589-7.581a2.7,2.7,0,0,0-3.82-3.82L4.877,11.053a4.352,4.352,0,0,0,6.155,6.155l7.581-7.581a.6.6,0,1,1,.849.849L11.88,18.057A5.535,5.535,0,0,1,7.954,19.68Z" transform="translate(-2.405 -1.483)" :stroke="svgFill?svgFill:'#d1d5db'" />
    </svg>
</template>
<script>
export default {
    name: 'IconPaperclip',
    props:{
        svgFill:{
            type : Object,
        }
    }
}
</script>
