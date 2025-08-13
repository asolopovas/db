<template>
    <div
        class="flex flex-col lg:flex-row items-center"
        v-if="isLoggedIn"
    >
        <timer
            :expires-at="expiresAt"
            :notify="notify"
            :end="logout"
            @remaining="remaining = $event"
        />
        <modal
            v-if="showModal"
            :controls="true"
            @close="closeModal"
            @yes="refresh"
            @no="closeModal"
        >
            Application will logout in {{ remaining }} seconds. Do you need more time?
        </modal>
        <div class="px-4 text-white">User: {{ name }}</div>
        <base-button
            class="text-red-600 text-left"
            @click.prevent="logout"
            >Logout</base-button
        >
    </div>
</template>

<script setup>
    import { ref, computed } from "vue"
    import { useStore } from "vuex"
    import Timer from "@/app/components/Timer.vue"

    const store = useStore()

    const remaining = ref(null)
    const showModal = ref(false)

    const user = computed(() => store.state.auth.user)
    const name = computed(() => user.value?.name.split(" ")[0])
    const isLoggedIn = computed(() => store.state.auth.loggedIn)
    const expiresAt = computed(() => store.state.auth.expires_at)

    function logout() {
        store.commit("logout")
    }

    function closeModal() {
        showModal.value = false
    }

    function refresh() {
        store.dispatch("refreshTokenAction")
        closeModal()
    }

    function notify() {
        showModal.value = true
        createNotification()
    }

    function createNotification() {
        const options = {
            body: "You will be logged out in 5 minutes",
            icon: "https://3oak.co.uk/wp-content/themes/3oak/static/img/logo-white.svgg",
        }
        const title = "Authentication expires soon..."
        if ("Notification" in window) {
            if (Notification.permission === "granted") {
                new Notification(title, options)
            } else if (Notification.permission !== "denied") {
                Notification.requestPermission().then((permission) => {
                    if (permission === "granted") {
                        new Notification(title, options)
                    }
                })
            }
        }
    }
</script>
