<template>
    <div class="items-options flex flex-wrap gap-3">
        <select
            id="per-page"
            class="h-[40px] px-4"
            v-model="perPage"
        >
            <option
                value=""
                disabled
            >
                Per Page
            </option>
            <option
                v-for="(option, key) in options"
                :key="key"
                :value="option"
                class="per-page-option"
                v-html="option"
            ></option>
        </select>
        <input
            id="search"
            class="h-[40px] px-4"
            v-model="search"
            type="text"
            placeholder="Search..."
        />
    </div>
</template>

<script setup lang="ts">
    import { ref, watch, onMounted } from "vue"
    import { useRoute, useRouter } from "vue-router"
    import { debounce } from "lodash-es"

    const search = ref<any>({})
    const perPage = ref<any>({})
    const options = ref([20, 30, 50])
    const route = useRoute()
    const router = useRouter()

    const pushChanges = () => {
        const query = { ...route.query }
        if (search.value.trim()) query.search = search.value.trim()
        else delete query.search

        if (perPage.value) query.perPage = perPage.value
        else delete query.perPage

        // Update query only if there are actual changes
        if (JSON.stringify(query) !== JSON.stringify(route.query)) {
            router.replace({ query })
        }
    }

    const debounceSearch = debounce(() => {
        pushChanges()
    }, 300)

    const updateParams = () => {
        search.value = route.query.search || ""
        perPage.value = route.query.perPage || ""
    }

    watch(perPage, pushChanges)
    watch(search, debounceSearch)
    watch(route, updateParams)

    onMounted(updateParams)
</script>
