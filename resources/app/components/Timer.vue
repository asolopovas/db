<template>
    <div class="flex px-4 h-full items-center text-white">
        <i class="fa-solid fa-clock pr-2"></i>
        <div class="item hours">{{ pad(hours) }}:</div>
        <div class="item minutes">{{ pad(minutesRemainder) }}:</div>
        <div class="item seconds">{{ pad(secondsRemainder) }}</div>
    </div>
</template>

<script setup lang="ts">
    import { ref, computed, watch, onMounted, onUnmounted } from "vue"

    const props = defineProps({
        expiresAt: { type: Number, required: true },
        update: { type: Function, required: false },
        expires: { type: Number, default: 300 },
        notify: { type: Function },
        end: { type: Function },
    })

    const seconds = ref(Math.round((props.expiresAt - new Date().getTime()) / 1000))
    let interval: ReturnType<typeof setInterval> | null = null

    const totalMinutes = computed(() => Math.floor(seconds.value / 60))
    const hours = computed(() => Math.floor(totalMinutes.value / 60))
    const minutesRemainder = computed(() => totalMinutes.value % 60)
    const secondsRemainder = computed(() => seconds.value % 60)

    watch(seconds, (newVal) => {
        if (newVal === props.expires) {
            props.update?.()
            props.notify?.()
        } else if (newVal < props.expires) {
            props.update?.()
        }
        if (newVal === 0) {
            if (interval) clearInterval(interval)
            props.end?.()
        }
    })

    function pad(n: number) {
        return n.toString().padStart(2, "0")
    }

    function startTimer() {
        interval = setInterval(() => {
            seconds.value = Math.round((props.expiresAt - new Date().getTime()) / 1000)
        }, 1000)
    }

    onMounted(() => startTimer())
    onUnmounted(() => {
        if (interval) clearInterval(interval)
    })
</script>
