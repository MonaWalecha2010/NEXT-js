<template>
<div v-if="files.length > 0" class="mb-1 overflow-hidden">
    <div class="px-4 py-4 sm:px-6">
        <div class="flex justify-start space-x-4 my-6 w-full">

            <h2 class="mt-3">Documents</h2>

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

                    <span class="document-actions">
                        <a :href="file.filepath" class="text-black mr-4" target="_blank">View</a>
                        <a class="text-red-600" @click.prevent="showDeleteDialog( file )">Delete</a>
                    </span>
                </li>

            </ul>

        </div>

    </div>

</div>

<ModalConfirmation v-if="file_to_delete" :action="'Delete'" :model="file_to_delete" :title="'Delete Attachment'" @confirmModalConfirmation="listenForModalConfirmation">
    Are you sure you want to delete <span v-if="file_to_delete" class="font-bold text-black">{{
            file_to_delete.name
        }}</span>?
    Once you have it will no longer be available when viewing active {{modalText==undefiend?'Work Packs':modalText}}
</ModalConfirmation>


</template>

<script>
import IconPaperclip from "@/Jetworks/Icons/Paperclip.vue";
import ModalConfirmation from "@/Jetworks/Common/ModalConfirmation.vue";

export default {
    components: {
        ModalConfirmation,
        IconPaperclip
    },
    props: {
        attachments: {
            type: Object,
            default: null,
        },
        modalText:{
            type : Object,
        }
    },
    data() {
        return {
            modal_visible: false,
            file_to_delete: null,
            files: this.attachments
        }
    },
    methods: {
        showDeleteDialog(file) {
            this.file_to_delete = file;
            this.modal_visible = true;

        },
        
        listenForModalConfirmation(data) {
            if (data == "Delete") {
                axios.delete(route('files.destroy', {
                        'file': this.file_to_delete.id
                    }))
                    .then((response) => {
                        if (response.data.success === true) {
                            this.deleteFile();
                        }
                    }).catch(error => {
                        console.log(error.response.status);
                        console.log(error.response.data.message);
                    }).finally(() => {
                        this.resetFileModal();
                    });
            } else {
                this.resetFileModal();
            }
        },
        deleteFile() {
            //Filter out the delete file
            const filteredFiles = this.files.filter(file => file.id !== this.file_to_delete.id);
            this.files = filteredFiles;
        },
        resetFileModal() {
            this.file_to_delete = null;
            this.modal_visible = null;
        },
        
    }

}
</script>
