import AlgoliaPlugin from '@app/plugins/algolia-plugin'
import qs from 'qs'
import apiFetch from '@root/resources/app/lib/apiFetch'
import type { ActionContext } from 'vuex'

export function buildQueryParams(params: Record<string, any>): string {
    const query = new URLSearchParams()

    for (let key in params) {
        if (!params.hasOwnProperty(key)) continue

        const value = params[key]

        if (value && typeof value === "object" && !Array.isArray(value)) {
            for (let subKey in value) {
                if (value.hasOwnProperty(subKey)) {
                    query.append(`${ key }[${ subKey }]`, value[subKey])
                }
            }
        } else {
            query.append(key, value)
        }
    }

    return query.toString()
}
export async function fetchItems(actionContext: ActionContext<RootState, RootState>, { endpoint, storePath, params }: FetchParams<Item>) {
    const { commit } = actionContext
    try {
        const response = await apiFetch(`/api/${ endpoint }`, {
            method: 'GET',
            headers: { 'Content-Type': 'application/json' },
            body: {
                params,
                paramsSerializer: qs.stringify(params, { arrayFormat: 'brackets' })
            } as any
        })
        commit('assignFetchedItems', { data: response.data, storePath })
        commit('setPagination', response)
    } catch (error) {
        commit('setErrorNotification', error)
    }
}


export async function fetchData(
    context: ActionContext<RootState, RootState>,
    { route }: FetchParams<Record<string, any>>
) {
    if (!route) return

    const { commit } = context
    const { name, query: params } = route

    if (params.hasOwnProperty("search")) {
        const args = {
            search: "",
            perPage: 20,
            index: `${ name }_index`,
            page: 1,
            ...params,
        }

        try {
            const algolia = new AlgoliaPlugin()
            const searchParams: SearchParams = {
                query: args.search,
                page: args.page,
                perPage: args.perPage,
                indexName: args.index,
                filter: params.filter,
            }


            const response = await algolia.search(searchParams)

            commit("assignFetchedData", { data: response.data, model: name })
            commit("setPagination", response)
        } catch (error) {
            console.error({ error })
        }
        return
    }

    try {
        const queryString = buildQueryParams(params)

        const { data }: { data: any } = await apiFetch(
            `/api/${ name }${ queryString ? `?${ queryString }` : "" }`
        )
        const isPaginated = data.hasOwnProperty("data")
        commit("assignFetchedData", { data: isPaginated ? data.data : data, model: name })
        commit("setPagination", data)
    } catch (error) {
        commit("setErrorNotification", error)
    }
}

export async function refreshTokenAction(context: ActionContext<RootState, RootState>) {
    const { commit } = context
    try {
        const data = await apiFetch('/auth/refresh', {
            method: 'POST'
        })
        commit('setToken', data)
    } catch (error) {
        console.error(error)
    }
}

export async function loginAction(context: ActionContext<RootState, RootState>, { username, password }: { username: string, password: string }) {
    const { commit, dispatch } = context
    const data = { username, password }
    try {
        const response = await apiFetch('/auth/login', {
            method: 'POST',
            body: JSON.stringify(data)
        })

        commit('setToken', response)
        await dispatch('getUser')
        await dispatch('getAlgoliaAuth')
        return Promise.resolve(response)
    } catch (error) {
        return Promise.reject(error)
    }
}

export async function getAlgoliaAuth(context: ActionContext<RootState, RootState>) {
    const { commit } = context
    try {
        const { data } = await apiFetch('/api/settings-query/get/all', {
            method: 'GET'
        })

        commit('setAlgoliaAuth', data)
    } catch (error) {
        console.error(error)
    }
}

export async function getUser(context: ActionContext<RootState, RootState>) {
    const { commit } = context
    try {
        const response = await apiFetch('/api/user', {
            method: 'GET'
        })

        commit('login', response.data)
    } catch (error) {
        console.error(error)
    }
}
