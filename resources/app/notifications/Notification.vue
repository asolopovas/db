<template>
    <div
        class="bg-slate-100 flex justify-center border drop-shadow w-full max-w-[90vw] md:max-w-[600px] min-h-[300px] fixed left-1/2 -translate-x-1/2 top-1/2 -translate-y-1/2 pb-4 z-[1000]"
        :class="[notification.display ? 'active' : '', notification.style]"
        v-show="notification.display"
        v-if="notification.type"
    >
        <div
            class="flex flex-col justify-center items-center w-full pt-8 px-4"
            :class="[notification.style]"
        >
            <h1
                class="items-center align-middle absolute top-2 text-center bg-green-500 text-2xl p-4 w-full"
            >
                {{ startCase(notification.type) }}
            </h1>
            <p class="text-center">
                <b>Message</b><br />
                {{ notification.message }}
            </p>
            <errors
                v-if="notification.errors.length !== 0 && notification.errors != undefined"
                :data="notification.errors"
            />
            <button
                class="h-10 w-10 bg-slate-100 hover:bg-slate-200 absolute border-b-0 border right-[-1px] top-[-40px]"
                aria-label="Close modal"
                @click="closeNotification(true)"
            >
                &times;
            </button>
        </div>
    </div>
</template>

<script lang="ts">
    import { defineComponent, computed } from "vue"
    import { useStore } from "vuex"
    import Errors from "./Errors.vue"
    import { startCase } from "lodash"

    export default defineComponent({
        name: "NotificationModal",
        components: { Errors },
        setup() {
            const store = useStore()

            const notification = computed(() => store.state.notification)

            const closeNotification = (value: boolean) => {
                store.commit("closeNotification", value)
            }

            const pad = (value: number | string): string => {
                return String(value).padStart(2, "0")
            }

            return {
                notification,
                closeNotification,
                startCase,
                pad,
            }
        },
    })
</script>
