<template>
    <div
        v-for="(monthStats, date) in ordersMonthly"
        :key="date"
        class="w-full max-w-[1200px] mx-auto px-8 pb-8"
    >
        <div class="overflow-x-auto mx-auto shadow-sm">
            <table class="md:table-auto text-sm">
                <thead>
                    <tr class="bg-blue-50">
                        <th
                            v-for="header in headers"
                            :key="header.key"
                            class="t-col cursor-pointer select-none"
                            :class="`t-col t-col-${header.label.toLowerCase().replace(' ', '-')}`"
                        >
                            {{ header.label }}
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr
                        v-for="order in monthStats"
                        :key="order.id"
                    >
                        <td
                            v-for="key in headers.map((h) => h.key)"
                            class="t-col"
                            :class="`t-col-${kebabCase(key)}`"
                            :data-label="startCase(key)"
                            :key="key"
                            v-html="formatOrder(order, key)"
                        ></td>
                    </tr>
                </tbody>
                <tfoot>
                    <tr class="bg-green-50 border-t border-double">
                        <td
                            class="t-col t-col-footer"
                            colspan="7"
                        >
                            <div class="flex flex-wrap justify-between gap-4 text-center">
                                <div class="pr-1">Month <br />{{ date }}</div>
                                <template
                                    v-for="metric in footerMetrics"
                                    :key="metric.key"
                                >
                                    <div v-if="orderTotals[date]?.[metric.key]">
                                        {{ metric.label }} <br />{{
                                            metric.format(orderTotals[date][metric.key])
                                        }}
                                    </div>
                                </template>
                            </div>
                        </td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
</template>

<script setup lang="ts">
    import { currency } from "@root/resources/app/lib/global-helpers"
    import { startCase, kebabCase } from 'lodash-es'

    defineProps({
        ordersMonthly: Object,
        orderTotals: {
            type: Object,
            default: () => ({}),
        },
    })

    const headers = [
        { label: "ID", key: "id" },
        { label: "Customer", key: "customer" },
        { label: "Product", key: "products" },
        { label: "Total", key: "total" },
        { label: "Vat", key: "vat_total" },
        { label: "Expenses", key: "expenses" },
        { label: "Grand Total", key: "grand_total" },
    ]

    const footerMetrics = [
        { label: "Meterage", key: "productsSold", format: (value: number) => `${value}m²` },
        { label: "Products", key: "productsTotal", format: currency },
        { label: "Avg Price", key: "aveUnitPrice", format: currency },
        { label: "Materials", key: "materialsTotal", format: currency },
        { label: "Services", key: "servicesTotal", format: currency },
        { label: "Money Out", key: "expenses", format: currency },
        { label: "Money In", key: "paymentsTotal", format: currency },
        { label: "Total", key: "grandTotal", format: currency },
        { label: "Vat Total", key: "vatGrandTotal", format: currency },
    ]

    const formatProducts = (products: Product[], order: Order): string => {
        if (!products || products.length === 0) return ""
        const productList = products
            .map((product: Product) => {
                const name = product.name?.replace(" - Bespoke Item", "")
                const meterage = product.meterage
                    ? `<span class="hidden md:inline-block">(${product.meterage}m²)</span>`
                    : ""
                const unit = product.unit_price ? ` (p/unit: ${currency(product.unit_price)})` : ""

                return `${name} ${meterage} ${unit}`
            })
            .join(", ")
        const additionalInfo: string[] = []
        if (order.orderMaterialsTotal)
            additionalInfo.push(`Materials: ${currency(order.orderMaterialsTotal)}`)
        if (order.orderServicesTotal)
            additionalInfo.push(`Services: ${currency(order.orderServicesTotal)}`)
        return `${productList} ${additionalInfo.length ? ` | ${additionalInfo.join(", ")}` : ""}`
    }

    const formatOrder = (order: any, key: any) => {
        const currencyKeys = ["total", "vat_total", "expenses", "vat_grand_total", "grand_total"]
        if (key === "products") return formatProducts(order.products, order)
        if (currencyKeys.includes(key)) return currency(order[key])
        return order[key]
    }
</script>
