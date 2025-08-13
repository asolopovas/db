<template>
    <div class="header-wrap bg-neutral-700 mb-4 lg:mt-[48px]">
        <div
            class="flex flex-wrap gap-4 w-full max-w-[1200px] mx-auto justify-between items-center px-4 pb-5 pt-4 lg:py-2 min-h-[56px]"
        >
            <div class="text-xl text-white">{{ startCase(String(route.name)) }}</div>
            <div class="flex-grow flex flex-wrap justify-left md:justify-end gap-4">
                <slot name="options"></slot>
            </div>
        </div>
    </div>
    <slot></slot>
    <div class="pagination-wrap container flex justify-end mx-auto bg-neutral-200 mb-6">
        <slot name="pagination"></slot>
    </div>
</template>

<script setup lang="ts">
    import { useRoute } from "vue-router"
    import { startCase } from 'lodash-es'
    const route = useRoute()
</script>
