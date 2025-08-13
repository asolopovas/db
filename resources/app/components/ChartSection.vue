<template>
    <LineChart
        v-if="data"
        :id="id"
        :options="chartOptions"
        :data="data"
    />
</template>

<script setup lang="ts">
    import { Line as LineChart } from "vue-chartjs"
    import type { ChartData, ChartOptions } from "chart.js"
    import ChartDataLabels from "chartjs-plugin-datalabels"

    import {
        Chart as ChartJS,
        Title,
        Tooltip,
        Legend,
        PointElement,
        CategoryScale,
        LinearScale,
        LineElement,
    } from "chart.js"
    import type { Plugin } from "chart.js"

    const backgroundColorPlugin: Plugin<"line"> = {
        id: "backgroundColorPlugin",
        beforeDraw: (chart) => {
            const ctx = chart.ctx
            const { top, left, width, height } = chart.chartArea
            ctx.save()
            ctx.fillStyle = "#f8f9fa"
            ctx.fillRect(left, top, width, height)
            ctx.restore()
        },
    }
    const legendMarginPlugin: Plugin<"line"> = {
        id: "legendMarginPlugin",
        beforeInit(chart) {
            const originalLegendFit = chart.legend?.fit
            if (originalLegendFit) {
                chart.legend.fit = function () {
                    originalLegendFit.bind(chart.legend)()
                    if (this.height !== undefined) {
                        this.height += 12
                    }
                }
            }
        },
    }
    const chartOptions: ChartOptions<"line"> = {
        responsive: true,
        scales: {
            x: {
                ticks: {
                    padding: 12,
                },
                grid: {
                    display: true,
                    lineWidth: 0,
                },
            },
            y: {
                ticks: {
                    padding: 20,
                    callback: (value: number | string) => {
                        return Number(value).toLocaleString("en-GB", {
                            style: "currency",
                            currency: "GBP",
                        })
                    },
                },
            },
        },
        plugins: {
            legend: {
                display: true,
            },
            datalabels: {
                align: "top",
                anchor: "end",
                formatter: (value: number) => {
                    // Only format values greater than 0
                    return value > 0
                        ? new Intl.NumberFormat("en-GB", {
                              style: "currency",
                              currency: "GBP",
                          }).format(value)
                        : ""
                },
                font: {
                    size: 12,
                },
                color: "#343436",
            },
        },
    }

    interface Props {
        id: string
        data: ChartData<"line"> | null
    }
    defineProps<Props>()
    ChartJS.register(
        Title,
        Tooltip,
        Legend,
        LineElement,
        LinearScale,
        CategoryScale,
        ChartDataLabels,
        PointElement,
        backgroundColorPlugin,
        legendMarginPlugin
    )
</script>
