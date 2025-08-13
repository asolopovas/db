<template>
    <modal @close="closeChart">
        <template #title>{{ title }}</template>
        <div class="flex flex-wrap gap-3 w-full mb-4">
            <div class="flex">
                <select
                    name="periods"
                    aria-label="Periods"
                    v-model="currentPeriod"
                    @change="updateDateRange"
                >
                    <option value="">Select Period</option>
                    <option
                        v-for="period in filteredPeriods"
                        :value="period"
                        :key="period"
                    >
                        {{ startCase(period) }}
                    </option>
                </select>
            </div>
            <div class="flex items-center gap-3">
                <label
                    for="intervals"
                    class="font-semibold"
                    >Interval</label
                >
                <select
                    name="intervals"
                    v-model.number="currentInterval"
                >
                    <option
                        v-for="interval in dynamicIntervals"
                        :value="interval"
                        :key="interval"
                    >
                        {{ interval === 1 ? `${interval} day` : `${interval} days` }}
                    </option>
                </select>
            </div>
            <div class="flex items-center gap-3">
                <label
                    for="startDate"
                    class="font-semibold"
                    >From</label
                >
                <input
                    id="startDate"
                    type="date"
                    v-model="startDate"
                    :min="minStartDate"
                    :max="maxEndDate"
                />
                <label
                    for="endDate"
                    class="font-semibold"
                    >To</label
                >
                <input
                    id="endDate"
                    type="date"
                    v-model="endDate"
                    :min="minStartDate"
                    :max="maxEndDate"
                />
            </div>

            <div class="checkbox-menu">
                <select
                    v-model="currentPreset"
                    @change="applyPreset(currentPreset)"
                >
                    <option value="default">Default</option>
                    <option value="minimal">Minimal</option>
                    <option value="all">All Fields</option>
                </select>
            </div>
        </div>

        <div class="elements-control flex flex-wrap justify-between gap-3">
            <div
                v-for="(field, key) in availableFields"
                :key="key"
                class="toggle-container"
            >
                <label
                    class="toggle-label select-none cursor-pointer"
                    :for="`field-toggle-${key}`"
                    >{{ key }}</label
                >
                <input
                    type="checkbox"
                    class="checkbox"
                    :id="`field-toggle-${key}`"
                    v-model="checkboxOptions[key]"
                />
                <label
                    :for="`field-toggle-${key}`"
                    class="switch cursor-pointer"
                ></label>
            </div>
        </div>
        <ChartSection
            id="chart-section"
            class="mb-4"
            :data="chartData"
        />
        <h2 class="text-xl">Grand Totals</h2>
        <hr class="mb-2" />
        <div class="flex flex-wrap gap-4 mb-2">
            <div
                v-for="(field, key) in fieldsMap"
                :key="key"
                class="flex gap-3 items-center justify-center"
            >
                <div class="font-bold">{{ key }}</div>
                <div>{{ currency(summaryTotals[key] || 0) }}</div>
            </div>
        </div>
    </modal>
</template>

