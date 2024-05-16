<template>
<div v-if="files.length > 0" class="mb-3 overflow-hidden">   
    <div class="flex items-center justify-start space-x-4 my-2 w-full">
        <template v-if="docType">            
            <h2 class="text-sm font-medium text-gray-500">{{docType}}</h2>
        </template>
        <template v-else>
            <h2 class="text-sm font-medium text-gray-500">Documents</h2>           
        </template>
        <div v-for="file in files" :key="file.id" class="flex items-center flex-auto justify-between p-3 border rounded-md border-gray-300 overflow-hidden">
            <span class="attachment-details">
                <span class="attachment-icon inline-block w-4 h-4 mr-2">
                    <IconPaperclip />
                </span>
                <span class="attachment-name text-sm text-gray-500">
                    {{ file.name }}
                </span>
            </span>
            <span class="document-actions">
                <a :href="filePath" class="text-gray-900 mr-4 text-sm font-medium" target="_blank">View</a>
            </span>
            <!-- <li v-for="file in files" :key="file.id" class="flex flex-auto justify-between flex-a- p-3">
                <span class="attachment-details">
                    <span class="attachment-icon inline-block w-4 h-4 mr-2">
                        <IconPaperclip />
                    </span>
                    <span class="text-sm text-gray-500">
                        {{ file.name }}
                    </span>
                </span>
                <span class="document-actions">
                    <a :href="file.filepath" class="text-gray-900 mr-4 text-sm font-medium" target="_blank">View</a>
                </span>
            </li> -->
        </div>

    </div>
</div>
</template>

<script>
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";

export default {
    // name: 'ViewDocument',
    components: {
        IconPaperclip
    },
    props: {
        attachments: {
            type: Object,
            default: null,
        },
        docType: {
            type: String,
        },
    },
    data() {
        return {
            files: this.attachments
        }
    },

}
</script>
