<template>
    <order-components @addItem="addItem = true">
        <template #title>Materials</template>
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
            <template #title>{{ $filters.startCase(mode) }} Material</template>
            <component
                v-for="(value, key) in materialModal.items"
                :key="key"
                class="mb-3"
                :is="value.type"
                :cast="value.cast"
                :loc="`${materialModal.parent}.${key}`"
                :set="materialModal.setter"
                :format="value.format"
                :alerts="alerts"
            />

            <span class="mt-2"><b>Sub-Total: </b>{{ currency(materialPrice) }}</span>

            <action-buttons
                class="flex justify-end gap-2 pt-4"
                :mode="mode"
                :buttons="buttons"
                @save="save"
                @cancel="reset"
                @add="add"
            />
        </modal>
        <div
            v-if="materials.length"
            class="table-wrap"
        >
            <div class="table-container">
                <table class="table-order-edit">
                    <thead>
                        <tr>
                            <th class="t-col t-col-name">Name</th>
                            <th class="t-col t-col-quantity">Quantity</th>
                            <th class="t-col t-col-unit-price">Unit Price</th>
                            <th class="t-col t-col-price">Price</th>
                            <th class="t-col t-col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="material in materials"
                            :key="material.id"
                        >
                            <td
                                data-label="Name"
                                class="t-col t-col-name"
                            >
                                {{ material.name || '' }}
                            </td>
                            <td
                                data-label="Quantity"
                                class="t-col t-col-quantity"
                            >
                                {{ material.quantity }} units
                            </td>
                            <td
                                data-label="Unit Price"
                                class="t-col t-col-unit-price"
                            >
                                {{ $filters.currency(material.unit_price) }}
                            </td>
                            <td
                                data-label="Price"
                                class="t-col t-col-price"
                            >
                                {{ $filters.currency(material.price) }}
                            </td>
                            <td
                                data-label="Action"
                                class="t-col t-col-action"
                            >
                                <view-buttons
                                    class="flex justify-end gap-2"
                                    @remove="() => promptDeletion(material)"
                                    @edit="edit(material)"
                                />
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td
                                colspan="4"
                                class="t-col text-right"
                            >
                                Total: {{ currency(materialsTotal) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div
            v-else
            class="text-center"
        >
            No Materials Present
        </div>
    </order-components>
</template>

<script setup lang="ts">
    import { ref, computed } from "vue"
    import { useStore } from "vuex"
    import { useRoute } from "vue-router"
    import { currency } from "@root/resources/app/lib/global-helpers"

    import buttons from "./buttons"
    import { useConfirmation } from "@lib/useConfirmation"
    const { confirmationModal, promptDeletion, confirmDeletion, cancelDeletion } = useConfirmation()

    interface Material {
        id: string
        name: string
        quantity: number
        unit_price: number
        price: number
    }

    const store = useStore()
    const route = useRoute()
    const addItem = ref(false)
    const editMode = ref(false)
    const mode = ref("add")
    const saved = ref(false)
    const alerts = ref<string[]>([])

    const materials = computed<Material[]>(() => store.state.order.order_materials)
    const material = computed<Record<string, any>>(() => store.state.order.order_material)
    const materialPrice = computed(() => material.value.quantity * material.value.unit_price)
    const materialsTotal = computed(() =>
        materials.value.reduce(
            (total: number, mat: Material) => total + mat.unit_price * mat.quantity,
            0
        )
    )
    const materialModal = ref<StoreUpdater>({
        setter: "setMaterialItem",
        parent: "order.order_material",
        items: {
            material: {
                type: "input-search",
                format: (item: any) =>
                    `${item.name}${item.price ? " - " + currency(item.price) : ""}`,
            },
            quantity: {
                type: "input-field",
                cast: "number",
            },
            unit_price: {
                type: "input-field",
                cast: "number",
            },
        },
    })

    function reset() {
        addItem.value = false
        editMode.value = false
        saved.value = false
        store.commit("resetEl", "order_material")
    }

    async function add() {
        alerts.value = validate(material.value, ["material", "quantity"])
        if (alerts.value.length === 0) {
            await store.dispatch("addEl", {
                el: {
                    order_id: route.params.id,
                    material_id: material.value.material.id,
                    unit_price: material.value.unit_price,
                    quantity: material.value.quantity,
                },
                endpoint: "order_material",
            })
            reset()
        }
    }

    function edit(materialItem: Material) {
        mode.value = "edit"
        store.commit("editEl", { loc: "order_material", el: materialItem })
        editMode.value = true
        addItem.value = true
    }

    async function save() {
        await store.dispatch("saveEl", {
            el: {
                material_id: material.value.material.id,
                unit_price: material.value.unit_price,
                quantity: material.value.quantity,
            },
            endpoint: "order_material",
        })
        saved.value = true
        reset()
    }

    function remove() {
        confirmDeletion(async (materialItem) => {
            store.dispatch("removeEl", { loc: "order_materials", el: materialItem })
        })
    }

    function validate(data: Record<string, any>, requiredFields: string[]): string[] {
        return requiredFields.filter((field: string) => !data[field])
    }
</script>
