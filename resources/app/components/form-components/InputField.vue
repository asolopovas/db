<template>
    <div class="input-wrap mb-2">
        <label
            v-if="labelOn"
            class="input-label inline-block font-semibold mb-1"
        >
            {{ startCase(label) }}
        </label>
        <div class="input-field-wrap">
            <input
                v-model="localField"
                :type="cast"
                :disabled="disabled"
                class="input-field block w-full p-2"
            />
        </div>
        <div
            v-if="alerts.includes(model) && empty"
            class="input-alert"
        >
            {{ startCase(model) }} field is required
        </div>
    </div>
</template>

<script setup lang="ts">
    import { computed } from "vue"
    import { get, startCase } from "lodash-es"
    import { useStore } from "vuex"

    // Props
    const props = defineProps({
        set: { type: String, required: true },
        cast: { type: String, default: "text" },
        disabled: { type: Boolean, default: false },
        loc: { type: String, required: true },
        labelOn: { type: Boolean, default: true },
        alerts: { type: Array, default: () => [] },
        format: { type: Function, default: (item: any) => item },
        model: { type: String, default: (props: any) => props.loc.split(".").pop() },
        label: { type: String, default: (props: any) => props.loc.split(".").pop() },
    })

    // Store
    const store = useStore()

    const field = computed({
        get: () => props.format(get(store.state, props.loc, null)),
        set: (value) => {
            const locKey = props.loc.split(".").pop()
            store.commit(props.set, { value, loc: locKey })
        },
    })


    const localField = computed({
        get: () => {
            // Convert 0 to an empty string
            if (field.value === "") {
                return ""
            }
            return props.cast === "number" ? Number(field.value) : field.value
        },
        set: (value) => {
            // Convert empty string to 0 if necessary
            if (value === "" || value === 0) {
                field.value = ""
            } else {
                field.value = props.cast === "number" ? Number(value) : value
            }
        },
    })
    const empty = computed(() => !field.value)

    const $filters = {
        startCase: (str: string) =>
            str.replace(/([A-Z])/g, " $1").replace(/^./, (c) => c.toUpperCase()),
    }
</script>
