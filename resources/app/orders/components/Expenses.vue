<template>
    <order-components @addItem="addItem = true">
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
            <template #title>{{ editMode ? "Edit" : "Add" }} Expense</template>
            <div class="frame-add mb-4">
                <component
                    v-for="(value, key) in expensesModal.items"
                    :key="key"
                    :is="value.type"
                    :loc="`${expensesModal.parent}.${key}`"
                    :set="expensesModal.setter"
                    :selectItems="selectItems"
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
            v-if="expenses.length"
            class="table-wrap"
        >
            <div class="table-container">
                <table class="table-order-edit">
                    <thead>
                        <tr>
                            <th class="t-col t-col-date">Date</th>
                            <th class="t-col t-col-category">Category</th>
                            <th class="t-col t-col-details">Details</th>
                            <th class="t-col t-col-amount">Amount</th>
                            <th class="t-col t-col-action">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr
                            v-for="expense in expenses"
                            :key="expense.id"
                        >
                            <td
                                data-label="Date"
                                class="t-col t-col-date"
                            >
                                {{ $filters.dateFormat(expense.created_at, "dd MMM yyyy HH:mm") }}
                            </td>
                            <td
                                data-label="Category"
                                class="t-col t-col-category"
                            >
                                {{ expense.category }}
                            </td>
                            <td
                                data-label="Details"
                                class="t-col t-col-details"
                            >
                                {{ expense.details }}
                            </td>
                            <td
                                data-label="Amount"
                                class="t-col t-col-amount"
                            >
                                {{ currency(expense.amount) }}
                            </td>
                            <td
                                data-label="Action"
                                class="t-col t-col-action"
                            >
                                <view-buttons
                                    class="flex justify-end gap-2"
                                    @remove="promptDeletion(expense)"
                                    @edit="edit(expense)"
                                />
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td
                                class="t-col text-right"
                                colspan="4"
                            >
                                Total: {{ $filters.currency(totals) }}
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

    interface Expense {
        id: string
        created_at: string
        category: string
        details: string
        amount: number
    }

    const store = useStore()
    const addItem = ref(false)
    const alerts = ref<string[]>([])
    const editMode = ref(false)
    const mode = ref("add")

    const expenses = computed<Expense[]>(() => store.state.order.expenses)
    const expense = computed(() => store.state.order.expense)
    const totals = computed(() => expenses.value.reduce((total, exp) => total + exp.amount, 0))

    const selectItems = [
        { name: "Transport" },
        { name: "Materials" },
        { name: "Flooring Price" },
        { name: "Finishing" },
        { name: "Wages" },
        { name: "Commissions" },
    ]

    const expensesModal = ref<StoreUpdater>({
        setter: "setExpenseItem",
        parent: "order.expense",
        items: {
            category: { type: "select-field" },
            amount: { type: "input-field" },
            details: { type: "input-field" },
        },
    })

    function reset() {
        addItem.value = false
        editMode.value = false
        store.commit("resetEl", "expense")
    }

    function add() {
        alerts.value = validate(expense.value, ["category", "amount"])
        if (!alerts.value.length) {
            store
                .dispatch("addEl", {
                    el: {
                        ...expense.value,
                        amount: Number(expense.value.amount.replace(/[^0-9.\-]+/g, "")),
                    },
                    endpoint: "expense",
                })
                .then(reset)
        }
    }

    function edit(expenseItem: Expense) {
        addItem.value = true
        editMode.value = true
        store.commit("editEl", { loc: "expense", el: expenseItem })
    }

    function save() {
        store
            .dispatch("saveEl", {
                el: expense.value,
                endpoint: "expense",
            })
            .then(reset)
    }

    function remove() {
        confirmDeletion(async (expenseItem) => {
            store.dispatch("removeEl", { loc: "expenses", el: expenseItem })
        })
    }

    function validate(data: Record<string, any>, requiredFields: string[]): string[] {
        return requiredFields.filter((field) => !data[field])
    }
</script>
