<template>
    <div :class="class">
        <label
            v-if="labelOn"
            :for="label"
            class="font-semibold input-label block mb-2"
        >
            {{ $filters.startCase(label) }}
        </label>
        <input
            v-if="inputTypes.includes(params.type)"
            :id="label"
            :value="modelValue"
            :autocomplete="params.autocomplete"
            :type="params.type"
            :placeholder="$filters.startCase(label)"
            :title="label"
            :disabled="params.disabled"
            class="w-full border p-2"
            @input="updateValue($event)"
        />
        <BaseEditor
            v-if="params.type === 'textarea'"
            v-model="val"
            :height="height"
            :disabled="params.disabled"
            @update:val="updateValue($event)"
        />
        <select
            v-if="params.type === 'select'"
            :id="label"
            :value="modelValue"
            :disabled="params.disabled"
            class="block border w-full p-2 mb-2"
            @change="updateValue($event)"
        >
            <option
                v-for="(option, index) in params.options"
                :key="index"
                :value="option"
            >
                {{ option }}
            </option>
        </select>
    </div>
</template>

<script setup lang="ts">
    import { onMounted, computed } from "vue"
    import { startCase } from "lodash-es"
    interface Props {
        class?: string
        autocomplete?: string
        height?: string
        modelValue: any
        label: string
        params: Record<string, any>
        labelOn: boolean
    }
    const props = withDefaults(defineProps<Props>(), {
        modelValue: null,
        type: "text",
        params: () => ({}),
        disabled: false,
        labelOn: true,
    })

    const val = computed({
        get() {
            return props.modelValue
        },
        set(value: string) {
            emit("update:modelValue", value)
        },
    })

    const emit = defineEmits(["update:modelValue"])

    const inputTypes = ["text", "date", "display", "number", "email", "tel", "password", "checkbox"]

    const updateValue = (event: Event) => {
        const { value } = event.target as HTMLInputElement

        if (props.params.type === "number") {
            emit("update:modelValue", Number(value))
            return
        }

        emit("update:modelValue", value)
    }

    onMounted(() => {
        if (props.params.hasOwnProperty("default")) {
            emit("update:modelValue", props.params.default)
        }
    })
</script>
