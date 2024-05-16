<template>
<div aria-labelledby="modal-title" aria-modal="true" class="relative z-50" role="dialog">
    <!--
          Background backdrop, show/hide based on modal state.

          Entering: "ease-out duration-300"
            From: "opacity-0"
            To: "opacity-100"
          Leaving: "ease-in duration-200"
            From: "opacity-100"
            To: "opacity-0"
        -->
    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
            <!--
                  Modal panel, show/hide based on modal state.

                  Entering: "ease-out duration-300"
                    From: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    To: "opacity-100 translate-y-0 sm:scale-100"
                  Leaving: "ease-in duration-200"
                    From: "opacity-100 translate-y-0 sm:scale-100"
                    To: "opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                -->
            <div class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                <div class="bg-white px-4 pt-4 pb-4 sm:p-4 sm:pb-4 ">
                    <div class="text-center">
                        <div class="mx-auto mt-3 flex h-20 w-20 flex-shrink-0 items-center justify-center rounded-full bg-blue-100">
                            <!-- Heroicon name: outline/exclamation-triangle -->
                            <svg aria-hidden="true" class="h-12 w-12 text-blue-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </div>
                        <div class="mx-auto mt-5 text-center sm:w-72">
                            <h3 id="modal-title" class="text-center font-semibold leading-6 text-2xl text-gray-900">{{ title}}</h3>
                            <div class="mt-3 text-center text-base font-normal  text-gray-500"> 
                                <p class="text-center text-base font-normal  text-gray-500">
                                <slot name="default" />   
                                </p>                             
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-lightest px-4  py-3 sm:flex sm:px-6 space-x-3 mt-3">
                    <button class="grow jw-btn-md border-gray-200 text-gray-500 btn-cancel bg-white text-base" type="button" @click.prevent="closeModal">
                        Cancel
                    </button>
                    <button class="grow jw-btn-md bg-red-600 text-base" type="button" @click.prevent="failAssesment">
                        Fail
                    </button>
                    <button class="grow jw-btn-md bg-green-500 text-base" type="button" @click.prevent="passAssesment">
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
        passAssesment: function () {
            this.$emit('confirmModalConfirmation', 'Pass');
        },
        failAssesment: function () {
            this.$emit('confirmModalConfirmation', 'Fail');
        }
    }
}
</script>
