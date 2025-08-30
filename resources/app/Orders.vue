<template>
    <main-layout>
        <modal
            v-if="showModal"
            class="yes-no-box"
            :controls="true"
            @close="showModal = false"
            @yes="deleteOrder"
        >
            Do you really want to delete this order?
        </modal>
        <spinner v-if="loading" />
        <template #options>
            <div class="flex flex-wrap gap-4">
                <options />
                <select
                    class="h-[40px] px-4"
                    v-model="filters.orderStatus"
                    name="statusFilters"
                >
                    <option value="null">Status</option>
                    <option value="quote">Quotes</option>
                    <option value="invoice">Invoice</option>
                </select>
                <select
                    class="h-[40px] px-4"
                    v-model="filters.various"
                    name="variousFilters"
                >
                    <option
                        :value="null"
                        selected
                    >
                        Filter
                    </option>
                    <option value="paid">Paid</option>
                    <option value="unpaid">Unpaid</option>
                    <option value="in_progress">In Progress</option>
                    <option value="tax_deductions">Tax Deductions</option>
                </select>
                <select
                    class="h-[40px] px-2"
                    v-model="filters.vat"
                    name="variousFilters"
                >
                    <option :value="null">Vat</option>
                    <option value="20">Vat 20%</option>
                    <option value="5">Vat 5%</option>
                    <option value="0">Vat 0%</option>
                </select>
                <base-button
                    class="options-button btn-action bg-sky-600 text-white"
                    @click="resetFilters"
                    >Reset</base-button
                >
            </div>
        </template>
        <div class="w-full max-w-[1366px] mx-auto">
            <div class="px-4 overflow-auto w-full">
                <table class="md:table-auto">
                    <thead>
                        <tr>
                            <th
                                v-for="(opts, value, index) in fields"
                                :key="index"
                                scope="col"
                                class="t-col cursor-pointer select-none"
                                :class="opts.class"
                                @click="sort(opts.sort, value)"
                            >
                                {{ startCase(String(value)) }}
                            </th>
                            <th class="t-col text-right px-2">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <order
                            v-for="item in items"
                            :key="item.id"
                            :item="item"
                            :fields="fields"
                            @deleteOrder="assignItem"
                            @loading="loading = $event"
                        />
                        <tr v-if="!items.length">
                            <td
                                colspan="10"
                                class="t-col text-center font-bold"
                            >
                                Sorry No Records Found
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination
                    class="w-full justify-end py-2 bg-neutral-100"
                    :click-handler="pageSwitch"
                    :per-slice="5"
                    :current-page="storePagination.current_page"
                    :last-page="storePagination.last_page"
                />
            </div>
        </div>
    </main-layout>
</template>

<script setup lang="ts">
    import { ref, reactive, computed, watch, onMounted } from "vue"
    import { useRouter, useRoute } from "vue-router"
    import { useStore } from "vuex"
    import { startCase } from "lodash-es"
    import Order from "@app/orders/Order.vue"
    import useIndexView from "@root/resources/app/mixins/indexView.ts"

    const route = useRoute()
    const router = useRouter()
    const store = useStore()
    const storePagination = store.state.pagination

    const { pageSwitch, fetchData } = useIndexView()

    const showModal = ref(false)
    const loading = ref(false)
    const sortBy = ref<string | null>(null)
    const sortOrder = ref<string | null>(null)
    const view = ref<string | null>(null)
    const item = ref<any>(null)

    const filters = reactive({
        orderStatus: null as string | null,
        various: null as string | null,
        vat: null as string | null,
    })

    const notesView = {
        id: {},
        customer: { sort: "firstname" },
        notes: { width: 350 },
        review_request: {},
        photo_request: {},
    }
    const ordersView = {
        id: { class: "t-col t-col-id uppercase" },
        base_id: { class: "t-col t-col-id w-[80px] uppercase" },
        date: {
            sort: "created_at",
            class: "t-col t-col-date",
            format: (date: any) => `$filters.dateFormat(date)`,
        },
        customer: { width: 200, sort: "firstname", class: "t-col t-col-customer" },
        address: { sort: "address_line_1", class: "t-col t-col-address" },
        status: { class: "t-col t-col-status" },
        grand_total: { class: "t-col t-col-grandtotal", width: 121 },
        vat: { class: "t-col t-col-vat" },
        balance: { class: "t-col t-col-balance" },
    }

    const items = computed<any>(() => store.state.items[route.name as string].items)

    const fields = computed(() => {
        const validator = {
            get(obj: any, prop: string) {
                if (typeof obj[prop] === "object" && obj[prop] !== null) {
                    return new Proxy(obj[prop], validator)
                }
                return prop in obj ? obj[prop] : null
            },
        }
        return view.value === "notes"
            ? new Proxy(notesView, validator)
            : new Proxy(ordersView, validator)
    })

    const query = computed(() => {
        const output: Record<string, any> = { orderBy: orderBy.value }

        const validFilters = Object.entries(filters).reduce<Record<string, any>>(
            (acc, [key, value]) => {
                if (value && value !== "null") {
                    acc[key] = value // No error now
                }
                return acc
            },
            {}
        )

        if (Object.keys(validFilters).length) {
            output.filters = validFilters
        }

        return output
    })

    const orderBy = computed(() => {
        return sortBy.value && sortOrder.value ? [sortBy.value, sortOrder.value] : []
    })

    const assignItem = (selectedItem: any) => {
        item.value = selectedItem
        showModal.value = true
    }

    const deleteOrder = () => {
        if (item.value) {
            store.dispatch("orderDelete", { routeName: route.name, id: item.value.id }).then(() => {
                showModal.value = false
            })
        }
    }


    const parseQueryFilters = (queryFilters: any) => {
        if (queryFilters) {
            Object.assign(filters, {
                orderStatus: queryFilters.orderStatus || null,
                various: queryFilters.various || null,
                vat: queryFilters.vat || null,
            })
        }
    }

    const resetFilters = () => {
        filters.orderStatus = null
        filters.various = null
        filters.vat = null
        router.push({ query: {} })
    }

    const sort = (name: any, def: any) => {
        const sortArg = name || def
        if (sortBy.value !== sortArg) {
            sortBy.value = sortArg
            sortOrder.value = "desc"
            return
        }
        sortOrder.value = sortOrder.value === "desc" ? "asc" : "desc"
    }

    watch(
        () => orderBy.value,
        () => {
            router.push({ query: query.value })
        }
    )
    watch(
        () => filters,
        () => {
            router.push({ query: query.value })
        },
        { deep: true }
    )
    watch(route, () => {
        fetchData()
    })

    onMounted(async () => {
        parseQueryFilters(route.query.filters)
        if (route.query.orderBy) {
            ;[sortBy.value, sortOrder.value] = route.query.orderBy as string[]
        }
        await fetchData()
    })
</script>
