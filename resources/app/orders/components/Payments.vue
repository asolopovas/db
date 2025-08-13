<template>
    <order-components @addItem="addItem = true">
        <template #title>Payments</template>
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
            class="payment"
            @close="reset"
        >
            <template #title>{{ mode ? "Edit" : "Add" }} Payment</template>
            <div class="frame-add mb-4">
                <component
                    v-for="(value, key) in paymentsModal.items"
                    :key="key"
                    :is="value.type"
                    :loc="`${paymentsModal.parent}.${key}`"
                    :set="paymentsModal.setter"
                    :format="value.format"
                    :cast="value.cast"
                    :alerts="alerts"
                />
                <action-buttons
                    class="flex justify-end gap-2 pt-4"
                    :mode="mode"
                    :buttons="buttons"
                    @save="save"
                    @cancel="reset"
                    @add="add"
                />
            </div>
        </modal>
        <div
            v-if="payments.length"
            class="table-wrap"
        >
            <div class="table-container">
                <table class="table-order-edit">
                    <thead>
                        <tr>
                            <th
                                class="t-col t-col-date"
                                scope="col"
                            >
                                Date
                            </th>
                            <th class="t-col t-col-description">Description</th>
                            <th class="t-col t-col-amount">Amount</th>
                            <th class="t-col t-col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="payment in payments"
                            :key="payment.id"
                        >
                            <td
                                data-label="Date"
                                class="t-col t-col-date"
                            >
                                {{ $filters.dateFormat(payment.date, "dd MMM yyyy") }}
                            </td>
                            <td
                                data-label="Description"
                                class="t-col t-col-description"
                            >
                                {{ payment.description }}
                            </td>
                            <td
                                data-label="Amount"
                                class="t-col t-col-amount"
                            >
                                {{ $filters.currency(payment.amount) }}
                            </td>
                            <td
                                data-label="Action"
                                class="t-col t-col-action"
                            >
                                <view-buttons
                                    class="flex justify-end gap-2"
                                    @remove="promptDeletion(payment)"
                                    @edit="edit(payment)"
                                />
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td
                                colspan="3"
                                class="t-col text-right"
                            >
                                Total: {{ currency(paymentsTotal) }}
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
    import { currency } from "@root/resources/app/lib/global-helpers"
    import buttons from "./buttons"
    import { useConfirmation } from "@lib/useConfirmation"
    const { confirmationModal, promptDeletion, confirmDeletion, cancelDeletion } = useConfirmation()

    interface Payment {
        id: string
        description: string
        date: string
        amount: number
    }

    const store = useStore()
    const addItem = ref(false)
    const mode = ref("add")
    const alerts = ref<string[]>([])

    const payments = computed<Payment[]>(() => store.state.order.payments)
    const payment = computed(() => store.state.order.payment)
    const paymentsTotal = computed(() =>
        payments.value.reduce((total, pay) => total + pay.amount, 0)
    )

    const paymentsModal = ref<StoreUpdater>({
        setter: "setPaymentItem",
        parent: "order.payment",
        items: {
            description: { type: "input-field" },
            date: { type: "input-field", cast: "date" },
            amount: { type: "input-field" },
        },
    })

    function reset() {
        addItem.value = false
        mode.value = "add"
        store.commit("resetEl", "payment")
    }

    function add() {
        alerts.value = validate(payment.value, ["amount", "date", "description"])
        if (!alerts.value.length) {
            store
                .dispatch("addEl", {
                    el: {
                        ...payment.value,
                        amount: Number(payment.value.amount.replace(/[^0-9.\-]+/g, "")),
                        order_id: store.state.order.id,
                    },
                    endpoint: "payment",
                })
                .then(reset)
        }
    }

    function edit(paymentItem: Payment) {
        addItem.value = true
        mode.value = "edit"
        store.commit("editEl", { loc: "payment", el: paymentItem })
    }

    function save() {
        store.dispatch("saveEl", { el: payment.value, endpoint: "payment" }).then(reset)
    }

    function remove() {
        confirmDeletion(async (paymentItem) => {
            store.dispatch("removeEl", { loc: "payments", el: paymentItem })
        })
    }

    function validate(data: Record<string, any>, requiredFields: string[]): string[] {
        return requiredFields.filter((field) => !data[field])
    }
</script>
