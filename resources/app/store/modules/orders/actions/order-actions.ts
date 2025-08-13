
import type { ActionContext } from 'vuex'
import apiFetch from "@root/resources/app/lib/apiFetch"

export async function addEl(
    context: ActionContext<OrderState, RootState>,
    { el, endpoint }: { el: any; endpoint: string }
): Promise<void> {
    const { commit } = context
    try {
        const response = await apiFetch(`/api/${ endpoint }s`, {
            method: 'POST',
            body: el,
        })
        const { data } = response as { data: { item: any } }
        commit('addEl', { loc: `${ endpoint }s`, el: data.item })
        commit('updateOrderPrice')
        commit('updateBalance')
        commit('setNotification', { response, display: true })
        commit('resetEl', endpoint)
    } catch (error) {
        console.error(error)
        commit('setErrorNotification', error)
    }
}

export async function saveEl(
    context: ActionContext<OrderState, RootState>,
    { el, endpoint }: { el: any; endpoint: string }
): Promise<any> {
    const { commit, state } = context
    try {
        const response = await apiFetch(`/api/${ endpoint }s/${ (state as any)[endpoint]?.id }`, {
            method: 'PUT',
            body: el,
        })
        const { data } = response as { data: { item: any } }
        commit('saveEl', { loc: `${ endpoint }s`, el: data.item })
        commit('updateOrderPrice')
        commit('updateBalance')
        commit('setNotification', { response, display: true })
        commit('resetEl', endpoint)
        return data
    } catch (error) {
        commit('setErrorNotification', error)
        return error
    }
}
export async function removeEl(
    context: ActionContext<OrderState, RootState>,
    { loc, el }: { loc: string; el: Element }
): Promise<void> {
    const { commit } = context
    try {
        const response = await apiFetch(`/api/${ loc }/${ el.id }`, {
            method: 'DELETE',
        })
        commit('removeEl', { loc, el })
        commit('updateOrderPrice')
        commit('updateBalance')
        commit('setNotification', { response, display: true })
    } catch (error) {
        console.error(error)
        commit('setErrorNotification', error)
    }
}
export async function orderFetch(
    context: ActionContext<OrderState, RootState>,
    id: number
): Promise<void> {
    const { commit } = context
    try {
        const response = await apiFetch(`/api/orders/${ id }`, {
            method: 'GET',
        })
        commit('assignOrderItem', response.data)
    } catch (error) {
        commit('setErrorNotification', error)
    }
}
export async function orderCreate(
    context: ActionContext<OrderState, RootState>,
    orderParams: { customer_id: number }
): Promise<any> {
    console.log({ orderParams })

    const { commit } = context
    try {
        return await apiFetch('/api/orders', {
            method: 'POST',
            body: orderParams,
        })
    } catch (error) {
        commit('setErrorNotification', error)
    }
}
export async function orderSaveItem(
    context: ActionContext<OrderState, RootState>,
    data: OrderState
): Promise<void> {
    const { commit, state } = context
    try {
        await apiFetch(`/api/orders/${ state.id }`, {
            method: 'PUT',
            body: data,
        })
    } catch (error) {
        commit('setErrorNotification', error)
    }
}
export async function orderSave(
    context: ActionContext<OrderState, RootState>,
    silent = false
): Promise<void> {
    const { commit, state } = context

    commit('updateOrderPrice')
    const requestBody: Partial<OrderState> = {
        id: state.id,
        cc: state.cc,
        customer_id: state.customer?.id,
        status_id: state.status?.id,
        street: state.street,
        city: state.city,
        town: state.town,
        county: state.county,
        postcode: state.postcode,
        country: state.country,
        notes: state.notes,
        vat: state.vat,
        proforma: state.proforma,
        proforma_breakdown: state.proforma_breakdown,
        reverse_charge: state.reverse_charge,
        proforma_message: state.proforma_message,
        payment_terms: state.payment_terms,
        mail_message: state.mail_message,
        due: state.due,
        due_amount: state.due_amount,
        date_due: state.date_due,
        discount: state.discount,
        status: state.status,
        company_id: state.company?.id,
        company: state.company,
        project_id: state.project ? state.project.id : undefined,
    }

    try {
        const response: any = await apiFetch(`/api/orders/${ state.id }`, {
            method: 'PUT',
            body: requestBody,
        })
        commit('assignOrderItem', response.data.item)

        if (!silent) {
            commit('setNotification', { response, display: true })
        }
    } catch (error) {
        commit('setErrorNotification', error)
    }
}
export async function orderSend(
    context: ActionContext<OrderState, RootState>,
): Promise<void> {
    const { commit, state, dispatch } = context
    try {
        const response = await apiFetch(`/api/orders/${ state.id }/send`, {
            method: 'POST',
        })
        await dispatch?.('orderFetch', state.id)
        commit('setNotification', { response, display: true })
    } catch (error) {
        console.error(error)
        commit('setErrorNotification', error)
    }
}
export async function orderDelete(
    context: ActionContext<OrderState, RootState>,
    { routeName, id }: { routeName: string; id: number }
): Promise<void> {
    const { commit } = context
    try {
        const response = await apiFetch(`/api/orders/${ id }`, {
            method: 'DELETE',
        })
        commit('deleteCollectionItem', { routeName, id })
        commit('setNotification', { response, display: true })
    } catch (error) {
        commit('setErrorNotification', error)
    }
}
