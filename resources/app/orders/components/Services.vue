<template>
    <order-components @addItem="addItem = true">
        <template #title>Services</template>
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
            <template #title>{{ $filters.startCase(mode) }} Service</template>
            <component
                v-for="(value, key) in servicesModal.items"
                :key="key"
                :is="value.type"
                :cast="value.cast"
                :loc="`${servicesModal.parent}.${key}`"
                :set="servicesModal.setter"
                :format="value.format"
                :alerts="alerts"
            />
            <span class="mt-2"><b>Sub-Total: </b>{{ servicePrice }}</span>
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
            v-if="services.length"
            class="table-wrap"
        >
            <div class="table-container">
                <table class="table-order-edit">
                    <thead>
                        <tr>
                            <th
                                scope="col"
                                class="t-col t-col-name"
                            >
                                Name
                            </th>
                            <th
                                scope="col"
                                class="t-col t-col-quantity"
                            >
                                Quantity
                            </th>
                            <th
                                scope="col"
                                class="t-col t-col-unit-price"
                            >
                                Unit Price
                            </th>
                            <th
                                scope="col"
                                class="t-col t-col-price"
                            >
                                Price
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
                            v-for="service in services"
                            :key="service.id"
                        >
                            <td
                                data-label="Name"
                                class="t-col t-col-name"
                            >
                                {{ service.service.name }}
                            </td>
                            <td
                                data-label="Quantity"
                                class="t-col t-col-quantity"
                            >
                                {{ service.quantity }} units
                            </td>
                            <td
                                data-label="Unit Price"
                                class="t-col t-col-unit-price"
                            >
                                {{ $filters.currency(service.unit_price) }} p/unit
                            </td>
                            <td
                                data-label="Price"
                                class="t-col t-col-price"
                            >
                                {{ $filters.currency(service.price) }}
                            </td>
                            <td
                                data-label="Action"
                                class="t-col t-col-action"
                            >
                                <view-buttons
                                    class="flex justify-end gap-2"
                                    @remove="() => promptDeletion(service)"
                                    @edit="edit(service)"
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
                                Total: {{ currency(servicesTotal) }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </order-components>
</template>

<script setup lang="ts">
    import { ref, computed } from "vue"
    import { useStore } from "vuex"
    import { useRoute } from "vue-router"
    import { currency } from "@lib/global-helpers"
    import buttons from "./buttons"
    import { useConfirmation } from "@lib/useConfirmation"
    const { confirmationModal, promptDeletion, confirmDeletion, cancelDeletion } = useConfirmation()

    interface Service {
        id: string
        service: { id: string; name: string }
        quantity: number
        unit_price: number
        price: number
    }

    const store = useStore()
    const route = useRoute()
    const addItem = ref(false)
    const mode = ref("add")
    const alerts = ref<string[]>([])

    const services = computed<Service[]>(() => store.state.order.order_services)
    const service = computed(() => store.state.order.order_service)
    const servicePrice = computed(() => currency(service.value.unit_price * service.value.quantity))
    const servicesTotal = computed(() =>
        services.value.reduce((total, serv) => total + serv.unit_price * serv.quantity, 0)
    )

    const servicesModal = ref<StoreUpdater>({
        setter: "setServiceItem",
        parent: "order.order_service",
        items: {
            service: {
                type: "input-search",
                format: (item: any) =>
                    `${item.name}${item.price ? " - " + currency(item.price) : ""}`,
            },
            quantity: { type: "input-field", cast: "number" },
            unit_price: { type: "input-field", cast: "number" },
            price: {
                type: "display-field",
                compute: (item: any) => item.unit_price * item.quantity,
            },
        },
    })

    function reset() {
        addItem.value = false
        mode.value = "add"
        store.commit("resetEl", "order_service")
    }

    function add() {
        alerts.value = validate(service.value, ["service", "quantity", "unit_price"])
        if (!alerts.value.length) {
            store
                .dispatch("addEl", {
                    el: {
                        order_id: route.params.id,
                        service_id: service.value.service.id,
                        unit_price: service.value.unit_price,
                        quantity: service.value.quantity,
                    },
                    endpoint: "order_service",
                })
                .then(reset)
        }
    }

    function edit(serviceItem: Service) {
        addItem.value = true
        mode.value = "edit"
        store.commit("editEl", { loc: "order_service", el: serviceItem })
    }

    function save() {
        store
            .dispatch("saveEl", {
                el: {
                    service_id: service.value.service.id,
                    unit_price: service.value.unit_price,
                    quantity: service.value.quantity,
                },
                endpoint: "order_service",
            })
            .then(reset)
    }

    function remove() {
        confirmDeletion(async (serviceItem) => {
            store.dispatch("removeEl", { loc: "order_services", el: serviceItem })
        })
    }

    function validate(data: Record<string, any>, requiredFields: string[]): string[] {
        return requiredFields.filter((field) => !data[field])
    }
</script>
