import * as Actions from "@store/modules/stats/stats-actions"
import * as Mutations from "@store/modules/stats/stats-mutations"
import {
    format,
    parseISO,
    isWithinInterval,
    subDays,
    startOfYear,
    startOfMonth,
    subMonths,
} from "date-fns"
import { sum } from "@store/modules/orders/order-helpers"
import type { GetterTree, Module } from "vuex"

const state: StatsState = {
    data: [],
    totals: {
        potentiallyDueBalance: null,
        dueBalance: null,
    },
}

const getters: GetterTree<StatsState, RootState> = {
    ordersByInterval:
        (state) =>
            ({
                intervalType = "thisMonth",
                customRange,
            }: { intervalType?: string; customRange?: { start: string; end: string } } = {}) => {
                if (!state.data.length) return []

                const now = new Date()
                const startOfThisMonth = startOfMonth(now)
                const startOfLastMonth = subMonths(startOfThisMonth, 1)
                const startOfThisQuarter = new Date(now.getFullYear(), Math.floor(now.getMonth() / 3) * 3, 1)
                const startOfLastQuarter = subMonths(startOfThisQuarter, 3)

                const predefinedIntervals: Record<string, { start: Date; end: Date }> = {
                    thisWeek: { start: subDays(now, now.getDay()), end: now },
                    lastWeek: {
                        start: subDays(now, now.getDay() + 7),
                        end: subDays(now, now.getDay() + 1),
                    },
                    thisMonth: { start: startOfThisMonth, end: now },
                    lastMonth: {
                        start: startOfLastMonth,
                        end: subDays(startOfThisMonth, 1),
                    },
                    thisQuarter: { start: startOfThisQuarter, end: now },
                    lastQuarter: {
                        start: startOfLastQuarter,
                        end: subDays(startOfThisQuarter, 1),
                    },
                    thisYear: { start: startOfYear(now), end: now },
                }

                let interval
                if (intervalType in predefinedIntervals) {
                    interval = predefinedIntervals[intervalType]
                } else if (customRange?.start && customRange?.end) {
                    interval = {
                        start: parseISO(customRange.start),
                        end: parseISO(customRange.end),
                    }
                } else {
                    return []
                }

                return state.data
                    .filter((item: Order) => {
                        try {
                            const paymentDate = parseISO(item.payment_date)
                            return isWithinInterval(paymentDate, interval)
                        } catch (err) {
                            console.error(err)
                            return false
                        }
                    })
                    .map((order) => ({
                        ...order,
                        payment_date: format(parseISO(order.payment_date), "dd-MMM-yyyy"),
                    }))
            },

    ordersByMonth: (state): Record<string, Item[]> =>
        state.data.length
            ? state.data.reduce((out: Record<string, Item[]>, item: Item) => {
                try {
                    const paymentDate = parseISO(item.payment_date)
                    const date = format(paymentDate, "dd-MMM-yyyy")
                    const key = format(paymentDate, "MMM-yyyy")
                    const clonedItem = { ...item, payment_date: date }

                    if (!out[key]) out[key] = []
                    out[key].push(clonedItem)
                } catch (err) {
                    console.error(err)
                }
                return out
            }, {})
            : {},

    orderMonthlyTotals: (state, getters) => {
        const monthlyEntries = Object.entries(getters.ordersByMonth) as [string, Item[]][]

        return monthlyEntries.reduce((out: Record<string, StatsTotals>, [key, value]) => {
            const totals: StatsTotals = {
                materialsTotal: sum(value, "materialsTotal"),
                servicesTotal: sum(value, "servicesTotal"),
                grandTotal: sum(value, "grand_total"),
                vatGrandTotal: sum(value, "vat_grand_total"),
                expenses: sum(value, "expenses"),
                paymentsTotal: sum(value, "paymentsTotal"),
                productsSold: sum(value, "productsSold"),
                productsTotal: sum(value, "productsTotal"),
                aveUnitPrice: 0,
            }

            if (totals.productsSold) {
                totals.aveUnitPrice = Number(
                    (totals.productsTotal / totals.productsSold).toFixed(2)
                )
            }

            out[key] = totals
            return out
        }, {})
    },
}

const statsModule: Module<StatsState, RootState> = {
    state,
    actions: Actions.default,
    mutations: Mutations.default,
    getters,
}

export default statsModule
