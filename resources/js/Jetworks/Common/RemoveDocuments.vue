<template>
<div class="mb-2">
    <div class="flex justify-start space-x-4 my-6 w-full">

        <h2 class="mt-3">New Documents</h2>

        <ul class="divide-y divide-gray-200 overflow-hidden rounded-md border grow">
            <li v-for="file in files" :key="file.id" class="flex flex-auto justify-between flex-a- p-3">
                <span class="attachment-details">
                    <span class="attachment-icon inline-block w-4 h-4 mr-2">
                        <IconPaperclip />
                    </span>
                    <span class="attachment-name">
                        {{ file.name }}
                    </span>
                </span>
                <span class="attachment-size">
                    <!-- <span class="text-black mr-4">Remove</span> -->
                    <span class="jw-text__red cursor-pointer" @click="deleteFile(file)">Remove</span>
                </span>

            </li>
        </ul>
    </div>
</div>
</template>

<script>
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";

export default {
    name: "RemoveDocuments",
    components: {
        IconPaperclip
    },
    props: {
        attachments: {
            type: Object,
            default: {},
        },
    },
    data() {
        return {
            file_to_delete: null,
            files: [], // Initialize as an empty array
        };
    },
    mounted() {
        // Update files array when component is mounted
        this.updateFiles();
    },
    watch: {
        attachments: {
            handler(newVal) {
                // Update files array when attachments prop changes
                this.updateFiles();
            },
            immediate: true, // Trigger handler immediately to update files initially
        },
    },
    methods: {
        updateFiles() {
            // Convert attachments object values to array and assign to files
            this.files = Object.values(this.attachments);
        },
        deleteFile(fileToDelete) {
            this.file_to_delete = fileToDelete;
            console.log(this.attachments);
            console.log('before : ',this.files);
            // Filter out the file to be deleted
            this.files = this.files.filter(file => file !== this.file_to_delete);
            console.log('after : ',this.files);

        },
    },
};
</script>
