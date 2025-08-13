<template>
    <div
        ref="editorContainer"
        class="flex-col w-full bg-white lg:h-full"
        :style="{ height }"
    ></div>
</template>

<script setup lang="ts">
    import { ref, onMounted, onUnmounted, watch } from "vue"
    import Quill from "quill"
    import "quill/dist/quill.snow.css"

    const props = defineProps({
        modelValue: String,
        disabled: Boolean,
        height: String,
        config: {
            type: Object,
            default: () => ({
                theme: "snow",
                modules: {
                    toolbar: [
                        ["bold", "italic", "underline", "strike"], // Formatting
                        [{ list: "ordered" }, { list: "bullet" }], // Lists
                        [{ align: [] }], // Alignment
                        ["link", "image"], // Media
                        ["clean"], // Remove formatting
                        ["code-block"],
                    ],
                },
            }),
        },
    })

    const emit = defineEmits(["update:modelValue", "change", "blur"])

    const editorContainer = ref<HTMLDivElement | null>(null)
    let quillInstance: Quill | null = null
    onMounted(() => {
        if (!editorContainer.value) return

        quillInstance = new Quill(editorContainer.value, props.config)
        quillInstance.enable(!props.disabled)
        quillInstance.on("text-change", () => {
            const content = quillInstance?.root.innerHTML || ""
            emit("update:modelValue", content)
            emit("change", content)
        })
        quillInstance.on("selection-change", (range) => {
            if (!range) emit("blur")
        })

        if (props.modelValue) quillInstance.root.innerHTML = props.modelValue
    })

    onUnmounted(() => {
        quillInstance?.off("text-change")
        quillInstance?.off("selection-change")
        quillInstance = null
    })

    watch(
        () => props.modelValue,
        (newVal) => {
            if (quillInstance && quillInstance.root.innerHTML !== newVal) {
                quillInstance.root.innerHTML = newVal || ""
            }
        }
    )

    watch(
        () => props.disabled,
        (isDisabled) => {
            quillInstance?.enable(!isDisabled)
        }
    )
</script>
