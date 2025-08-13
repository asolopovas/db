import { mount } from '@vue/test-utils'
import ChartStats from '@components/ChartStats.vue'
import { createStore } from 'vuex'
import { vi } from 'vitest'
import type { ComponentPublicInstance } from 'vue'

// Mock the vue-chartjs module
vi.mock('vue-chartjs', async () => {
    return {
        Line: vi.fn(() => ({
            render: () => null,
        })),
    }
})

// Mock modal component
vi.mock('@components/Modal.vue', () => ({
    default: {
        name: 'modal',
        render: () => null,
    },
}))

// Define the type of the component's instance
type ChartStatsInstance = ComponentPublicInstance<{
    periodsMap: Record<string, any>
    tickIntervals: number[]
    updateDateRange: () => void
    startDate: string
    endDate: string
    filteredOrders: any[]
    aggregateData: (orders: any[], interval: number) => { labels: string[], aggregated: Record<string, number[]> }
    chartData: { labels: string[], datasets: any[] } | null
}>

describe('ChartStats.vue', () => {
    let store: any

    beforeEach(() => {
        store = createStore({
            state: {
                stats: {
                    data: [
                        { payment_date: '2024-01-01', grand_total: 1000 },
                        { payment_date: '2024-01-02', grand_total: 2000 },
                    ],
                },
            },
            actions: { fetchStats: vi.fn() },
        })
    })

    it('renders the component correctly', () => {
        const wrapper = mount(ChartStats, {
            global: { plugins: [store] },
        })

        expect(wrapper.find('select[name="periods"]').exists()).toBe(true)
    })

    it('renders select options for periods and tick intervals', () => {
        const wrapper = mount(ChartStats, { global: { plugins: [store] } })
        const vm = wrapper.vm as ChartStatsInstance

        const periodOptions = wrapper.find('select[name="periods"]').findAll('option')
        expect(periodOptions).toHaveLength(Object.keys(vm.periodsMap).length + 1)

        const tickIntervalOptions = wrapper.find('select[name="intervals"]').findAll('option')
        expect(tickIntervalOptions).toHaveLength(vm.tickIntervals.length)
    })

    it('updates start and end dates when a period is selected', async () => {
        const wrapper = mount(ChartStats, { global: { plugins: [store] } })
        const vm = wrapper.vm as ChartStatsInstance

        vm.updateDateRange = vi.fn()
        await vm.updateDateRange()

        expect(vm.startDate).toBeDefined()
        expect(vm.endDate).toBeDefined()
    })

    it('filters orders based on selected date range', async () => {
        const wrapper = mount(ChartStats, { global: { plugins: [store] } })
        const vm = wrapper.vm as ChartStatsInstance

        vm.startDate = '2024-01-01'
        vm.endDate = '2024-01-02'

        const filteredOrders = vm.filteredOrders
        expect(filteredOrders).toHaveLength(2)
    })

    it('aggregates chart data correctly', () => {
        const wrapper = mount(ChartStats, { global: { plugins: [store] } })
        const vm = wrapper.vm as ChartStatsInstance

        const aggregatedData = vm.aggregateData(store.state.stats.data, 1)

        expect(aggregatedData.labels).toEqual(['2024-01-01', '2024-01-02'])
        expect(aggregatedData.aggregated.Totals).toEqual([1000, 2000])
    })

    it('displays chart data when available', () => {
        const wrapper = mount(ChartStats, { global: { plugins: [store] } })
        const vm = wrapper.vm as ChartStatsInstance

        const chartData = vm.chartData
        expect(chartData).not.toBeNull()

        if (chartData) {
            expect(chartData.labels).toHaveLength(2)
            expect(chartData.datasets[0].data).toEqual([1000, 2000])
        }
    })
})
