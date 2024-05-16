<template>

    <div class="mb-6 divide-y divide-gray-200 overflow-hidden rounded-lg bg-white shadow">
        <div class="px-4 py-5 sm:p-6 border-t rounded-t-md" v-if="model.description">
            <div class="workpack-description" v-html="model.description"/>
        </div>
        <div class="px-4 py-4 sm:px-6">
            <div class="flex justify-start space-x-4 my-6 w-full`">

                <h2 class="mt-3">Documents</h2>

                <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                    <li v-for="attachment in attachments"
                        :key="attachment.id"
                        class="flex flex-auto justify-between flex-a- p-3">
                                <span class="attachment-details flex items-center">
                                    <span class="attachment-icon inline-block icon icon__gray-dark w-4 h-4 mr-2">
                                        <IconPaperclip/>
                                    </span>
                                    <span class="attachment-name text-gray-500">
                                        {{ attachment.name }}
                                    </span>
                                </span>
                        <a :href="attachment.filepath" class="text-black font-semibold mr-2" target="_blank">View</a>
                    </li>
                </ul>

            </div>

        </div>
    </div>

</template>

<script>
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";

export default {
    components: {IconPaperclip},
    props: {
        model: {
            type: Object,
            default: null,
        },
        attachments: {
            type: Object,
            default: null,
        },
    }
}
</script>
