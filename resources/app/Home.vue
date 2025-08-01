<template>
    <main-layout>
        <template #options>
            <div class="flex flex-wrap items-center gap-4 px-3 text-white">
                <div
                    v-for="(value, label) in totals"
                    :key="label"
                    class="flex"
                >
                    <div class="font-bold pr-3">{{ startCase(label) }}:</div>
                    <div>{{ currency(value) }}</div>
                </div>
            </div>
            <base-button
                class="btn-action bg-yellow-200"
                @click="showCharts = !showCharts"
            >
                View Charts
            </base-button>
        </template>

        <ChartStats
            v-if="showCharts"
            @close="showCharts = false"
        />
        <StatsTable
            v-if="isAdmin"
            :orders-monthly="ordersMonthly"
            :order-totals="orderMonthlyTotals"
        />
    </main-layout>
</template>

<script setup lang="ts">
    import { computed, onMounted, ref } from "vue"
    import { useStore } from "vuex"
    import { useRoute } from "vue-router"
    import MainLayout from "@app/MainLayout.vue"
    import StatsTable from "@components/StatsTable.vue"
    import ChartStats from "@components/ChartStats.vue"
    import { currency } from "@root/resources/app/lib/global-helpers"
    import { startCase } from 'lodash-es'

    const route = useRoute()
    const store = useStore()

    const showCharts = ref<boolean>(false)

    const isAdmin = computed(() => store.state.auth.user.role.name === "admin")
    const ordersMonthly = computed(() => store.getters.ordersByMonth)
    const orderMonthlyTotals = computed(() => store.getters.orderMonthlyTotals)
    const totals = computed<Record<string, number>>(() => store.state.stats.totals)

    const fetchData = () => {
        store.dispatch("fetchStats")
        store.dispatch("fetchTotals")
    }

    onMounted(fetchData)
</script>
