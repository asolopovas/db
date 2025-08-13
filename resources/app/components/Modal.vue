<template>
    <div
        id="modal-overlay"
        class="fixed inset-0 flex justify-center items-center z-30 pointer-events-none"
    >
        <div
            id="modal"
            class="relative shadow-lg pointer-events-auto max-w-[90vw] overflow-auto"
            :style="{ width }"
        >
            <!-- Modal Header -->
            <div class="header flex items-center justify-between text-slate-50 bg-neutral-600 pl-4">
                <h2>
                    <slot name="title"></slot>
                </h2>
                <button
                    class="w-[44px] h-[44px] text-slate-950 bg-stone-500 hover:bg-stone-300 text-xl transition-colors"
                    aria-label="Close modal"
                    @click="close"
                >
                    ×
                </button>
            </div>
            <!-- Modal Body -->
            <div
                class="bg-slate-100 flex flex-col flex-1 border-x border-b p-3 min-w-[320px] ld:min-w-auto max-h-[75vh] overflow-auto"
                :style="style"
            >
                <slot></slot>

                <div
                    v-if="controls"
                    class="flex justify-end gap-3 mt-4"
                >
                    <base-button
                        class="btn-action bg-green-700 text-white"
                        @click="yes"
                    >
                        Yes
                    </base-button>
                    <base-button
                        class="btn-action bg-yellow-400"
                        @click="close"
                    >
                        No
                    </base-button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
    defineProps<{
        style?: { [key: string]: string }
        width?: string
        controls?: boolean
    }>()

    const emit = defineEmits(["close", "yes"])

    const close = () => emit("close")
    const yes = () => emit("yes")
</script>
