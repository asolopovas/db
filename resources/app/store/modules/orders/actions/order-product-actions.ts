import { find, cloneDeep } from 'lodash-es'
import { extractDiff, extractIds } from '@lib/global-helpers'
import apiFetch from '@root/resources/app/lib/apiFetch'
import type { ActionContext } from 'vuex'

const endpoint = '/api/products'


export async function addProduct(
    context: ActionContext<OrderState, RootState>,
): Promise<void> {
    const { commit, state } = context
    if (!state.product) return
    const product: Product = cloneDeep(state.product)

    extractIds(['floor', 'grade', 'extra', 'dimension'], product)
    product.order_id = state.id

    try {
        const response: any = await apiFetch(`${ endpoint }`, {
            method: 'POST',
            body: product,
        })
        const { data } = response

        commit('addEl', { loc: 'products', el: data.item })
        commit('updateOrderPrice')
        commit('resetProduct')
    } catch (error) {
        console.error(error)
    }
}

export async function saveProduct(
    context: ActionContext<OrderState, RootState>,
): Promise<void> {
    const { commit, state } = context
    if (!state.product || !state.products) return
    const updProduct: Product = state.product
    const products: Product[] = state.products
    const oldProduct: Product | undefined = find(products, { id: updProduct.id })

    if (!oldProduct) {
        console.error('Old product not found')
        return
    }

    const patch: Product | null = extractDiff(oldProduct, updProduct)
    if (patch !== null) {
        extractIds(['floor', 'grade', 'extra', 'dimension'], patch)

        try {
            await apiFetch(`${ endpoint }/${ oldProduct.id }`, {
                method: 'PATCH',
                body: patch,
            })

            commit('saveProduct', updProduct)
            commit('updateOrderPrice')
            commit('resetProduct')
        } catch (error) {
            console.error(error)
        }
    }
}

export async function deleteProduct(
    context: ActionContext<OrderState, RootState>,
    product: Product
): Promise<void> {
    const { commit } = context
    try {
        await apiFetch(`${ endpoint }/${ product.id }`, {
            method: 'DELETE',
        })

        commit('removeRelatedFloorAreas', product.id)
        commit('deleteProduct', product)
    } catch (error) {
        console.error(error)
    }
}
