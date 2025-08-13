<template>
    <order-components @addItem="addItem = true">
        <template #title>Tax Deductions</template>
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
            <template #title>{{ $filters.startCase(mode) }} Deduction</template>
            <component
                v-for="(value, key) in deductionsModal.items"
                :key="key"
                :is="value.type"
                :loc="`${deductionsModal.parent}.${key}`"
                :set="deductionsModal.setter"
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
        </modal>
        <div
            v-if="taxDeductions.length"
            class="table-wrap"
        >
            <div class="table-container">
                <table class="table-order-edit">
                    <thead>
                        <tr>
                            <th class="t-col t-col-date">Date</th>
                            <th class="t-col t-col-ref">Ref</th>
                            <th class="t-col t-col-amount">Amount</th>
                            <th class="t-col t-col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="deduction in taxDeductions"
                            :key="deduction.id"
                        >
                            <td
                                data-label="Date"
                                class="t-col t-col-date"
                            >
                                {{ $filters.dateFormat(deduction.date, "dd MM yyyy") }}
                            </td>
                            <td
                                data-label="Ref"
                                class="t-col t-col-name"
                            >
                                {{ deduction.ref }}
                            </td>
                            <td
                                data-label="Amount"
                                class="t-col t-col-amount text-right"
                            >
                                {{ $filters.currency(deduction.amount) }}
                            </td>
                            <td
                                data-label="Action"
                                class="t-col text-right"
                            >
                                <view-buttons
                                    class="flex justify-end gap-2"
                                    @remove="() => promptDeletion(deduction)"
                                    @edit="edit(deduction)"
                                />
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td
                                class="t-col text-right"
                                colspan="3"
                            >
                                Total: {{ $filters.currency(taxDeductionsTotal) }}
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
    import { dateFormat } from "@root/resources/app/lib/global-helpers"
    import buttons from "./buttons"
    import { useConfirmation } from "@lib/useConfirmation"
    const { confirmationModal, promptDeletion, confirmDeletion, cancelDeletion } = useConfirmation()

    interface TaxDeduction {
        id: string
        date: string
        ref: string
        amount: number
    }

    const store = useStore()
    const addItem = ref(false)
    const mode = ref("add")
    const alerts = ref<string[]>([])

    const taxDeductions = computed<TaxDeduction[]>(() => store.state.order.tax_deductions)
    const taxDeduction = computed(() => store.state.order.tax_deduction)
    const taxDeductionsTotal = computed(() =>
        taxDeductions.value.reduce((total, deduction) => total + deduction.amount, 0)
    )

    const deductionsModal = ref<StoreUpdater>({
        setter: "setDeductionItem",
        parent: "order.tax_deduction",
        items: {
            date: {
                type: "input-field",
                cast: "date",
                format: (item: any) => dateFormat(item, "yyyy-MM-dd"),
            },
            ref: { type: "input-field" },
            amount: { type: "input-field", cast: "number" },
        },
    })

    function reset() {
        addItem.value = false
        mode.value = "add"
        store.commit("resetEl", "tax_deduction")
    }

    async function add() {
        alerts.value = validate(taxDeduction.value, ["amount", "ref", "date"])

        if (!alerts.value.length) {
            await store.dispatch("addEl", {
                el: {
                    ref: taxDeduction.value.ref,
                    amount: taxDeduction.value.amount,
                    date: taxDeduction.value.date,
                    order_id: store.state.order.id,
                },
                endpoint: "tax_deduction",
            })
            reset()
        }
    }

    function edit(deduction: TaxDeduction) {
        addItem.value = true
        mode.value = "edit"
        store.commit("editEl", { loc: "tax_deduction", el: deduction })
    }

    function save() {
        store
            .dispatch("saveEl", {
                el: {
                    ref: taxDeduction.value.ref,
                    amount: taxDeduction.value.amount,
                    date: taxDeduction.value.date,
                },
                endpoint: "tax_deduction",
            })
            .then(reset)
    }

    function remove() {
        confirmDeletion(async (deduction) => {
            store.dispatch("removeEl", { loc: "tax_deductions", el: deduction })
        })
    }

    function validate(data: Record<string, any>, requiredFields: string[]): string[] {
        return requiredFields.filter((field) => !data[field])
    }
</script>
