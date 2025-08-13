import { extractDiff } from '@root/resources/app/lib/global-helpers'
import apiFetch from '@root/resources/app/lib/apiFetch'
import type { ActionContext } from 'vuex'

export async function createItemAction(
    context: ActionContext<ItemsState, RootState>,
    { item, routeName }: CreateItemParams
): Promise<void> {
    const { commit } = context
    try {
        const response = await apiFetch(`/api/${ routeName }`, {
            method: 'POST',
            body: item,
        })
        commit('createItem', { response, model: routeName })
        commit('setNotification', { response, display: true })
    } catch (error) {
        commit('setErrorNotification', error)
        return Promise.reject(error)
    }
}

export async function updateItemAction(
    context: ActionContext<ItemsState, RootState>,
    { item, itemClone, routeName }: UpdateItemParams
): Promise<void> {
    const { commit } = context
    const patch = extractDiff(item, itemClone)

    if (patch !== null) {
        try {
            const response = await apiFetch(`/api/${ routeName }/${ item.id }`, {
                method: 'PUT',
                body: patch,
            })
            commit('updateItem', { itemClone, model: routeName })
            commit('setNotification', { response, display: true })
        } catch (error) {
            console.error(error)
            commit('setErrorNotification', error)
        }
    }
}

export async function deleteItemAction(
    context: ActionContext<ItemsState, RootState>,
    { id, routeName }: DeleteItemParams
): Promise<void> {
    const { commit } = context
    try {
        const response = await apiFetch(`/api/${ routeName }/${ id }`, {
            method: 'DELETE',
        })
        commit('deleteCollectionItem', { id, routeName })
        commit('setNotification', { response, display: true })
    } catch (error) {
        commit('setErrorNotification', error)
    }
}
