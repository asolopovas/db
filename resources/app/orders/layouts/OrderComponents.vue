<template>
    <div>
        <div class="bg-yellow-200 flex justify-between mb-4 relative">
            <div class="flex flex-wrap gap-3 items-center mx-auto">
                <div class="flex flex-wrap gap-2 items-center min-h-[40px] p-4 lg:p-0">
                    <div><b>Total:</b> {{ $filters.currency(total) }}</div>
                    <div><b>Excl. VAT:</b> {{ $filters.currency(exVatPrice) }}</div>
                    <div><b>Vat:</b> {{ vat }}%</div>
                    <div><b>Due:</b> {{ due }}%</div>
                    <div><b>Balance:</b> {{ $filters.currency(balance) }}</div>
                    <slot name="meta"></slot>
                    <div v-if="route.name !== 'orderDetails'">
                        <base-button
                            class="btn-action fixed bottom-5 left-4 lg:relative lg:bottom-0 lg:left-0 bg-green-600 text-white lg:ml-4"
                            @click="handleAddItem"
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="18"
                                height="18"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    fill="currentColor"
                                    d="M13 4v7h7v2h-7v7h-2v-7H4v-2h7V4h2Z"
                                />
                            </svg>
                        </base-button>
                    </div>
                </div>
            </div>
        </div>
        <slot></slot>
    </div>

    <slot name="loading"></slot>
</template>

<script setup lang="ts">
    import { computed } from "vue"
    import { useStore } from "vuex"
    import { useRoute } from "vue-router"

    const emit = defineEmits(["addItem"])
    const store = useStore()
    const route = useRoute()

    const total = computed(() => store.state.order.grand_total)
    const exVatPrice = computed(() => store.state.order.total)
    const vat = computed(() => store.state.order.vat)
    const due = computed(() => store.state.order.due)
    const balance = computed(() => store.state.order.balance)

    const handleAddItem = () => {
        emit("addItem")
    }
</script>
