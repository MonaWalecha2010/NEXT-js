<template>  
    <div class="flex items-center justify-start space-x-4 my-2 w-full">
        <template v-if="docType">
            <h2 class="text-sm font-medium text-gray-500">{{docType}}</h2>
        </template>
        <template v-else>
            <h2 class="text-sm font-medium text-gray-500">Documents</h2>
        </template>        
        <div class="flex items-center flex-auto justify-between p-3 border rounded-md border-gray-300 overflow-hidden">
            <span class="attachment-details">
                <span class="attachment-icon inline-block w-4 h-4 mr-2">
                    <IconPaperclip />
                </span>
                <span class="attachment-name text-sm text-gray-500">
                {{fileName}}
                </span>
            </span>
            <span class="document-actions">
                <a :href="filePath" class="text-gray-900 mr-4 text-sm font-medium" target="_blank">View</a>
            </span>
        </div>
    </div>
</template>

<script>
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";

export default {
    // name: 'ViewHistoryDocument',
    components: {
        IconPaperclip
    },
    props: {
        fileName: {
            type: Object,
            default: null,
        },
        filePath: {
            type: Object,
            default: null,
        },
        docType: {
            type: String,
        },
    },
}
</script>
