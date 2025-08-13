<template>
    <order-components @addItem="modalShow = true">
        <template #title>Products</template>
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
            v-if="modalShow"
            @close="resetProduct"
        >
            <template #title>Add Product</template>
            <div class="frame-add overflow-y-auto max-h-[500px] p-4">
                <component
                    v-for="(value, key) in productModal.items"
                    :key="key"
                    :is="value.type"
                    :set="productModal.setter"
                    :format="value.format"
                    :disabled="value.disabled"
                    :alerts="alerts"
                    :loc="`${productModal.parent}.${key}`"
                />
            </div>
            <action-buttons
                class="flex justify-end gap-2 pt-4"
                :mode="mode"
                :buttons="buttons"
                @save="saveProduct"
                @cancel="resetProduct"
                @add="addProduct"
            />
        </modal>
        <div
            v-if="products.length"
            class="table-wrap"
        >
            <div class="table-container">
                <table class="table-order-edit">
                    <thead>
                        <tr>
                            <th class="t-col w-[200px]">Name</th>
                            <th class="t-col">Specs</th>
                            <th class="t-col t-col-discount">Discount</th>
                            <th class="t-col t-col-wastage w-[100px]">Wastage</th>
                            <th class="t-col t-col-meterage">Meterage</th>
                            <th class="t-col t-col-price">Price</th>
                            <th class="t-col t-col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="product in productsView"
                            :key="product.id"
                        >
                            <td
                                v-for="field in viewFields"
                                :data-label="startCase(field)"
                                :key="field"
                                class="t-col"
                                :class="`t-col-${field === 'discountedPrice' ? 'price' : field}`"
                            >
                                {{ product[field] }}
                            </td>
                            <td
                                data-label="Action"
                                class="t-col t-col-action"
                            >
                                <view-buttons
                                    class="flex justify-end gap-2"
                                    @edit="editProduct(product.el)"
                                    @remove="() => promptDeletion(product.el)"
                                />
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td
                                colspan="6"
                                class="t-col text-right"
                            >
                                Total: {{ currency(productsTotal) }}
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
            No Products Available
        </div>
    </order-components>
</template>

<script setup lang="ts">
    import { currency, validate } from "@root/resources/app/lib/global-helpers"
    import { startCase } from "lodash-es"
    import { computed, reactive, ref } from "vue"
    import { useStore } from "vuex"
    import buttons from "./buttons"

    import { useConfirmation } from "@lib/useConfirmation"
    const { confirmationModal, promptDeletion, confirmDeletion, cancelDeletion } = useConfirmation()

    interface Product {
        id: number
        areas: any[]
        floor: any | null
        grade: any | null
        dimension: any | null
        extra: any | null
        meterage: number | null
        wastage: number | null
        wastage_rate: number | null
        unit_price: number | null
        price: number | null
    }

    const store = useStore()

    const modalShow = ref(false)
    const mode = ref("add")
    const alerts = ref<string[]>([])

    const viewFields = ["name", "specs", "discount", "wastage", "meterage", "discountedPrice"]

    const products = computed(() => store.state.order.products)
    const product = computed<Product>(() => store.state.order.product)
    const productsView = computed(() => store.getters.productsView)

    const productModal = reactive<StoreUpdater>({
        setter: "setProductComponent",
        parent: "order.product",
        items: {
            floor: {
                format: (item: any) => `${currency(item.price)} ${item.name}`,
                type: "input-search",
            },
            grade: {
                format: (item: any) => `${currency(item.price)} ${item.name}`,
                type: "input-search",
            },
            dimension: {
                format: (item: any) => {
                    let value = `${currency(item.price)} ${item.type} `
                    if (item.length) value += `${item.length}x`
                    value += `${item.width}x${item.thickness}mm`
                    return value
                },
                type: "input-search",
            },
            extra: {
                format: (item: any) => `${currency(item.price)} ${item.name}`,
                type: "input-search",
            },
            wastage_rate: { type: "input-field" },
            discount: { type: "input-field" },
            unit_price: { type: "input-field" },
            meterage: { type: "input-field" },
            price: {
                format: (item: any) => currency(item),
                type: "display-field",
            },
            discountedPrice: {
                format: (item: any) => currency(item),
                type: "display-field",
            },
        },
    })

    const productsTotal = computed(() =>
        products.value.reduce((sum: any, product: any) => sum + product.discountedPrice, 0)
    )

    function resetProduct() {
        modalShow.value = false
        mode.value = "add"

        Object.keys(productModal.items).forEach((key) => {
            if ("alert" in productModal.items[key]) productModal.items[key].alert = false
        })

        store.commit("resetProduct")
    }

    async function addProduct() {
        alerts.value = validate(product, viewFields)
        if (!alerts.value.length) {
            mode.value = "add"
            await store.dispatch("addProduct")
            resetProduct()
        }
    }

    function editProduct(product: Product) {
        store.commit("editProduct", product.id)
        modalShow.value = true
        mode.value = "edit"
        toggleMeterage()
    }

    async function saveProduct() {
        alerts.value = validate(product, viewFields)
        if (!alerts.value.length) {
            await store.dispatch("saveProduct")
            resetProduct()
        }
    }

    function remove() {
        confirmDeletion(async (product) => {
            store.dispatch("removeEl", { loc: "products", el: product })
        })
    }

    function toggleMeterage() {
        productModal.items.meterage.disabled = product.value.areas.length > 0
    }
</script>
