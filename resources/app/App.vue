<template>
    <div id="main">
        <main-nav class="text"></main-nav>
        <notification />
        <router-view />
        <base-button
            type="button"
            class="scroll-up fixed bottom-5 right-4 h-[44px] w-[44px] bg-white"
            :class="{ show: windowPos > 0 }"
            @click="scrollUp"
        >
            <i class="fa fa-arrow-up-long"></i>
        </base-button>
    </div>
</template>

<script setup lang="ts">
    import { onMounted, ref } from "vue"

    const windowPos = ref(window.pageYOffset)
    const scrollUp = () => {
        window.scroll({ top: 0, behavior: "smooth" })
    }

    const handleScroll = () => {
        windowPos.value = window.pageYOffset
    }

    onMounted(() => {
        window.addEventListener("scroll", handleScroll)
    })
</script>
