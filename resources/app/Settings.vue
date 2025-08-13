<template>
    <main-layout>
        <div class="max-w-[1200px] mx-auto flex flex-wrap db-settings px-4">
            <!-- File Lists and Other Settings -->
            <div class="grid md:grid-cols-2 gap-4 bg-gray-100 p-8">
                <div class="flex flex-wrap content-start gap-2">
                    <!-- Upload Area -->
                    <h2 class="font-semibold">File Upload</h2>
                    <div
                        class="upload border p-4 w-full h-[111px] flex flex-col justify-center items-center cursor-pointer"
                        @dragover.prevent="dragActive = true"
                        @dragenter.prevent="dragActive = true"
                        @dragleave.prevent="dragActive = false"
                        @drop.prevent="handleDrop"
                        :class="{ 'drag-active': dragActive }"
                        @click="triggerFileSelect"
                    >
                        <h2 class="font-bold w-full text-stone-700 text-center">
                            Drop files or click to upload
                        </h2>
                        <file-upload
                            v-model="files"
                            class="w-full"
                            :headers="{ authorization: authToken }"
                            post-action="/api/attachments"
                            :drop="true"
                            :drop-directory="true"
                            :multiple="true"
                            @input-file="inputFile"
                            @input-filter="inputFilter"
                            ref="upload"
                        />
                    </div>
                    <div
                        v-show="files.length > 0"
                        class="border p-4 w-full"
                    >
                        <div
                            v-for="(file, index) in displayFiles"
                            :key="'existing-' + index"
                            class="flex items-center"
                        >
                            <span class="mr-2">{{ file.name }}</span>
                            <span class="mr-2 text-gray-500">{{ file.modified }}</span>
                            <base-button
                                class="h-[40px] w-[40px]"
                                @click="removeAttachment(file)"
                            >
                                <i
                                    class="fa fa-times text-red-700"
                                    aria-hidden="true"
                                ></i>
                            </base-button>
                        </div>
                    </div>
                    <base-button
                        class="btn-upload flex items-center"
                        @click="uploadFiles"
                    >
                        Upload
                    </base-button>
                </div>

                <div class="flex flex-wrap">
                    <div class="flex flex-col mb-4 w-full">
                        <label
                            for="invoice-message"
                            class="mb-2 font-semibold"
                            >Invoice Message</label
                        >
                        <BaseEditor
                            id="invoice-message"
                            height="150px"
                            :disabled="false"
                            v-model="invoice_message"
                        />
                    </div>

                    <div class="flex flex-col w-full mb-4">
                        <label
                            for="quote-message"
                            class="mb-2 font-semibold"
                            >Quote Message</label
                        >
                        <BaseEditor
                            id="quote-message"
                            height="150px"
                            :disabled="false"
                            v-model="quotation_message"
                        />
                    </div>
                    <div class="text-right w-full text-white">
                        <base-button
                            class="btn-save"
                            @click.prevent="saveMessageSettings"
                        >
                            Save
                        </base-button>
                    </div>
                </div>
            </div>
        </div>
    </main-layout>
</template>

<script setup lang="ts">
    import { ref, reactive, computed, onMounted } from "vue"
    import FileUpload from "vue-upload-component"
    import apiFetch from "@root/resources/app/lib/apiFetch"
    import { useStore } from "vuex"
    import type { VueUploadItem } from "vue-upload-component"
    const upload = ref<InstanceType<typeof FileUpload> | null>(null)
    interface FileData extends VueUploadItem {
        modified: string
    }
    export interface SettingsData {
        files?: FileData[]
        [key: string]: any
    }
    const store = useStore()
    const settings = reactive<SettingsData>({
        files: [],
        invoice_message: "",
        quotation_message: "",
    })
    const files = ref<VueUploadItem[]>([])
    const dragActive = ref(false)
    const displayFiles = computed(() => files.value)
    const authToken = computed(() =>
        store.state.auth?.access_token ? `Bearer ${store.state.auth.access_token}` : ""
    )

    const invoice_message = ref("")
    const quotation_message = ref("")
    const refreshSettings = async () => {
        const { data } = await apiFetch<SettingsData>("/api/settings-query/get/all")

        files.value = data.files as VueUploadItem[]
        invoice_message.value = data.invoice_message
        quotation_message.value = data.quotation_message
    }
    const handleDrop = async (event: DragEvent) => {
        dragActive.value = false
        if (event.dataTransfer && event.dataTransfer.items.length > 0) {
            const dataTransfer = event.dataTransfer
            await (upload.value as any).addDataTransfer(dataTransfer)
        }
    }
    const inputFile = (newFile: VueUploadItem | null, oldFile: VueUploadItem | null) => {
        if (newFile && oldFile && !newFile.active && oldFile.active) {
            if (newFile.response && typeof newFile.response.modified === "string") {
                newFile.modified = newFile.response.modified
            } else {
                newFile.modified = new Date().toLocaleString()
            }
        }
    }
    const inputFilter = (newFile: VueUploadItem | null, prevent: () => void) => {
        if (newFile?.name && !/\.(pdf|jpe?g|png)$/i.test(newFile.name)) {
            prevent()
        }
    }
    const removeAttachment = async (file: any) => {
        try {
            await apiFetch("/api/attachments?name=" + encodeURIComponent(file.name), {
                method: "DELETE",
            })
            files.value = files.value.filter((f) => f.name !== file.name)
        } catch (error) {
            console.error("Failed to remove attachment:", error)
        }
    }
    const uploadFiles = async () => {
        try {
            if (upload.value) upload.value.active = true
            await new Promise((resolve) => {
                const checkUploadCompletion = setInterval(() => {
                    if (upload.value && !upload.value.active) {
                        clearInterval(checkUploadCompletion)
                        resolve(true)
                    }
                }, 500)
            })

            const newFiles: FileData[] = files.value.map((file) => ({
                id: Date.now().toString() + Math.random().toString(),
                name: file.name ?? "",
                modified: new Date().toLocaleString(),
                size: file.size ?? 0,
                type: file.type ?? "",
            }))

            files.value = newFiles
        } catch (error) {
            console.error(error)
        }
    }
    const triggerFileSelect = () => {
        const fileInput = upload.value?.$refs?.input as HTMLInputElement | undefined

        if (fileInput) {
            fileInput.click()
        }
    }
    const saveMessageSettings = async () => {
        const body = {
            invoice_message: invoice_message.value,
            quotation_message: quotation_message.value,
        }

        await apiFetch("/api/settings-query/save", {
            method: "POST",
            body,
        })
    }

    onMounted(refreshSettings)
</script>

<style scoped>
    .upload {
        border: 2px dashed #ccc;
        transition: border-color 0.3s;
    }

    .upload.drag-active {
        border-color: #00aaff;
        background-color: rgba(0, 170, 255, 0.1);
    }
</style>
