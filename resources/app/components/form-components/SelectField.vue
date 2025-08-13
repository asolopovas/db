<template>
    <div class="input-wrap flex flex-col mb-2">
        <label
            v-if="labelOn"
            class="input-label font-semibold mb-2"
            >{{ $filters.startCase(label) }}
        </label>
        <div class="input-field-wrap">
            <select
                class="block py-2 w-full border"
                v-model="field"
            >
                <option
                    v-for="item in selectItems"
                    :value="item"
                >
                    {{ format(item) }}
                </option>
            </select>
        </div>
        <div
            v-if="alerts.includes(model) && empty"
            class="input-alert"
        >
            {{ $filters.startCase(model) }} field is required
        </div>
    </div>
</template>
<script>
    import { defineComponent } from "vue"

    import { get } from 'lodash-es'

    export default defineComponent({
        props: {
            set: { type: String, required: true },
            loc: { type: String, required: true },
            labelOn: { type: Boolean, default: true },
            selectItems: { type: Array },
            alerts: { type: Array },
            format: {
                default: (item) => item.name,
                type: Function,
            },
            label: {
                type: String,
                default(props) {
                    return props.loc.split(".").pop()
                },
            },
        },

        computed: {
            empty() {
                return this.field === null || this.field === "" ? true : false
            },
            model() {
                return `${this.loc.split(".").pop()}`
            },
            field: {
                get() {
                    let item = get(this.$store.state, this.loc, null)
                    return typeof item == "string" ? { name: item } : item
                },
                set(value) {
                    this.$store.commit(this.set, { value, loc: this.model })
                },
            },
        },
    })
</script>
