<template>
<div aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>
    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl  transition-all sm:my-8 sm:w-full sm:max-w-lg">
            
                <div class="bg-white flex justify-center px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="items-center">
                        <div class="flex justify-center">
                            <div class="m-auto my-2 flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full bg-blue-100 sm:mx-0 sm:h-20 sm:w-20">
                                <svg aria-hidden="true" class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>
                        </div>
                        <div class="mt-3 text-center sm:w-72 mx-auto">
                            <h3 id="modal-title" class="text-2xl font-semibold leading-6 text-gray-900 text-center">{{title}}</h3>
                            <div class="mt-3 text-center text-base font-normal  text-gray-500"> 
                                <p class="text-center text-base font-normal  text-gray-500">
                                <slot name="default" />   
                                </p>                             
                            </div>                            
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 w-full px-4 gap-x-5 py-3 sm:flex justify-around sm:px-6 p-3">                    
                     <button class="w-full jw-btn border-gray-300 text-gray-500 btn-cancel" type="button" @click.prevent="closeModal">
                        Cancel
                    </button>
                    <button class="w-full jw-btn jw-btn__blue" type="button" @click.prevent="confirmModal">
                        {{ action }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
</template>

<script>
export default {
    components: {},
    props: {
        title: {
            type: String,
            default: '',
        },
        description: {
            type: String,
            default: '',
        },
        action: {
            type: String,
            default: 'Continue',
        },
        model: {
            type: Object,
            default: {},
        }
    },
    methods: {

        closeModal: function () {
            this.$emit('confirmModalConfirmation', 'Close');
        },
        confirmModal: function () {
            this.$emit('confirmModalConfirmation', this.action);
        }
    }
}
</script>
