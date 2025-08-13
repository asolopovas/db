import { cloneDeep } from 'lodash-es'

function createItem(state: ItemsState, { response, model }: { response: any; model: string }): void {
    state[model].items.unshift(response.data.item) // Accessing state[model] directly
}

export function assignFetchedData(state: ItemsState, { data, model }: { data: any; model: string }) {
    state[model].items = data
}

export function deleteCollectionItem(state: ItemsState, { id, routeName }: { id: number; routeName: string }) {
    const items = state[routeName].items
    state[routeName].items = items.filter((item: { id: number }) => item.id !== id)
}
function updateItem(state: ItemsState, { itemClone, model }: { itemClone: Item; model: string }): void {
    const items = state[model].items
    for (let i = 0; i < items.length; i++) {
        if (items[i].id === itemClone.id) {
            items[i] = cloneDeep(itemClone)
            break
        }
    }
}

export {
    createItem,
    updateItem,
}
