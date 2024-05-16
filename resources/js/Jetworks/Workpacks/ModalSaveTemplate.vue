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
                <div
                    class="relative transform overflow-hidden rounded-lg bg-white text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-lg">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="mb-6 text-center">
                            <h3 id="modal-title" class="text-xl font-semibold text-gray-900">Save as Template</h3>
                            <div class="mt-2">
                                <p class="text-sm text-gray-400">Please enter a template name</p>
                            </div>
                        </div>
                        <div class="jw-form mb-6 text-left">
                            <label for="email">Template Name</label>
                            <div class="mt-1">
                                <input id="name" v-model="this.template_name" aria-invalid="true" class="" type="text">
                            </div>
                        </div>
                    </div>
                    <div class="flex bg-gray-100 px-4 py-3 sm:flex sm:px-6">
                        <div class="sm:flex-auto">
                            <button class="flex-auto jw-btn jw-btn__gray-light" type="button"
                                    @click.prevent="closeModal">
                                Cancel
                            </button>
                        </div>
                        <div class="mt-4 sm:mt-0 sm:ml-16 sm:flex-none space-x-6">
                            <button class="flex-none jw-btn jw-btn__blue" type="button"
                                    @click.prevent="confirmModal">
                                Save Template
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</template>
<script>
export default {
    name: "WorkpackModalSaveTemplate",
    components: {},
    data() {
        return {
            template_name : '',
        }
    },
    methods: {

        closeModal: function () {
            this.$emit('closeSaveTemplateModal', false);
        },
        confirmModal: function () {
            this.$emit('closeSaveTemplateModal', this.template_name );
        }
    }
}
</script>
