<template>
    <button
        :class="computedClasses"
        @click="emit('click', $event)"
    >
        <slot></slot>
    </button>
</template>

<script setup lang="ts">
    import { computed } from "vue"
    const emit = defineEmits(["click"])
    const attrs = defineProps<{ class?: string }>()
    const computedClasses = computed(() => `transition-colors ${attrs.class || ""}`.trim())
</script>
