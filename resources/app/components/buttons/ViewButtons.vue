<template>
    <div :class="class">
        <base-button
            class="btn-action drop-shadow bg-red-500 hover:bg-red-600 text-white button-width px-4"
            @click="$emit('remove')"
            >Del</base-button
        >
        <base-button
        class="btn-action drop-shadow bg-emerald-600 hover:bg-emerald-700 text-white px-4"
            @click="$emit('edit')"
            >Edit</base-button
        >
    </div>
</template>

<script setup lang="ts">
    interface Props {
        class: string
    }
    defineProps<Props>()
</script>
