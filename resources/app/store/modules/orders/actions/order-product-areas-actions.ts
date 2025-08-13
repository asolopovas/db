import apiFetch from "@root/resources/app/lib/apiFetch"
import type { ActionContext } from 'vuex'


async function addProductAreaAction(
    context: ActionContext<OrderState, RootState>,
): Promise<void> {
    const { commit, state } = context
    const el = {
        area_id: state.product_area?.area?.id,
        product_id: state.product_area?.product?.id,
        meterage: state.product_area?.meterage,
    }

    try {
        const response: any = await apiFetch('/api/product_areas', {
            method: 'POST',
            body: el,
        })
        const { data } = response

        commit('addProductArea', data.item)
        commit('updateProductMeterage', el.product_id)
        commit('resetEl', 'product_area')
        commit('setNotification', { response, display: true })
    } catch (error) {
        commit('setErrorNotification', error)
    }
}

async function removeProductAreaAction(
    context: ActionContext<OrderState, RootState>,
    { area, product }: { area: { id: number }; product: { id: number } }
): Promise<void> {
    const { commit } = context
    try {
        await apiFetch(`/api/product_areas/${ area.id }`, {
            method: 'DELETE',
        })
        commit('removeProductArea', { area, product })
        commit('updateProductMeterage', product.id)
    } catch (error) {
        commit('setErrorNotification', error)
    }
}

async function saveProductAreaAction(
    context: ActionContext<OrderState, RootState>,
): Promise<void> {
    const { commit, state } = context
    const el = {
        id: state.product_area?.id,
        area_id: state.product_area?.area?.id,
        product_id: state.product_area?.product?.id,
        meterage: state.product_area?.meterage,
    }

    try {
        await apiFetch(`/api/product_areas/${ el.id }`, {
            method: 'PUT',
            body: el,
        })
        commit('saveProductArea')
        commit('updateProductMeterage', el.product_id)
        commit('updateOrderPrice')
    } catch (error) {
        commit('setErrorNotification', error)
    }
}

export { addProductAreaAction, removeProductAreaAction, saveProductAreaAction }
