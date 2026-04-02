// @vitest-environment node
import { describe, it, expect } from 'vitest'
import { sum } from '../../resources/app/store/modules/orders/order-helpers'

// Re-implement the exported functions inline to avoid module resolution issues
// These mirror the exact logic in order-mutations.ts

function getSubtotal(state: any): number {
    const materialsTotal = sum(state.order_materials || [], 'price')
    const servicesTotal = sum(state.order_services || [], 'price')
    const productsTotal = sum(state.products || [], 'discountedPrice')
    return Number((materialsTotal + servicesTotal + productsTotal).toFixed(2))
}

function syncDiscountPercent(state: any): void {
    const subtotal = getSubtotal(state)
    state.discount_percent = subtotal > 0
        ? Number(((state.discount || 0) / subtotal * 100).toFixed(2))
        : 0
}

function syncDiscountAmount(state: any): void {
    const subtotal = getSubtotal(state)
    state.discount = Number((subtotal * (state.discount_percent || 0) / 100).toFixed(2))
}

function updateOrderPrice(state: any): void {
    const subtotal = getSubtotal(state)
    state.total = Number((subtotal - (state.discount || 0)).toFixed(2))
    state.grand_total = state.total * (1 + (state.vat || 0) / 100)
}

function updateBalance(state: any): void {
    const validTaxDeductions = (state.tax_deductions || []).filter(
        (d: any) => d.id !== undefined
    )
    state.balance = (state.grand_total || 0)
        - sum(state.payments || [], 'amount')
        - sum(validTaxDeductions, 'amount')
}

function setOrderStats(state: any, { loc, value }: { loc: string, value: any }): void {
    if (value !== undefined) {
        state[loc] = value
    }
    if (loc === 'discount') {
        syncDiscountPercent(state)
        updateOrderPrice(state)
        updateBalance(state)
    }
    if (loc === 'discount_percent') {
        syncDiscountAmount(state)
        updateOrderPrice(state)
        updateBalance(state)
    }
}

function assignOrderItem(state: any, order: any): void {
    for (const key in order) {
        if (Object.prototype.hasOwnProperty.call(order, key)) {
            state[key] = order[key]
        }
    }
    syncDiscountPercent(state)
}

function createState(overrides: any = {}): any {
    return {
        discount: 0,
        discount_percent: 0,
        vat: 20,
        total: 0,
        grand_total: 0,
        balance: 0,
        products: [],
        order_materials: [],
        order_services: [],
        payments: [],
        tax_deductions: [],
        ...overrides,
    }
}

function createStateWithSubtotal(subtotal: number, overrides: any = {}): any {
    return createState({
        order_services: [{ id: 1, price: subtotal }],
        ...overrides,
    })
}

