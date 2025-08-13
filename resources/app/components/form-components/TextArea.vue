<template>
    <div class="text-area-wrap">
        <label
            v-if="labelOn"
            class="inline-block mb-2"
            >{{ $filters.startCase(label) }}</label
        >
        <BaseEditor v-model="field" height="150px" />
    </div>
</template>
<script>
    import { defineComponent } from "vue"
    import { get as _get, debounce as _debounce } from 'lodash-es'

    export default defineComponent({
        props: {
            set: { type: String, required: true },
            loc: { type: String, required: true },
            labelOn: { type: Boolean, default: true },
            action: {
                type: Function,
                default: function () {},
            },
            disabled: { type: Boolean, default: false },
            label: {
                type: String,
                default(props) {
                    return props.loc.split(".").pop()
                },
            },
        },

        watch: {
            field: function () {
                this.actionExecute()
            },
        },

        methods: {
            actionExecute: _debounce(function () {
                setTimeout(
                    function () {
                        this.action()
                    }.bind(this),
                    1000
                )
            }, 500),
        },

        computed: {
            field: {
                get() {
                    return _get(this.$store.state, this.loc, null)
                },
                set(value) {
                    const loc = this.loc.substring(this.loc.indexOf(".") + 1)
                    this.$store.commit(this.set, { value, loc })
                },
            },
        },
    })
</script>
