<template>
    <nav class="flex select-none py-2">
        <a
            class="page-button page-first p-3 cursor-pointer"
            :class="{ 'cursor-not-allowed': !prevPage }"
            aria-label="First"
            @click.prevent="first"
        >
            &laquo;
        </a>
        <a
            v-if="perSlice > 4"
            class="page-button page-prev p-3 cursor-pointer"
            :class="{ 'cursor-not-allowed': !prevPage }"
            aria-label="Previous"
            @click.prevent="prevPage && go(prevPage)"
        >
            &#10096;
        </a>
        <a
            v-for="page in pages"
            :key="page"
            class="page-button p-3 cursor-pointer text-center"
            :class="{ 'border-round font-bold': page === currentPage }"
            @click.prevent="go(page)"
        >
            {{ page }}
        </a>
        <a
            v-if="perSlice > 4"
            class="page-button page-next cursor-pointer px-4 py-3"
            :class="{ 'cursor-not-allowed': !nextPage }"
            aria-label="Next"
            @click.prevent="nextPage && go(nextPage)"
        >
            &#10097;
        </a>
        <a
            class="page-button page-last p-3 cursor-pointer font-thin"
            :class="{ 'cursor-not-allowed': !nextPage }"
            aria-label="Next"
            @click.prevent="last"
        >
            &raquo;
        </a>
    </nav>
</template>

<style>
    .page-button {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 44px;
        height: 44px;
    }
</style>

<script setup lang="ts">
    import { ref, computed, watch, onMounted } from "vue"
    import { debounce } from "lodash-es"
    interface Props {
        lastPage: number
        currentPage: number
        perSlice: number
        clickHandler: (page: number) => void
    }

    const props = withDefaults(defineProps<Props>(), {
        lastPage: 1,
        currentPage: 1,
        perSlice: 5,
        clickHandler: () => {},
    })

    const deviceWidth = ref(window.innerWidth)
    const sliceStart = ref(props.currentPage - 1)
    const sliceEnd = ref(props.currentPage + props.perSlice - 1)

    const allPages = computed(() => Array.from({ length: props.lastPage }, (_, i) => i + 1))

    const pagePositionInSlice = computed(() => pages.value.indexOf(props.currentPage))

    const pages = computed(() => {
        return props.lastPage < 4
            ? allPages.value
            : allPages.value.slice(sliceStart.value, sliceEnd.value)
    })

    const nextPage = computed(() =>
        props.currentPage < props.lastPage ? props.currentPage + 1 : false
    )

    const prevPage = computed(() => (props.currentPage > 1 ? props.currentPage - 1 : false))

    function initSliceCoordinates() {
        if (props.lastPage <= props.perSlice) {
            sliceStart.value = 0
            sliceEnd.value = props.lastPage
            return
        }

        if (props.currentPage > 1 && props.currentPage <= props.lastPage - props.perSlice) {
            sliceStart.value = props.currentPage - 2
            sliceEnd.value = props.currentPage + props.perSlice - 2
        }

        const firstInFinalSlice = props.lastPage - props.perSlice + 1

        if (props.currentPage > firstInFinalSlice) {
            sliceStart.value = props.lastPage - props.perSlice
            sliceEnd.value = props.lastPage
        }

        if (props.currentPage === firstInFinalSlice) {
            sliceStart.value = props.lastPage - props.perSlice - 1
            sliceEnd.value = props.lastPage - 1
        }
    }

    function go(page: number) {
        if (page >= 1 && page <= props.lastPage && page !== props.currentPage) {
            props.clickHandler(page)
        }
    }

    function first() {
        sliceStart.value = 0
        sliceEnd.value = props.perSlice
        props.clickHandler(1)
    }

    function last() {
        sliceStart.value = props.lastPage <= props.perSlice ? 0 : props.lastPage - props.perSlice
        sliceEnd.value = props.lastPage
        props.clickHandler(props.lastPage)
    }

    watch(
        () => props.perSlice,
        (value) => {
            sliceStart.value = props.currentPage - 1
            sliceEnd.value = sliceStart.value + value
            initSliceCoordinates()
        },

        { immediate: true }
    )

    watch(
        () => props.currentPage,
        (newVal, oldVal) => {
            if (
                pagePositionInSlice.value === props.perSlice - 1 &&
                newVal > oldVal &&
                newVal !== props.lastPage
            ) {
                sliceStart.value += 1
                sliceEnd.value += 1
            }

            if (pagePositionInSlice.value === 0 && newVal < oldVal && newVal !== 1) {
                sliceStart.value -= 1
                sliceEnd.value -= 1
            }
        }
    )

    onMounted(() => {
        window.addEventListener(
            "resize",
            debounce((e) => {
                deviceWidth.value = e.target.innerWidth
            }, 100)
        )

        initSliceCoordinates()
    })
</script>
