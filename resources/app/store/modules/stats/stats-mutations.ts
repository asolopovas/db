import type { MutationTree } from 'vuex'

function assignStats(
    state: StatsState,
    { data }: { data: { data: any } }
): void {
    state.data = data.data
}

function assignTotals(
    state: StatsState,
    { data }: { data: any }
): void {
    state.totals = data
}

const mutations: MutationTree<StatsState> = {
    assignStats,
    assignTotals,
}

export default mutations
