<template>
    <order-components @addItem="addItem = true">
        <template #title>Areas</template>
        <modal
            v-if="confirmationModal"
            :controls="true"
            class="send"
            @close="cancelDeletion"
            @no="cancelDeletion"
            @yes="remove"
        >
            Do you confirm deletion?
        </modal>
        <modal
            v-if="addItem"
            @close="reset"
        >
            <template #title>{{ mode }} Area</template>
            <div class="frame-add mb-4">
                <template
                    v-for="(value, key) in areaModal.items"
                    :key="key"
                >
                    <component
                        :is="value.type"
                        :loc="`${areaModal.parent}.${key}`"
                        :set="areaModal.setter"
                        :format="value.format"
                        :alerts="alerts"
                        :selectItems="products"
                    />
                </template>
            </div>
            <action-buttons
                class="flex justify-end gap-2 pt-4 pr-4"
                :mode="mode"
                :buttons="buttons"
                @save="save"
                @cancel="reset"
                @add="add"
            />
       </modal>
        <div
            v-if="productsAreas.length"
            class="mx-auto px-4"
        >
            <div
                v-for="product in productsAreas"
                :key="product.id"
                class="table-wrap"
            >
                <div class="table-container">
                    <table class="table-order-edit">
                        <thead>
                            <tr>
                                <th
                                    scope="col"
                                    class="t-col t-col-area"
                                >
                                    Area Product -
                                    <span class="normal-case"
                                        >{{ product.name }} {{ product.type }} - </span
                                    ><span class="lowercase">{{ product.specs }}</span>
                                </th>
                                <th
                                    scope="col"
                                    class="t-col t-col-meterage"
                                >
                                    Meterage
                                </th>
                                <th
                                    scope="col"
                                    class="t-col t-col-action"
                                >
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr
                                v-for="area in product.areas"
                                :key="area.id"
                            >
                                <td
                                    data-label="Name"
                                    class="t-col t-col-name"
                                >
                                    {{ area.name }}
                                </td>
                                <td
                                    data-label="Meterage"
                                    class="t-col t-col-meterage"
                                >
                                    {{ area.meterage }}m²
                                </td>
                                <td
                                    data-label="Action"
                                    class="t-col t-col-action"
                                >
                                    <view-buttons
                                        class="flex justify-end gap-2"
                                        @remove="() => promptDeletion(area, product)"
                                        @edit="edit(area, product)"
                                    />
                                </td>
                            </tr>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td
                                    colspan="2"
                                    class="t-col text-right"
                                >
                                    Total: {{ product.meterage }}m²
                                </td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
        <div
            v-else
            class="text-center"
        >
            No Areas Present
        </div>
    </order-components>
</template>

<script setup lang="ts">
    import { ref, computed } from "vue"
    import { useStore } from "vuex"
    import { useRoute } from "vue-router"
    import buttons from "./buttons"
    import { useConfirmation } from "@lib/useConfirmation"
    const { confirmationModal, promptDeletion, confirmDeletion, cancelDeletion } = useConfirmation()
    const store = useStore()
    const route = useRoute()

    const addItem = ref(false)
    const alerts = ref<string[]>([])
    const mode = ref("add")

    const productsAreas = computed<ProductAreaGetter[]>(() => store.getters.productsAreas)
    const productArea = computed(() => store.state.order.product_area)
    const products = computed(() => store.state.order.products)
    const required = ["area", "meterage", "product"]

    const areaModal = ref<StoreUpdater>({
        setter: "setProductAreaComponent",
        parent: "order.product_area",
        items: {
            area: { type: "input-search" },
            meterage: { type: "input-field" },
            product: {
                type: "select-field",
                format: (item: any) => {
                    const { thickness, width, length } = item.dimension
                    return length
                        ? `${item.floor.name} ${thickness}x${width}x${length}mm`
                        : `${item.floor.name} ${thickness}x${width}mm`
                },
            },
        },
    })

    function reset() {
        addItem.value = false
        mode.value = "add"
        store.commit("resetEl", "product_area")
    }

    async function add() {
        alerts.value = validate(productArea.value, required)

        if (alerts.value.length === 0) {
            await store.dispatch("addProductAreaAction", route.params.id)
            reset()
        }
    }

    function edit(area: ProductArea, product: ProductAreaGetter) {
        mode.value = "edit"
        store.commit("editProductArea", { area, product })
        addItem.value = true
    }

    async function save() {
        await store.dispatch("saveProductAreaAction")
        reset()
    }

    function remove() {
        confirmDeletion(async (itemToDelete, parentToDelete) => {
            await store.dispatch("removeProductAreaAction", {
                area: itemToDelete,
                product: parentToDelete,
            })
        })
    }

    function validate(data: Record<string, any>, requiredFields: string[]): string[] {
        return requiredFields.filter((field) => !data[field])
    }
</script>
