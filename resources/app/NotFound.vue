<template>
    <main-layout>
        <template #title>{{ routeName }}</template>
        <div class="grid-x grid-padding-x cell">
            404 - Sorry the page is not found
        </div>
    </main-layout>
</template>

<script>
import {defineComponent, computed} from 'vue'
import {useRoute} from 'vue-router'
import MainLayout from './MainLayout.vue'

export default defineComponent({
    components: {MainLayout},
    setup() {
        const route = useRoute()
        const routeName = computed(() => startCase(route.name))

        return {routeName}
    }
})
</script>
