<template>
    <active-tabs
        v-if="enableTabs && structure.tabs"
        @update:active="activeTab = $event"
        :tabs="tabs"
        @update:tabs="setTab($event)"
    />
    <div
        class="fields-wrap overflow-auto my-4"
        :style="{ height: structure.height }"
    >
        <div :class="fieldsWrapClass">
            <template
                v-for="(params, fieldName) in groupFields.fields"
                :key="fieldName"
            >
                <edit-row
                    v-if="!params.hide"
                    :class="params.class"
                    :modelValue="getValue(fieldName)"
                    @update:modelValue="updateValue($event, fieldName, params)"
                    :height="params.height"
                    :label="fieldName"
                    :label-on="true"
                    :type="params.type"
                    :edit="true"
                    :params="params"
                />
            </template>
        </div>
    </div>
    <action-buttons
        :mode="mode"
        :buttons="buttons"
        :class="buttonsWrapper"
        @newOrder="newOrder"
        @viewOrders="viewOrders"
        @add="add"
        @save="save"
        @cancel="cancel"
    />
</template>

<script setup lang="ts">
    import { ref, computed } from "vue"
    import type { RouteRecordNameGeneric } from "vue-router"
    import { useStore } from "vuex"

    const emit = defineEmits([
        "update:modelValue",
        "add",
        "save",
        "cancel",
        "newOrder",
        "viewOrders",
        "update:tabs",
    ])
    const store: any = useStore()

    interface Props {
        modelValue?: Record<string, any>
        structure: Record<string, any>
        routeName: RouteRecordNameGeneric
        mode?: "add" | "edit"
        class?: string
        enableTabs?: boolean
    }

    const props = withDefaults(defineProps<Props>(), {
        modelValue: () => ({}) as Record<string, any>,
        mode: "add",
        routeName: "",
        structure: () => ({}),
        enableTabs: false,
    })

    const structureHasGroups = computed(() => !!props.structure.groups)
    const Role = Object.freeze({ ADMIN: 1, USER: 2 } as const)

    const getValue = (fieldName: string) => {
        if (fieldName === "role") return props.modelValue.role?.name ?? ""
        const val = props.modelValue[fieldName]
        return val != null ? String(val) : ""
    }

    const updateValue = (value: string | number, fieldName: string, params: any) => {
        if (fieldName === "role") {
            const roleId = Role[value.toString().toUpperCase() as keyof typeof Role]
            if (roleId !== undefined) {
                props.modelValue.role_id = roleId
                const role = store.state.auth.roles.find((r: any) => r.id === roleId)
                props.modelValue.role = role
                emit("update:modelValue", props.modelValue)
                return
            }
            console.warn(`Invalid role: ${value}`)
            return
        }
        props.modelValue[fieldName] = params.type === "number" ? Number(value) : value
        emit("update:modelValue", props.modelValue)
    }

    const structure = computed(() => props.structure)
    const tabs = ref({ ...structure.value.tabs })
    const activeTab = ref<string | undefined>(
        Object.keys(tabs.value).find((key) => tabs.value[key])
    )

    const buttons = structure.value.buttons ?? undefined

    const buttonsWrapper = structure.value.buttonsWrapper ?? ""
    const groupFields = computed(() => {
        if (!structureHasGroups.value)
            return { fields: structure.value.fields as Record<string, any>, className: "" }

        const group = structure.value.groups.find((g: any) => g.name === activeTab.value)
        const { name, className, ...fields } = group

        return { fields: fields as Record<string, any>, className }
    })

    const fieldsWrapClass = computed(() =>
        structureHasGroups.value ? groupFields.value.className : structure.value.className
    )

    const setTab = (tab: string) => {
        structure.value.tabs[tab] = true
    }

    const cancel = () => emit("cancel")
    const add = () => emit("add", props.modelValue)
    const save = () => emit("save", props.modelValue)
    const newOrder = () => emit("newOrder", props.modelValue)
    const viewOrders = () => emit("viewOrders", props.modelValue)
</script>
