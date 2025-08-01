<template>
    <div
        v-if="field"
        class="display-field-wrap flex gap-3"
    >
        <b class="block">{{ formattedLabel }}</b> {{ field }}
    </div>
</template>

<script setup lang="ts">
    import { computed } from "vue"
    import { useStore } from "vuex"
    import { get } from "lodash-es"
    import { filters } from "@root/resources/app/lib/global-helpers"

    const props = defineProps({
        loc: { type: String, required: true },
        labelOn: { type: Boolean, default: true },
        compute: { type: Function, default: (item: any) => item },
        value: { type: String, default: "" },
        label: {
            type: String,
            default(props: any) {
                return props.loc.split(".").pop()
            },
        },
        format: {
            default: (item: any) => item,
            type: Function,
        },
    })

    const store = useStore()

    const formattedLabel = computed(() => {
        return filters.startCase(props.label)
    })

    const field = computed(() => {
        const value = get(store.state, props.loc, null) ?? props.value
        if (value === null) return null
        return props.format ? props.format(value, "ccc dd MMM yyyy HH:MM") : value
    })
</script>
