<template>
    <div class="input-search-wrap" :class="wrap">
        <label
            v-if="labelOn"
            class="input-label inline-block mb-1 font-semibold"
        >
            {{ label }}
        </label>
        <div class="input-field-wrap relative">
            <input
                v-model="input"
                class="input-field"
                :class="class"
                @input="inputSearch"
                @keydown="(event) => eventAction(event, resultsList)"
                @click="toggleResults"
            />
            <base-button
                class="btn-action bg-sky-600 text-white"
                @click="reset"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="16"
                    height="16"
                    viewBox="0 0 24 24"
                >
                    <path
                        fill="none"
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="1.5"
                        d="M6.758 17.243L12.001 12m5.243-5.243L12 12m0 0L6.758 6.757M12.001 12l5.243 5.243"
                    />
                </svg>
            </base-button>
            <ul
                v-show="resultsDisplay"
                ref="resultsList"
                class="search-results absolute border bg-neutral-400 w-full top-[102%] z-20 max-h-64 overflow-auto"
            >
                <li
                    v-for="result in results"
                    :key="result.id"
                    tabindex="-1"
                    class="result px-4 py-3 text-white hover:bg-zinc-500 cursor-pointer"
                    @keydown="resultsNavigation(result, $event)"
                    @click="setResult(result)"
                >
                    <span v-html="format(result)" />
                </li>
            </ul>
        </div>
        <div
            v-if="alerts.includes(model) && empty"
            class="input-alert"
        >
            {{ model }} field is required
        </div>
    </div>
</template>

<script setup lang="ts">
    import { ref, computed, watch, onMounted } from "vue"
    import { useStore } from "vuex"
    import AlgoliaPlugin from "@/app/plugins/algolia-plugin.js"
    import { get, debounce, filter } from 'lodash-es'
    import pluralize from "pluralize"
    import { filters } from "@root/resources/app/lib/global-helpers"

    // Define a type for the result objects
    interface SearchResult {
        id: string | number
        [key: string]: any
    }
    const props = defineProps({
        wrap: { type: String },
        class: { type: String },
        skip: {type: Array, default: () => [] },
        set: { type: String, required: true },
        loc: { type: String, required: true },
        labelOn: { type: Boolean, default: true },
        alerts: { type: Array },
        label: {
            type: String,
            default: (props: any) => filters.startCase(props.loc.split(".").pop()),
        },
        format: {
            type: Function,
            default: (item: any) => item.name,
        },
    })

    const store = useStore()
    const input = ref("")
    const resultsDisplay = ref(false)
    const results = ref<SearchResult[]>([])
    const resultsList = ref<HTMLElement | null>(null)

    const field = computed({
        get: () => {
            const value = get(store.state, props.loc, null)
            return value === null ? value : props.format(value)
        },
        set: (value) => {
            store.commit(props.set, { value, loc: model.value })
        },
    })

    const empty = computed(() => field.value === null)
    const model = computed(() => `${props.loc.split(".").pop()}`)
    const index = computed(() => `${pluralize(model.value)}_index`)

    const reset = () => {
        input.value = ""
        field.value = null
        results.value = []
        resultsDisplay.value = false
    }

    const search = async (value = "") => {
        const algolia = new AlgoliaPlugin()

        const searchParams = {
            query: value,
            page: 1,
            perPage: 32,
            indexName: index.value,
        }
        const { data } = await algolia.search(searchParams)
        const filteredData = (data as SearchResult[]).filter(
            (item) => !props.skip.includes(item.id)
        )

        console.log({filteredData, skip: props.skip});

        results.value = filteredData as SearchResult[]

        if (!results.value.length) {
            const fallbackParams = {
                query: "",
                page: 1,
                perPage: 32,
                indexName: index.value,
            }
            const fallbackData = await algolia.search(fallbackParams)
            results.value = fallbackData.data as SearchResult[]
        }
    }

    const inputSearch = debounce(() => {
        search(input.value)
        resultsDisplay.value = true
    }, 500)

    const toggleResults = () => {
        if (!results.value.length) search()
        resultsDisplay.value = !resultsDisplay.value
    }

    // Define the missing navigate function
    const navigate = (action: string, el?: HTMLElement | null) => {
        if (!el) return

        const children = Array.from(el.children) as HTMLElement[]
        let target: HTMLElement | undefined

        if (action === "first") {
            target = children[0]
        } else if (action === "last") {
            target = children[children.length - 1]
        } else if (action === "next") {
            let sibling = el.nextElementSibling
            while (sibling && !(sibling instanceof HTMLElement)) {
                sibling = sibling.nextElementSibling
            }
            target = sibling as HTMLElement
        } else if (action === "prev") {
            let sibling = el.previousElementSibling
            while (sibling && !(sibling instanceof HTMLElement)) {
                sibling = sibling.previousElementSibling
            }
            target = sibling as HTMLElement
        }

        if (target) {
            target.focus()
            // Scroll the target into view
            target.scrollIntoView({ behavior: "smooth", block: "nearest" })
        }
    }

    const eventAction = (event: KeyboardEvent, resultsEl: HTMLElement | null) => {
        if (resultsEl) {
            if (!resultsDisplay.value) toggleResults()

            if (event.key === "ArrowDown") {
                navigate("first", resultsEl)
            }
            if (event.key === "ArrowUp") {
                navigate("last", resultsEl)
            }
        }
    }

    const resultsNavigation = (result: SearchResult, event: KeyboardEvent) => {
        switch (event.key) {
            case "ArrowDown":
                navigate("next", event.target as HTMLElement)
                break
            case "ArrowUp":
                navigate("prev", event.target as HTMLElement)
                break
            case "Enter":
                setResult(result)
        }
    }

    const setResult = (result: SearchResult) => {
        field.value = result
        resultsDisplay.value = false
    }

    watch(field, () => {
        input.value = field.value
    })

    onMounted(() => {
        input.value = field.value
    })
</script>
