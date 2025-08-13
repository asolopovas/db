import apiFetch from "@app/lib/apiFetch"
import type { ActionTree, ActionContext } from 'vuex'

function downloadFunc(data: BlobPart, name: string, ext: string): void {
    const url = window.URL.createObjectURL(new Blob([data]))
    const link = document.createElement('a')
    link.href = url
    link.setAttribute('download', `${ name }.${ ext }`)
    document.body.appendChild(link)
    link.click()
    document.body.removeChild(link)
}

async function getStats(
    _: Record<string, any>,
    name: string,
    ext: string = 'xlsx'
): Promise<void> {
    ext = name === 'tax-deducted-orders' ? 'zip' : ext

    try {
        const { data } = await apiFetch(`/api/${ name }`, {
            method: 'GET',
            responseType: 'blob',
        })

        downloadFunc(data as BlobPart, name, ext)
    } catch (error) {
        console.error(`Error fetching stats for ${ name }:`, error)
    }
}

async function fetchStats(
    context: ActionContext<StatsState, RootState>,
): Promise<void> {
    const {
        rootState: {
            auth: {
                user: { role },
            },
        },
        commit,
    } = context

    try {
        if (role.name === "admin") {
            const response = await apiFetch('/api/stats', {
                method: 'GET',
            })

            commit('assignStats', response)
            commit('setPagination', response)
        }
    } catch (error) {
        console.error("Error fetching stats:", error)
    }
}

async function fetchTotals(
    context: ActionContext<StatsState, RootState>
): Promise<void> {
    const { commit } = context

    try {
        const response = await apiFetch('/api/orders/totals', {
            method: 'GET',
        })

        commit('assignTotals', response)
    } catch (error) {
        console.error("Error fetching totals:", error)
    }
}

const actions: ActionTree<StatsState, RootState> = {
    fetchStats,
    fetchTotals,
    getStats,
}

export default actions