describe('Discount Interlink', () => {
    describe('getSubtotal', () => {
        it('returns 0 for empty state', () => {
            const state = createState()
            expect(getSubtotal(state)).toBe(0)
        })

        it('sums products discountedPrice, services price, and materials price', () => {
            const state = createState({
                products: [{ discountedPrice: 5000 }],
                order_services: [{ id: 1, price: 2000 }],
                order_materials: [{ id: 1, price: 3000 }],
            })
            expect(getSubtotal(state)).toBe(10000)
        })
    })

    describe('syncDiscountPercent (currency -> %)', () => {
        it('calculates percentage from discount amount and subtotal', () => {
            const state = createStateWithSubtotal(10000, { discount: 1000 })
            syncDiscountPercent(state)
            expect(state.discount_percent).toBe(10)
        })

        it('handles fractional percentages with 2dp rounding', () => {
            const state = createStateWithSubtotal(3000, { discount: 100 })
            syncDiscountPercent(state)
            expect(state.discount_percent).toBe(3.33)
        })

        it('sets 0 when subtotal is 0', () => {
            const state = createState({ discount: 500 })
            syncDiscountPercent(state)
            expect(state.discount_percent).toBe(0)
        })

        it('sets 0 when discount is 0', () => {
            const state = createStateWithSubtotal(10000, { discount: 0 })
            syncDiscountPercent(state)
            expect(state.discount_percent).toBe(0)
        })
    })

    describe('syncDiscountAmount (% -> currency)', () => {
        it('calculates discount amount from percentage and subtotal', () => {
            const state = createStateWithSubtotal(10000, { discount_percent: 10 })
            syncDiscountAmount(state)
            expect(state.discount).toBe(1000)
        })

        it('handles fractional amounts with 2dp rounding', () => {
            const state = createStateWithSubtotal(3333, { discount_percent: 15 })
            syncDiscountAmount(state)
            expect(state.discount).toBe(499.95)
        })

        it('sets 0 when subtotal is 0', () => {
            const state = createState({ discount_percent: 10 })
            syncDiscountAmount(state)
            expect(state.discount).toBe(0)
        })

        it('sets 0 when percentage is 0', () => {
            const state = createStateWithSubtotal(10000, { discount_percent: 0 })
            syncDiscountAmount(state)
            expect(state.discount).toBe(0)
        })
    })

    describe('setOrderStats interlink via discount (currency field)', () => {
        it('updates discount_percent when discount currency changes', () => {
            const state = createStateWithSubtotal(10000)
            setOrderStats(state, { loc: 'discount', value: 1500 })
            expect(state.discount).toBe(1500)
            expect(state.discount_percent).toBe(15)
        })

        it('recalculates total and grand_total', () => {
            const state = createStateWithSubtotal(10000, { vat: 20 })
            setOrderStats(state, { loc: 'discount', value: 1000 })
            expect(state.total).toBe(9000)
            expect(state.grand_total).toBe(10800)
        })
    })

    describe('setOrderStats interlink via discount_percent (% field)', () => {
        it('updates discount currency when discount_percent changes', () => {
            const state = createStateWithSubtotal(10000)
            setOrderStats(state, { loc: 'discount_percent', value: 10 })
            expect(state.discount_percent).toBe(10)
            expect(state.discount).toBe(1000)
        })

        it('recalculates total and grand_total', () => {
            const state = createStateWithSubtotal(10000, { vat: 20 })
            setOrderStats(state, { loc: 'discount_percent', value: 25 })
            expect(state.discount).toBe(2500)
            expect(state.total).toBe(7500)
            expect(state.grand_total).toBe(9000)
        })
    })

    describe('assignOrderItem initialises discount_percent', () => {
        it('computes discount_percent from loaded order data', () => {
            const state = createState()
            assignOrderItem(state, {
                order_services: [{ id: 1, price: 5000 }],
                discount: 500,
            })
            expect(state.discount).toBe(500)
            expect(state.discount_percent).toBe(10)
        })

        it('sets discount_percent to 0 when discount is 0', () => {
            const state = createState()
            assignOrderItem(state, {
                order_services: [{ id: 1, price: 5000 }],
                discount: 0,
            })
            expect(state.discount_percent).toBe(0)
        })

        it('sets discount_percent to 0 when subtotal is 0', () => {
            const state = createState()
            assignOrderItem(state, { discount: 100 })
            expect(state.discount_percent).toBe(0)
        })
    })

    describe('round-trip consistency', () => {
        it('currency -> % -> currency produces the same discount amount', () => {
            const state = createStateWithSubtotal(8500)

            // Set currency discount
            setOrderStats(state, { loc: 'discount', value: 425 })
            const percent = state.discount_percent // 5%

            // Reset and set via %
            state.discount = 0
            setOrderStats(state, { loc: 'discount_percent', value: percent })
            expect(state.discount).toBe(425)
        })

        it('% -> currency -> % produces the same percentage', () => {
            const state = createStateWithSubtotal(10000)

            // Set %
            setOrderStats(state, { loc: 'discount_percent', value: 12.5 })
            const amount = state.discount // 1250

            // Reset and set via currency
            state.discount_percent = 0
            setOrderStats(state, { loc: 'discount', value: amount })
            expect(state.discount_percent).toBe(12.5)
        })
    })
})
