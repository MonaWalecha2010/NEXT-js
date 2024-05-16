<template>

        <div class="mb-2">
            <div class="flex justify-start space-x-4 my-6 w-full">

                <h2 class="mt-3">New Documents</h2>

                <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
                    <li v-for="attachment in attachments"
                        :key="attachment.id"
                        class="flex flex-auto justify-between flex-a- p-3">
                                <span class="attachment-details">
                                    <span class="attachment-icon inline-block w-4 h-4 mr-2">
                                        <IconPaperclip/>
                                    </span>
                                    <span class="attachment-name">
                                        {{ attachment.name }}
                                    </span>
                                </span>
                        <span class="attachment-size">
                            <span class="text-black mr-4">File Size</span>
                            <span class="jw-text__red">{{ getAttachmentSize( attachment ) }}KB</span>
                        </span>


                    </li>
                </ul>
            </div>
        </div>

</template>

<script>
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";

export default {
    name: "Documents",
    components: {IconPaperclip},
    props: {
        attachments: {
            type: Object,
            default: null,
        },
    },
    methods: {
        getAttachmentSize( attachment ){
            let byteSize = attachment.size;
            return Math.round( byteSize / 1024 );
        }
    }
}
</script>
