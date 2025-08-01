<template>
    <div :class="className">
        <base-button
            v-for="(button, buttonIndex) in resolvedButtons"
            :key="buttonIndex"
            :class="button.class"
            :disabled="button.disabled"
            @click="handleAction(button.action)"
        >
            <span
                class="pr-[4px]"
                v-html="button.icon"
            ></span>
            {{ button.label }}
        </base-button>
    </div>
</template>

<script setup lang="ts">
    import { computed } from "vue"
    import AddIcon from "@icons/add.svg?raw"
    import CancelIcon from "@icons/cancel.svg?raw"
    import EditIcon from "@icons/edit.svg?raw"
    import DeleteIcon from "@icons/delete.svg?raw"
    import SaveIcon from "@icons/save.svg?raw"

    interface ButtonConfig {
        icon?: any
        label: string
        class: string
        action: string
        disabled?: boolean
    }

    interface ButtonSet {
        view: ButtonConfig[]
        add: ButtonConfig[]
        edit: ButtonConfig[]
    }
    interface ButtonGroup {
        class?: string
        mode?: keyof ButtonSet
        buttons?: ButtonSet
    }

    const defaultClass = "flex justify-end gap-2"
    const props = withDefaults(defineProps<ButtonGroup>(), {
        class: defaultClass,
        mode: "view",
        buttons: () => ({
            view: [
                {
                    icon: DeleteIcon,
                    class: "btn-action bg-red-500 hover:bg-red-600 text-white",
                    label: "Del",
                    action: "remove",
                    disabled: false,
                },
                {
                    icon: EditIcon,
                    class: "btn-action bg-emerald-600 hover:bg-emerald-700 text-white",
                    label: "Edit",
                    action: "edit",
                    disabled: false,
                },
            ],
            add: [
                {
                    icon: CancelIcon,
                    class: "btn-action bg-emerald-600 hover:bg-emerald-700 text-white",
                    label: "Cancel",
                    action: "cancel",
                    disabled: false,
                },
                {
                    icon: AddIcon,
                    class: "btn-action bg-blue-500 hover:bg-blue-600 text-white",
                    label: "Add",
                    action: "add",
                    disabled: false,
                },
            ],
            edit: [
                {
                    icon: CancelIcon,
                    class: "btn-action bg-rose-500 hover:bg-rose-600 text-white",
                    label: "Cancel",
                    action: "cancel",
                    disabled: false,
                },
                {
                    icon: SaveIcon,
                    class: "btn-action bg-yellow-500  text-gray-800 hover:bg-yellow-400 hover:text-gray-700",
                    label: "Save",
                    action: "save",
                    disabled: false,
                },
            ],
        }),
    })
    const className = computed(() => (props.class === "" ? defaultClass : props.class))
    const emit = defineEmits<{
        (event: string): void
    }>()

    const resolvedButtons = computed(() => {
        const mode = props.mode
        return props.buttons[mode]
    })

    const handleAction = (action: string) => {
        emit(action)
    }
</script>
