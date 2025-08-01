<template>
    <div class="lg:mt-[48px]">
        <div
            class="flex flex-wrap justify-center bg-stone-700 text-white px-7 py-4 md:px-4 lg:py-0"
        >
            <router-link
                v-for="link in links"
                :key="link"
                class="tabs-title px-4 py-3"
                active-class="bg-stone-500"
                :to="{ name: `order${upperFirst(camelCase(link))}` }"
            >
                {{ startCase(link) }}
            </router-link>
        </div>
        <router-view v-if="!loading" />
    </div>
</template>

<script setup lang="ts">
    import { ref, onMounted } from "vue"
    import { useStore } from "vuex"
    import { useRoute } from "vue-router"
    import { camelCase, upperFirst, startCase } from "lodash-es"

    const store = useStore()
    const route = useRoute()

    const loading = ref(true)
    const links = [
        "Details",
        "Products",
        "Areas",
        "Materials",
        "Services",
        "Payments",
        "Tax Deductions",
        "Expenses",
    ]
    onMounted(async () => {
        if (!route.params.id) {
            console.error("Order ID is missing")
            return
        }

        await store.dispatch("orderFetch", route.params.id)
        loading.value = false
    })
</script>