<script setup lang="ts">
    import { ref, computed, onMounted, watch } from "vue"
    import { useStore } from "vuex"
    import {
        format,
        parseISO,
        startOfWeek,
        endOfWeek,
        startOfMonth,
        endOfMonth,
        startOfQuarter,
        endOfQuarter,
        startOfYear,
        endOfYear,
        subDays,
        subWeeks,
        subMonths,
        subYears,
    } from "date-fns"
    import { startCase } from 'lodash-es'
    import { currency } from "@root/resources/app/lib/global-helpers"
    import ChartSection from "@components/ChartSection.vue"

    const emit = defineEmits(["close"])
    type Product = {
        name: string
        meterage: number
        unit_price: number
        price: number
    }
    type Order = {
        id: number
        customer: string
        products: Product[]
        productsTotal: number
        payment_date: string
        materialsTotal: number
        servicesTotal: number
        productsSold: number
        paymentsTotal: number
        vat_total: number
        total: number
        expenses: number
        grand_total: number
        balance: number
    }

    const store = useStore()
    const title = ref("Charts")
    const today = new Date()
    const availableFields: Record<string, keyof Order> = {
        "Total Sales": "grand_total",
        Products: "productsTotal",
        Materials: "materialsTotal",
        Services: "servicesTotal",
        Payments: "paymentsTotal",
    }

    const presets: Record<"default" | "minimal" | "all", string[]> = {
        default: ["Total Sales", "Products", "Materials", "Services"],
        minimal: ["Total Sales"],
        all: Object.keys(availableFields),
    }

    const checkboxOptions = ref(
        Object.fromEntries(Object.keys(availableFields).map((key) => [key, true]))
    )

    const currentPreset = ref("default")

    const applyPreset = (presetName: string) => {
        if (!(presetName in presets)) return
        Object.keys(checkboxOptions.value).forEach((key) => {
            checkboxOptions.value[key] = presets[presetName as keyof typeof presets].includes(key)
        })
    }

    const currentPeriod = ref<keyof typeof periodsMap | "">("lastMonth")
    const currentInterval = ref<number>(7)
    const startDate = ref<string>("")
    const endDate = ref<string>("")
    const minStartDate = ref<string>("")
    const maxEndDate = ref<string>("")

    const periodsMap = {
        thisWeek: () => [
            startOfWeek(today, { weekStartsOn: 1 }),
            endOfWeek(today, { weekStartsOn: 1 }),
        ],
        lastWeek: () => [
            startOfWeek(subWeeks(today, 1), { weekStartsOn: 1 }),
            endOfWeek(subWeeks(today, 1), { weekStartsOn: 1 }),
        ],
        last2Weeks: () => [
            startOfWeek(subWeeks(today, 2), { weekStartsOn: 1 }),
            endOfWeek(subWeeks(today, 1), { weekStartsOn: 1 }),
        ],
        thisMonth: () => [startOfMonth(today), endOfMonth(today)],
        lastMonth: () => [startOfMonth(subMonths(today, 1)), endOfMonth(subMonths(today, 1))],
        thisQuarter: () => [startOfQuarter(today), endOfQuarter(today)],
        lastQuarter: () => [startOfQuarter(subMonths(today, 3)), endOfQuarter(subMonths(today, 3))],
        thisYear: () => [startOfYear(today), endOfYear(today)],
        lastYear: () => [startOfYear(subYears(today, 1)), endOfYear(subYears(today, 1))],
        yearToDate: () => [startOfYear(today), today],
        past7Days: () => [subDays(today, 7), today],
        past30Days: () => [subDays(today, 30), today],
        past90Days: () => [subDays(today, 90), today],
        past365Days: () => [subDays(today, 365), today],
    }

    const allPeriods = Object.keys(periodsMap) as (keyof typeof periodsMap)[]
    const filteredPeriods = computed(() => {
        if (!minStartDate.value || !maxEndDate.value) return allPeriods

        const minDate = parseISO(minStartDate.value)
        const maxDate = parseISO(maxEndDate.value)
        const orders = store.state.stats.data as Order[]

        return allPeriods.filter((period) => {
            const [start, end] = periodsMap[period]()
            const hasOrdersInPeriod = orders.some((order) => {
                const paymentDate = parseISO(order.payment_date)
                return paymentDate >= start && paymentDate <= end
            })
            return start <= maxDate && end >= minDate && hasOrdersInPeriod
        })
    })

    const tickIntervals = [1, 2, 3, 5, 7, 14, 30]
    const dynamicIntervals = computed(() => {
        if (!startDate.value || !endDate.value) return tickIntervals

        const start = parseISO(startDate.value)
        const end = parseISO(endDate.value)
        const daysInRange = Math.ceil((end.getTime() - start.getTime()) / (1000 * 60 * 60 * 24))

        return tickIntervals.filter((interval) => daysInRange / interval >= 1)
    })

    watch(dynamicIntervals, (newIntervals) => {
        if (!newIntervals.includes(currentInterval.value)) {
            currentInterval.value = newIntervals[0] || 1
        }
    })

    const filteredOrders = computed(() => {
        const start = parseISO(startDate.value)
        const end = parseISO(endDate.value)
        if (!start || !end) return []
        return store.state.stats.data.filter((order: Order) => {
            const paymentDate = parseISO(order.payment_date)
            return paymentDate >= start && paymentDate <= end
        })
    })

    const updateDateRange = () => {
        if (!currentPeriod.value) return
        const [start, end] = periodsMap[currentPeriod.value]()
        startDate.value =
            start < parseISO(minStartDate.value)
                ? minStartDate.value
                : start.toISOString().split("T")[0]
        endDate.value =
            end > parseISO(maxEndDate.value) ? maxEndDate.value : end.toISOString().split("T")[0]
    }

    const initializeDateRange = () => {
        const orders = store.state.stats.data as Order[]
        const dates = orders.map((order) => parseISO(order.payment_date)).filter(Boolean) as Date[]

        const minDate = new Date(Math.min(...dates.map((d) => d.getTime())))
        const maxDate = new Date(Math.max(...dates.map((d) => d.getTime())))
        minStartDate.value = minDate.toISOString().split("T")[0]
        maxEndDate.value = maxDate.toISOString().split("T")[0]
    }

    const closeChart = () => {
        currentPeriod.value = ""
        currentInterval.value = 0
        emit("close")
    }

    const fieldsMap = computed(() =>
        Object.keys(checkboxOptions.value)
            .filter((key) => checkboxOptions.value[key])
            .reduce(
                (map, key) => {
                    map[key] = availableFields[key]
                    return map
                },
                {} as Record<string, keyof Order>
            )
    )

    function aggregateData(
        orders: Order[],
        intervalInDays: number,
        rangeStart: Date,
        rangeEnd: Date
    ) {
        if (!orders.length) return { labels: [], aggregated: {} }

        const sorted = [...orders].sort(
            (a, b) => parseISO(a.payment_date).getTime() - parseISO(b.payment_date).getTime()
        )

        const labels: string[] = []
        const aggregated: Record<string, number[]> = Object.fromEntries(
            Object.keys(fieldsMap.value).map((label) => [label, []])
        )

        const current = new Date(rangeStart)
        while (current <= rangeEnd) {
            const start = new Date(current)
            const end = new Date(current)
            end.setDate(end.getDate() + intervalInDays - 1)
            if (end > rangeEnd) end.setDate(rangeEnd.getDate())

            const bucketOrders = sorted.filter((order) => {
                const date = parseISO(order.payment_date)
                return date >= start && date <= end
            })

            const label = `${format(start, "yy-MMM-dd")} - ${format(end, "yy-MMM-dd")}`
            labels.push(label)

            Object.keys(fieldsMap.value).forEach((fieldKey) => {
                const field = fieldsMap.value[fieldKey]
                const bucketTotal = bucketOrders.reduce(
                    (sum: number, order: Order) =>
                        sum + (typeof order[field] === "number" ? order[field] : 0),
                    0
                )
                aggregated[fieldKey].push(bucketTotal)
            })

            current.setDate(current.getDate() + intervalInDays)
        }

        return { labels, aggregated }
    }

    const chartData = computed(() => {
        if (!filteredOrders.value.length || !startDate.value || !endDate.value) return null
        const chartColors = ["#0fa3b1", "#78caf9", "#e9874e", "#eddea4", "#f7a072"]

        const start = parseISO(startDate.value)
        const end = parseISO(endDate.value)
        const { labels, aggregated } = aggregateData(
            filteredOrders.value,
            currentInterval.value,
            start,
            end
        )

        if (!labels.length) return null

        const datasets = Object.keys(fieldsMap.value).map((label, index) => ({
            label,
            data: aggregated[label],
            backgroundColor: chartColors[index % chartColors.length],
            borderColor: chartColors[index % chartColors.length],
            borderWidth: 2,
        }))

        return { labels, datasets }
    })

    const summaryTotals = computed(() => {
        if (!filteredOrders.value.length) return {}

        return Object.keys(fieldsMap.value).reduce(
            (totals, fieldKey) => {
                const field = fieldsMap.value[fieldKey]
                totals[fieldKey] = filteredOrders.value.reduce(
                    (sum: number, order: Order) =>
                        sum + (typeof order[field] === "number" ? order[field] : 0),
                    0
                )
                return totals
            },
            {} as Record<string, number>
        )
    })

    const isLoading = ref(true)
    onMounted(async () => {
        if (!store.state.stats.data.length) {
            await store.dispatch("fetchStats")
        }
        initializeDateRange()
        updateDateRange()
        applyPreset(currentPreset.value)
        isLoading.value = false
    })
</script>
