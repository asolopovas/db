import { notificationStyle } from '@root/resources/app/lib/global-helpers'
import { set } from 'lodash-es'
import router from '@root/resources/app/lib/router'
import user from '@root/resources/app/store/emptyUser'
import { setDescendantProp } from '@root/resources/app/lib/global-helpers'


export function setObjProp(state: RootState, { value, loc }: { value: any; loc: string }) {
    setDescendantProp(state, loc, value)
}

export function assignFetchedItems(state: RootState, { data, storePath }: { data: any; storePath: string }) {
    set(state, storePath, data)
}


export function setNotification(state: RootState, { response, display }: { response: any; display: boolean }) {
    state.notification = {
        type: response.data.type,
        message: response.data.message,
        error: null,
        errors: [],
        style: notificationStyle(response.data.status),
        display
    }
}

export function stateSetter(state: RootState, { name, prop, value }: { name: keyof RootState; prop: keyof RootState[typeof name]; value: any }) {
    (state[name] as any)[prop] = value
}

export function setErrorNotification(state: RootState, response: any) {
    for (const key in response.data) {
        if (key in state.notification) {
            (state.notification as any)[key] = response.data[key]
        }
    }
    state.notification.type = 'error'
    state.notification.style = notificationStyle(response.status)
    state.notification.display = true
}

export function closeNotification(state: RootState, force = false) {
    if (state.notification.type !== 'error' || force) {
        state.notification = {
            message: null,
            type: null,
            error: null,
            errors: [],
            style: null,
            display: false
        }
    }
}

export function setPagination(state: RootState, data: Record<string, any>) {
    for (const key in state.pagination) {
        state.pagination[key] = data[key]
    }
}

export function login(state: RootState, user: any) {
    state.auth.user = user
    state.auth.loggedIn = true
    router.push('/')
}


export function setAlgoliaAuth(
    state: RootState,
    { algolia_app_id, algolia_secret }: { algolia_app_id: string; algolia_secret: string }
) {
    state.auth.algolia.app_id = algolia_app_id
    state.auth.algolia.secret = algolia_secret
    state.auth.algolia.initIndex = import.meta.env.VITE_ALGOLIA_INDEX as string
}

export function setToken(
    state: RootState,
    { data: { access_token, expires_in, roles } }:
        { data: { access_token: string; expires_in: number; roles: string[] } }
) {
    state.auth.roles = roles
    state.auth.access_token = access_token
    state.auth.expires_at = Date.now() + expires_in
}

export function logout(state: RootState) {
    if (!state.auth.loggedIn) {
        return
    }
    state.auth.access_token = null
    state.auth.expires_at = null
    state.auth.loggedIn = false
    state.auth.user = user
    router.push({ name: 'login' })
}
