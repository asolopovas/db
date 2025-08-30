<template>
    <tr>
        <td
            v-for="(opts, field, key) in fields"
            :key="key"
            :data-label="startCase(field)"
            :class="opts.class"
            v-html="applyFilter(item[field], field)"
        ></td>

        <td
            class="t-col t-col-action"
            data-label="Action"
        >
            <div class="flex gap-2 items-center justify-end md:flex text-neutral-50">
                <base-button
                    class="btn-action bg-red-500"
                    @click="$emit('deleteOrder', item)"
                >
                    <DeleteIcon />
                </base-button>
                <base-button
                    class="btn-action bg-yellow-400 text-zinc-600"
                    @click="view"
                >
                    <ViewIcon />
                </base-button>
                <base-button
                    class="btn-action bg-blue-600"
                    @click="download"
                >
                    <DownloadIcon />
                </base-button>
                <base-button
                    class="btn-action bg-green-600"
                    @click="edit(item.id)"
                >
                    <EditIcon />
                </base-button>
                <base-button
                    class="btn-action bg-indigo-600"
                    @click="duplicate"
                >
                    <CopyIcon />
                </base-button>
            </div>
        </td>
    </tr>
</template>
<script setup lang="ts">
    import { computed } from "vue"
    import { useStore } from "vuex"
    import { useRouter } from "vue-router"
    import { filters } from "@root/resources/app/lib/global-helpers"
    import { startCase } from "lodash-es"
    import apiFetch from "@root/resources/app/lib/apiFetch"
    import DownloadIcon from "@icons/download.svg"
    import ViewIcon from "@icons/view.svg"
    import EditIcon from "@icons/edit.svg"
    import CopyIcon from "@icons/svgs/fi-page-copy.svg"
    import DeleteIcon from "@icons/delete.svg"

    const router = useRouter()
    const store = useStore()
    const props = defineProps({
        item: { type: Object, required: true },
        fields: { type: Object },
        filters: {
            type: Object,
            default: () => ({
                grand_total: "currency",
                balance: "currency",
                vat: "percentage",
            }),
        },
        loading: Boolean,
    })

    const { item, fields } = props
    const token = computed(() => store.state.auth.access_token)

    const applyFilter = (value: any, field: string) => {
        if (props.filters.hasOwnProperty(field)) {
            const filterName = (props.filters as any)[field]
            return (filters as any)[filterName](value)
        }
        return value
    }

    const edit = (id: number) => {
        router.push({ path: `/orders/${id}/details` })
    }
    const download = async () => {
        try {
            const response = await apiFetch<Blob>(`/api/orders/${item.id}/download`, {
                method: "GET",
            })

            const url = window.URL.createObjectURL(new Blob([response.data]))
            const link = document.createElement("a")
            link.href = url
            link.setAttribute("download", `Customer - ${item.customer} #${item.id}.pdf`)
            document.body.appendChild(link)
            link.click()
            link.remove()
            window.URL.revokeObjectURL(url)
        } catch (error) {
            console.error("Failed to download the file:", error)
        }
    }

    const view = () => {
        const height = window.screen.availHeight
        const width = window.screen.availWidth / 2
        const time = Date.now()

        window.open(
            `/api/orders/${item.id}/pdf-default?token=${token.value}&time=${time}`,
            "_blank",
            `location=true,resizable=yes,scrollbars=yes,status=yes,width=${width},height=${height},left=0,top=0`
        )
    }

    const emit = defineEmits(["deleteOrder", "loading"])

    const duplicate = async () => {
        try {
            const response = await apiFetch(`/api/orders/${item.id}/duplicate`, { method: 'POST' })
            // Prefer item.id in response, fallback if structure differs
            const newId = (response as any)?.data?.item?.id || (response as any)?.data?.id
            if (newId) {
                router.push({ path: `/orders/${newId}/details` })
            }
        } catch (error) {
            console.error('Failed to duplicate order:', error)
        }
    }
</script>
