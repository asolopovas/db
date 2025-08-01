import { findIndex, cloneDeep } from 'lodash-es'
import { sum } from '../order-helpers'


interface Identifiable {
    id: number
}
interface OrderPayload<T = any> {
    loc: keyof T
    el?: T[keyof T]
    value?: T[keyof T]
}

function addEl<K extends keyof OrderState>(
    state: OrderState,
    { loc, el }: GenericOrderPayload<OrderState, K>
): void {
    const target = state[loc]
    if (Array.isArray(target)) {
        target.push(el as typeof target[number])
    }
}

function editEl<K extends keyof OrderState>(
    state: OrderState,
    { loc, el }: OrderPayload<OrderState>
): void {
    if (el !== undefined) {
        state[loc] = cloneDeep(el) as OrderState[K]
    }
}

function saveEl<K extends keyof OrderState>(
    state: OrderState,
    { loc, el }: GenericOrderPayload<OrderState, K>
): void {
    const target = state[loc]
    if (Array.isArray(target) && el && typeof el === 'object' && 'id' in el) {
        const index = findIndex(target, (item: Identifiable) => item.id === el.id)
        if (index !== -1) {
            target[index] = el as typeof target[number]
        } else {
            throw new Error(`Element with id ${ el.id } not found in ${ String(loc) }.`)
        }
    } else {
        throw new Error(`Cannot save element. ${ String(loc) } is not an array.`)
    }
}

function removeEl<K extends keyof OrderState>(
    state: OrderState,
    { loc, el }: GenericOrderPayload<OrderState, K>
): void {
    const target = state[loc]
    if (Array.isArray(target) && el && typeof el === 'object' && 'id' in el) {
        const index = target.findIndex((item: Identifiable) => item.id === (el as Identifiable).id)
        if (index !== -1) {
            target.splice(index, 1)
        } else {
            throw new Error(`Element not found in ${ String(loc) }.`)
        }
    } else {
        throw new Error(`Cannot remove element. ${ String(loc) } is not an array.`)
    }
}

function resetEl<K extends keyof OrderState>(
    state: OrderState,
    loc: K
): void {
    const target = state[loc]
    if (typeof target === 'object' && target !== null) {
        for (const key in target) {
            if (Object.prototype.hasOwnProperty.call(target, key)) {
                const value = target[key]
                if (typeof value === 'string' || typeof value === 'number') {
                    target[key] = ""
                } else if (typeof value === 'object' && value !== null) {
                    target[key] = null
                } else {
                    target[key] = null
                }
            }
        }
    } else {
        throw new Error(`Cannot reset. ${ String(loc) } is not an object.`)
    }
}

function setOrderStats<K extends keyof OrderState>(
    state: OrderState,
    { loc, value }: OrderPayload<OrderState>
): void {
    if (value !== undefined) {
        state[loc] = value as OrderState[K]
    }
    if (loc === 'discount') {
        updateOrderPrice(state)
        updateBalance(state)
    }
}

function updateOrderPrice(state: OrderState): void {
    const materialsTotal = sum(state.order_materials || [], 'price')
    const servicesTotal = sum(state.order_services || [], 'price')
    const productsTotal = sum(state.products || [], 'discountedPrice')
    state.total = (materialsTotal + servicesTotal + productsTotal) - (state.discount || 0)
    state.total = Number(state.total.toFixed(2))
    state.grand_total = state.total * (1 + (state.vat || 0) / 100)
}

function updateBalance(state: OrderState): void {
    const validTaxDeductions = (state.tax_deductions || []).filter(
        (deduction): deduction is TaxDeduction & { id: number } => deduction.id !== undefined
    )

    state.balance = (state.grand_total || 0)
        - sum(state.payments || [], 'amount')
        - sum(validTaxDeductions, 'amount')
}

function assignOrderItem(state: OrderState, order: Partial<OrderState>): void {
    for (const key in order) {
        if (Object.prototype.hasOwnProperty.call(order, key)) {
            state[key] = order[key]
        }
    }
}

function setDeductionItem<K extends keyof TaxDeduction>(
    state: OrderState,
    { loc, value }: { loc: K; value: TaxDeduction[K] }
): void {
    if (state.tax_deduction) {
        state.tax_deduction[loc] = value
    }
}

function setExpenseItem<K extends keyof Expense>(
    state: OrderState,
    { loc, value }: { loc: K; value: Expense[K] }
): void {
    const expense = state.expense

    if (!expense) {
        throw new Error('Expense is undefined')
    }

    if (loc === 'category' && typeof value === 'object' && value !== null && 'name' in value) {
        expense[loc] = (value as any).name
    } else {
        expense[loc] = value
    }
}

export {
    updateBalance,
    setOrderStats,
    setExpenseItem,
    assignOrderItem,
    updateOrderPrice,
    setDeductionItem,
    resetEl,
    addEl,
    editEl,
    removeEl,
    saveEl,
}
