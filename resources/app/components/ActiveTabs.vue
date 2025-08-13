<template>
    <div class="tabs flex py-2">
        <base-button
            v-for="(isActive, name) in tabs"
            :key="name"
            class="tabs-title p-2 mr-2 border hover:bg-slate-400 hover:text-white"
            :class="{ 'bg-slate-500 text-white': isActive, 'bg-slate-300 text-black': !isActive }"
            @click="setActiveTab(name)"
        >
            {{ startCase(name) }}
        </base-button>
    </div>
    <hr class="mb-4" />
</template>

<script setup lang="ts">
    import { computed } from "vue"
    import { startCase } from 'lodash-es'
    const props = defineProps({
        tabs: { type: Object, required: true },
    })

    const emit = defineEmits(["update:tabs", "update:active"])

    const tabs = computed({
        get: () => props.tabs,
        set: (value) => emit("update:tabs", value),
    })

    const setActiveTab = (name: string) => {
        emit("update:active", name)
        Object.keys(tabs.value).forEach((key) => (tabs.value[key] = key === name))
    }
</script>

<style scoped>
    .tabs-title {
        transition:
            background-color 0.3s,
            color 0.3s;
    }
</style>
