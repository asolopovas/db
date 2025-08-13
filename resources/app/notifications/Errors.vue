<template>
    <div class="pt-4">
        <hr />
        <h2 class="font-semibold">Fields Missing:</h2>
        <div
            class="error-wrap"
            v-for="(, key, index) in data"
            :key="index"
        >
            <span class="font-semibold text-red-500">{{ $filters.startCase(key) }}</span>
        </div>
    </div>
</template>

<script>
    import { defineComponent } from "vue"
    export default defineComponent({
        props: ["data"],
    })
</script>
