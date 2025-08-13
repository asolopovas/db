import type { ActionTree, MutationTree, Module } from 'vuex'
import * as Actions from '@store/modules/items/items-actions'
import * as Mutations from '@store/modules/items/items-mutations'
const entities = [
    'areas',
    'companies',
    'customers',
    'dimensions',
    'extras',
    'floors',
    'grades',
    'materials',
    'orders',
    'projects',
    'services',
    'settings',
    'stats',
    'statuses',
    'users',
] as const

const actions: ActionTree<ItemsState, RootState> = Actions
const mutations: MutationTree<ItemsState> = Mutations
const state: ItemsState = entities.reduce((acc, key) => {
    acc[key] = { items: [] }
    return acc
}, {} as ItemsState)

const items: Module<ItemsState, RootState> = {
    state,
    actions,
    mutations,
}

export default items
