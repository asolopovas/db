<template>
    <main-layout>
        <!-- Header -->
        <template #options>
            <base-button
                v-if="selectedItems.length"
                class="btn-action bg-red-600 hover:bg-red-700 text-white mr-2"
                @click="confirmBulkDelete"
            >
                Delete Selected ({{ selectedItems.length }})
            </base-button>
            <base-button
                class="btn-action bg-emerald-600 hover:bg-emerald-700 text-white"
                @click="addItem"
            >
                <svg
                    xmlns="http://www.w3.org/2000/svg"
                    width="18"
                    height="18"
                    viewBox="0 0 24 24"
                >
                    <path
                        fill="currentColor"
                        d="M13 4v7h7v2h-7v7h-2v-7H4v-2h7V4h2Z"
                    />
                </svg>
            </base-button>
            <items-options />
        </template>
        <!-- Delete Confirmation Modal -->
        <modal
            v-if="confirmation"
            :controls="true"
            class="send"
            @close="confirmation = false"
            @no="confirmation = false"
            @yes="remove"
        >
            {{
                selectedItems.length > 0
                    ? `Do you wish to delete ${selectedItems.length} selected items?`
                    : "Do you wish to delete this item?"
            }}
        </modal>
        <!-- New Item Modal -->
        <modal
            v-if="showNewItemModal"
            @close="showNewItemModal = false"
        >
            <template #title>Add Item</template>
            <item
                v-model="item"
                mode="add"
                :routeName="route.name"
                :structure="structure"
                :enableTabs="true"
                @add="add"
                @cancel="showNewItemModal = false"
            />
        </modal>
        <!-- Edit Item Modal -->
        <modal
            v-if="edit"
            @close="edit = false"
            :style="{ 'height': structure.modalMinHeight }"
        >
            <template #title>Edit Item</template>
            <item
                mode="edit"
                v-model="itemClone"
                :routeName="route.name"
                :structure="structure"
                :enableTabs="true"
                @newOrder="newOrder"
                @viewOrders="viewOrders"
                @save="save"
                @cancel="edit = false"
            />
        </modal>
        <div class="table-wrap">
            <div class="px-4 overflow-auto w-full">
                <table>
                    <thead>
                        <tr class="bg-white">
                            <th
                                scope="col"
                                class="t-col w-12 text-center align-middle"
                            >
                                <div class="w-full h-full flex justify-center items-center">
                                    <input
                                        name="select-all"
                                        type="checkbox"
                                        :checked="isAllSelected"
                                        @change="toggleSelectAll"
                                        class="rounded h-5 w-5"
                                    />
                                </div>
                            </th>
                            <th
                                v-for="key in tableKeys"
                                :key="key"
                                scope="col"
                                :class="`t-col t-col-${key.toLowerCase()}`"
                                @click="sort(key)"
                            >
                                {{ startCase(key) }}
                            </th>
                            <th
                                scope="col"
                                class="t-col t-col-action w-[200px]"
                            >
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="item in itemsAggregate"
                            :key="item.id"
                        >
                            <td class="t-col w-12 h-12">
                                <div class="w-full h-full flex justify-center items-center">
                                    <input
                                        name="select-item"
                                        class="rounded h-5 w-5"
                                        type="checkbox"
                                        :value="item.id"
                                        v-model="selectedItems"
                                    />
                                </div>
                            </td>
                            <td
                                v-for="key in tableKeys"
                                class="t-col"
                                :class="`t-col-${key.toLowerCase()}`"
                                :data-label="startCase(key)"
                                :key="key"
                                v-html="itemValue(item, key)"
                            ></td>
                            <td
                                class="t-col t-col-action content-center"
                                data-label="Action"
                            >
                                <action-buttons
                                    :buttons="buttons"
                                    mode="view"
                                    @edit="editItem(item.id)"
                                    @remove="confirm(item)"
                                    @viewOrders="viewOrders(item)"
                                    @orderCreate="
                                        async () => {
                                            const { data } = await store.dispatch('orderCreate', {
                                                customer_id: item.id,
                                            })

                                            router.push({
                                                name: 'orderDetails',
                                                params: { id: data.id },
                                            })
                                        }
                                    "
                                    class="flex justify-end gap-2"
                                />
                            </td>
                        </tr>
                        <tr v-if="!items.length">
                            <td
                                :colspan="tableKeys.length + 2"
                                class="text-center text-capitalize text-uppercase font-bold"
                            >
                                Sorry No Records Found
                            </td>
                        </tr>
                    </tbody>
                </table>
                <pagination
                    v-if="storePagination.last_page && storePagination.current_page"
                    :key="storePagination.current_page + '-' + storePagination.last_page"
                    class="w-full justify-end py-2 bg-neutral-100 border-t-2"
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
    import { ref, computed, watch } from "vue"
    import { useStore } from "vuex"
    import { useRoute, useRouter } from "vue-router"
    import { startCase } from "lodash-es"
    import { currency, getAggregatedItem } from "@root/resources/app/lib/global-helpers"
    import getStructure from "@root/resources/app/lib/struct"
    import useIndexView from "@app/mixins/indexView.ts"
    import Item from "@app/Item.vue"
    import { table } from "console"
    // Core
    const store = useStore()
    const router = useRouter()
    const route = useRoute()
    const { showNewItemModal, edit, pageSwitch, fetchData } = useIndexView()

    // Vars
    const confirmation = ref(false)
    const items = computed<any>(() => store.state.items[route.name as string].items)
    const item = ref<any>({})
    const itemClone = ref<any>({})
    const sortDirections = ref<Record<string, "asc" | "desc">>({})
    const selectedItems = ref<number[]>([])

    // Computed
    const storePagination = computed(() => store.state.pagination)
    const structure = computed(() => getStructure(route.name as string))
    const buttons = computed(() => structure.value.buttons)

    const orderBy = computed(() =>
        Object.entries(sortDirections.value)
            .map(([k, v]) => `${k},${v}`)
            .join(";")
    )
    const columns = computed(() => structure.value.columns)
    const itemsAggregate = computed(() => {
        const columnMap = structure.value.columns
        if (!columnMap) return items.value

        const output = items.value.map((item: any) => getAggregatedItem(item, columnMap))

        return output
    })
    const sort = (key: string) => {
        const sortKey = structure.value.sort?.[key] ?? key
        const current = sortDirections.value[sortKey] || "asc"
        sortDirections.value = { [sortKey]: current === "asc" ? "desc" : "asc" }
    }
    const tableKeys = computed(() =>
        columns.value ? Object.keys(columns.value) : Object.keys(structure.value.fields)
    )
    const isAllSelected = computed(
        () => items.value.length > 0 && selectedItems.value.length === items.value.length
    )

    // Functions
    const addItem = () => {
        resetValue()
        showNewItemModal.value = true
    }

    const resetValue = () => {
        item.value = {}
        itemClone.value = {}
        selectedItems.value = []
    }

    const toggleSelectAll = () => {
        if (isAllSelected.value) {
            selectedItems.value = []
        } else {
            selectedItems.value = items.value.map((item: any) => item.id)
        }
    }

    const confirmBulkDelete = () => {
        confirmation.value = true
    }
    const itemValue = (item: any, key: string) => {
        if (key === "measurement_unit") return item.measurement_unit
        if (key === "price") return currency(item.price)

        return item[key]
    }

    const confirm = (selectedItem: any) => {
        selectedItems.value = []
        item.value = { ...selectedItem }
        itemClone.value = { ...selectedItem }
        confirmation.value = true
    }
    const remove = async () => {
        if (selectedItems.value.length > 0) {
            for (const id of selectedItems.value) {
                await store.dispatch("deleteItemAction", {
                    id,
                    routeName: route.name,
                })
            }
        } else {
            await store.dispatch("deleteItemAction", {
                id: item.value.id,
                routeName: route.name,
            })
        }
        confirmation.value = false
        resetValue()
    }
    const add = async () => {
        await store.dispatch("createItemAction", { item: item.value, routeName: route.name })
        item.value = {}
        showNewItemModal.value = false
    }
    const editItem = (id: any) => {
        edit.value = true
        const itemValue = items.value.find((i: any) => i.id === Number(id))
        item.value = { ...itemValue }
        itemClone.value = { ...itemValue }
    }
    const save = async () => {
        await store.dispatch("updateItemAction", {
            item: item.value,
            itemClone: itemClone.value,
            routeName: route.name,
        })
        edit.value = false
        resetValue()
    }

    const viewOrders = (item: any) => {
        router.push({
            name: "orders",
            query: {
                // @ts-ignore
                filters: { customer: item.id },
            },
        })
    }
    const newOrder = async () => {
        const { data } = await store.dispatch("orderCreate", {
            customer_id: item.value.id,
        })

        router.push({ name: "orderDetails", params: { id: data.id } })
    }

    watch(orderBy, (newVal) => {
        const query = { ...route.query, orderBy: newVal || undefined }
        if (route.query.orderBy === newVal) {
            router.replace({ query: { ...query, _ts: Date.now() } })
        } else {
            router.replace({ query })
        }
    })

    watch(
        route,
        () => {
            fetchData()
        },
        { immediate: true }
    )
</script>
