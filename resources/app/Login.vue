<template>
    <main-layout>
        <template #title>
            {{ routeName || "Default Title" }}
        </template>
        <div class="flex justify-center items-center w-full pt-12 py">
            <div class="flex flex-col w-full max-w-[400px] px-4">
                <form @submit.prevent="login">
                    <div class="pb-4 flex flex-col">
                        <label for="username">Username</label>
                        <input
                            id="username"
                            class="border px-4"
                            autocomplete="username"
                            v-model="username"
                            type="text"
                        />
                    </div>
                    <div class="pb-4 flex flex-col">
                        <label
                            for="password"
                            required
                            >Password</label
                        >
                        <input
                            id="password"
                            class="border px-4"
                            autocomplete="current-password"
                            v-model="password"
                            type="password"
                        />
                    </div>
                    <div
                        v-if="message"
                        class="cell mt-2 text-right"
                    >
                        <div class="error">{{ message }}</div>
                    </div>
                    <div class="flex justify-end">
                        <base-button
                            class="btn-action drop-shadow bg-green-500"
                            type="submit"
                        >
                            Submit
                        </base-button>
                    </div>
                </form>
            </div>
        </div>
    </main-layout>
</template>

<script>
    import { ref, computed, defineComponent } from "vue"
    import { useRoute } from "vue-router"
    import { useStore } from "vuex"

    export default defineComponent({
        setup() {
            const username = ref("")
            const password = ref("")
            const message = ref("")

            const route = useRoute()
            const store = useStore()

            const routeName = computed(() => {
                return route.name || "Default Title"
            })

            const login = async () => {
                if (!username.value || !password.value) {
                    message.value = "Please fill in all fields"
                    return
                }

                try {
                    await store.dispatch("loginAction", {
                        username: username.value,
                        password: password.value,
                    })
                    message.value = "Login successful!"
                } catch (error) {
                    message.value = error.response?.data?.message || "An error occurred"
                }
            }

            return {
                username,
                password,
                message,
                routeName,
                login,
            }
        },
    })
</script>

<style lang="scss" scoped>
    input {
        height: 55px;
    }
</style>
