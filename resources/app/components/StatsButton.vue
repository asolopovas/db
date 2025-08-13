<template>
    <base-button
        class="hover:bg-neutral-200 hover:text-neutral-950"
        :aria-label="'Download Stats for ' + label"
        @click="emit('click', dataType)"
    >
        {{ label }}
    </base-button>
</template>

<script setup lang="ts">
    interface Props {
        label: string
        dataType: string
    }
    const props = defineProps<Props>()
    const emit = defineEmits(["click"])
</script>
